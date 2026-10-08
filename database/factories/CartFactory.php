<?php

namespace Database\Factories;

use App\Domains\Commerce\Models\Cart;
use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Cart>
 */
#[UseModel(Cart::class)]
class CartFactory extends Factory
{
    public function definition(): array
    {
        return [
            'token' => (string) Str::uuid(),
            'user_id' => null,
            'coupon_id' => null,
            'currency' => 'INR',
        ];
    }
}
