<?php

namespace App\Domains\Commerce\Data;

use App\Support\Money;

final readonly class PriceBreakdown
{
    /**
     * @param  list<PriceLine>  $lines
     */
    public function __construct(
        public array $lines,
        public int $subtotal,
        public int $memberDiscount,
        public int $couponDiscount,
        public int $tax,
        public int $total,
        public string $currency = 'INR',
    ) {}

    public function discount(): int
    {
        return $this->memberDiscount + $this->couponDiscount;
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'lines' => array_map(fn (PriceLine $line): array => $line->toArray(), $this->lines),
            'subtotal' => Money::toProp($this->subtotal, $this->currency),
            'member_discount' => Money::toProp($this->memberDiscount, $this->currency),
            'coupon_discount' => Money::toProp($this->couponDiscount, $this->currency),
            'discount' => Money::toProp($this->discount(), $this->currency),
            'tax' => Money::toProp($this->tax, $this->currency),
            'total' => Money::toProp($this->total, $this->currency),
            'currency' => $this->currency,
        ];
    }
}
