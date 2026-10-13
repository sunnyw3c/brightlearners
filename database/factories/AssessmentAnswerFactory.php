<?php

namespace Database\Factories;

use App\Domains\Learning\Models\AssessmentAnswer;
use App\Domains\Learning\Models\AssessmentAttempt;
use App\Domains\Learning\Models\AssessmentQuestion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AssessmentAnswer>
 */
class AssessmentAnswerFactory extends Factory
{
    protected $model = AssessmentAnswer::class;

    public function definition(): array
    {
        return [
            'attempt_id' => AssessmentAttempt::factory(),
            'question_id' => AssessmentQuestion::factory(),
            'response' => ['selected' => 'Option A'],
            'is_correct' => true,
            'score' => 1,
        ];
    }
}
