<?php

namespace App\Domains\Commerce\Data;

use App\Domains\Commerce\Models\Coupon;
use App\Support\Money;

class PriceBreakdown
{
    /**
     * @param  list<PriceBreakdownLine>  $lines
     */
    public function __construct(
        public readonly array $lines,
        public readonly int $subtotal,
        public readonly int $memberDiscount,
        public readonly int $couponDiscount,
        public readonly int $discount,
        public readonly int $tax,
        public readonly int $total,
        public readonly ?Coupon $coupon = null,
        public readonly string $currency = 'INR',
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'lines' => array_map(fn (PriceBreakdownLine $line) => $line->toArray(), $this->lines),
            'subtotal' => Money::toProp($this->subtotal, $this->currency),
            'member_discount' => Money::toProp($this->memberDiscount, $this->currency),
            'coupon_discount' => Money::toProp($this->couponDiscount, $this->currency),
            'discount' => Money::toProp($this->discount, $this->currency),
            'tax' => Money::toProp($this->tax, $this->currency),
            'total' => Money::toProp($this->total, $this->currency),
            'coupon' => $this->coupon ? [
                'id' => $this->coupon->id,
                'code' => $this->coupon->code,
                'type' => $this->coupon->type->value,
                'value' => $this->coupon->value,
            ] : null,
            'currency' => $this->currency,
        ];
    }
}
