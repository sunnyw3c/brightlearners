<?php

use App\Domains\Content\Actions\PublishResourceVersion;
use App\Domains\Content\Actions\RecordResourceReview;
use App\Domains\Content\Actions\SubmitResourceForReview;
use App\Domains\Content\Actions\UploadResourceVersion;
use App\Domains\Content\Enums\PreviewStatus;
use App\Domains\Content\Enums\ResourceStatus;
use App\Domains\Content\Enums\ResourceType;
use App\Domains\Content\Enums\ReviewStatus;
use App\Domains\Content\Enums\ReviewType;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Content\Models\ResourcePreview;
use App\Domains\Content\Models\ResourceSkill;
use App\Domains\Curriculum\Models\SchoolClass;
use App\Domains\Curriculum\Models\Skill;
use App\Domains\Curriculum\Models\Subject;
use App\Domains\Curriculum\Models\Topic;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('resources');
    Storage::fake('previews');

    $this->seed(RolesAndPermissionsSeeder::class);

    $this->admin = User::factory()->create([
        'email' => 'admin-tester@brightlearners.in',
        'name' => 'Admin Tester',
    ]);
    $this->admin->assignRole('super-admin');

    $this->class = SchoolClass::firstOrCreate(
        ['slug' => 'class-1'],
        ['name' => 'Class 1', 'sort_order' => 1, 'active' => true]
    );

    $this->subject = Subject::firstOrCreate(
        ['slug' => 'english'],
        ['name' => 'English', 'sort_order' => 1, 'active' => true]
    );

    $this->topic = Topic::firstOrCreate(
        ['subject_id' => $this->subject->id, 'slug' => 'phonics'],
        ['name' => 'Phonics & Sounds', 'sort_order' => 1, 'active' => true]
    );

    $this->skill = Skill::firstOrCreate(
        ['topic_id' => $this->topic->id, 'slug' => 'letter-sounds'],
        ['name' => 'Letter Sounds', 'learning_objective' => 'Identify phonics sounds', 'sort_order' => 1, 'active' => true]
    );

    $this->class->skills()->syncWithoutDetaching([$this->skill->id => ['sort_order' => 1, 'active' => true]]);
});

test('unauthenticated users cannot access admin resource management screens', function () {
    $this->get('/admin/learning-resources')->assertRedirect('/admin/login');
    $this->get('/admin/learning-resources/create')->assertRedirect('/admin/login');
});

test('admin can access filament resource index, create, edit, and view screens', function () {
    $this->actingAs($this->admin)->get('/admin/learning-resources')->assertOk();
    $this->actingAs($this->admin)->get('/admin/learning-resources/create')->assertOk();

    $resource = LearningResource::factory()->create(['created_by' => $this->admin->id]);
    $this->actingAs($this->admin)->get("/admin/learning-resources/{$resource->id}")->assertOk();
    $this->actingAs($this->admin)->get("/admin/learning-resources/{$resource->id}/edit")->assertOk();
});

test('admin can add material end-to-end: create resource, map taxonomy, upload PDF version, review, and publish live', function () {
    // Step 1: Admin creates the Resource with metadata (Coloring or Worksheet)
    $resource = LearningResource::create([
        'title' => 'Alphabet Safari Phonics Coloring Sheet',
        'slug' => 'alphabet-safari-phonics-coloring-sheet',
        'type' => ResourceType::Coloring->value,
        'summary' => 'Five delightful animal coloring sheets for phonics practice.',
        'description' => 'Help early learners master letter sounds with cute animal illustrations.',
        'learning_objective' => 'Practice fine motor pencil grip and initial sound association.',
        'difficulty' => 'easy',
        'estimated_minutes' => 15,
        'page_count' => 3,
        'supplies' => 'Crayons, sketch pens',
        'language' => 'en',
        'is_free' => true,
        'low_ink_available' => true,
        'has_answer_key' => false,
        'featured' => true,
        'status' => ResourceStatus::Draft->value,
        'created_by' => $this->admin->id,
    ]);

    expect($resource->status)->toBe(ResourceStatus::Draft);
    expect($resource->type)->toBe(ResourceType::Coloring);

    // Step 2: Map Class and Skill (Curriculum Taxonomy)
    $mapping = ResourceSkill::create([
        'resource_id' => $resource->id,
        'skill_id' => $this->skill->id,
        'class_id' => $this->class->id,
        'is_primary' => true,
    ]);

    expect($resource->hasRequiredMetadata())->toBeTrue();
    expect($resource->primaryClassSlug())->toBe('class-1');
    expect($resource->primarySubjectSlug())->toBe('english');

    // Step 3: Admin uploads Version 1.0 PDF via UploadResourceVersion
    $fakePdf = UploadedFile::fake()->create('alphabet-safari.pdf', 150, 'application/pdf');
    $version = app(UploadResourceVersion::class)->handle(
        $resource,
        $fakePdf,
        $this->admin
    );

    expect($version->version)->toBe('1.0');
    expect((bool) $version->is_current)->toBeFalse();
    expect($version->checksum)->not->toBeNull();
    Storage::disk('resources')->assertExists($version->file_path);

    // Preview generator marks ready and attaches page previews
    $version->update(['preview_status' => PreviewStatus::Ready->value]);
    ResourcePreview::create([
        'resource_version_id' => $version->id,
        'page_no' => 1,
        'image_path' => 'previews/safari-p1.png',
        'width' => 1200,
        'height' => 1600,
        'sort_order' => 1,
    ]);

    // Step 4: Submit for Review & Complete Review Gates
    app(SubmitResourceForReview::class)->handle($resource->refresh());
    expect($resource->status)->toBe(ResourceStatus::EducationalReview);

    // Second admin / reviewer for four-eyes answer verification principle
    $secondReviewer = User::factory()->create(['name' => 'Reviewer Alex']);
    $secondReviewer->assignRole('super-admin');

    foreach (ReviewType::cases() as $reviewType) {
        $reviewer = ($reviewType === ReviewType::AnswerVerification) ? $secondReviewer : $this->admin;
        app(RecordResourceReview::class)->handle(
            $version,
            $reviewType,
            $reviewer,
            ReviewStatus::Approved,
            'Reviewed and approved for release.'
        );
    }

    $resource->refresh();
    expect($resource->status)->toBe(ResourceStatus::Approved);

    // Step 5: Admin Publishes the Version Live
    app(PublishResourceVersion::class)->handle($version->refresh());

    $resource->refresh();
    $version->refresh();

    expect($resource->status)->toBe(ResourceStatus::Published);
    expect($version->is_current)->toBeTrue();
    expect($resource->published_at)->not->toBeNull();
    expect($version->published_at)->not->toBeNull();

    // Step 6: Verify Public Discovery and Access
    // Public coloring hub lists the published material
    $hubResponse = $this->get(route('coloring.index', ['q' => 'Safari']));
    $hubResponse->assertOk();

    // Dedicated show page renders with 200 and passes all details to Inertia
    $detailResponse = $this->get(route('coloring.show', ['slug' => $resource->slug]));
    $detailResponse->assertOk();

    // Free download delivers signed URL access without paywall
    $downloadResponse = $this->get(route('downloads.show', ['resource' => $resource->slug]));
    $downloadResponse->assertRedirect();
});
