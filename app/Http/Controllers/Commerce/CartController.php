<?php

namespace App\Http\Controllers\Commerce;

use App\Domains\Catalog\Models\Product;
use App\Domains\Commerce\Actions\ApplyCoupon;
use App\Domains\Commerce\Services\CartManager;
use App\Domains\Commerce\Services\PricingService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Commerce\AddToCartRequest;
use App\Http\Requests\Commerce\ApplyCouponRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CartController extends Controller
{
    public function __construct(
        private readonly CartManager $cartManager,
        private readonly PricingService $pricingService,
    ) {}

    public function show(Request $request): Response
    {
        $cart = $this->cartManager->getCart($request->user());
        $removedMessages = $this->cartManager->reloadAndValidateCart($cart);

        $breakdown = $this->pricingService->calculate($cart, $request->user());

        return Inertia::render('cart/show', [
            'breakdown' => $breakdown->toArray(),
            'notice' => count($removedMessages) > 0 ? implode(' ', $removedMessages) : null,
        ]);
    }

    public function addItem(AddToCartRequest $request): RedirectResponse
    {
        $cart = $this->cartManager->getCart($request->user());
        $product = Product::query()->findOrFail($request->validated('product_id'));

        $this->cartManager->addItem($cart, $product);

        return back()->with('success', "Added {$product->name} to cart.");
    }

    public function removeItem(Request $request, Product $product): RedirectResponse
    {
        $cart = $this->cartManager->getCart($request->user());
        $this->cartManager->removeItem($cart, $product->id);

        return back()->with('success', "Removed {$product->name} from cart.");
    }

    public function applyCoupon(ApplyCouponRequest $request, ApplyCoupon $applyCoupon): RedirectResponse
    {
        $cart = $this->cartManager->getCart($request->user());
        $applyCoupon->handle($cart, $request->validated('code'), $request->user());

        return back()->with('success', 'Coupon applied successfully.');
    }

    public function removeCoupon(Request $request): RedirectResponse
    {
        $cart = $this->cartManager->getCart($request->user());
        $this->cartManager->removeCoupon($cart);

        return back()->with('success', 'Coupon removed.');
    }
}
