<?php

namespace App\Domains\Membership\Enums;

enum SubscriptionEventType: string
{
    case Activated = 'activated';
    case Renewed = 'renewed';
    case ExpiringNotice = 'expiring_notice';
    case Expired = 'expired';
    case Cancelled = 'cancelled';
}
