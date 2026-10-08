<?php

use App\Domains\Access\Models\Entitlement;
use App\Domains\Content\Enums\ResourceStatus;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Content\Models\ResourceVersion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('User A with entitlement can download resource while User B without entitlement is denied 403', function () {
    $userA = User::factory()->create();
    $userB = User::factory()->create();

    $resource = LearningResource::factory()->create([
        'status' => ResourceStatus::Published,
        'is_free' => false,
    ]);
    ResourceVersion::factory()->create(['resource_id' => $resource->id, 'is_current' => true, 'file_path' => 'resources/test.pdf']);

    Entitlement::factory()->create([
        'user_id' => $userA->id,
        'resource_id' => $resource->id,
    ]);

    // User A can access download
    $responseA = $this->actingAs($userA)->get(route('downloads.show', $resource->slug));
    $responseA->assertRedirect();
    expect($responseA->headers->get('Location'))->toContain('http');

    // User B cannot access download (403 Forbidden)
    $responseB = $this->actingAs($userB)->get(route('downloads.show', $resource->slug));
    $responseB->assertStatus(403);
});
