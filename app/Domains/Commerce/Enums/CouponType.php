<?php

namespace App\Domains\Commerce\Enums;

enum CouponType: string
{
    case Percent = 'percent';
    case Fixed = 'fixed';
}
