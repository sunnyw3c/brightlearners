<?php

namespace Database\Factories;

use App\Domains\Commerce\Models\Coupon;
use App\Domains\Commerce\Models\CouponUsage;
use App\Domains\Commerce\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CouponUsage>
 */
#[UseModel(CouponUsage::class)]
class CouponUsageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'coupon_id' => Coupon::factory(),
            'user_id' => User::factory(),
            'order_id' => Order::factory(),
            'used_at' => now(),
        ];
    }
}
