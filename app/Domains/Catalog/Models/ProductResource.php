<?php

namespace App\Domains\Catalog\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Which resources a product includes, with a sort order
 * (docs/plan/phase-06-product-catalogue.md, step 6.2).
 */
class ProductResource extends Pivot
{
    protected $table = 'product_resources';
}
