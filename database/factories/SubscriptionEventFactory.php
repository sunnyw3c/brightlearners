<?php

namespace Database\Factories;

use App\Domains\Membership\Enums\SubscriptionEventType;
use App\Domains\Membership\Models\Subscription;
use App\Domains\Membership\Models\SubscriptionEvent;
use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SubscriptionEvent>
 */
#[UseModel(SubscriptionEvent::class)]
class SubscriptionEventFactory extends Factory
{
    public function definition(): array
    {
        return [
            'subscription_id' => Subscription::factory(),
            'type' => SubscriptionEventType::Activated,
            'occurred_at' => now(),
            'metadata' => null,
        ];
    }
}
