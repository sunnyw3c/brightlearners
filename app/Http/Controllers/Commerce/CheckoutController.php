<?php

namespace App\Http\Controllers\Commerce;

use App\Domains\Commerce\Actions\CreateCartOrder;
use App\Domains\Commerce\Services\CartManager;
use App\Domains\Commerce\Services\PricingService;
use App\Domains\Payments\Contracts\PaymentGateway;
use App\Http\Controllers\Controller;
use App\Http\Requests\Commerce\CheckoutRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly CartManager $cartManager,
        private readonly PricingService $pricingService,
        private readonly PaymentGateway $paymentGateway,
    ) {}

    public function show(Request $request): Response|RedirectResponse
    {
        $user = $request->user();
        $cart = $this->cartManager->getCart($user);
        $removedMessages = $this->cartManager->reloadAndValidateCart($cart);

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.show')->with('warning', 'Your cart is empty.');
        }

        $breakdown = $this->pricingService->calculate($cart, $user);

        return Inertia::render('checkout/show', [
            'breakdown' => $breakdown->toArray(),
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
            ],
            'notice' => count($removedMessages) > 0 ? implode(' ', $removedMessages) : null,
        ]);
    }

    public function store(CheckoutRequest $request, CreateCartOrder $createCartOrder): RedirectResponse
    {
        $user = $request->user();
        $cart = $this->cartManager->getCart($user);

        $order = $createCartOrder->handle($cart, $user, $request->validated());

        // Initialize fake/configured payment adapter
        $this->paymentGateway->createPaymentOrder($order);

        return redirect()->route('account.orders.show', $order)->with('success', 'Order created successfully.');
    }
}
