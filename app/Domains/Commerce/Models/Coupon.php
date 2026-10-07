<?php

namespace App\Domains\Commerce\Models;

use App\Domains\Commerce\Enums\CouponType;
use Database\Factories\CouponFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

#[Fillable(['code', 'type', 'value', 'minimum_order', 'starts_at', 'expires_at', 'max_uses', 'uses_per_user', 'active'])]
#[UseFactory(CouponFactory::class)]
class Coupon extends Model
{
    /** @use HasFactory<CouponFactory> */
    use HasFactory;

    protected static function booted(): void
    {
        static::saving(function (Coupon $coupon): void {
            $coupon->code = Str::upper(trim($coupon->code));
        });
    }

    /** @return HasMany<CouponUsage, $this> */
    public function usages(): HasMany
    {
        return $this->hasMany(CouponUsage::class);
    }

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'type' => CouponType::class,
            'starts_at' => 'datetime',
            'expires_at' => 'datetime',
            'active' => 'boolean',
        ];
    }
}
