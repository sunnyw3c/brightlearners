<?php

use App\Domains\Content\Actions\CreateResourceCorrection;
use App\Domains\Content\Actions\PublishResourceVersion;
use App\Domains\Content\Enums\CorrectionSeverity;
use App\Domains\Content\Enums\PreviewStatus;
use App\Domains\Content\Enums\ReviewType;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Content\Models\ResourcePreview;
use App\Domains\Content\Models\ResourceReview;
use App\Domains\Content\Models\ResourceSkill;
use App\Domains\Content\Models\ResourceVersion;
use App\Domains\Curriculum\Models\SchoolClass;
use App\Domains\Curriculum\Models\Skill;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

function approveAllReviews(ResourceVersion $version, User $reviewer): void
{
    foreach (ReviewType::cases() as $type) {
        ResourceReview::factory()->approved()->create([
            'resource_id' => $version->resource_id,
            'resource_version_id' => $version->id,
            'review_type' => $type,
            'reviewer_id' => $reviewer->id,
        ]);
    }
}

test('the old version stays retrievable after a correction is published', function () {
    Storage::fake('resources');

    $uploader = User::factory()->create();
    $reviewer = User::factory()->create();
    $resource = LearningResource::factory()->create(['created_by' => $uploader->id]);

    $class = SchoolClass::factory()->create();
    $skill = Skill::factory()->create();
    $class->skills()->attach($skill->id, ['sort_order' => 0, 'active' => true]);
    ResourceSkill::factory()->create(['resource_id' => $resource->id, 'skill_id' => $skill->id, 'class_id' => $class->id]);

    $v1 = ResourceVersion::factory()->previewReady()->create([
        'resource_id' => $resource->id,
        'version' => '1.0',
        'file_path' => 'resource/v1.0/original.pdf',
        'created_by' => $uploader->id,
    ]);
    Storage::disk('resources')->put($v1->file_path, 'original pdf contents');
    ResourcePreview::factory()->create(['resource_version_id' => $v1->id]);
    approveAllReviews($v1, $reviewer);

    app(PublishResourceVersion::class)->handle($v1->refresh());

    $v2 = app(CreateResourceCorrection::class)->handle(
        $resource->refresh(),
        UploadedFile::fake()->create('corrected.pdf', 10, 'application/pdf'),
        $uploader,
        CorrectionSeverity::Minor,
        false,
        'Fixed a typo on page 2.',
    );

    ResourcePreview::factory()->create(['resource_version_id' => $v2->id]);
    $v2->update(['preview_status' => PreviewStatus::Ready]);
    approveAllReviews($v2, $reviewer);

    app(PublishResourceVersion::class)->handle($v2->refresh());

    $v1->refresh();
    $v2->refresh();

    expect($v1->exists)->toBeTrue();
    expect($v1->is_current)->toBeFalse();
    expect($v1->published_at)->not->toBeNull();
    Storage::disk('resources')->assertExists($v1->file_path);

    expect($v2->is_current)->toBeTrue();
    expect($v2->version)->toBe('1.1');

    $resource->refresh();
    expect($resource->status->value)->toBe('published');
});

test('a published version refuses to change its file columns', function () {
    Storage::fake('resources');

    $uploader = User::factory()->create();
    $reviewer = User::factory()->create();
    $resource = LearningResource::factory()->create(['created_by' => $uploader->id]);

    $class = SchoolClass::factory()->create();
    $skill = Skill::factory()->create();
    $class->skills()->attach($skill->id, ['sort_order' => 0, 'active' => true]);
    ResourceSkill::factory()->create(['resource_id' => $resource->id, 'skill_id' => $skill->id, 'class_id' => $class->id]);

    $version = ResourceVersion::factory()->previewReady()->create([
        'resource_id' => $resource->id,
        'created_by' => $uploader->id,
    ]);
    ResourcePreview::factory()->create(['resource_version_id' => $version->id]);
    approveAllReviews($version, $reviewer);

    app(PublishResourceVersion::class)->handle($version->refresh());

    expect(fn () => $version->refresh()->update(['file_path' => 'tampered.pdf']))
        ->toThrow(RuntimeException::class);

    // Flipping is_current on a published row is allowed — it is not a file column.
    $version->refresh()->update(['is_current' => false]);
    expect($version->refresh()->is_current)->toBeFalse();
});
