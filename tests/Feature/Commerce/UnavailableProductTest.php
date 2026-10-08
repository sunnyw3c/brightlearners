<?php

use App\Domains\Catalog\Enums\ProductStatus;
use App\Domains\Catalog\Models\Product;
use App\Domains\Commerce\Services\CartManager;
use App\Models\User;

test('draft product cannot be added to cart', function () {
    $user = User::factory()->create();
    $draftProduct = Product::factory()->create(['status' => ProductStatus::Draft]);

    $response = $this->actingAs($user)
        ->post(route('cart.items.store'), [
            'product_id' => $draftProduct->id,
        ]);

    $response->assertSessionHasErrors('product_id');
});

test('archived product cannot be added to cart', function () {
    $user = User::factory()->create();
    $archivedProduct = Product::factory()->create(['status' => ProductStatus::Archived]);

    $response = $this->actingAs($user)
        ->post(route('cart.items.store'), [
            'product_id' => $archivedProduct->id,
        ]);

    $response->assertSessionHasErrors('product_id');
});

test('product deactivated while in cart is automatically removed on validation', function () {
    $user = User::factory()->create();
    $product = Product::factory()->active()->create();

    $cartManager = app(CartManager::class);
    $cart = $cartManager->getCart($user);
    $cartManager->addItem($cart, $product);

    expect($cart->items()->count())->toBe(1);

    // Now deactivate product
    $product->status = ProductStatus::Archived;
    $product->save();

    // Re-validate cart
    $messages = $cartManager->reloadAndValidateCart($cart);

    expect($messages)->not->toBeEmpty();
    expect($cart->fresh()->items()->count())->toBe(0);
});

test('checkout fails when cart item product is deactivated before submission', function () {
    $user = User::factory()->create();
    $product = Product::factory()->active()->create();

    $cartManager = app(CartManager::class);
    $cart = $cartManager->getCart($user);
    $cartManager->addItem($cart, $product);

    // Product is deactivated before checkout POST call
    $product->status = ProductStatus::Draft;
    $product->save();

    $response = $this->actingAs($user)
        ->post(route('checkout.store'), [
            'billing_name' => $user->name,
            'billing_email' => $user->email,
            'terms' => true,
        ]);

    $response->assertSessionHasErrors('cart');
});
