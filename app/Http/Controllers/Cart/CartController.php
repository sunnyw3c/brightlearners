<?php

namespace App\Http\Controllers\Cart;

use App\Domains\Commerce\Actions\ApplyCoupon;
use App\Domains\Commerce\Services\CartManager;
use App\Domains\Commerce\Services\PricingService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class CartController extends Controller
{
    public function __invoke(
        Request $request,
        CartManager $cartManager,
        PricingService $pricing,
        ApplyCoupon $applyCoupon,
    ): Response {
        $cart = $cartManager->current($request);
        $removed = $cartManager->removeUnavailable($cart);
        $cart->load(['items.product', 'coupon']);
        $couponMessage = null;

        if ($cart->coupon !== null) {
            try {
                $subtotal = $pricing->calculate($cart->items->pluck('product'), $request->user())->subtotal;
                $applyCoupon->validate($cart->coupon, $subtotal, $request->user());
            } catch (ValidationException $exception) {
                $couponMessage = $exception->errors()['coupon'][0] ?? 'The coupon was removed.';
                $cart->update(['coupon_id' => null]);
                $cart->unsetRelation('coupon');
            }
        }

        $breakdown = $pricing->calculate($cart->items->pluck('product'), $request->user(), $cart->coupon);

        return Inertia::render('cart/show', [
            'cart' => [
                'id' => $cart->id,
                'coupon' => $cart->coupon?->code,
                'pricing' => $breakdown->toArray(),
            ],
            'notice' => $removed !== []
                ? implode(', ', $removed).' was removed because it is no longer available.'
                : $couponMessage,
        ]);
    }
}
