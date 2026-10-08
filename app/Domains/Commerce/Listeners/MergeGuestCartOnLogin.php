<?php

namespace App\Domains\Commerce\Listeners;

use App\Domains\Commerce\Services\CartManager;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Request;

class MergeGuestCartOnLogin
{
    public function __construct(
        private readonly CartManager $cartManager,
    ) {}

    public function handle(Login $event): void
    {
        $token = Cookie::get(CartManager::COOKIE_NAME) ?? Request::cookie(CartManager::COOKIE_NAME);

        if (! empty($token) && is_string($token)) {
            $this->cartManager->mergeGuestCart($event->user, $token);
            Cookie::queue(Cookie::forget(CartManager::COOKIE_NAME));
        }
    }
}
