<?php

namespace App\Domains\Catalog\Actions;

use App\Domains\Catalog\Models\Product;
use App\Support\Audit;
use Carbon\CarbonInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;

class ChangeProductPrice
{
    public function handle(
        Product $product,
        int $regularPrice,
        ?int $salePrice = null,
        ?CarbonInterface $saleStartsAt = null,
        ?CarbonInterface $saleEndsAt = null
    ): Product {
        $actor = Auth::user();

        if ($actor !== null && ! $actor->can('products.edit-price')) {
            throw new AuthorizationException('Only a role with products.edit-price can change a live price.');
        }

        $oldPrice = [
            'regular_price' => $product->regular_price,
            'sale_price' => $product->sale_price,
            'sale_starts_at' => $product->sale_starts_at,
            'sale_ends_at' => $product->sale_ends_at,
        ];

        $product->update([
            'regular_price' => $regularPrice,
            'sale_price' => $salePrice,
            'sale_starts_at' => $saleStartsAt,
            'sale_ends_at' => $saleEndsAt,
        ]);

        $newPrice = [
            'regular_price' => $regularPrice,
            'sale_price' => $salePrice,
            'sale_starts_at' => $saleStartsAt,
            'sale_ends_at' => $saleEndsAt,
        ];

        Audit::record(
            'price.changed',
            $product,
            $oldPrice,
            $newPrice,
            ['actor_id' => $actor?->id]
        );

        return $product;
    }
}
