<?php

namespace App\Domains\Payments\Models;

use App\Domains\Commerce\Models\Order;
use App\Domains\Payments\Enums\PaymentStatus;
use Database\Factories\PaymentFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'order_id', 'provider', 'provider_order_id', 'provider_payment_id',
    'amount', 'currency', 'status', 'method', 'failure_reason',
    'verified_at', 'paid_at', 'payload_reference',
])]
#[UseFactory(PaymentFactory::class)]
class Payment extends Model
{
    /** @use HasFactory<PaymentFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<Order, $this>
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * @return HasMany<Refund, $this>
     */
    public function refunds(): HasMany
    {
        return $this->hasMany(Refund::class);
    }

    public function refundedAmount(): int
    {
        return (int) $this->refunds()
            ->whereIn('status', ['pending', 'processed'])
            ->sum('amount');
    }

    public function remainingRefundableAmount(): int
    {
        return max(0, $this->amount - $this->refundedAmount());
    }

    protected function casts(): array
    {
        return [
            'status' => PaymentStatus::class,
            'verified_at' => 'datetime',
            'paid_at' => 'datetime',
            'payload_reference' => 'encrypted',
        ];
    }
}
