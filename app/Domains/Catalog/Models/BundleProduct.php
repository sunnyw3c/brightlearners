<?php

namespace App\Domains\Catalog\Models;

use App\Domains\Catalog\Enums\ProductType;
use Illuminate\Database\Eloquent\Relations\Pivot;
use RuntimeException;

/**
 * Which products a bundle includes (docs/plan/phase-06-product-catalogue.md,
 * step 6.3). A bundle cannot contain another bundle — enforced here, not
 * just by convention.
 */
class BundleProduct extends Pivot
{
    protected $table = 'bundle_products';

    protected static function booted(): void
    {
        static::creating(function (BundleProduct $bundleProduct): void {
            $child = Product::query()->find($bundleProduct->product_id);

            if ($child?->type === ProductType::Bundle) {
                throw new RuntimeException('A bundle cannot contain another bundle.');
            }
        });
    }
}
