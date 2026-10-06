<?php

use App\Domains\Content\Actions\PublishResourceVersion;
use App\Domains\Content\Enums\PreviewStatus;
use App\Domains\Content\Enums\ResourceStatus;
use App\Domains\Content\Enums\ReviewStatus;
use App\Domains\Content\Enums\ReviewType;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Content\Models\ResourcePreview;
use App\Domains\Content\Models\ResourceReview;
use App\Domains\Content\Models\ResourceSkill;
use App\Domains\Content\Models\ResourceVersion;
use App\Domains\Curriculum\Models\SchoolClass;
use App\Domains\Curriculum\Models\Skill;
use App\Models\User;
use Illuminate\Validation\ValidationException;

/**
 * Builds a resource/version pair that satisfies every publish gate, so
 * each test can unset exactly the one condition it is checking.
 */
function readyToPublishVersion(): ResourceVersion
{
    $uploader = User::factory()->create();
    $resource = LearningResource::factory()->create(['created_by' => $uploader->id]);

    $class = SchoolClass::factory()->create();
    $skill = Skill::factory()->create();
    $class->skills()->attach($skill->id, ['sort_order' => 0, 'active' => true]);

    ResourceSkill::factory()->create([
        'resource_id' => $resource->id,
        'skill_id' => $skill->id,
        'class_id' => $class->id,
    ]);

    $version = ResourceVersion::factory()->previewReady()->create([
        'resource_id' => $resource->id,
        'created_by' => $uploader->id,
    ]);

    ResourcePreview::factory()->create(['resource_version_id' => $version->id]);

    $reviewer = User::factory()->create();

    foreach (ReviewType::cases() as $type) {
        ResourceReview::factory()->approved()->create([
            'resource_id' => $resource->id,
            'resource_version_id' => $version->id,
            'review_type' => $type,
            'reviewer_id' => $reviewer->id,
        ]);
    }

    return $version->refresh();
}

test('publishes a version when every gate is satisfied', function () {
    $version = readyToPublishVersion();

    $published = app(PublishResourceVersion::class)->handle($version);

    expect($published->published_at)->not->toBeNull();
    expect($published->is_current)->toBeTrue();
    expect($published->resource->refresh()->status)->toBe(ResourceStatus::Published);
});

test('refuses to publish when a required review is missing or not approved', function (ReviewType $missingType) {
    $version = readyToPublishVersion();

    ResourceReview::query()
        ->where('resource_version_id', $version->id)
        ->where('review_type', $missingType)
        ->update(['status' => ReviewStatus::ChangesRequested]);

    expect(fn () => app(PublishResourceVersion::class)->handle($version))
        ->toThrow(ValidationException::class);

    expect($version->refresh()->published_at)->toBeNull();
})->with(ReviewType::cases());

test('refuses to publish when the answer-verification reviewer uploaded the file', function () {
    $version = readyToPublishVersion();

    ResourceReview::query()
        ->where('resource_version_id', $version->id)
        ->where('review_type', ReviewType::AnswerVerification)
        ->update(['reviewer_id' => $version->created_by]);

    expect(fn () => app(PublishResourceVersion::class)->handle($version))
        ->toThrow(ValidationException::class);

    expect($version->refresh()->published_at)->toBeNull();
});

test('refuses to publish when required metadata is missing', function (string $attribute, mixed $value) {
    $version = readyToPublishVersion();
    $version->resource->update([$attribute => $value]);

    expect(fn () => app(PublishResourceVersion::class)->handle($version))
        ->toThrow(ValidationException::class);
})->with([
    'no learning objective' => ['learning_objective', null],
    'no page count' => ['page_count', null],
]);

test('refuses to publish when there is no class and skill mapping', function () {
    $version = readyToPublishVersion();
    $version->resource->skillMappings()->delete();

    expect(fn () => app(PublishResourceVersion::class)->handle($version->refresh()))
        ->toThrow(ValidationException::class);
});

test('a preview failure does not publish a broken resource', function () {
    $version = readyToPublishVersion();
    $version->update(['preview_status' => PreviewStatus::Failed]);

    expect(fn () => app(PublishResourceVersion::class)->handle($version))
        ->toThrow(ValidationException::class);

    expect($version->refresh()->published_at)->toBeNull();
});

test('refuses to publish when preview_status is ready but no preview image exists', function () {
    $version = readyToPublishVersion();
    $version->previews()->delete();

    expect(fn () => app(PublishResourceVersion::class)->handle($version))
        ->toThrow(ValidationException::class);
});
