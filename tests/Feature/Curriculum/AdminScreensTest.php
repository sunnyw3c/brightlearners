<?php

use App\Domains\Curriculum\Models\SchoolClass;
use App\Domains\Curriculum\Models\Skill;
use App\Domains\Curriculum\Models\Subject;
use App\Domains\Curriculum\Models\Topic;
use App\Models\User;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    $this->superAdmin = User::factory()->create();
    $this->superAdmin->assignRole(Role::findOrCreate('super-admin', 'web'));
});

test('the class list, create and edit screens render, including relation managers', function () {
    $class = SchoolClass::factory()->create();
    $subject = Subject::factory()->create();
    $topic = Topic::factory()->for($subject)->create();
    $skill = Skill::factory()->for($topic)->create();
    $class->subjects()->attach($subject->id, ['sort_order' => 0, 'active' => true]);
    $class->skills()->attach($skill->id, ['sort_order' => 0, 'active' => true]);

    $this->actingAs($this->superAdmin)->get('/admin/school-classes')->assertOk();
    $this->actingAs($this->superAdmin)->get('/admin/school-classes/create')->assertOk();
    $this->actingAs($this->superAdmin)->get("/admin/school-classes/{$class->id}/edit")->assertOk();
});

test('the subject, topic and skill list, create and edit screens render', function () {
    $subject = Subject::factory()->create();
    $topic = Topic::factory()->for($subject)->create();
    $skill = Skill::factory()->for($topic)->create();

    foreach ([
        ['subjects', $subject->id],
        ['topics', $topic->id],
        ['skills', $skill->id],
    ] as [$path, $id]) {
        $this->actingAs($this->superAdmin)->get("/admin/{$path}")->assertOk();
        $this->actingAs($this->superAdmin)->get("/admin/{$path}/create")->assertOk();
        $this->actingAs($this->superAdmin)->get("/admin/{$path}/{$id}/edit")->assertOk();
    }
});

test('the staff users screen still renders alongside the curriculum resources', function () {
    $this->actingAs($this->superAdmin)->get('/admin/users')->assertOk();
});
