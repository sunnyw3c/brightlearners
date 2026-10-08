<?php

namespace Database\Factories;

use App\Domains\Commerce\Enums\CouponType;
use App\Domains\Commerce\Models\Coupon;
use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Coupon>
 */
#[UseModel(Coupon::class)]
class CouponFactory extends Factory
{
    public function definition(): array
    {
        return [
            'code' => strtoupper(Str::random(8)),
            'type' => CouponType::Percent,
            'value' => 10,
            'minimum_order' => 0,
            'starts_at' => null,
            'expires_at' => null,
            'max_uses' => null,
            'uses_per_user' => null,
            'active' => true,
        ];
    }

    public function fixed(int $paise): static
    {
        return $this->state(fn (array $attributes): array => [
            'type' => CouponType::Fixed,
            'value' => $paise,
        ]);
    }

    public function percent(int $percent): static
    {
        return $this->state(fn (array $attributes): array => [
            'type' => CouponType::Percent,
            'value' => $percent,
        ]);
    }
}
