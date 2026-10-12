<?php

namespace Database\Factories;

use App\Domains\Accounts\Enums\FeedbackCategory;
use App\Domains\Accounts\Models\Feedback;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Feedback>
 */
class FeedbackFactory extends Factory
{
    protected $model = Feedback::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'learning_resource_id' => null,
            'category' => fake()->randomElement(FeedbackCategory::cases()),
            'rating' => fake()->numberBetween(3, 5),
            'message' => fake()->paragraph(),
            'url' => fake()->url(),
            'status' => 'new',
            'admin_response' => null,
        ];
    }
}
