<?php

namespace Database\Factories;

use App\Domains\Catalog\Enums\ProductStatus;
use App\Domains\Catalog\Enums\ProductType;
use App\Domains\Catalog\Models\Product;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
#[UseModel(Product::class)]
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->sentence(3);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'type' => ProductType::TopicPack,
            'short_description' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'regular_price' => 7_900,
            'currency' => 'INR',
            'member_discount_eligible' => false,
            'status' => ProductStatus::Draft,
            'featured' => false,
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => ProductStatus::Active,
            'published_at' => now(),
        ]);
    }

    public function bundle(): static
    {
        return $this->state(fn (array $attributes): array => [
            'type' => ProductType::Bundle,
            'regular_price' => 34_900,
        ]);
    }

    public function onSale(int $salePrice, ?DateTimeInterface $startsAt = null, ?DateTimeInterface $endsAt = null): static
    {
        return $this->state(fn (array $attributes): array => [
            'sale_price' => $salePrice,
            'sale_starts_at' => $startsAt,
            'sale_ends_at' => $endsAt,
        ]);
    }
}
