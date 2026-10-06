<?php

namespace App\Support\Data;

use App\Domains\Catalog\Models\Product;
use App\Domains\Catalog\Services\ProductPrice;
use App\Support\Money;
use Illuminate\Support\Facades\Storage;

/**
 * The common product-card shape used on the shop index, type landing and
 * related-product rails (mirrors `ResourceCard` from Phase 5).
 */
final class ProductCard
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $slug,
        public readonly ?string $short_description,
        public readonly string $type,
        public readonly ?string $class_name,
        public readonly string $url,
        public readonly ?string $cover_image_url,
        /** @var array{paise: int, formatted: string} */
        public readonly array $regular_price,
        /** @var array{paise: int, formatted: string}|null */
        public readonly ?array $price,
        public readonly bool $on_sale,
    ) {}

    public static function fromProduct(Product $product): self
    {
        $effectivePrice = ProductPrice::for($product);
        $onSale = $effectivePrice !== $product->regular_price;

        return new self(
            id: $product->id,
            name: $product->name,
            slug: $product->slug,
            short_description: $product->short_description,
            type: $product->type->value,
            class_name: $product->primaryClass?->name,
            url: route('shop.show', ['class' => $product->classSlug(), 'slug' => $product->slug]),
            cover_image_url: $product->cover_path !== null ? Storage::disk('previews')->url($product->cover_path) : null,
            regular_price: Money::toProp($product->regular_price, $product->currency),
            price: Money::toProp($effectivePrice, $product->currency),
            on_sale: $onSale,
        );
    }
}
