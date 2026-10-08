<?php

use App\Domains\Catalog\Models\Product;
use App\Domains\Commerce\Models\Cart;
use App\Domains\Commerce\Models\Coupon;
use App\Domains\Commerce\Services\CartManager;
use App\Domains\Commerce\Services\PricingService;
use App\Domains\Membership\Contracts\MembershipChecker;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('pricing service applies sale price when sale window is open', function () {
    $product = Product::factory()->active()->onSale(8_000, now()->subHour(), now()->addHour())->create([
        'regular_price' => 10_000,
    ]);

    $cartManager = app(CartManager::class);
    $pricingService = app(PricingService::class);

    $cart = Cart::factory()->create();
    $cartManager->addItem($cart, $product);

    $breakdown = $pricingService->calculate($cart);

    expect($breakdown->subtotal)->toBe(8_000);
    expect($breakdown->total)->toBe(8_000);
});

test('pricing service applies coupon discount only', function () {
    $product = Product::factory()->active()->create(['regular_price' => 10_000]);
    $coupon = Coupon::factory()->percent(20)->create();

    $cartManager = app(CartManager::class);
    $pricingService = app(PricingService::class);

    $cart = Cart::factory()->create();
    $cartManager->addItem($cart, $product);
    $cartManager->applyCoupon($cart, $coupon);

    $breakdown = $pricingService->calculate($cart);

    expect($breakdown->subtotal)->toBe(10_000);
    expect($breakdown->couponDiscount)->toBe(2_000);
    expect($breakdown->total)->toBe(8_000);
});

test('pricing service applies member discount when user has active membership', function () {
    $user = User::factory()->create();
    $product = Product::factory()->active()->create([
        'regular_price' => 10_000,
        'member_discount_eligible' => true,
    ]);

    $cartManager = app(CartManager::class);

    // Mock MembershipChecker to return true
    $membershipChecker = Mockery::mock(MembershipChecker::class);
    $membershipChecker->shouldReceive('hasActiveMembership')->andReturn(true);

    $pricingService = new PricingService($membershipChecker);

    $cart = Cart::factory()->create(['user_id' => $user->id]);
    $cartManager->addItem($cart, $product);

    $breakdown = $pricingService->calculate($cart, $user);

    // 15% of 10000 = 1500
    expect($breakdown->subtotal)->toBe(10_000);
    expect($breakdown->memberDiscount)->toBe(1_500);
    expect($breakdown->total)->toBe(8_500);
});

test('pricing service applies member discount AND coupon in order: sale -> member -> coupon', function () {
    $user = User::factory()->create();
    $product = Product::factory()->active()->onSale(8_000, now()->subHour(), now()->addHour())->create([
        'regular_price' => 10_000,
        'member_discount_eligible' => true,
    ]);
    $coupon = Coupon::factory()->percent(10)->create(); // 10% coupon

    $cartManager = app(CartManager::class);

    $membershipChecker = Mockery::mock(MembershipChecker::class);
    $membershipChecker->shouldReceive('hasActiveMembership')->andReturn(true);

    $pricingService = new PricingService($membershipChecker);

    $cart = Cart::factory()->create(['user_id' => $user->id]);
    $cartManager->addItem($cart, $product);
    $cartManager->applyCoupon($cart, $coupon);

    $breakdown = $pricingService->calculate($cart, $user);

    // 1. Line price = 8,000
    // 2. Member discount (15% of 8,000) = 1,200 (Remaining = 6,800)
    // 3. Coupon discount (10% of 6,800) = 680
    // 4. Total discount = 1,880
    // 5. Total = 6,120
    expect($breakdown->subtotal)->toBe(8_000);
    expect($breakdown->memberDiscount)->toBe(1_200);
    expect($breakdown->couponDiscount)->toBe(680);
    expect($breakdown->discount)->toBe(1_880);
    expect($breakdown->total)->toBe(6_120);
});

test('line totals sum exactly to order total', function () {
    $product1 = Product::factory()->active()->create(['regular_price' => 3_333]);
    $product2 = Product::factory()->active()->create(['regular_price' => 6_667]);
    $coupon = Coupon::factory()->percent(15)->create();

    $cartManager = app(CartManager::class);
    $pricingService = app(PricingService::class);

    $cart = Cart::factory()->create();
    $cartManager->addItem($cart, $product1);
    $cartManager->addItem($cart, $product2);
    $cartManager->applyCoupon($cart, $coupon);

    $breakdown = $pricingService->calculate($cart);

    $linesSum = array_sum(array_map(fn ($line) => $line->lineTotal, $breakdown->lines));

    expect($linesSum)->toBe($breakdown->total);
});
