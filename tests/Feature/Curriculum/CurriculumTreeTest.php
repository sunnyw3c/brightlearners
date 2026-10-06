<?php

use App\Domains\Curriculum\Models\SchoolClass;
use App\Domains\Curriculum\Models\Skill;
use App\Domains\Curriculum\Models\Subject;
use App\Domains\Curriculum\Models\Topic;
use App\Domains\Curriculum\Queries\CurriculumTree;

function mapSkillToClass(SchoolClass $class, Skill $skill, bool $active = true): void
{
    $class->skills()->attach($skill->id, [
        'learning_objective' => 'Objective',
        'sort_order' => 0,
        'active' => $active,
    ]);
}

test('an active, fully mapped skill appears in the tree', function () {
    $class = SchoolClass::factory()->create();
    $subject = Subject::factory()->create();
    $topic = Topic::factory()->for($subject)->create();
    $skill = Skill::factory()->for($topic)->create();

    $class->subjects()->attach($subject->id, ['sort_order' => 0, 'active' => true]);
    mapSkillToClass($class, $skill);

    $tree = (new CurriculumTree)->get();

    $skills = $tree->firstWhere('id', $class->id)
        ?->subjects->firstWhere('id', $subject->id)
        ?->topics->firstWhere('id', $topic->id)
        ?->skills;

    expect($skills)->not->toBeNull();
    expect($skills->pluck('id'))->toContain($skill->id);
});

test('an inactive class is absent from the tree', function () {
    $class = SchoolClass::factory()->inactive()->create();

    $tree = (new CurriculumTree)->get();

    expect($tree->pluck('id'))->not->toContain($class->id);
});

test('an inactive subject is absent from the tree', function () {
    $class = SchoolClass::factory()->create();
    $subject = Subject::factory()->inactive()->create();

    $class->subjects()->attach($subject->id, ['sort_order' => 0, 'active' => true]);

    $subjects = (new CurriculumTree)->get()->firstWhere('id', $class->id)?->subjects;

    expect($subjects->pluck('id'))->not->toContain($subject->id);
});

test('a subject with an inactive class_subject mapping is absent from the tree', function () {
    $class = SchoolClass::factory()->create();
    $subject = Subject::factory()->create();

    $class->subjects()->attach($subject->id, ['sort_order' => 0, 'active' => false]);

    $subjects = (new CurriculumTree)->get()->firstWhere('id', $class->id)?->subjects;

    expect($subjects->pluck('id'))->not->toContain($subject->id);
});

test('an inactive topic is absent from the tree', function () {
    $class = SchoolClass::factory()->create();
    $subject = Subject::factory()->create();
    $topic = Topic::factory()->for($subject)->inactive()->create();

    $class->subjects()->attach($subject->id, ['sort_order' => 0, 'active' => true]);

    $topics = (new CurriculumTree)->get()
        ->firstWhere('id', $class->id)
        ?->subjects->firstWhere('id', $subject->id)
        ?->topics;

    expect($topics->pluck('id'))->not->toContain($topic->id);
});

test('an inactive skill is absent from the tree', function () {
    $class = SchoolClass::factory()->create();
    $subject = Subject::factory()->create();
    $topic = Topic::factory()->for($subject)->create();
    $skill = Skill::factory()->for($topic)->inactive()->create();

    $class->subjects()->attach($subject->id, ['sort_order' => 0, 'active' => true]);
    mapSkillToClass($class, $skill);

    $skills = (new CurriculumTree)->get()
        ->firstWhere('id', $class->id)
        ?->subjects->firstWhere('id', $subject->id)
        ?->topics->firstWhere('id', $topic->id)
        ?->skills;

    expect($skills->pluck('id'))->not->toContain($skill->id);
});

test('a skill with an inactive class_skill mapping is absent from the tree', function () {
    $class = SchoolClass::factory()->create();
    $subject = Subject::factory()->create();
    $topic = Topic::factory()->for($subject)->create();
    $skill = Skill::factory()->for($topic)->create();

    $class->subjects()->attach($subject->id, ['sort_order' => 0, 'active' => true]);
    mapSkillToClass($class, $skill, active: false);

    $skills = (new CurriculumTree)->get()
        ->firstWhere('id', $class->id)
        ?->subjects->firstWhere('id', $subject->id)
        ?->topics->firstWhere('id', $topic->id)
        ?->skills;

    expect($skills->pluck('id'))->not->toContain($skill->id);
});

test('a skill mapped to a different class does not appear for this class', function () {
    $class = SchoolClass::factory()->create();
    $otherClass = SchoolClass::factory()->create();
    $subject = Subject::factory()->create();
    $topic = Topic::factory()->for($subject)->create();
    $skill = Skill::factory()->for($topic)->create();

    $class->subjects()->attach($subject->id, ['sort_order' => 0, 'active' => true]);
    mapSkillToClass($otherClass, $skill);

    $skills = (new CurriculumTree)->get()
        ->firstWhere('id', $class->id)
        ?->subjects->firstWhere('id', $subject->id)
        ?->topics->firstWhere('id', $topic->id)
        ?->skills;

    expect($skills->pluck('id'))->not->toContain($skill->id);
});
