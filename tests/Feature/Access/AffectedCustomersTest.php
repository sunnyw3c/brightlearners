<?php

use App\Domains\Access\Models\Download;
use App\Domains\Access\Models\Entitlement;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Content\Models\ResourceVersion;
use App\Domains\Content\Queries\AffectedCustomers;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('AffectedCustomers returns users from both downloads and valid entitlements', function () {
    $resource = LearningResource::factory()->create();
    $version = ResourceVersion::factory()->create(['resource_id' => $resource->id]);

    $userDownloaded = User::factory()->create();
    $userEntitled = User::factory()->create();
    $userBoth = User::factory()->create();
    $userUnrelated = User::factory()->create();

    // User who downloaded
    Download::factory()->create([
        'user_id' => $userDownloaded->id,
        'resource_id' => $resource->id,
        'resource_version_id' => $version->id,
    ]);

    // User who has an entitlement
    Entitlement::factory()->create([
        'user_id' => $userEntitled->id,
        'resource_id' => $resource->id,
    ]);

    // User with both download and entitlement
    Download::factory()->create([
        'user_id' => $userBoth->id,
        'resource_id' => $resource->id,
        'resource_version_id' => $version->id,
    ]);
    Entitlement::factory()->create([
        'user_id' => $userBoth->id,
        'resource_id' => $resource->id,
    ]);

    $query = app(AffectedCustomers::class);
    $affected = $query->forResource($resource->id);

    expect($affected)->toHaveCount(3);
    $affectedIds = $affected->pluck('id')->all();

    expect($affectedIds)->toContain($userDownloaded->id);
    expect($affectedIds)->toContain($userEntitled->id);
    expect($affectedIds)->toContain($userBoth->id);
    expect($affectedIds)->not->toContain($userUnrelated->id);
});

test('AffectedCustomers does not include users with revoked entitlements if they never downloaded', function () {
    $resource = LearningResource::factory()->create();

    $userRevoked = User::factory()->create();
    Entitlement::factory()->revoked()->create([
        'user_id' => $userRevoked->id,
        'resource_id' => $resource->id,
    ]);

    $query = app(AffectedCustomers::class);
    $affected = $query->forResource($resource->id);

    expect($affected)->toBeEmpty();
});
