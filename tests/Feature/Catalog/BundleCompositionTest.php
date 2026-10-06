<?php

use App\Domains\Catalog\Models\Product;
use App\Domains\Content\Models\LearningResource;

test('a normal product deliverable resources are its own mapped resources', function () {
    $resource = LearningResource::factory()->create();
    $product = Product::factory()->create();
    $product->resources()->attach($resource->id, ['sort_order' => 0, 'version_policy' => 'current']);

    expect($product->deliverableResources()->pluck('id')->all())->toBe([$resource->id]);
});

test('a bundle deliverable resources is the union of its child products resources, without duplicates', function () {
    $sharedResource = LearningResource::factory()->create();
    $resourceA = LearningResource::factory()->create();
    $resourceB = LearningResource::factory()->create();

    $childA = Product::factory()->create();
    $childA->resources()->attach([
        $sharedResource->id => ['sort_order' => 0, 'version_policy' => 'current'],
        $resourceA->id => ['sort_order' => 1, 'version_policy' => 'current'],
    ]);

    $childB = Product::factory()->create();
    $childB->resources()->attach([
        $sharedResource->id => ['sort_order' => 0, 'version_policy' => 'current'],
        $resourceB->id => ['sort_order' => 1, 'version_policy' => 'current'],
    ]);

    $bundle = Product::factory()->bundle()->create();
    $bundle->childProducts()->attach($childA->id, ['sort_order' => 0]);
    $bundle->childProducts()->attach($childB->id, ['sort_order' => 1]);

    $deliverable = $bundle->deliverableResources();

    expect($deliverable)->toHaveCount(3);
    expect($deliverable->pluck('id')->sort()->values()->all())
        ->toBe(collect([$sharedResource->id, $resourceA->id, $resourceB->id])->sort()->values()->all());
});

test('a bundle cannot contain another bundle', function () {
    $bundle = Product::factory()->bundle()->create();
    $innerBundle = Product::factory()->bundle()->create();

    expect(fn () => $bundle->childProducts()->attach($innerBundle->id, ['sort_order' => 0]))
        ->toThrow(RuntimeException::class);

    expect($bundle->childProducts()->count())->toBe(0);
});
