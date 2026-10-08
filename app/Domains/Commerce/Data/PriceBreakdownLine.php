<?php

namespace App\Domains\Commerce\Data;

use App\Domains\Catalog\Models\Product;
use App\Support\Money;

class PriceBreakdownLine
{
    public function __construct(
        public readonly Product $product,
        public readonly int $quantity,
        public readonly int $unitPrice,
        public readonly int $lineSubtotal,
        public readonly int $memberDiscount,
        public readonly int $couponDiscount,
        public readonly int $lineDiscount,
        public readonly int $tax,
        public readonly int $lineTotal,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'product_type' => $this->product->type->value,
            'sku' => $this->product->sku,
            'quantity' => $this->quantity,
            'unit_price' => Money::toProp($this->unitPrice, $this->product->currency),
            'line_subtotal' => Money::toProp($this->lineSubtotal, $this->product->currency),
            'member_discount' => Money::toProp($this->memberDiscount, $this->product->currency),
            'coupon_discount' => Money::toProp($this->couponDiscount, $this->product->currency),
            'line_discount' => Money::toProp($this->lineDiscount, $this->product->currency),
            'tax' => Money::toProp($this->tax, $this->product->currency),
            'line_total' => Money::toProp($this->lineTotal, $this->product->currency),
        ];
    }
}
