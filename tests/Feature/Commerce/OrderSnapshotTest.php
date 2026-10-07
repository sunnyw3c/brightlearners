<?php

use App\Domains\Catalog\Models\Product;
use App\Domains\Commerce\Actions\CreateCartOrder;
use App\Domains\Commerce\Models\Cart;
use App\Domains\Commerce\Models\CartItem;
use App\Models\User;

test('keeps the purchased product snapshot after catalogue changes', function () {
    $user = User::factory()->create();
    $product = Product::factory()->active()->create(['name' => 'Maths Mastery', 'sku' => 'MATH-2', 'regular_price' => 19_900]);
    $cart = Cart::factory()->create(['user_id' => $user->id]);
    CartItem::factory()->create(['cart_id' => $cart->id, 'product_id' => $product->id]);
    $order = app(CreateCartOrder::class)->handle($cart, $user, [
        'billing_name' => $user->name,
        'billing_email' => $user->email,
    ]);

    $product->forceFill(['name' => 'Renamed Workbook', 'regular_price' => 29_900])->save();
    $snapshot = $order->items()->sole();

    expect($snapshot->product_name)->toBe('Maths Mastery')
        ->and($snapshot->sku)->toBe('MATH-2')
        ->and($snapshot->unit_price)->toBe(19_900)
        ->and($snapshot->total)->toBe(19_900);
});

test('returns the same unexpired pending order for a repeated checkout', function () {
    $user = User::factory()->create();
    $product = Product::factory()->active()->create();
    $cart = Cart::factory()->create(['user_id' => $user->id]);
    CartItem::factory()->create(['cart_id' => $cart->id, 'product_id' => $product->id]);
    $billing = ['billing_name' => $user->name, 'billing_email' => $user->email];

    $first = app(CreateCartOrder::class)->handle($cart, $user, $billing);
    $second = app(CreateCartOrder::class)->handle($cart, $user, $billing);

    expect($second->is($first))->toBeTrue();
    $this->assertDatabaseCount('orders', 1);
});
