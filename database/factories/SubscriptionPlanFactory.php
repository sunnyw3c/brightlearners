<?php

namespace Database\Factories;

use App\Domains\Membership\Models\SubscriptionPlan;
use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SubscriptionPlan>
 */
#[UseModel(SubscriptionPlan::class)]
class SubscriptionPlanFactory extends Factory
{
    public function definition(): array
    {
        return [
            'code' => 'plan_'.fake()->unique()->slug(1),
            'name' => fake()->words(3, true),
            'duration_days' => 90,
            'duration_months' => 3,
            'price' => 49900,
            'currency' => 'INR',
            'auto_renew_supported' => false,
            'active' => true,
            'benefits' => [
                'member_discount_percent' => 15,
                'inclusions' => 'All weekly learning packs',
            ],
        ];
    }

    public function pilot(): static
    {
        return $this->state(fn () => [
            'code' => 'pilot_90',
            'name' => '90-Day Family Pilot',
            'duration_days' => 90,
            'duration_months' => 3,
            'price' => 49900,
            'active' => true,
        ]);
    }
}
