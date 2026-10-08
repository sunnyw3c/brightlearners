<?php

namespace App\Domains\Commerce\Enums;

/**
 * Order state machine statuses (docs/plan/phase-07-cart-pricing-orders.md, step 7.7).
 */
enum OrderStatus: string
{
    case Draft = 'draft';
    case PendingPayment = 'pending_payment';
    case Paid = 'paid';
    case Failed = 'failed';
    case Cancelled = 'cancelled';
    case Refunded = 'refunded';
    case PartiallyRefunded = 'partially_refunded';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::PendingPayment => 'Pending Payment',
            self::Paid => 'Paid',
            self::Failed => 'Failed',
            self::Cancelled => 'Cancelled',
            self::Refunded => 'Refunded',
            self::PartiallyRefunded => 'Partially Refunded',
        };
    }
}
