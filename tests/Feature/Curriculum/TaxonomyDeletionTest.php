<?php

use App\Domains\Curriculum\Models\SchoolClass;
use App\Domains\Curriculum\Models\Skill;
use App\Domains\Curriculum\Models\Subject;
use App\Domains\Curriculum\Models\Topic;
use App\Models\User;
use Spatie\Permission\Models\Permission;

function curriculumEditor(): User
{
    Permission::findOrCreate('curriculum.edit', 'web');

    $user = User::factory()->create();
    $user->givePermissionTo('curriculum.edit');

    return $user;
}

test('a subject with topics cannot be deleted, but can be archived', function () {
    $user = curriculumEditor();
    $subject = Subject::factory()->create();
    Topic::factory()->for($subject)->create();

    expect($user->cannot('delete', $subject))->toBeTrue();

    $subject->update(['active' => false]);

    expect($subject->refresh()->active)->toBeFalse();
});

test('a topic with skills cannot be deleted, but can be archived', function () {
    $user = curriculumEditor();
    $topic = Topic::factory()->create();
    Skill::factory()->for($topic)->create();

    expect($user->cannot('delete', $topic))->toBeTrue();

    $topic->update(['active' => false]);

    expect($topic->refresh()->active)->toBeFalse();
});

test('a skill mapped to a class cannot be deleted, but can be archived', function () {
    $user = curriculumEditor();
    $skill = Skill::factory()->create();
    $class = SchoolClass::factory()->create();

    $class->skills()->attach($skill->id, ['sort_order' => 0, 'active' => true]);

    expect($user->cannot('delete', $skill))->toBeTrue();

    $skill->update(['active' => false]);

    expect($skill->refresh()->active)->toBeFalse();
});

test('an unreferenced subject can be deleted', function () {
    $user = curriculumEditor();
    $subject = Subject::factory()->create();

    expect($user->can('delete', $subject))->toBeTrue();
});
