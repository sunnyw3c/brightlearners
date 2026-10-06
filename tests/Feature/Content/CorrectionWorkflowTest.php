<?php

use App\Domains\Content\Actions\CreateResourceCorrection;
use App\Domains\Content\Actions\PublishResourceVersion;
use App\Domains\Content\Enums\CorrectionSeverity;
use App\Domains\Content\Enums\PreviewStatus;
use App\Domains\Content\Enums\ReviewType;
use App\Domains\Content\Events\MaterialCorrectionPublished;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Content\Models\ResourceCorrection;
use App\Domains\Content\Models\ResourcePreview;
use App\Domains\Content\Models\ResourceReview;
use App\Domains\Content\Models\ResourceSkill;
use App\Domains\Content\Models\ResourceVersion;
use App\Domains\Curriculum\Models\SchoolClass;
use App\Domains\Curriculum\Models\Skill;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;

function publishedResourceWithVersion(User $uploader, User $reviewer): LearningResource
{
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

    foreach (ReviewType::cases() as $type) {
        ResourceReview::factory()->approved()->create([
            'resource_id' => $resource->id,
            'resource_version_id' => $version->id,
            'review_type' => $type,
            'reviewer_id' => $reviewer->id,
        ]);
    }

    app(PublishResourceVersion::class)->handle($version->refresh());

    return $resource->refresh();
}

test('creating a correction adds a new draft version while the current one stays live', function () {
    Storage::fake('resources');

    $uploader = User::factory()->create();
    $reviewer = User::factory()->create();
    $resource = publishedResourceWithVersion($uploader, $reviewer);
    $liveVersion = $resource->currentVersion;

    $correction = app(CreateResourceCorrection::class)->handle(
        $resource,
        UploadedFile::fake()->create('corrected.pdf', 5, 'application/pdf'),
        $uploader,
        CorrectionSeverity::Material,
        true,
        'Corrected a wrong answer on question 4.',
    );

    expect($correction->version)->toBe('1.1');
    expect($correction->published_at)->toBeNull();
    expect($liveVersion->refresh()->is_current)->toBeTrue();
    expect($resource->refresh()->status->value)->toBe('draft');
});

test('publishing a correction writes a resource_corrections row', function () {
    Storage::fake('resources');

    $uploader = User::factory()->create();
    $reviewer = User::factory()->create();
    $resource = publishedResourceWithVersion($uploader, $reviewer);

    $correction = app(CreateResourceCorrection::class)->handle(
        $resource,
        UploadedFile::fake()->create('corrected.pdf', 5, 'application/pdf'),
        $uploader,
        CorrectionSeverity::Minor,
        false,
        'Fixed a typo.',
    );

    $correction->update(['preview_status' => PreviewStatus::Ready]);
    ResourcePreview::factory()->create(['resource_version_id' => $correction->id]);

    foreach (ReviewType::cases() as $type) {
        ResourceReview::factory()->approved()->create([
            'resource_id' => $resource->id,
            'resource_version_id' => $correction->id,
            'review_type' => $type,
            'reviewer_id' => $reviewer->id,
        ]);
    }

    app(PublishResourceVersion::class)->handle($correction->refresh());

    expect(ResourceCorrection::query()->where('resource_id', $resource->id)->count())->toBe(1);
    $row = ResourceCorrection::query()->where('resource_id', $resource->id)->first();
    expect($row->version_from)->toBe('1.0');
    expect($row->version_to)->toBe('1.1');
});

test('publishing a material correction that requires a notice fires MaterialCorrectionPublished', function () {
    Storage::fake('resources');
    Event::fake([MaterialCorrectionPublished::class]);

    $uploader = User::factory()->create();
    $reviewer = User::factory()->create();
    $resource = publishedResourceWithVersion($uploader, $reviewer);

    $correction = app(CreateResourceCorrection::class)->handle(
        $resource,
        UploadedFile::fake()->create('corrected.pdf', 5, 'application/pdf'),
        $uploader,
        CorrectionSeverity::Material,
        true,
        'Corrected a wrong answer key.',
    );

    $correction->update(['preview_status' => PreviewStatus::Ready]);
    ResourcePreview::factory()->create(['resource_version_id' => $correction->id]);

    foreach (ReviewType::cases() as $type) {
        ResourceReview::factory()->approved()->create([
            'resource_id' => $resource->id,
            'resource_version_id' => $correction->id,
            'review_type' => $type,
            'reviewer_id' => $reviewer->id,
        ]);
    }

    app(PublishResourceVersion::class)->handle($correction->refresh());

    Event::assertDispatched(MaterialCorrectionPublished::class);
});

test('publishing a minor correction does not fire MaterialCorrectionPublished', function () {
    Storage::fake('resources');
    Event::fake([MaterialCorrectionPublished::class]);

    $uploader = User::factory()->create();
    $reviewer = User::factory()->create();
    $resource = publishedResourceWithVersion($uploader, $reviewer);

    $correction = app(CreateResourceCorrection::class)->handle(
        $resource,
        UploadedFile::fake()->create('corrected.pdf', 5, 'application/pdf'),
        $uploader,
        CorrectionSeverity::Minor,
        false,
        'Fixed a typo.',
    );

    $correction->update(['preview_status' => PreviewStatus::Ready]);
    ResourcePreview::factory()->create(['resource_version_id' => $correction->id]);

    foreach (ReviewType::cases() as $type) {
        ResourceReview::factory()->approved()->create([
            'resource_id' => $resource->id,
            'resource_version_id' => $correction->id,
            'review_type' => $type,
            'reviewer_id' => $reviewer->id,
        ]);
    }

    app(PublishResourceVersion::class)->handle($correction->refresh());

    Event::assertNotDispatched(MaterialCorrectionPublished::class);
});
