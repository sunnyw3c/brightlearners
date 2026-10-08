<?php

namespace App\Domains\Commerce\Enums;

enum CouponType: string
{
    case Percent = 'percent';
    case Fixed = 'fixed';

    public function label(): string
    {
        return match ($this) {
            self::Percent => 'Percentage Discount',
            self::Fixed => 'Fixed Amount Discount',
        };
    }
}
