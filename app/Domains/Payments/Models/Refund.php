<?php

namespace App\Domains\Payments\Models;

use App\Domains\Payments\Enums\RefundStatus;
use App\Models\User;
use Database\Factories\RefundFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'payment_id', 'amount', 'status', 'provider_refund_id',
    'reason', 'initiated_by', 'processed_at',
])]
#[UseFactory(RefundFactory::class)]
class Refund extends Model
{
    /** @use HasFactory<RefundFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<Payment, $this>
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function initiator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'initiated_by');
    }

    protected function casts(): array
    {
        return [
            'status' => RefundStatus::class,
            'processed_at' => 'datetime',
        ];
    }
}
