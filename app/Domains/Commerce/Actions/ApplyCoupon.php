<?php

namespace App\Domains\Commerce\Actions;

use App\Domains\Commerce\Models\Cart;
use App\Domains\Commerce\Models\Coupon;
use App\Domains\Commerce\Services\PricingService;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ApplyCoupon
{
    public function __construct(private readonly PricingService $pricing) {}

    public function handle(Cart $cart, string $code, ?User $user): Coupon
    {
        $coupon = Coupon::query()->where('code', Str::upper(trim($code)))->first();

        if ($coupon === null) {
            $this->fail('This coupon code is not valid.');
        }

        $products = $cart->items()->with('product')->get()->pluck('product');
        $subtotal = $this->pricing->calculate($products, $user)->subtotal;
        $this->validate($coupon, $subtotal, $user);

        $breakdown = $this->pricing->calculate($products, $user, $coupon);

        if ($breakdown->total <= 0) {
            $this->fail('This coupon cannot reduce the order total to zero.');
        }

        $cart->update(['coupon_id' => $coupon->id]);

        return $coupon;
    }

    public function validate(Coupon $coupon, int $subtotal, ?User $user): void
    {
        if (! $coupon->active) {
            $this->fail('This coupon is not active.');
        }

        if ($coupon->starts_at !== null && now()->lt($coupon->starts_at)) {
            $this->fail('This coupon is not active yet.');
        }

        if ($coupon->expires_at !== null && now()->gt($coupon->expires_at)) {
            $this->fail('This coupon has expired.');
        }

        if ($subtotal < $coupon->minimum_order) {
            $this->fail('The cart does not meet this coupon’s minimum order value.');
        }

        if ($coupon->max_uses !== null && $coupon->usages()->count() >= $coupon->max_uses) {
            $this->fail('This coupon has reached its usage limit.');
        }

        if ($user !== null && $coupon->uses_per_user !== null
            && $coupon->usages()->where('user_id', $user->id)->count() >= $coupon->uses_per_user) {
            $this->fail('You have already used this coupon the maximum number of times.');
        }
    }

    private function fail(string $message): never
    {
        throw ValidationException::withMessages(['coupon' => $message]);
    }
}
