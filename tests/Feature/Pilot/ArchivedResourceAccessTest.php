<?php

use App\Domains\Access\Enums\AccessDecisionReason;
use App\Domains\Access\Models\Entitlement;
use App\Domains\Access\Services\AccessService;
use App\Domains\Catalog\Enums\ProductStatus;
use App\Domains\Catalog\Models\Product;
use App\Domains\Content\Enums\PreviewStatus;
use App\Domains\Content\Enums\ResourceStatus;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Content\Models\ResourceVersion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('archived resource denies access to guest and non-entitled user', function () {
    $resource = LearningResource::factory()->create([
        'status' => ResourceStatus::Archived,
        'is_free' => false,
    ]);
    ResourceVersion::factory()->create([
        'resource_id' => $resource->id,
        'is_current' => true,
        'preview_status' => PreviewStatus::Ready,
        'published_at' => now()->subMonth(),
    ]);

    $accessService = app(AccessService::class);
    $user = User::factory()->create();

    // Guest check
    $guestDecision = $accessService->canAccess(null, $resource);
    expect($guestDecision->allowed)->toBeFalse()
        ->and($guestDecision->reason)->toBe(AccessDecisionReason::NotPublished);

    // Non-entitled user check
    $userDecision = $accessService->canAccess($user, $resource);
    expect($userDecision->allowed)->toBeFalse()
        ->and($userDecision->reason)->toBe(AccessDecisionReason::NotPublished);
});

test('existing entitled owner retains access to download archived resource', function () {
    $user = User::factory()->create();
    $resource = LearningResource::factory()->create([
        'status' => ResourceStatus::Archived,
        'is_free' => false,
    ]);
    $version = ResourceVersion::factory()->create([
        'resource_id' => $resource->id,
        'is_current' => true,
        'preview_status' => PreviewStatus::Ready,
        'published_at' => now()->subMonth(),
    ]);

    Entitlement::factory()->create([
        'user_id' => $user->id,
        'resource_id' => $resource->id,
        'starts_at' => now()->subWeeks(2),
        'ends_at' => null,
        'revoked_at' => null,
        'source_type' => 'purchase',
    ]);

    $accessService = app(AccessService::class);
    $decision = $accessService->canAccess($user, $resource);

    expect($decision->allowed)->toBeTrue()
        ->and($decision->reason)->toBe(AccessDecisionReason::Entitled)
        ->and($decision->source)->toBe('purchase');
});

test('archived free resource is no longer open for free download', function () {
    $resource = LearningResource::factory()->create([
        'status' => ResourceStatus::Archived,
        'is_free' => true,
    ]);
    ResourceVersion::factory()->create([
        'resource_id' => $resource->id,
        'is_current' => true,
        'preview_status' => PreviewStatus::Ready,
    ]);

    $accessService = app(AccessService::class);
    $user = User::factory()->create();

    $decision = $accessService->canAccess($user, $resource);
    expect($decision->allowed)->toBeFalse()
        ->and($decision->reason)->toBe(AccessDecisionReason::NotPublished);
});

test('archived product is excluded from active query and shop listing', function () {
    $activeProduct = Product::factory()->active()->create([
        'name' => 'Active Product Sheet',
    ]);
    $archivedProduct = Product::factory()->create([
        'name' => 'Archived Product Sheet',
        'status' => ProductStatus::Archived,
    ]);

    $activeIds = Product::query()->active()->pluck('id');

    expect($activeIds)->toContain($activeProduct->id)
        ->and($activeIds)->not->toContain($archivedProduct->id);
});
