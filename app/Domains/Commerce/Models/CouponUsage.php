<?php

namespace App\Domains\Commerce\Models;

use App\Models\User;
use Database\Factories\CouponUsageFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'coupon_id', 'user_id', 'order_id', 'used_at',
])]
#[UseFactory(CouponUsageFactory::class)]
class CouponUsage extends Model
{
    /** @use HasFactory<CouponUsageFactory> */
    use HasFactory;

    public $timestamps = false;

    /**
     * @return BelongsTo<Coupon, $this>
     */
    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<Order, $this>
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    protected function casts(): array
    {
        return [
            'used_at' => 'datetime',
        ];
    }
}
