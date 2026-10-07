<?php

use App\Domains\Catalog\Models\Product;
use App\Domains\Commerce\Actions\ApplyCoupon;
use App\Domains\Commerce\Actions\CreateCartOrder;
use App\Domains\Commerce\Models\Cart;
use App\Domains\Commerce\Models\CartItem;
use App\Domains\Commerce\Models\Coupon;
use App\Domains\Commerce\Models\CouponUsage;
use App\Models\User;
use Illuminate\Validation\ValidationException;

function cartWithProductFor(User $user, Product $product): Cart
{
    $cart = Cart::factory()->create(['user_id' => $user->id]);
    CartItem::factory()->create(['cart_id' => $cart->id, 'product_id' => $product->id]);

    return $cart;
}

test('refuses the next order after the coupons final use is claimed', function () {
    $product = Product::factory()->active()->create(['regular_price' => 10_000]);
    $coupon = Coupon::factory()->create(['code' => 'LASTUSE', 'max_uses' => 1]);
    $firstUser = User::factory()->create();
    $firstCart = cartWithProductFor($firstUser, $product);
    app(ApplyCoupon::class)->handle($firstCart, 'LASTUSE', $firstUser);
    app(CreateCartOrder::class)->handle($firstCart, $firstUser, ['billing_name' => $firstUser->name, 'billing_email' => $firstUser->email]);
    $secondUser = User::factory()->create();
    $secondCart = cartWithProductFor($secondUser, $product);

    expect(fn () => app(ApplyCoupon::class)->handle($secondCart, 'LASTUSE', $secondUser))
        ->toThrow(ValidationException::class);
    expect($coupon->usages()->count())->toBe(1);
});

test('enforces the per-user coupon limit', function () {
    $user = User::factory()->create();
    $product = Product::factory()->active()->create();
    $coupon = Coupon::factory()->create(['uses_per_user' => 1]);
    CouponUsage::factory()->create(['coupon_id' => $coupon->id, 'user_id' => $user->id]);
    $cart = cartWithProductFor($user, $product);

    expect(fn () => app(ApplyCoupon::class)->handle($cart, $coupon->code, $user))
        ->toThrow(ValidationException::class);
});

test('refuses a coupon below its minimum order and one that makes the total zero', function () {
    $user = User::factory()->create();
    $product = Product::factory()->active()->create(['regular_price' => 1_000]);
    $cart = cartWithProductFor($user, $product);
    $minimum = Coupon::factory()->create(['code' => 'MINIMUM', 'minimum_order' => 2_000]);
    $free = Coupon::factory()->fixed(1_000)->create(['code' => 'FREE']);

    expect(fn () => app(ApplyCoupon::class)->handle($cart, $minimum->code, $user))->toThrow(ValidationException::class);
    expect(fn () => app(ApplyCoupon::class)->handle($cart, $free->code, $user))->toThrow(ValidationException::class);
});
