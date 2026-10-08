<?php

namespace App\Domains\Commerce\Models;

use App\Domains\Commerce\Enums\CouponType;
use App\Models\User;
use Database\Factories\CouponFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'code', 'type', 'value', 'minimum_order', 'starts_at',
    'expires_at', 'max_uses', 'uses_per_user', 'active',
])]
#[UseFactory(CouponFactory::class)]
class Coupon extends Model
{
    /** @use HasFactory<CouponFactory> */
    use HasFactory;

    protected static function booted(): void
    {
        static::saving(function (Coupon $coupon): void {
            $coupon->code = strtoupper($coupon->code);
        });
    }

    /**
     * @return HasMany<CouponUsage, $this>
     */
    public function usages(): HasMany
    {
        return $this->hasMany(CouponUsage::class);
    }

    public function isValid(?User $user = null, int $cartSubtotal = 0, ?string &$reason = null): bool
    {
        if (! $this->active) {
            $reason = 'This coupon is no longer active.';

            return false;
        }

        $now = now();

        if ($this->starts_at !== null && $now->lt($this->starts_at)) {
            $reason = 'This coupon is not valid yet.';

            return false;
        }

        if ($this->expires_at !== null && $now->gt($this->expires_at)) {
            $reason = 'This coupon has expired.';

            return false;
        }

        if ($cartSubtotal < $this->minimum_order) {
            $reason = 'Cart does not meet the minimum order amount for this coupon.';

            return false;
        }

        if ($this->max_uses !== null && $this->usages()->count() >= $this->max_uses) {
            $reason = 'This coupon has reached its maximum use limit.';

            return false;
        }

        if ($user !== null && $this->uses_per_user !== null) {
            $userUsageCount = $this->usages()->where('user_id', $user->id)->count();
            if ($userUsageCount >= $this->uses_per_user) {
                $reason = 'You have already used this coupon the maximum allowed times.';

                return false;
            }
        }

        return true;
    }

    protected function casts(): array
    {
        return [
            'type' => CouponType::class,
            'active' => 'boolean',
            'starts_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }
}
