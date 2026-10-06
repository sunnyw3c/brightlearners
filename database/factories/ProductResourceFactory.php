<?php

namespace Database\Factories;

use App\Domains\Catalog\Models\Product;
use App\Domains\Catalog\Models\ProductResource;
use App\Domains\Content\Models\LearningResource;
use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductResource>
 */
#[UseModel(ProductResource::class)]
class ProductResourceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'resource_id' => LearningResource::factory(),
            'sort_order' => 0,
            'version_policy' => 'current',
        ];
    }
}
