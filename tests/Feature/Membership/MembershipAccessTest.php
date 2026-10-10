<?php

use App\Domains\Access\Enums\AccessDecisionReason;
use App\Domains\Access\Models\Entitlement;
use App\Domains\Access\Services\AccessService;
use App\Domains\Accounts\Models\LearningProfile;
use App\Domains\Catalog\Enums\ProductType;
use App\Domains\Catalog\Models\Product;
use App\Domains\Content\Enums\ResourceStatus;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Content\Models\ResourceVersion;
use App\Domains\Curriculum\Models\SchoolClass;
use App\Domains\Membership\Models\LearningItem;
use App\Domains\Membership\Models\LearningProgram;
use App\Domains\Membership\Models\LearningWeek;
use App\Domains\Membership\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('A Class 2 profile receives Class 2 items but not Class 1 items', function () {
    $class1 = SchoolClass::factory()->create(['name' => 'Class 1', 'slug' => 'class-1']);
    $class2 = SchoolClass::factory()->create(['name' => 'Class 2', 'slug' => 'class-2']);

    $user = User::factory()->create();
    // User profile is enrolled in Class 2
    LearningProfile::factory()->create([
        'user_id' => $user->id,
        'class_id' => $class2->id,
        'active' => true,
    ]);

    // Active subscription
    Subscription::factory()->create([
        'user_id' => $user->id,
        'starts_at' => now()->subDays(10),
        'ends_at' => now()->addDays(80),
    ]);

    // Published Class 1 Programme & Released Week
    $program1 = LearningProgram::factory()->published()->create([
        'class_id' => $class1->id,
        'month' => (int) now()->format('n'),
        'year' => (int) now()->format('Y'),
    ]);
    $week1 = LearningWeek::factory()->create([
        'program_id' => $program1->id,
        'released_at' => now()->subDays(5),
    ]);
    $res1 = LearningResource::factory()->create(['status' => ResourceStatus::Published, 'is_free' => false]);
    ResourceVersion::factory()->create(['resource_id' => $res1->id, 'is_current' => true]);
    LearningItem::factory()->create(['week_id' => $week1->id, 'resource_id' => $res1->id]);

    // Published Class 2 Programme & Released Week
    $program2 = LearningProgram::factory()->published()->create([
        'class_id' => $class2->id,
        'month' => (int) now()->format('n'),
        'year' => (int) now()->format('Y'),
    ]);
    $week2 = LearningWeek::factory()->create([
        'program_id' => $program2->id,
        'released_at' => now()->subDays(5),
    ]);
    $res2 = LearningResource::factory()->create(['status' => ResourceStatus::Published, 'is_free' => false]);
    ResourceVersion::factory()->create(['resource_id' => $res2->id, 'is_current' => true]);
    LearningItem::factory()->create(['week_id' => $week2->id, 'resource_id' => $res2->id]);

    $accessService = app(AccessService::class);

    // Class 2 resource: ALLOWED via membership
    $decision2 = $accessService->canAccess($user, $res2);
    expect($decision2->allowed)->toBeTrue();
    expect($decision2->reason)->toBe(AccessDecisionReason::Membership);
    expect($decision2->source)->toBe('membership');

    // Class 1 resource: DENIED
    $decision1 = $accessService->canAccess($user, $res1);
    expect($decision1->allowed)->toBeFalse();
});

test('A future week cannot be accessed before release', function () {
    $class = SchoolClass::factory()->create();
    $user = User::factory()->create();
    LearningProfile::factory()->create(['user_id' => $user->id, 'class_id' => $class->id, 'active' => true]);
    Subscription::factory()->create(['user_id' => $user->id]);

    $program = LearningProgram::factory()->published()->create([
        'class_id' => $class->id,
        'month' => (int) now()->format('n'),
        'year' => (int) now()->format('Y'),
    ]);
    $unreleasedWeek = LearningWeek::factory()->unreleased()->create([
        'program_id' => $program->id,
    ]);

    $res = LearningResource::factory()->create(['status' => ResourceStatus::Published, 'is_free' => false]);
    ResourceVersion::factory()->create(['resource_id' => $res->id, 'is_current' => true]);
    LearningItem::factory()->create(['week_id' => $unreleasedWeek->id, 'resource_id' => $res->id]);

    $decision = app(AccessService::class)->canAccess($user, $res);
    expect($decision->allowed)->toBeFalse();
});

test('An excluded flagship product resource is not granted by membership', function () {
    $user = User::factory()->create();
    $class = SchoolClass::factory()->create();
    LearningProfile::factory()->create(['user_id' => $user->id, 'class_id' => $class->id, 'active' => true]);
    Subscription::factory()->create(['user_id' => $user->id]);

    // Flagship ebook resource not part of any released learning week
    $flagshipResource = LearningResource::factory()->create(['status' => ResourceStatus::Published, 'is_free' => false]);
    ResourceVersion::factory()->create(['resource_id' => $flagshipResource->id, 'is_current' => true]);

    $flagshipProduct = Product::factory()->create(['type' => ProductType::ActivityEbook]);
    $flagshipProduct->resources()->attach($flagshipResource->id);

    $decision = app(AccessService::class)->canAccess($user, $flagshipResource);
    expect($decision->allowed)->toBeFalse();
});

test('An expired member cannot access new releases', function () {
    $class = SchoolClass::factory()->create();
    $user = User::factory()->create();
    LearningProfile::factory()->create(['user_id' => $user->id, 'class_id' => $class->id, 'active' => true]);

    // Expired subscription
    Subscription::factory()->expired()->create([
        'user_id' => $user->id,
    ]);

    $program = LearningProgram::factory()->published()->create([
        'class_id' => $class->id,
        'month' => (int) now()->format('n'),
        'year' => (int) now()->format('Y'),
    ]);
    $week = LearningWeek::factory()->create([
        'program_id' => $program->id,
        'released_at' => now(), // Released today, after sub expired
    ]);

    $res = LearningResource::factory()->create(['status' => ResourceStatus::Published, 'is_free' => false]);
    ResourceVersion::factory()->create(['resource_id' => $res->id, 'is_current' => true]);
    LearningItem::factory()->create(['week_id' => $week->id, 'resource_id' => $res->id]);

    $decision = app(AccessService::class)->canAccess($user, $res);
    expect($decision->allowed)->toBeFalse();
});

test('A permanent purchase stays accessible after membership expires', function () {
    $user = User::factory()->create();

    // Expired membership
    Subscription::factory()->expired()->create(['user_id' => $user->id]);

    // Purchased entitlement
    $purchasedResource = LearningResource::factory()->create(['status' => ResourceStatus::Published, 'is_free' => false]);
    ResourceVersion::factory()->create(['resource_id' => $purchasedResource->id, 'is_current' => true]);
    Entitlement::factory()->create(['user_id' => $user->id, 'resource_id' => $purchasedResource->id]);

    $decision = app(AccessService::class)->canAccess($user, $purchasedResource);
    expect($decision->allowed)->toBeTrue();
    expect($decision->source)->toBe('purchase');
});
