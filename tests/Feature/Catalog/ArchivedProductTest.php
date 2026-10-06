<?php

use App\Domains\Catalog\Enums\ProductStatus;
use App\Domains\Catalog\Models\Product;
use App\Domains\Curriculum\Models\SchoolClass;
use Inertia\Testing\AssertableInertia as Assert;

test('an archived product is excluded from the shop index', function () {
    Product::factory()->create(['status' => ProductStatus::Archived]);

    $this->get('/shop')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page->where('products.data', []));
});

test('an archived product is excluded from its type landing page', function () {
    $product = Product::factory()->create(['status' => ProductStatus::Archived]);

    $this->get('/shop/'.$product->type->routeSegment())
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page->where('products.data', []));
});

test('an archived product still resolves at its canonical URL', function () {
    $class = SchoolClass::factory()->create();
    $product = Product::factory()->create(['status' => ProductStatus::Archived, 'primary_class_id' => $class->id]);

    $this->get("/shop/{$class->slug}/{$product->slug}")
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page->where('product.slug', $product->slug));
});

test('an archived product is never searchable', function () {
    $product = Product::factory()->create(['status' => ProductStatus::Archived]);

    expect($product->shouldBeSearchable())->toBeFalse();
});
