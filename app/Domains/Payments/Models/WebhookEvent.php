<?php

namespace App\Domains\Payments\Models;

use App\Domains\Payments\Enums\WebhookStatus;
use Database\Factories\WebhookEventFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'provider', 'event_id', 'event_type', 'signature_valid',
    'payload', 'received_at', 'processed_at', 'status',
    'error', 'attempts',
])]
#[UseFactory(WebhookEventFactory::class)]
class WebhookEvent extends Model
{
    /** @use HasFactory<WebhookEventFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'signature_valid' => 'boolean',
            'status' => WebhookStatus::class,
            'received_at' => 'datetime',
            'processed_at' => 'datetime',
            'payload' => 'encrypted',
        ];
    }
}
