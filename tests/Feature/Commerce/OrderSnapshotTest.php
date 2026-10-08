<?php

use App\Domains\Catalog\Models\Product;
use App\Domains\Commerce\Actions\CreateCartOrder;
use App\Domains\Commerce\Services\CartManager;
use App\Models\User;

test('an order snapshot is unchanged when the product is later renamed or repriced', function () {
    $user = User::factory()->create();
    $product = Product::factory()->active()->create([
        'name' => 'Original Workbook Name',
        'regular_price' => 15_000,
    ]);

    $cartManager = app(CartManager::class);
    $createCartOrder = app(CreateCartOrder::class);

    $cart = $cartManager->getCart($user);
    $cartManager->addItem($cart, $product);

    $order = $createCartOrder->handle($cart, $user, [
        'billing_name' => $user->name,
        'billing_email' => $user->email,
    ]);

    $orderItem = $order->items->first();
    expect($orderItem->product_name)->toBe('Original Workbook Name');
    expect($orderItem->unit_price)->toBe(15_000);

    // Modify original product name & price
    $product->name = 'Updated Product Title';
    $product->regular_price = 25_000;
    $product->save();

    // Order item snapshot must remain unchanged
    $orderItem->refresh();
    expect($orderItem->product_name)->toBe('Original Workbook Name');
    expect($orderItem->unit_price)->toBe(15_000);
    expect($order->fresh()->total)->toBe(15_000);
});
