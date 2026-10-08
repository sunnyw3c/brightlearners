<?php

use App\Domains\Access\Enums\AccessDecisionReason;
use App\Domains\Access\Models\Entitlement;
use App\Domains\Access\Services\AccessService;
use App\Domains\Catalog\Models\Product;
use App\Domains\Content\Enums\ResourceStatus;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Content\Models\ResourceVersion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('1. Resource not published denies access with NotPublished reason', function () {
    $resource = LearningResource::factory()->create([
        'status' => ResourceStatus::Draft,
        'is_free' => false,
    ]);

    $accessService = app(AccessService::class);
    $user = User::factory()->create();

    $decision = $accessService->canAccess($user, $resource);
    expect($decision->allowed)->toBeFalse();
    expect($decision->reason)->toBe(AccessDecisionReason::NotPublished);
});

test('2. Free published resource allows access with Free reason', function () {
    $resource = LearningResource::factory()->create([
        'status' => ResourceStatus::Published,
        'is_free' => true,
    ]);
    ResourceVersion::factory()->create(['resource_id' => $resource->id, 'is_current' => true]);

    $accessService = app(AccessService::class);
    $user = User::factory()->create();

    $decision = $accessService->canAccess($user, $resource);
    expect($decision->allowed)->toBeTrue();
    expect($decision->reason)->toBe(AccessDecisionReason::Free);
    expect($decision->source)->toBe('free');
});

test('3. Active entitlement grants access with Entitled reason', function () {
    $user = User::factory()->create();
    $resource = LearningResource::factory()->create([
        'status' => ResourceStatus::Published,
        'is_free' => false,
    ]);
    ResourceVersion::factory()->create(['resource_id' => $resource->id, 'is_current' => true]);

    Entitlement::factory()->create([
        'user_id' => $user->id,
        'resource_id' => $resource->id,
        'starts_at' => now()->subDay(),
        'ends_at' => null,
        'revoked_at' => null,
    ]);

    $accessService = app(AccessService::class);
    $decision = $accessService->canAccess($user, $resource);

    expect($decision->allowed)->toBeTrue();
    expect($decision->reason)->toBe(AccessDecisionReason::Entitled);
    expect($decision->source)->toBe('purchase');
});

test('4. Expired entitlement denies access', function () {
    $user = User::factory()->create();
    $resource = LearningResource::factory()->create([
        'status' => ResourceStatus::Published,
        'is_free' => false,
    ]);
    ResourceVersion::factory()->create(['resource_id' => $resource->id, 'is_current' => true]);

    Entitlement::factory()->expired()->create([
        'user_id' => $user->id,
        'resource_id' => $resource->id,
    ]);

    $accessService = app(AccessService::class);
    $decision = $accessService->canAccess($user, $resource);

    expect($decision->allowed)->toBeFalse();
    expect($decision->reason)->toBe(AccessDecisionReason::NoAccess);
});

test('5. Revoked entitlement denies access', function () {
    $user = User::factory()->create();
    $resource = LearningResource::factory()->create([
        'status' => ResourceStatus::Published,
        'is_free' => false,
    ]);
    ResourceVersion::factory()->create(['resource_id' => $resource->id, 'is_current' => true]);

    Entitlement::factory()->revoked()->create([
        'user_id' => $user->id,
        'resource_id' => $resource->id,
    ]);

    $accessService = app(AccessService::class);
    $decision = $accessService->canAccess($user, $resource);

    expect($decision->allowed)->toBeFalse();
    expect($decision->reason)->toBe(AccessDecisionReason::NoAccess);
});

test('6. Member-discount flag on product does NOT grant access without entitlement', function () {
    $user = User::factory()->create();
    $resource = LearningResource::factory()->create([
        'status' => ResourceStatus::Published,
        'is_free' => false,
    ]);
    ResourceVersion::factory()->create(['resource_id' => $resource->id, 'is_current' => true]);

    // Product marked member_discount_eligible = true
    Product::factory()->create([
        'member_discount_eligible' => true,
    ]);

    $accessService = app(AccessService::class);
    $decision = $accessService->canAccess($user, $resource);

    expect($decision->allowed)->toBeFalse();
    expect($decision->reason)->toBe(AccessDecisionReason::NoAccess);
});

test('7. Permanent purchase (ends_at = null) stays accessible indefinitely', function () {
    $user = User::factory()->create();
    $resource = LearningResource::factory()->create([
        'status' => ResourceStatus::Published,
        'is_free' => false,
    ]);
    ResourceVersion::factory()->create(['resource_id' => $resource->id, 'is_current' => true]);

    Entitlement::factory()->create([
        'user_id' => $user->id,
        'resource_id' => $resource->id,
        'starts_at' => now()->subYears(2),
        'ends_at' => null,
        'revoked_at' => null,
    ]);

    $accessService = app(AccessService::class);
    $decision = $accessService->canAccess($user, $resource);

    expect($decision->allowed)->toBeTrue();
});
