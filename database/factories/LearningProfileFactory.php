<?php

namespace Database\Factories;

use App\Domains\Accounts\Models\LearningProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LearningProfile>
 */
#[UseModel(LearningProfile::class)]
class LearningProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'nickname' => fake()->firstName(),
            'class_id' => null,
            'avatar_key' => fake()->randomElement(['fox', 'owl', 'panda', 'rabbit']),
            'interests' => [],
            'active' => true,
        ];
    }

    /**
     * Indicate that the profile has been deactivated.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'active' => false,
        ]);
    }
}
