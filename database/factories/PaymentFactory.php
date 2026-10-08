<?php

namespace Database\Factories;

use App\Domains\Commerce\Models\Order;
use App\Domains\Payments\Enums\PaymentStatus;
use App\Domains\Payments\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Payment>
 */
#[UseModel(Payment::class)]
class PaymentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'provider' => 'razorpay',
            'provider_order_id' => 'order_'.Str::random(14),
            'provider_payment_id' => null,
            'amount' => 10_000,
            'currency' => 'INR',
            'status' => PaymentStatus::Created,
            'method' => 'card',
            'failure_reason' => null,
            'verified_at' => null,
            'paid_at' => null,
            'payload_reference' => null,
        ];
    }

    public function captured(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => PaymentStatus::Captured,
            'provider_payment_id' => 'pay_'.Str::random(14),
            'verified_at' => now(),
            'paid_at' => now(),
        ]);
    }
}
