<?php

namespace App\Domains\Commerce\Listeners;

use App\Domains\Commerce\Services\CartManager;
use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class MergeGuestCart
{
    public function __construct(
        private readonly CartManager $cartManager,
        private readonly Request $request,
    ) {}

    public function handle(Login $event): void
    {
        $token = $this->request->cookie((string) config('commerce.cart_cookie'));

        if (! is_string($token)) {
            return;
        }

        $cart = $this->cartManager->mergeGuestCart($event->user, $token);

        Cookie::queue(
            (string) config('commerce.cart_cookie'),
            $cart->token,
            (int) config('commerce.cart_cookie_minutes'),
            secure: $this->request->isSecure(),
            httpOnly: true,
            sameSite: 'lax',
        );
    }
}
