<?php

use App\Domains\Curriculum\Models\SchoolClass;
use Database\Seeders\CurriculumScaleSeeder;
use Database\Seeders\CurriculumSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('curriculum scale seeder populates early years, class 4, and class 5', function () {
    $this->seed(CurriculumSeeder::class);
    $this->seed(CurriculumScaleSeeder::class);

    $earlyYears = SchoolClass::where('slug', 'early-years')->first();
    $class4 = SchoolClass::where('slug', 'class-4')->first();
    $class5 = SchoolClass::where('slug', 'class-5')->first();

    expect($earlyYears)->not->toBeNull()
        ->and($earlyYears->name)->toBe('Early Years (Pre-Primary)')
        ->and($earlyYears->sort_order)->toBe(0);

    expect($class4)->not->toBeNull()
        ->and($class4->name)->toBe('Class 4');

    expect($class5)->not->toBeNull()
        ->and($class5->name)->toBe('Class 5');
});

test('early years has foundational subjects and skills', function () {
    $this->seed(CurriculumSeeder::class);
    $this->seed(CurriculumScaleSeeder::class);

    $earlyYears = SchoolClass::where('slug', 'early-years')->firstOrFail();

    $subjects = $earlyYears->subjects;
    expect($subjects->count())->toBeGreaterThanOrEqual(2);

    $subjectSlugs = $subjects->pluck('slug')->all();
    expect($subjectSlugs)->toContain('maths')
        ->and($subjectSlugs)->toContain('english');

    $skills = $earlyYears->skills;
    expect($skills->count())->toBeGreaterThanOrEqual(2);

    $skillSlugs = $skills->pluck('slug')->all();
    expect($skillSlugs)->toContain('counting-objects-to-10')
        ->and($skillSlugs)->toContain('alphabet-sounds-a-z');
});

test('classes 4 and 5 contain advanced multi-step curriculum skills', function () {
    $this->seed(CurriculumSeeder::class);
    $this->seed(CurriculumScaleSeeder::class);

    $class4 = SchoolClass::where('slug', 'class-4')->firstOrFail();
    $class5 = SchoolClass::where('slug', 'class-5')->firstOrFail();

    $class4SkillNames = $class4->skills->pluck('name')->all();
    expect($class4SkillNames)->toContain('Fractions with Like Denominators');

    $class5SkillNames = $class5->skills->pluck('name')->all();
    expect($class5SkillNames)->toContain('Basic Percentages and Bar Graphs');
});
