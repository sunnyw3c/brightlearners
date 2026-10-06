<?php

namespace Database\Factories;

use App\Domains\Catalog\Models\BundleProduct;
use App\Domains\Catalog\Models\Product;
use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BundleProduct>
 */
#[UseModel(BundleProduct::class)]
class BundleProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'bundle_id' => Product::factory()->bundle(),
            'product_id' => Product::factory(),
            'sort_order' => 0,
        ];
    }
}
