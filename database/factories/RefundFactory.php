<?php

namespace Database\Factories;

use App\Domains\Payments\Enums\RefundStatus;
use App\Domains\Payments\Models\Payment;
use App\Domains\Payments\Models\Refund;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Refund>
 */
#[UseModel(Refund::class)]
class RefundFactory extends Factory
{
    public function definition(): array
    {
        return [
            'payment_id' => Payment::factory()->captured(),
            'amount' => 10_000,
            'status' => RefundStatus::Pending,
            'provider_refund_id' => 'rfnd_'.Str::random(14),
            'reason' => 'Customer request',
            'initiated_by' => User::factory(),
            'processed_at' => null,
        ];
    }

    public function processed(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => RefundStatus::Processed,
            'processed_at' => now(),
        ]);
    }
}
