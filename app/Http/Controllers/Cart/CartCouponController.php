<?php

namespace App\Http\Controllers\Cart;

use App\Domains\Commerce\Actions\ApplyCoupon;
use App\Domains\Commerce\Services\CartManager;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cart\ApplyCouponRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CartCouponController extends Controller
{
    public function store(ApplyCouponRequest $request, CartManager $cartManager, ApplyCoupon $applyCoupon): RedirectResponse
    {
        $cart = $cartManager->current($request);
        $coupon = $applyCoupon->handle($cart, $request->string('coupon')->toString(), $request->user());

        return redirect()->route('cart.show')->with('success', "Coupon {$coupon->code} applied.");
    }

    public function destroy(Request $request, CartManager $cartManager): RedirectResponse
    {
        $cartManager->current($request)->update(['coupon_id' => null]);

        return redirect()->route('cart.show')->with('success', 'Coupon removed.');
    }
}
