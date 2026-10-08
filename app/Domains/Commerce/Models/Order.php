<?php

namespace App\Domains\Commerce\Models;

use App\Domains\Commerce\Enums\OrderStatus;
use App\Domains\Commerce\Policies\OrderPolicy;
use App\Models\User;
use Database\Factories\OrderFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable([
    'order_number', 'user_id', 'subtotal', 'discount', 'tax', 'total',
    'currency', 'status', 'coupon_id', 'coupon_code', 'billing_name',
    'billing_email', 'billing_phone', 'billing_state', 'gstin',
    'invoice_number', 'paid_at', 'expires_at',
])]
#[UseFactory(OrderFactory::class)]
#[UsePolicy(OrderPolicy::class)]
class Order extends Model
{
    /** @use HasFactory<OrderFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<Coupon, $this>
     */
    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    /**
     * @return HasMany<OrderItem, $this>
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * @return HasOne<CouponUsage, $this>
     */
    public function couponUsage(): HasOne
    {
        return $this->hasOne(CouponUsage::class);
    }

    protected function casts(): array
    {
        return [
            'status' => OrderStatus::class,
            'paid_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }
}
