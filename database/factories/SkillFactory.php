<?php

namespace Database\Factories;

use App\Domains\Curriculum\Models\Skill;
use App\Domains\Curriculum\Models\Topic;
use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Skill>
 */
#[UseModel(Skill::class)]
class SkillFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->sentence(3);

        return [
            'topic_id' => Topic::factory(),
            'name' => $name,
            'slug' => Str::slug($name),
            'learning_objective' => fake()->sentence(),
            'difficulty_band' => fake()->randomElement(['core', 'stretch']),
            'sort_order' => 0,
            'active' => true,
        ];
    }

    /**
     * Indicate that the skill is archived.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes): array => [
            'active' => false,
        ]);
    }
}
