<?php

namespace Database\Factories;

use App\Domains\Membership\Enums\SubscriptionStatus;
use App\Domains\Membership\Models\Subscription;
use App\Domains\Membership\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Subscription>
 */
#[UseModel(Subscription::class)]
class SubscriptionFactory extends Factory
{
    public function definition(): array
    {
        $startsAt = now();

        return [
            'user_id' => User::factory(),
            'plan_id' => SubscriptionPlan::factory(),
            'source_order_id' => null,
            'starts_at' => $startsAt,
            'ends_at' => $startsAt->copy()->addDays(90),
            'status' => SubscriptionStatus::Active,
            'auto_renew' => false,
            'cancelled_at' => null,
        ];
    }

    public function expired(): static
    {
        return $this->state(fn () => [
            'starts_at' => now()->subDays(100),
            'ends_at' => now()->subDays(10),
            'status' => SubscriptionStatus::Expired,
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn () => [
            'status' => SubscriptionStatus::Cancelled,
            'cancelled_at' => now(),
        ]);
    }
}
