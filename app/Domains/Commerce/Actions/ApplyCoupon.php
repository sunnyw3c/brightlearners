<?php

namespace App\Domains\Commerce\Actions;

use App\Domains\Commerce\Models\Cart;
use App\Domains\Commerce\Models\Coupon;
use App\Domains\Commerce\Services\PricingService;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class ApplyCoupon
{
    public function __construct(
        private readonly PricingService $pricingService,
    ) {}

    public function handle(Cart $cart, string $code, ?User $user = null): Coupon
    {
        $code = strtoupper(trim($code));

        $coupon = Coupon::query()->where('code', $code)->first();

        if ($coupon === null) {
            throw ValidationException::withMessages([
                'code' => 'Invalid coupon code.',
            ]);
        }

        $user ??= $cart->user;

        // Calculate subtotal before applying coupon
        $tempCart = clone $cart;
        $tempCart->coupon_id = null;
        $currentBreakdown = $this->pricingService->calculate($tempCart, $user);

        $reason = null;
        if (! $coupon->isValid($user, $currentBreakdown->subtotal, $reason)) {
            throw ValidationException::withMessages([
                'code' => $reason ?? 'This coupon is not valid.',
            ]);
        }

        // Test pricing with the coupon
        $tempCart->coupon_id = $coupon->id;
        $tempCart->setRelation('coupon', $coupon);
        $testBreakdown = $this->pricingService->calculate($tempCart, $user);

        // Step 7.5 pilot rule: Razorpay cannot process a zero payment
        if ($testBreakdown->total <= 0) {
            throw ValidationException::withMessages([
                'code' => 'Coupons cannot reduce the order total to zero.',
            ]);
        }

        $cart->coupon_id = $coupon->id;
        $cart->save();

        return $coupon;
    }
}
