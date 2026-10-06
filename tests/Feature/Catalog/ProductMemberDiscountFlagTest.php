<?php

use App\Domains\Catalog\Models\Product;

/**
 * `member_discount_eligible` is read only by pricing (Phase 7); it never
 * grants access (docs/plan/phase-06-product-catalogue.md, step 6.9). The
 * full access guarantee is `AccessServiceTest` in Phase 9 — this just
 * proves the column is a plain, inert boolean today.
 */
test('the flag defaults to false', function () {
    $product = Product::factory()->create();

    expect($product->member_discount_eligible)->toBeFalse();
});

test('toggling the flag changes nothing about a product deliverable or activation state', function () {
    ['resource' => $resource] = createPublishedFreeResource();
    $product = Product::factory()->create(['member_discount_eligible' => false]);
    $product->resources()->attach($resource->id, ['sort_order' => 0, 'version_policy' => 'current']);

    $activatableBefore = $product->meetsActivationRequirements();
    $resourceIdsBefore = $product->deliverableResources()->pluck('id')->all();

    $product->update(['member_discount_eligible' => true]);

    expect($product->meetsActivationRequirements())->toBe($activatableBefore);
    expect($product->deliverableResources()->pluck('id')->all())->toBe($resourceIdsBefore);
});
