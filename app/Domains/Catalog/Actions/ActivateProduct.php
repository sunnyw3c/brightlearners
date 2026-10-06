<?php

namespace App\Domains\Catalog\Actions;

use App\Domains\Catalog\Enums\ProductStatus;
use App\Domains\Catalog\Models\Product;
use Illuminate\Validation\ValidationException;

/**
 * The only way a product becomes `active` (docs/plan/phase-06-product-catalogue.md,
 * step 6.5). Refuses unless the product has a real deliverable: a resource
 * with a published current version and a preview, or (for a bundle) at
 * least two active child products.
 */
class ActivateProduct
{
    public function handle(Product $product): Product
    {
        if (! $product->meetsActivationRequirements()) {
            throw ValidationException::withMessages([
                'status' => 'This product has no publishable deliverable yet.',
            ]);
        }

        $product->update([
            'status' => ProductStatus::Active,
            'published_at' => $product->published_at ?? now(),
        ]);

        return $product;
    }
}
