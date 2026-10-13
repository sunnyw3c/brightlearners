<?php

use App\Domains\Learning\Actions\AutoGradeExercise;
use App\Domains\Learning\Models\InteractiveExercise;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('auto grade exercise accurately evaluates multiple choice answers', function () {
    $exercise = InteractiveExercise::factory()->create([
        'question_config' => [
            'type' => 'multiple_choice',
            'question' => 'What is the sum of 14 + 19?',
            'options' => ['31', '33', '35', '43'],
            'correct_answer' => '33',
            'explanation' => '14 + 19 = 33.',
        ],
    ]);

    $action = app(AutoGradeExercise::class);

    // Correct answer
    $correctResult = $action->handle($exercise, '33');
    expect($correctResult['is_correct'])->toBeTrue()
        ->and($correctResult['score'])->toBe(100)
        ->and($correctResult['feedback'])->toContain('Excellent')
        ->and($correctResult['explanation'])->toBe('14 + 19 = 33.');

    // Incorrect answer
    $incorrectResult = $action->handle($exercise, '31');
    expect($incorrectResult['is_correct'])->toBeFalse()
        ->and($incorrectResult['score'])->toBe(0)
        ->and($incorrectResult['feedback'])->toContain('Incorrect');
});

test('auto grade exercise accurately evaluates numeric and string answers case-insensitively', function () {
    $exercise = InteractiveExercise::factory()->create([
        'question_config' => [
            'type' => 'short_answer',
            'question' => 'What is the capital of India?',
            'correct_answer' => 'New Delhi',
            'explanation' => 'New Delhi is the official capital city of India.',
        ],
    ]);

    $action = app(AutoGradeExercise::class);

    $result = $action->handle($exercise, '  new delhi  ');
    expect($result['is_correct'])->toBeTrue()
        ->and($result['score'])->toBe(100);

    $wrongResult = $action->handle($exercise, 'Mumbai');
    expect($wrongResult['is_correct'])->toBeFalse()
        ->and($wrongResult['score'])->toBe(0);
});
