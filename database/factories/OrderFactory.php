<?php

namespace Database\Factories;

use App\Domains\Commerce\Enums\OrderStatus;
use App\Domains\Commerce\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<Order> */
#[UseModel(Order::class)]
class OrderFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'order_number' => 'TEST-'.Str::upper(Str::random(12)),
            'user_id' => User::factory(),
            'subtotal' => 19_900,
            'discount' => 0,
            'tax' => 0,
            'total' => 19_900,
            'currency' => 'INR',
            'status' => OrderStatus::PendingPayment,
            'billing_name' => fake()->name(),
            'billing_email' => fake()->safeEmail(),
            'expires_at' => now()->addMinutes(30),
        ];
    }

    public function paid(): static
    {
        return $this->state(fn (): array => ['status' => OrderStatus::Paid, 'paid_at' => now(), 'expires_at' => null]);
    }
}
