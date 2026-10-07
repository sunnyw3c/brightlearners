<?php

namespace App\Http\Controllers\Checkout;

use App\Domains\Commerce\Actions\CreateCartOrder;
use App\Domains\Commerce\Services\CartManager;
use App\Domains\Commerce\Services\PricingService;
use App\Domains\Payments\Contracts\PaymentGateway;
use App\Http\Controllers\Controller;
use App\Http\Requests\Checkout\CreateOrderRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CheckoutController extends Controller
{
    public function show(Request $request, CartManager $cartManager, PricingService $pricing): Response|RedirectResponse
    {
        $cart = $cartManager->current($request);
        $removed = $cartManager->removeUnavailable($cart);
        $cart->load(['items.product', 'coupon']);

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.show')->with('error', 'Add a product before checking out.');
        }

        $breakdown = $pricing->calculate($cart->items->pluck('product'), $request->user(), $cart->coupon);

        return Inertia::render('checkout/show', [
            'pricing' => $breakdown->toArray(),
            'billing' => [
                'name' => $request->user()->name,
                'email' => $request->user()->email,
            ],
            'notice' => $removed === [] ? null : 'An unavailable product was removed before checkout.',
        ]);
    }

    public function store(
        CreateOrderRequest $request,
        CartManager $cartManager,
        CreateCartOrder $createCartOrder,
        PaymentGateway $gateway,
    ): RedirectResponse {
        $order = $createCartOrder->handle(
            $cartManager->current($request),
            $request->user(),
            $request->safe()->only(['billing_name', 'billing_email', 'billing_phone']),
        );
        $gateway->createPayment($order);

        return redirect()->route('account.orders.show', $order)
            ->with('success', 'Your order is ready for secure payment.');
    }
}
