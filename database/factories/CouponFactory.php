<?php

namespace Database\Factories;

use App\Domains\Commerce\Enums\CouponType;
use App\Domains\Commerce\Models\Coupon;
use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Coupon> */
#[UseModel(Coupon::class)]
class CouponFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'code' => strtoupper(fake()->unique()->bothify('SAVE##??')),
            'type' => CouponType::Percent,
            'value' => 10,
            'minimum_order' => 0,
            'active' => true,
        ];
    }

    public function fixed(int $paise): static
    {
        return $this->state(fn (): array => ['type' => CouponType::Fixed, 'value' => $paise]);
    }

    public function expired(): static
    {
        return $this->state(fn (): array => ['expires_at' => now()->subMinute()]);
    }
}
