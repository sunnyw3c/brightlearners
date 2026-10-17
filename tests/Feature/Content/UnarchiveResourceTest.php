<?php

use App\Domains\Content\Actions\UnarchiveResource;
use App\Domains\Content\Enums\ResourceStatus;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Content\Models\ResourceVersion;
use Illuminate\Validation\ValidationException;

test('an archived resource with a published version goes back to published', function () {
    $resource = LearningResource::factory()->create(['status' => ResourceStatus::Archived]);
    ResourceVersion::factory()->published()->create(['resource_id' => $resource->id]);

    app(UnarchiveResource::class)->handle($resource);

    expect($resource->refresh()->status)->toBe(ResourceStatus::Published);
});

test('an archived resource with no published version goes back to draft', function () {
    $resource = LearningResource::factory()->create(['status' => ResourceStatus::Archived]);
    ResourceVersion::factory()->create(['resource_id' => $resource->id]);

    app(UnarchiveResource::class)->handle($resource);

    expect($resource->refresh()->status)->toBe(ResourceStatus::Draft);
});

test('a resource that is not archived cannot be unarchived', function () {
    $resource = LearningResource::factory()->create(['status' => ResourceStatus::Draft]);

    expect(fn () => app(UnarchiveResource::class)->handle($resource))->toThrow(ValidationException::class);
});
