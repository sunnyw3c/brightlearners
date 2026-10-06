<?php

namespace Database\Factories;

use App\Domains\Content\Enums\ResourceStatus;
use App\Domains\Content\Enums\ResourceType;
use App\Domains\Content\Models\LearningResource;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<LearningResource>
 */
#[UseModel(LearningResource::class)]
class LearningResourceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->sentence(3);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'type' => ResourceType::Worksheet,
            'summary' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'learning_objective' => fake()->sentence(),
            'difficulty' => fake()->randomElement(['core', 'stretch']),
            'estimated_minutes' => fake()->numberBetween(10, 30),
            'page_count' => fake()->numberBetween(1, 5),
            'language' => 'en',
            'has_answer_key' => false,
            'low_ink_available' => false,
            'licence_type' => 'household',
            'is_free' => false,
            'ai_assisted' => false,
            'featured' => false,
            'status' => ResourceStatus::Draft,
            'created_by' => User::factory(),
        ];
    }

    public function free(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_free' => true,
        ]);
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => ResourceStatus::Published,
            'published_at' => now(),
        ]);
    }
}
