<?php

use App\Domains\Catalog\Models\Product;
use App\Domains\Commerce\Actions\CreateCartOrder;
use App\Domains\Commerce\Actions\TransitionOrder;
use App\Domains\Commerce\Enums\OrderStatus;
use App\Domains\Commerce\Models\Coupon;
use App\Domains\Commerce\Services\CartManager;
use App\Models\User;
use Illuminate\Validation\ValidationException;

test('coupon max_uses limit prevents order creation when limit is exhausted', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $product = Product::factory()->active()->create(['regular_price' => 10_000]);
    $coupon = Coupon::factory()->percent(10)->create(['max_uses' => 1]);

    $cartManager = app(CartManager::class);
    $createCartOrder = app(CreateCartOrder::class);

    // Both users attach the coupon to their cart while 1 use remains
    $cart1 = $cartManager->getCart($user1);
    $cartManager->addItem($cart1, $product);
    $cartManager->applyCoupon($cart1, $coupon);

    $cart2 = $cartManager->getCart($user2);
    $cartManager->addItem($cart2, $product);
    $cartManager->applyCoupon($cart2, $coupon);

    // User 1 completes checkout first, using the last available use
    $createCartOrder->handle($cart1, $user1, [
        'billing_name' => $user1->name,
        'billing_email' => $user1->email,
    ]);

    // User 2 checkout fails because the row-locked check refuses the exhausted coupon
    expect(fn () => $createCartOrder->handle($cart2, $user2, [
        'billing_name' => $user2->name,
        'billing_email' => $user2->email,
    ]))->toThrow(ValidationException::class);
});

test('per-user coupon usage limit is enforced', function () {
    $user = User::factory()->create();
    $product = Product::factory()->active()->create(['regular_price' => 10_000]);
    $coupon = Coupon::factory()->percent(10)->create(['uses_per_user' => 1]);

    $cartManager = app(CartManager::class);
    $createCartOrder = app(CreateCartOrder::class);

    // User's 1st order
    $cart = $cartManager->getCart($user);
    $cartManager->addItem($cart, $product);
    $cartManager->applyCoupon($cart, $coupon);
    $createCartOrder->handle($cart, $user, [
        'billing_name' => $user->name,
        'billing_email' => $user->email,
    ]);

    // User attempts to use coupon a 2nd time
    $cart2 = $cartManager->getCart($user);
    $cartManager->addItem($cart2, $product);

    expect(fn () => $cartManager->applyCoupon($cart2, $coupon))
        ->toThrow(ValidationException::class);
});

test('coupon usage is released when a pending order expires and is cancelled', function () {
    $user = User::factory()->create();
    $product = Product::factory()->active()->create(['regular_price' => 10_000]);
    $coupon = Coupon::factory()->percent(10)->create(['max_uses' => 1]);

    $cartManager = app(CartManager::class);
    $createCartOrder = app(CreateCartOrder::class);
    $transitionOrder = app(TransitionOrder::class);

    $cart = $cartManager->getCart($user);
    $cartManager->addItem($cart, $product);
    $cartManager->applyCoupon($cart, $coupon);
    $order = $createCartOrder->handle($cart, $user, [
        'billing_name' => $user->name,
        'billing_email' => $user->email,
    ]);

    expect($coupon->usages()->count())->toBe(1);

    // Cancel order (simulating order expiration cleanup)
    $transitionOrder->handle($order, OrderStatus::Cancelled);

    // Coupon usage must be deleted/released
    expect($coupon->usages()->count())->toBe(0);
});
