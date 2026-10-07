<?php

use App\Domains\Catalog\Models\Product;
use App\Domains\Commerce\Models\Cart;
use App\Domains\Commerce\Models\CartItem;
use App\Domains\Commerce\Services\CartManager;
use App\Models\User;

test('merges a guest cart into the user cart without duplicate products', function () {
    $user = User::factory()->create();
    $shared = Product::factory()->active()->create();
    $guestOnly = Product::factory()->active()->create();
    $guest = Cart::factory()->create();
    $userCart = Cart::factory()->create(['user_id' => $user->id]);
    CartItem::factory()->create(['cart_id' => $guest->id, 'product_id' => $shared->id]);
    CartItem::factory()->create(['cart_id' => $guest->id, 'product_id' => $guestOnly->id]);
    CartItem::factory()->create(['cart_id' => $userCart->id, 'product_id' => $shared->id]);

    $merged = app(CartManager::class)->mergeGuestCart($user, $guest->token, $userCart);

    expect($merged->items)->toHaveCount(2)
        ->and($merged->items->pluck('product_id')->sort()->values()->all())->toBe([$shared->id, $guestOnly->id]);
    $this->assertModelMissing($guest);
});
