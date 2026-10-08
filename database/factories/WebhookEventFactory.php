<?php

namespace Database\Factories;

use App\Domains\Payments\Enums\WebhookStatus;
use App\Domains\Payments\Models\WebhookEvent;
use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<WebhookEvent>
 */
#[UseModel(WebhookEvent::class)]
class WebhookEventFactory extends Factory
{
    public function definition(): array
    {
        return [
            'provider' => 'razorpay',
            'event_id' => 'evt_'.Str::random(14),
            'event_type' => 'payment.captured',
            'signature_valid' => true,
            'payload' => json_encode(['event' => 'payment.captured']),
            'received_at' => now(),
            'processed_at' => null,
            'status' => WebhookStatus::Received,
            'error' => null,
            'attempts' => 1,
        ];
    }
}
