<?php

use App\Domains\Content\Enums\ResourceStatus;
use App\Domains\Content\Enums\ResourceType;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Content\Models\ResourcePreview;
use App\Domains\Content\Models\ResourceSkill;
use App\Domains\Content\Models\ResourceVersion;
use App\Domains\Curriculum\Models\SchoolClass;
use App\Domains\Curriculum\Models\Skill;
use App\Domains\Curriculum\Models\Subject;
use App\Domains\Curriculum\Models\Topic;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->class = SchoolClass::firstOrCreate(
        ['slug' => 'class-1'],
        ['name' => 'Class 1', 'sort_order' => 1, 'active' => true]
    );

    $this->subject = Subject::firstOrCreate(
        ['slug' => 'arts'],
        ['name' => 'Arts & Crafts', 'sort_order' => 1, 'active' => true]
    );

    $this->topic = Topic::firstOrCreate(
        ['subject_id' => $this->subject->id, 'slug' => 'coloring-fun'],
        ['name' => 'Coloring Fun', 'sort_order' => 1, 'active' => true]
    );

    $this->skill = Skill::firstOrCreate(
        ['topic_id' => $this->topic->id, 'slug' => 'fine-motor-coloring'],
        ['name' => 'Fine Motor Coloring', 'learning_objective' => 'Color within lines', 'sort_order' => 1, 'active' => true]
    );

    $this->coloringResource = LearningResource::firstOrCreate(
        ['slug' => 'test-jungle-animals-coloring'],
        [
            'title' => 'Test Jungle Animals Coloring Sheet',
            'type' => ResourceType::Coloring->value,
            'summary' => 'Cute jungle animals for coloring.',
            'description' => 'A wonderful jungle animal coloring sheet.',
            'learning_objective' => 'Practice border control and pencil grip.',
            'difficulty' => 'easy',
            'estimated_minutes' => 15,
            'page_count' => 3,
            'language' => 'en',
            'is_free' => true,
            'status' => ResourceStatus::Published->value,
            'published_at' => now(),
            'created_by' => $this->user->id,
        ]
    );

    ResourceSkill::firstOrCreate([
        'resource_id' => $this->coloringResource->id,
        'skill_id' => $this->skill->id,
        'class_id' => $this->class->id,
    ], ['is_primary' => true]);

    $this->version = ResourceVersion::firstOrCreate(
        ['resource_id' => $this->coloringResource->id, 'version' => '1.0'],
        [
            'file_path' => 'resources/test-jungle.pdf',
            'checksum' => hash('sha256', 'test-jungle'),
            'preview_status' => 'ready',
            'is_current' => true,
            'published_at' => now(),
            'created_by' => $this->user->id,
        ]
    );

    ResourcePreview::firstOrCreate(
        ['resource_version_id' => $this->version->id, 'page_no' => 1],
        ['image_path' => 'previews/test-jungle-p1.png', 'width' => 1200, 'height' => 1600, 'sort_order' => 1]
    );
});

test('coloring hub index page renders successfully with Inertia', function () {
    $response = $this->get(route('coloring.index'));

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page
        ->component('coloring/index')
        ->has('resources.data')
        ->has('themes')
        ->has('classes')
        ->has('filters')
        ->has('featuredWorkbooks')
        ->has('seo')
    );
});

test('coloring hub filters by theme and search keyword', function () {
    $response = $this->get(route('coloring.index', ['theme' => 'animals', 'q' => 'Jungle']));

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page
        ->component('coloring/index')
        ->where('filters.theme', 'animals')
        ->where('filters.q', 'Jungle')
        ->has('resources.data')
    );
});

test('coloring detail page renders successfully with preview and download URL', function () {
    $response = $this->get(route('coloring.show', ['slug' => $this->coloringResource->slug]));

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page
        ->component('coloring/show')
        ->where('resource.title', 'Test Jungle Animals Coloring Sheet')
        ->where('resource.type', 'coloring')
        ->has('resourceDetail.previews')
        ->has('resourceDetail.download_url')
        ->has('breadcrumbs')
        ->has('related')
        ->has('shopRecommendations')
        ->has('seo')
    );
});

test('coloring detail returns 404 for non-existent slug', function () {
    $response = $this->get(route('coloring.show', ['slug' => 'non-existent-coloring-sheet-xyz']));

    $response->assertNotFound();
});

test('coloring resource canonical URL routes to coloring.show', function () {
    expect($this->coloringResource->canonicalUrl())
        ->toBe(route('coloring.show', ['slug' => $this->coloringResource->slug]));
});
