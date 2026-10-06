<?php

use App\Domains\Content\Models\LearningResource;
use App\Domains\Content\Models\ResourcePreview;
use App\Domains\Content\Models\ResourceSkill;
use App\Domains\Content\Models\ResourceVersion;
use App\Domains\Curriculum\Models\SchoolClass;
use App\Domains\Curriculum\Models\Skill;
use App\Domains\Curriculum\Models\Subject;
use App\Domains\Curriculum\Models\Topic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->beforeEach(function (): void {
        // The cache store is Redis, which persists across tests (needed
        // for the Foundation scheduler test). Without this, a role or
        // permission created in one test can leave a stale cached ID that
        // no longer exists after the next test's RefreshDatabase.
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    })
    ->in('Feature');

/**
 * A free, published resource with the full taxonomy chain
 * (class -> subject -> topic -> skill) and a current, published version
 * with a ready preview — everything the Phase 5 public library tests need,
 * so each test only has to override what it is checking.
 *
 * @param  array<string, mixed>  $resourceAttributes
 * @return array{resource: LearningResource, class: SchoolClass, subject: Subject, topic: Topic, skill: Skill}
 */
function createPublishedFreeResource(array $resourceAttributes = []): array
{
    $uploader = User::factory()->create();

    $subject = Subject::factory()->create();
    $topic = Topic::factory()->create(['subject_id' => $subject->id]);
    $skill = Skill::factory()->create(['topic_id' => $topic->id]);
    $class = SchoolClass::factory()->create();

    $class->subjects()->attach($subject->id, ['sort_order' => 0, 'active' => true]);
    $class->skills()->attach($skill->id, ['sort_order' => 0, 'active' => true]);
    $class->topics()->attach($topic->id, ['sort_order' => 0, 'active' => true]);

    $resource = LearningResource::factory()->free()->published()->create(array_merge([
        'created_by' => $uploader->id,
    ], $resourceAttributes));

    ResourceSkill::factory()->create([
        'resource_id' => $resource->id,
        'skill_id' => $skill->id,
        'class_id' => $class->id,
        'is_primary' => true,
    ]);

    $version = ResourceVersion::factory()->published()->create([
        'resource_id' => $resource->id,
        'created_by' => $uploader->id,
    ]);

    ResourcePreview::factory()->create([
        'resource_version_id' => $version->id,
        'sort_order' => 0,
    ]);

    return [
        'resource' => $resource->refresh(),
        'class' => $class,
        'subject' => $subject,
        'topic' => $topic,
        'skill' => $skill,
    ];
}
