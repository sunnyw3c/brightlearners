<?php

namespace App\Domains\Accounts\Enums;

enum FeedbackCategory: string
{
    case Content = 'content';
    case Ux = 'ux';
    case Payment = 'payment';
    case Support = 'support';

    public function label(): string
    {
        return match ($this) {
            self::Content => 'Learning Content',
            self::Ux => 'Usability & Design',
            self::Payment => 'Payment & Checkout',
            self::Support => 'Customer Support',
        };
    }
}
