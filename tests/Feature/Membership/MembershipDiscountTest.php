<?php

use App\Domains\Catalog\Models\Product;
use App\Domains\Commerce\Models\Cart;
use App\Domains\Commerce\Models\CartItem;
use App\Domains\Commerce\Services\PricingService;
use App\Domains\Membership\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('The member discount applies only while active and does not apply after expiry or for non-members', function () {
    $product = Product::factory()->create([
        'regular_price' => 10000, // ₹100
        'member_discount_eligible' => true,
    ]);

    $pricingService = app(PricingService::class);

    // 1. Non-member user
    $nonMember = User::factory()->create();
    $cart1 = Cart::factory()->create(['user_id' => $nonMember->id]);
    CartItem::factory()->create(['cart_id' => $cart1->id, 'product_id' => $product->id, 'quantity' => 1]);

    $breakdown1 = $pricingService->calculate($cart1, $nonMember);
    expect($breakdown1->memberDiscount)->toBe(0);
    expect($breakdown1->total)->toBe(10000);

    // 2. Active member
    $activeMember = User::factory()->create();
    Subscription::factory()->create([
        'user_id' => $activeMember->id,
        'starts_at' => now()->subDay(),
        'ends_at' => now()->addDays(89),
    ]);
    $cart2 = Cart::factory()->create(['user_id' => $activeMember->id]);
    CartItem::factory()->create(['cart_id' => $cart2->id, 'product_id' => $product->id, 'quantity' => 1]);

    $breakdown2 = $pricingService->calculate($cart2, $activeMember);
    expect($breakdown2->memberDiscount)->toBe(1500); // 15% of ₹100 = ₹15 (1500 paise)
    expect($breakdown2->total)->toBe(8500);

    // 3. Expired member
    $expiredMember = User::factory()->create();
    Subscription::factory()->expired()->create(['user_id' => $expiredMember->id]);
    $cart3 = Cart::factory()->create(['user_id' => $expiredMember->id]);
    CartItem::factory()->create(['cart_id' => $cart3->id, 'product_id' => $product->id, 'quantity' => 1]);

    $breakdown3 = $pricingService->calculate($cart3, $expiredMember);
    expect($breakdown3->memberDiscount)->toBe(0);
    expect($breakdown3->total)->toBe(10000);
});
