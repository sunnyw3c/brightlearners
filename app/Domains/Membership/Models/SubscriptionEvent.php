<?php

namespace App\Domains\Membership\Models;

use App\Domains\Membership\Enums\SubscriptionEventType;
use Database\Factories\SubscriptionEventFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'subscription_id', 'type', 'occurred_at', 'metadata',
])]
#[UseFactory(SubscriptionEventFactory::class)]
class SubscriptionEvent extends Model
{
    /** @use HasFactory<SubscriptionEventFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<Subscription, $this>
     */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    protected function casts(): array
    {
        return [
            'type' => SubscriptionEventType::class,
            'occurred_at' => 'datetime',
            'metadata' => 'array',
        ];
    }
}
