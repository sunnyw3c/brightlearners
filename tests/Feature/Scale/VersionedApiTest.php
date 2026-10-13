<?php

use App\Domains\Accounts\Models\LearningProfile;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Content\Models\ResourceVersion;
use App\Domains\Curriculum\Models\SchoolClass;
use App\Domains\Curriculum\Models\Subject;
use App\Models\ApiToken;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('public api can list curriculum hierarchy', function () {
    $class = SchoolClass::factory()->create(['name' => 'Class 1', 'sort_order' => 1]);
    $subject = Subject::factory()->create(['name' => 'Mathematics']);
    $class->subjects()->attach($subject->id, ['sort_order' => 1, 'active' => true]);

    $response = $this->getJson('/api/v1/curriculum');

    $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => ['id', 'name', 'slug', 'sort_order', 'subjects'],
            ],
        ])
        ->assertJsonFragment([
            'name' => 'Class 1',
        ]);
});

test('public api can list published resources and view single resource', function () {
    $resource = LearningResource::factory()->published()->create([
        'title' => 'Adding Two Numbers',
        'slug' => 'adding-two-numbers',
    ]);
    ResourceVersion::factory()->create([
        'resource_id' => $resource->id,
        'is_current' => true,
    ]);

    $this->getJson('/api/v1/resources')
        ->assertOk()
        ->assertJsonStructure([
            'data',
            'links',
            'meta',
        ])
        ->assertJsonFragment([
            'title' => 'Adding Two Numbers',
        ]);

    $this->getJson('/api/v1/resources/adding-two-numbers')
        ->assertOk()
        ->assertJsonFragment([
            'slug' => 'adding-two-numbers',
        ]);
});

test('unauthenticated api requests return 401', function () {
    $this->getJson('/api/v1/user')->assertUnauthorized();
    $this->getJson('/api/v1/profiles')->assertUnauthorized();
    $this->postJson('/api/v1/progress', [])->assertUnauthorized();
});

test('authenticated api requests work with bearer token', function () {
    $user = User::factory()->create(['name' => 'Priya Sharma', 'email' => 'priya@example.com']);
    $token = ApiToken::generateForUser($user, 'mobile-app');

    $class = SchoolClass::factory()->create(['name' => 'Class 3']);
    $profile = LearningProfile::factory()->create([
        'user_id' => $user->id,
        'nickname' => 'Rohan',
        'class_id' => $class->id,
    ]);

    $resource = LearningResource::factory()->published()->free()->create();
    ResourceVersion::factory()->create([
        'resource_id' => $resource->id,
        'is_current' => true,
    ]);

    // Test GET /api/v1/user
    $this->withToken($token)
        ->getJson('/api/v1/user')
        ->assertOk()
        ->assertJson([
            'id' => $user->id,
            'name' => 'Priya Sharma',
            'email' => 'priya@example.com',
        ]);

    // Test GET /api/v1/profiles
    $this->withToken($token)
        ->getJson('/api/v1/profiles')
        ->assertOk()
        ->assertJsonFragment([
            'nickname' => 'Rohan',
            'class_name' => 'Class 3',
        ]);

    // Test POST /api/v1/progress
    $this->withToken($token)
        ->postJson('/api/v1/progress', [
            'profile_id' => $profile->id,
            'resource_id' => $resource->id,
            'status' => 'completed',
        ])
        ->assertOk()
        ->assertJson([
            'status' => 'ok',
            'progress' => [
                'status' => 'completed',
            ],
        ]);
});

test('expired api token is rejected with 401', function () {
    $user = User::factory()->create();
    $token = ApiToken::generateForUser($user, 'expired-token', now()->subMinutes(10));

    $this->withToken($token)
        ->getJson('/api/v1/user')
        ->assertUnauthorized()
        ->assertJson(['message' => 'Invalid or expired API token.']);
});
