<?php

use App\Domains\Catalog\Models\Product;
use App\Domains\Catalog\Services\ProductPrice;
use Illuminate\Validation\ValidationException;

test('returns the regular price when there is no sale price', function () {
    $product = Product::factory()->create(['regular_price' => 10_000, 'sale_price' => null]);

    expect(ProductPrice::for($product))->toBe(10_000);
});

test('returns the sale price while the sale window is open', function () {
    $product = Product::factory()->create([
        'regular_price' => 10_000,
        'sale_price' => 8_000,
        'sale_starts_at' => now()->subDay(),
        'sale_ends_at' => now()->addDay(),
    ]);

    expect(ProductPrice::for($product))->toBe(8_000);
});

test('returns the regular price before the sale window starts', function () {
    $product = Product::factory()->create([
        'regular_price' => 10_000,
        'sale_price' => 8_000,
        'sale_starts_at' => now()->addDay(),
        'sale_ends_at' => now()->addDays(2),
    ]);

    expect(ProductPrice::for($product))->toBe(10_000);
});

test('returns the regular price after the sale window ends', function () {
    $product = Product::factory()->create([
        'regular_price' => 10_000,
        'sale_price' => 8_000,
        'sale_starts_at' => now()->subDays(2),
        'sale_ends_at' => now()->subDay(),
    ]);

    expect(ProductPrice::for($product))->toBe(10_000);
});

test('treats the sale window boundaries as inclusive', function () {
    $product = Product::factory()->create([
        'regular_price' => 10_000,
        'sale_price' => 8_000,
        'sale_starts_at' => now()->subHour(),
        'sale_ends_at' => now()->addHour(),
    ]);

    // A datetime cast stores whole-second precision, so reading the
    // boundary back from the model (rather than reusing the original
    // sub-second `now()` call) is what makes this a same-instant check.
    expect(ProductPrice::for($product, $product->sale_starts_at))->toBe(8_000);
    expect(ProductPrice::for($product, $product->sale_ends_at))->toBe(8_000);
});

test('an open-ended sale applies once it has started', function () {
    $product = Product::factory()->create([
        'regular_price' => 10_000,
        'sale_price' => 8_000,
        'sale_starts_at' => now()->subDay(),
        'sale_ends_at' => null,
    ]);

    expect(ProductPrice::for($product))->toBe(8_000);
});

test('refuses to save a sale price that is not lower than the regular price', function () {
    expect(fn () => Product::factory()->create(['regular_price' => 10_000, 'sale_price' => 10_000]))
        ->toThrow(ValidationException::class);

    expect(fn () => Product::factory()->create(['regular_price' => 10_000, 'sale_price' => 15_000]))
        ->toThrow(ValidationException::class);
});
