<?php

namespace Database\Factories;

use App\Domains\Catalog\Enums\ProductType;
use App\Domains\Commerce\Models\Order;
use App\Domains\Commerce\Models\OrderItem;
use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<OrderItem> */
#[UseModel(OrderItem::class)]
class OrderItemFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'product_name' => fake()->sentence(3),
            'product_type' => ProductType::Workbook->value,
            'unit_price' => 19_900,
            'quantity' => 1,
            'discount' => 0,
            'tax' => 0,
            'total' => 19_900,
        ];
    }
}
