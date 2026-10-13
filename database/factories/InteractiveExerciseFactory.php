<?php

namespace Database\Factories;

use App\Domains\Content\Models\LearningResource;
use App\Domains\Curriculum\Models\Skill;
use App\Domains\Learning\Models\InteractiveExercise;
use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<InteractiveExercise>
 */
#[UseModel(InteractiveExercise::class)]
class InteractiveExerciseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(3);

        return [
            'resource_id' => LearningResource::factory(),
            'skill_id' => Skill::factory(),
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->numberBetween(100, 9999),
            'question_config' => [
                'type' => 'multiple_choice',
                'question' => 'What is 5 + 3?',
                'options' => ['6', '7', '8', '9'],
                'correct_answer' => '8',
                'explanation' => '5 + 3 = 8',
            ],
            'active' => true,
        ];
    }

    /**
     * Indicate that the exercise is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes): array => [
            'active' => false,
        ]);
    }
}
