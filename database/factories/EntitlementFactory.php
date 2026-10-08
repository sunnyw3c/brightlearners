<?php

namespace Database\Factories;

use App\Domains\Access\Models\Entitlement;
use App\Domains\Content\Models\LearningResource;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Entitlement>
 */
class EntitlementFactory extends Factory
{
    protected $model = Entitlement::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'resource_id' => LearningResource::factory(),
            'source_type' => 'order_item',
            'source_id' => $this->faker->randomNumber(5),
            'starts_at' => now(),
            'ends_at' => null,
            'revoked_at' => null,
        ];
    }

    public function revoked(): static
    {
        return $this->state(fn (array $attributes) => [
            'revoked_at' => now(),
        ]);
    }

    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'starts_at' => now()->subDays(10),
            'ends_at' => now()->subDays(1),
        ]);
    }
}
