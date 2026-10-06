<?php

use App\Domains\Catalog\Actions\ActivateProduct;
use App\Domains\Catalog\Enums\ProductStatus;
use App\Domains\Catalog\Models\Product;
use App\Domains\Content\Models\LearningResource;
use Illuminate\Validation\ValidationException;

test('refuses to activate a product with no resources', function () {
    $product = Product::factory()->create();

    expect(fn () => app(ActivateProduct::class)->handle($product))
        ->toThrow(ValidationException::class);

    expect($product->refresh()->status)->toBe(ProductStatus::Draft);
});

test('refuses to activate a product whose resource has no published version', function () {
    $product = Product::factory()->create();
    $resource = LearningResource::factory()->create();

    $product->resources()->attach($resource->id, ['sort_order' => 0, 'version_policy' => 'current']);

    expect(fn () => app(ActivateProduct::class)->handle($product))
        ->toThrow(ValidationException::class);

    expect($product->refresh()->status)->toBe(ProductStatus::Draft);
});

test('refuses to activate a bundle with only one active child product', function () {
    $bundle = Product::factory()->bundle()->create();
    $child = Product::factory()->active()->create();

    $bundle->childProducts()->attach($child->id, ['sort_order' => 0]);

    expect(fn () => app(ActivateProduct::class)->handle($bundle))
        ->toThrow(ValidationException::class);

    expect($bundle->refresh()->status)->toBe(ProductStatus::Draft);
});

test('activates a product once it has a published, previewed resource', function () {
    ['resource' => $resource] = createPublishedFreeResource();
    $product = Product::factory()->create();
    $product->resources()->attach($resource->id, ['sort_order' => 0, 'version_policy' => 'current']);

    $activated = app(ActivateProduct::class)->handle($product);

    expect($activated->status)->toBe(ProductStatus::Active);
    expect($activated->published_at)->not->toBeNull();
});

test('activates a bundle once it has two active child products', function () {
    $bundle = Product::factory()->bundle()->create();
    $childA = Product::factory()->active()->create();
    $childB = Product::factory()->active()->create();

    $bundle->childProducts()->attach($childA->id, ['sort_order' => 0]);
    $bundle->childProducts()->attach($childB->id, ['sort_order' => 1]);

    $activated = app(ActivateProduct::class)->handle($bundle);

    expect($activated->status)->toBe(ProductStatus::Active);
});
