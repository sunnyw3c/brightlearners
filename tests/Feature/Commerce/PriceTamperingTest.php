<?php

use App\Domains\Catalog\Models\Product;
use App\Domains\Commerce\Models\Cart;
use App\Models\User;

test('tampering with price or total parameters in requests is completely ignored by server', function () {
    $user = User::factory()->create();
    $product = Product::factory()->active()->create([
        'regular_price' => 19_900,
    ]);

    // 1. Send crafted request to add item with fake low price
    $this->actingAs($user)
        ->post(route('cart.items.store'), [
            'product_id' => $product->id,
            'price' => 100,
            'total' => 100,
            'unit_price' => 100,
        ])
        ->assertRedirect();

    $cart = Cart::query()->where('user_id', $user->id)->first();
    expect($cart)->not->toBeNull();
    expect($cart->items)->toHaveCount(1);

    // 2. Perform checkout with crafted low total in form payload
    $response = $this->actingAs($user)
        ->post(route('checkout.store'), [
            'billing_name' => 'John Doe',
            'billing_email' => $user->email,
            'terms' => true,
            'total' => 100, // Tampered field
            'amount' => 100,
        ]);

    $response->assertRedirect();

    $order = $user->orders()->latest('id')->first();
    expect($order)->not->toBeNull();
    // Server order total must be exact catalog price (19900 paise = ₹199), not 100
    expect($order->total)->toBe(19_900);
    expect($order->subtotal)->toBe(19_900);
});
