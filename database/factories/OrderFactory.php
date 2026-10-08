<?php

namespace Database\Factories;

use App\Domains\Commerce\Enums\OrderStatus;
use App\Domains\Commerce\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
#[UseModel(Order::class)]
class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'order_number' => 'BL-2026-'.fake()->unique()->numerify('######'),
            'user_id' => User::factory(),
            'subtotal' => 10_000,
            'discount' => 0,
            'tax' => 0,
            'total' => 10_000,
            'currency' => 'INR',
            'status' => OrderStatus::PendingPayment,
            'coupon_id' => null,
            'coupon_code' => null,
            'billing_name' => fake()->name(),
            'billing_email' => fake()->safeEmail(),
            'billing_phone' => fake()->phoneNumber(),
            'expires_at' => now()->addMinutes(30),
        ];
    }
}
