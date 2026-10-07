<?php

namespace App\Domains\Commerce\Data;

use App\Domains\Catalog\Models\Product;
use App\Support\Money;

final readonly class PriceLine
{
    public function __construct(
        public Product $product,
        public int $unitPrice,
        public int $memberDiscount,
        public int $couponDiscount,
        public int $tax,
        public int $total,
    ) {}

    public function discount(): int
    {
        return $this->memberDiscount + $this->couponDiscount;
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'product_id' => $this->product->id,
            'name' => $this->product->name,
            'slug' => $this->product->slug,
            'type' => $this->product->type->value,
            'sku' => $this->product->sku,
            'cover_image_url' => $this->product->cover_path === null ? null : asset('storage/'.$this->product->cover_path),
            'unit_price' => Money::toProp($this->unitPrice, $this->product->currency),
            'member_discount' => Money::toProp($this->memberDiscount, $this->product->currency),
            'coupon_discount' => Money::toProp($this->couponDiscount, $this->product->currency),
            'discount' => Money::toProp($this->discount(), $this->product->currency),
            'tax' => Money::toProp($this->tax, $this->product->currency),
            'total' => Money::toProp($this->total, $this->product->currency),
            'quantity' => 1,
        ];
    }
}
