<?php

namespace Database\Factories;

use App\Domains\Catalog\Models\Product;
use App\Domains\Commerce\Models\Cart;
use App\Domains\Commerce\Models\CartItem;
use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<CartItem> */
#[UseModel(CartItem::class)]
class CartItemFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        return ['cart_id' => Cart::factory(), 'product_id' => Product::factory(), 'quantity' => 1];
    }
}
