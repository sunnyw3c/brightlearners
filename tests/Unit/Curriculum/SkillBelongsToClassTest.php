<?php

use App\Domains\Curriculum\Models\SchoolClass;
use App\Domains\Curriculum\Models\Skill;
use App\Rules\SkillBelongsToClass;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('the rule fails when the class has no mapping for the skill', function () {
    $class = SchoolClass::factory()->create();
    $skill = Skill::factory()->create();

    $validator = Validator::make(
        ['skill_id' => $skill->id],
        ['skill_id' => [new SkillBelongsToClass($class->id)]],
    );

    expect($validator->fails())->toBeTrue();
});

test('the rule fails when the mapping exists but is inactive', function () {
    $class = SchoolClass::factory()->create();
    $skill = Skill::factory()->create();
    $class->skills()->attach($skill->id, ['sort_order' => 0, 'active' => false]);

    $validator = Validator::make(
        ['skill_id' => $skill->id],
        ['skill_id' => [new SkillBelongsToClass($class->id)]],
    );

    expect($validator->fails())->toBeTrue();
});

test('the rule passes when the class has an active mapping for the skill', function () {
    $class = SchoolClass::factory()->create();
    $skill = Skill::factory()->create();
    $class->skills()->attach($skill->id, ['sort_order' => 0, 'active' => true]);

    $validator = Validator::make(
        ['skill_id' => $skill->id],
        ['skill_id' => [new SkillBelongsToClass($class->id)]],
    );

    expect($validator->fails())->toBeFalse();
});

test('the rule fails when no class is given', function () {
    $skill = Skill::factory()->create();

    $validator = Validator::make(
        ['skill_id' => $skill->id],
        ['skill_id' => [new SkillBelongsToClass(null)]],
    );

    expect($validator->fails())->toBeTrue();
});
