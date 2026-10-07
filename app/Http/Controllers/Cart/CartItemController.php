<?php

namespace App\Http\Controllers\Cart;

use App\Domains\Catalog\Enums\ProductStatus;
use App\Domains\Catalog\Models\Product;
use App\Domains\Commerce\Services\CartManager;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cart\StoreCartItemRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CartItemController extends Controller
{
    public function store(StoreCartItemRequest $request, CartManager $cartManager): RedirectResponse
    {
        $product = Product::query()->findOrFail($request->integer('product_id'));

        if ($product->status !== ProductStatus::Active) {
            throw ValidationException::withMessages(['product_id' => 'This product is not available.']);
        }

        $cart = $cartManager->current($request);
        $cart->items()->firstOrCreate(['product_id' => $product->id], ['quantity' => 1]);

        return redirect()->route('cart.show')->with('success', $product->name.' was added to your cart.');
    }

    public function destroy(Request $request, Product $product, CartManager $cartManager): RedirectResponse
    {
        $cart = $cartManager->current($request);
        $cart->items()->where('product_id', $product->id)->delete();

        return redirect()->route('cart.show')->with('success', 'Item removed from your cart.');
    }
}
