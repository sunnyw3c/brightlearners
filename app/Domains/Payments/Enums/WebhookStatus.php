<?php

namespace App\Domains\Payments\Enums;

enum WebhookStatus: string
{
    case Received = 'received';
    case Pending = 'pending';
    case Processed = 'processed';
    case Failed = 'failed';
    case Ignored = 'ignored';

    public function label(): string
    {
        return match ($this) {
            self::Received => 'Received',
            self::Pending => 'Pending',
            self::Processed => 'Processed',
            self::Failed => 'Failed',
            self::Ignored => 'Ignored',
        };
    }
}
