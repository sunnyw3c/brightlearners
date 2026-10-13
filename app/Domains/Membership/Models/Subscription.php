<?php

namespace App\Domains\Membership\Models;

use App\Domains\Commerce\Models\Order;
use App\Domains\Membership\Enums\SubscriptionStatus;
use App\Models\User;
use Database\Factories\SubscriptionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'user_id', 'plan_id', 'source_order_id', 'starts_at', 'ends_at',
    'status', 'auto_renew', 'cancelled_at',
    'provider_subscription_id', 'provider', 'current_term_starts_at',
    'current_term_ends_at', 'grace_ends_at', 'cancel_at_period_end',
])]
#[UseFactory(SubscriptionFactory::class)]
class Subscription extends Model
{
    /** @use HasFactory<SubscriptionFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<SubscriptionPlan, $this>
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }

    /**
     * @return BelongsTo<Order, $this>
     */
    public function sourceOrder(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'source_order_id');
    }

    /**
     * @return HasMany<SubscriptionEvent, $this>
     */
    public function events(): HasMany
    {
        return $this->hasMany(SubscriptionEvent::class);
    }

    public function isActive(): bool
    {
        $hasActiveStatus = $this->status === SubscriptionStatus::Active;
        $isWithinWindow = $this->starts_at->isPast() && ($this->ends_at->isFuture() || $this->isWithinGracePeriod());

        return $hasActiveStatus && $isWithinWindow;
    }

    public function isWithinGracePeriod(): bool
    {
        return $this->grace_ends_at !== null && $this->grace_ends_at->isFuture();
    }

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'cancelled_at' => 'datetime',
            'current_term_starts_at' => 'datetime',
            'current_term_ends_at' => 'datetime',
            'grace_ends_at' => 'datetime',
            'status' => SubscriptionStatus::class,
            'auto_renew' => 'boolean',
            'cancel_at_period_end' => 'boolean',
        ];
    }
}
