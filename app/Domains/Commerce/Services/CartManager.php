<?php

namespace App\Domains\Commerce\Services;

use App\Domains\Catalog\Enums\ProductStatus;
use App\Domains\Commerce\Models\Cart;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CartManager
{
    public function current(Request $request): Cart
    {
        $token = $request->cookie((string) config('commerce.cart_cookie'));
        $user = $request->user();

        if ($user !== null) {
            $cart = $this->userCart($user);

            if (is_string($token) && $token !== $cart->token) {
                $this->mergeGuestCart($user, $token, $cart);
            }
        } else {
            $cart = is_string($token)
                ? Cart::query()->whereNull('user_id')->where('token', $token)->first()
                : null;

            $cart ??= Cart::query()->create(['token' => (string) Str::uuid(), 'currency' => 'INR']);
        }

        Cookie::queue(
            (string) config('commerce.cart_cookie'),
            $cart->token,
            (int) config('commerce.cart_cookie_minutes'),
            secure: $request->isSecure(),
            httpOnly: true,
            sameSite: 'lax',
        );

        return $cart->load(['items.product', 'coupon']);
    }

    public function mergeGuestCart(User $user, string $token, ?Cart $destination = null): Cart
    {
        return DB::transaction(function () use ($user, $token, $destination): Cart {
            $guest = Cart::query()->whereNull('user_id')->where('token', $token)->lockForUpdate()->first();
            $destination ??= $this->userCart($user);

            if ($guest === null || $guest->is($destination)) {
                return $destination->load(['items.product', 'coupon']);
            }

            foreach ($guest->items()->pluck('product_id') as $productId) {
                $destination->items()->firstOrCreate(['product_id' => $productId], ['quantity' => 1]);
            }

            if ($destination->coupon_id === null && $guest->coupon_id !== null) {
                $destination->update(['coupon_id' => $guest->coupon_id]);
            }

            $guest->delete();

            return $destination->load(['items.product', 'coupon']);
        });
    }

    /** @return list<string> */
    public function removeUnavailable(Cart $cart): array
    {
        $items = $cart->items()
            ->whereHas('product', fn ($query) => $query->where('status', '!=', ProductStatus::Active->value))
            ->with('product')
            ->get();
        $names = $items->pluck('product.name')->filter()->values()->all();

        if ($items->isNotEmpty()) {
            $cart->items()->whereKey($items->modelKeys())->delete();
        }

        return $names;
    }

    private function userCart(User $user): Cart
    {
        return Cart::query()->firstOrCreate(
            ['user_id' => $user->id],
            ['token' => (string) Str::uuid(), 'currency' => 'INR'],
        );
    }
}
