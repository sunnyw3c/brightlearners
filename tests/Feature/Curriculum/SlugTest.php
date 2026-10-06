<?php

use App\Domains\Curriculum\Models\SchoolClass;
use App\Domains\Curriculum\Models\Subject;
use App\Domains\Curriculum\Models\Topic;
use Illuminate\Database\QueryException;

test('a duplicate class slug is rejected', function () {
    SchoolClass::factory()->create(['slug' => 'class-9']);

    expect(fn () => SchoolClass::factory()->create(['slug' => 'class-9']))
        ->toThrow(QueryException::class);
});

test('a duplicate subject slug is rejected', function () {
    Subject::factory()->create(['slug' => 'geography']);

    expect(fn () => Subject::factory()->create(['slug' => 'geography']))
        ->toThrow(QueryException::class);
});

test('a topic slug is unique within its subject but may repeat across subjects', function () {
    $subject = Subject::factory()->create();
    Topic::factory()->for($subject)->create(['slug' => 'intro']);

    expect(fn () => Topic::factory()->for($subject)->create(['slug' => 'intro']))
        ->toThrow(QueryException::class);

    $otherSubject = Subject::factory()->create();

    expect(Topic::factory()->for($otherSubject)->create(['slug' => 'intro']))
        ->not->toBeNull();
});

test('renaming a class does not change its slug', function () {
    $class = SchoolClass::factory()->create(['name' => 'Class 4', 'slug' => 'class-4']);

    $class->update(['name' => 'Class Four (Renamed)']);

    expect($class->refresh()->slug)->toBe('class-4');
});
