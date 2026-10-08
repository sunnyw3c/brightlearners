<?php

namespace Database\Factories;

use App\Domains\Catalog\Models\Product;
use App\Domains\Commerce\Models\Order;
use App\Domains\Commerce\Models\OrderItem;
use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrderItem>
 */
#[UseModel(OrderItem::class)]
class OrderItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'product_id' => Product::factory(),
            'product_name' => fake()->sentence(3),
            'product_type' => 'topic_pack',
            'sku' => fake()->bothify('SKU-????-####'),
            'unit_price' => 10_000,
            'quantity' => 1,
            'discount' => 0,
            'tax' => 0,
            'total' => 10_000,
            'metadata' => [],
        ];
    }
}
