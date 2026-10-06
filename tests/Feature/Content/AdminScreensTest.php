<?php

use App\Domains\Content\Models\LearningResource;
use App\Domains\Content\Models\ResourcePreview;
use App\Domains\Content\Models\ResourceReview;
use App\Domains\Content\Models\ResourceSkill;
use App\Domains\Content\Models\ResourceVersion;
use App\Domains\Curriculum\Models\SchoolClass;
use App\Domains\Curriculum\Models\Skill;
use App\Models\User;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    $this->superAdmin = User::factory()->create();
    $this->superAdmin->assignRole(Role::findOrCreate('super-admin', 'web'));
});

test('the resource list, create, view and edit screens render', function () {
    $resource = LearningResource::factory()->create();
    $class = SchoolClass::factory()->create();
    $skill = Skill::factory()->create();
    $class->skills()->attach($skill->id, ['sort_order' => 0, 'active' => true]);
    ResourceSkill::factory()->create(['resource_id' => $resource->id, 'skill_id' => $skill->id, 'class_id' => $class->id]);
    $version = ResourceVersion::factory()->previewReady()->create(['resource_id' => $resource->id]);
    ResourcePreview::factory()->create(['resource_version_id' => $version->id]);
    ResourceReview::factory()->approved()->create(['resource_id' => $resource->id, 'resource_version_id' => $version->id]);

    $this->actingAs($this->superAdmin)->get('/admin/learning-resources')->assertOk();
    $this->actingAs($this->superAdmin)->get('/admin/learning-resources/create')->assertOk();
    $this->actingAs($this->superAdmin)->get("/admin/learning-resources/{$resource->id}")->assertOk();
    $this->actingAs($this->superAdmin)->get("/admin/learning-resources/{$resource->id}/edit")->assertOk();
});

test('the review queue, overdue reviews and correction queue pages render', function () {
    $this->actingAs($this->superAdmin)->get('/admin/review-queue')->assertOk();
    $this->actingAs($this->superAdmin)->get('/admin/overdue-reviews')->assertOk();
    $this->actingAs($this->superAdmin)->get('/admin/correction-queue')->assertOk();
});
