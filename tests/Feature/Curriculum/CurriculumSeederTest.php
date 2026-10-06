<?php

use Database\Seeders\CurriculumSeeder;
use Illuminate\Support\Facades\DB;

test('the curriculum seeder is safe to run twice', function () {
    $this->seed(CurriculumSeeder::class);

    $countsAfterFirstRun = [
        'classes' => DB::table('classes')->count(),
        'subjects' => DB::table('subjects')->count(),
        'topics' => DB::table('topics')->count(),
        'skills' => DB::table('skills')->count(),
        'class_subject' => DB::table('class_subject')->count(),
        'class_skill' => DB::table('class_skill')->count(),
    ];

    expect($countsAfterFirstRun['classes'])->toBeGreaterThan(0);

    $this->seed(CurriculumSeeder::class);

    $countsAfterSecondRun = [
        'classes' => DB::table('classes')->count(),
        'subjects' => DB::table('subjects')->count(),
        'topics' => DB::table('topics')->count(),
        'skills' => DB::table('skills')->count(),
        'class_subject' => DB::table('class_subject')->count(),
        'class_skill' => DB::table('class_skill')->count(),
    ];

    expect($countsAfterSecondRun)->toBe($countsAfterFirstRun);
});

test('the curriculum seeder creates Class 1 to 3 with the right slugs', function () {
    $this->seed(CurriculumSeeder::class);

    expect(DB::table('classes')->pluck('slug')->sort()->values()->all())
        ->toBe(['class-1', 'class-2', 'class-3']);
});
