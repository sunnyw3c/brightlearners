<?php

namespace App\Domains\Catalog\Services;

use App\Domains\Catalog\Models\Product;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Date;

/**
 * The sale price when the sale window is open, otherwise the regular
 * price (docs/plan/phase-06-product-catalogue.md, step 6.4). Phase 7's
 * pricing service builds on this.
 */
class ProductPrice
{
    public static function for(Product $product, ?CarbonInterface $at = null): int
    {
        $at ??= Date::now();

        if ($product->sale_price !== null && self::saleWindowOpen($product, $at)) {
            return $product->sale_price;
        }

        return $product->regular_price;
    }

    private static function saleWindowOpen(Product $product, CarbonInterface $at): bool
    {
        if ($product->sale_starts_at !== null && $at->lt($product->sale_starts_at)) {
            return false;
        }

        if ($product->sale_ends_at !== null && $at->gt($product->sale_ends_at)) {
            return false;
        }

        return true;
    }
}
