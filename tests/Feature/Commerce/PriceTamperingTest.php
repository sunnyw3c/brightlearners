<?php

use App\Domains\Catalog\Models\Product;
use App\Domains\Commerce\Models\Cart;
use App\Domains\Commerce\Models\CartItem;
use App\Domains\Commerce\Models\Order;
use App\Models\User;

test('ignores browser supplied prices and creates the order from the catalogue price', function () {
    $user = User::factory()->create();
    $product = Product::factory()->active()->create(['regular_price' => 19_900]);
    $cart = Cart::factory()->create(['user_id' => $user->id]);
    CartItem::factory()->create(['cart_id' => $cart->id, 'product_id' => $product->id]);

    $response = $this->actingAs($user)->post(route('checkout.store'), [
        'billing_name' => 'Priya Parent',
        'billing_email' => $user->email,
        'terms_accepted' => '1',
        'price' => 1,
        'total' => 1,
        'discount' => 19_899,
    ]);

    $order = Order::query()->sole();
    $response->assertRedirect(route('account.orders.show', $order));
    expect($order->subtotal)->toBe(19_900)
        ->and($order->discount)->toBe(0)
        ->and($order->total)->toBe(19_900)
        ->and($order->items()->sole()->unit_price)->toBe(19_900);
});
