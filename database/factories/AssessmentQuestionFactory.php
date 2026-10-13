<?php

namespace Database\Factories;

use App\Domains\Curriculum\Models\Skill;
use App\Domains\Learning\Enums\QuestionDifficulty;
use App\Domains\Learning\Enums\QuestionType;
use App\Domains\Learning\Models\AssessmentQuestion;
use App\Domains\Learning\Models\AssessmentSection;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AssessmentQuestion>
 */
class AssessmentQuestionFactory extends Factory
{
    protected $model = AssessmentQuestion::class;

    public function definition(): array
    {
        return [
            'section_id' => AssessmentSection::factory(),
            'skill_id' => Skill::factory(),
            'type' => QuestionType::SingleChoice,
            'prompt' => fake()->sentence().'?',
            'difficulty' => QuestionDifficulty::Medium,
            'answer_config' => [
                'options' => ['Option A', 'Option B', 'Option C', 'Option D'],
                'correct_answer' => 'Option A',
                'explanation' => 'Option A is the correct answer based on curriculum rules.',
            ],
            'points' => 1,
            'sort_order' => 1,
        ];
    }
}
