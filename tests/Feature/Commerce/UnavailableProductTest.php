<?php

use App\Domains\Catalog\Enums\ProductStatus;
use App\Domains\Catalog\Models\Product;
use App\Domains\Commerce\Models\Cart;
use App\Domains\Commerce\Models\CartItem;
use App\Models\User;

test('refuses to add a product that is not active', function (ProductStatus $status) {
    $product = Product::factory()->create(['status' => $status]);

    $this->post(route('cart.items.store'), ['product_id' => $product->id])
        ->assertSessionHasErrors('product_id');

    $this->assertDatabaseCount('cart_items', 0);
})->with([
    'draft' => ProductStatus::Draft,
    'archived' => ProductStatus::Archived,
]);

test('removes a product that was deactivated after it entered the cart', function () {
    $user = User::factory()->create();
    $product = Product::factory()->active()->create();
    $cart = Cart::factory()->create(['user_id' => $user->id]);
    CartItem::factory()->create(['cart_id' => $cart->id, 'product_id' => $product->id]);
    $product->forceFill(['status' => ProductStatus::Archived])->save();

    $this->actingAs($user)->get(route('cart.show'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page->where('cart.pricing.lines', [])->has('notice'));

    $this->assertDatabaseCount('cart_items', 0);
});
