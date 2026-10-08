<?php

namespace App\Domains\Commerce\Services;

use App\Domains\Catalog\Enums\ProductStatus;
use App\Domains\Catalog\Models\Product;
use App\Domains\Catalog\Services\ProductPrice;
use App\Domains\Commerce\Actions\ApplyCoupon;
use App\Domains\Commerce\Models\Cart;
use App\Domains\Commerce\Models\CartItem;
use App\Domains\Commerce\Models\Coupon;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CartManager
{
    public const COOKIE_NAME = 'cart_token';

    public function getCart(?User $user = null): Cart
    {
        $user ??= Auth::user();

        if ($user !== null) {
            $cart = Cart::query()->firstOrCreate(
                ['user_id' => $user->id],
                ['token' => (string) Str::uuid(), 'currency' => 'INR']
            );

            // If a guest cart token cookie exists on the request, merge it into the user cart now
            $guestToken = Cookie::get(self::COOKIE_NAME) ?? request()->cookie(self::COOKIE_NAME);
            if (! empty($guestToken) && is_string($guestToken)) {
                $this->mergeGuestCart($user, $guestToken);
                Cookie::queue(Cookie::forget(self::COOKIE_NAME));
                $cart->refresh();
            }

            return $cart;
        }

        $token = Cookie::get(self::COOKIE_NAME) ?? request()->cookie(self::COOKIE_NAME);

        if (! empty($token) && is_string($token)) {
            $cart = Cart::query()->where('token', $token)->whereNull('user_id')->first();
            if ($cart !== null) {
                return $cart;
            }
        }

        $newToken = (string) Str::uuid();
        Cookie::queue(self::COOKIE_NAME, $newToken, 60 * 24 * 30, null, null, false, true);

        return Cart::query()->create([
            'token' => $newToken,
            'user_id' => null,
            'currency' => 'INR',
        ]);
    }

    public function addItem(Cart $cart, Product $product, int $quantity = 1): CartItem
    {
        if ($product->status !== ProductStatus::Active) {
            throw ValidationException::withMessages([
                'product_id' => 'This product is not available for purchase.',
            ]);
        }

        // Digital products are quantity 1
        return CartItem::query()->firstOrCreate(
            ['cart_id' => $cart->id, 'product_id' => $product->id],
            ['quantity' => 1]
        );
    }

    public function removeItem(Cart $cart, int $productId): void
    {
        CartItem::query()->where('cart_id', $cart->id)->where('product_id', $productId)->delete();
    }

    public function applyCoupon(Cart $cart, Coupon|string $coupon, ?User $user = null): Coupon
    {
        $code = is_string($coupon) ? $coupon->code ?? $coupon : $coupon->code;

        return app(ApplyCoupon::class)->handle($cart, $code, $user);
    }

    public function removeCoupon(Cart $cart): void
    {
        $cart->coupon_id = null;
        $cart->save();
    }

    public function mergeGuestCart(User $user, string $guestToken): void
    {
        $guestCart = Cart::query()->where('token', $guestToken)->whereNull('user_id')->with('items')->first();
        if ($guestCart === null) {
            return;
        }

        $userCart = Cart::query()->firstOrCreate(
            ['user_id' => $user->id],
            ['token' => (string) Str::uuid(), 'currency' => 'INR']
        );

        foreach ($guestCart->items as $guestItem) {
            CartItem::query()->firstOrCreate(
                ['cart_id' => $userCart->id, 'product_id' => $guestItem->product_id],
                ['quantity' => 1]
            );
        }

        if ($userCart->coupon_id === null && $guestCart->coupon_id !== null) {
            $userCart->coupon_id = $guestCart->coupon_id;
            $userCart->save();
        }

        $guestCart->delete();
    }

    /**
     * Re-validate cart items before checkout (step 7.2).
     *
     * @return list<string> Messages describing removed products
     */
    public function reloadAndValidateCart(Cart $cart): array
    {
        $cart->loadMissing(['items.product', 'coupon']);
        $removedMessages = [];

        foreach ($cart->items as $item) {
            $product = $item->product;

            if ($product === null || $product->status !== ProductStatus::Active || $product->trashed()) {
                $productName = $product?->name ?? 'A product';
                $item->delete();
                $removedMessages[] = "{$productName} is no longer available and was removed from your cart.";
            }
        }

        $cart->load('items');

        if ($cart->coupon !== null) {
            $subtotal = $cart->items->sum(fn (CartItem $item) => ProductPrice::for($item->product) * $item->quantity);
            if (! $cart->coupon->isValid($cart->user, $subtotal)) {
                $cart->coupon_id = null;
                $cart->save();
                $removedMessages[] = 'The coupon applied to your cart is no longer valid and was removed.';
            }
        }

        return $removedMessages;
    }
}
