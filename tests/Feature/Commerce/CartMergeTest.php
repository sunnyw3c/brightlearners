<?php

use App\Domains\Catalog\Models\Product;
use App\Domains\Commerce\Models\Cart;
use App\Domains\Commerce\Services\CartManager;
use App\Models\User;
use Illuminate\Support\Str;

test('a guest cart merges into user cart on login without duplicating products', function () {
    $user = User::factory()->create();
    $product1 = Product::factory()->active()->create();
    $product2 = Product::factory()->active()->create();

    $cartManager = app(CartManager::class);

    // 1. User cart already has product1
    $userCart = $cartManager->getCart($user);
    $cartManager->addItem($userCart, $product1);

    // 2. Guest cart has product1 AND product2
    $guestToken = (string) Str::uuid();
    $guestCart = Cart::query()->create([
        'token' => $guestToken,
        'user_id' => null,
        'currency' => 'INR',
    ]);
    $cartManager->addItem($guestCart, $product1);
    $cartManager->addItem($guestCart, $product2);

    expect($guestCart->items()->count())->toBe(2);

    // 3. Merge guest cart into user cart
    $cartManager->mergeGuestCart($user, $guestToken);

    $userCart->refresh();
    expect($userCart->items)->toHaveCount(2);

    // Product1 should not be duplicated (unique product_ids)
    $productIds = $userCart->items->pluck('product_id')->toArray();
    expect(array_unique($productIds))->toHaveCount(2);

    // Guest cart record should be deleted
    expect(Cart::query()->where('token', $guestToken)->first())->toBeNull();
});
