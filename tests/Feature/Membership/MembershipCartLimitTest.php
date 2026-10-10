<?php

use App\Domains\Catalog\Enums\ProductType;
use App\Domains\Catalog\Models\Product;
use App\Domains\Commerce\Models\Cart;
use App\Domains\Commerce\Services\CartManager;
use App\Domains\Membership\Models\Subscription;
use App\Domains\Membership\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;

uses(RefreshDatabase::class);

test('Cart allows only one membership product', function () {
    $plan1 = SubscriptionPlan::factory()->create();
    $membership1 = Product::factory()->active()->create([
        'type' => ProductType::Membership,
        'subscription_plan_id' => $plan1->id,
    ]);

    $plan2 = SubscriptionPlan::factory()->create();
    $membership2 = Product::factory()->active()->create([
        'type' => ProductType::Membership,
        'subscription_plan_id' => $plan2->id,
    ]);

    $cartManager = app(CartManager::class);
    $cart = Cart::factory()->create();

    // Adding first membership succeeds
    $cartManager->addItem($cart, $membership1);
    expect($cart->items()->count())->toBe(1);

    // Adding second membership product fails
    expect(fn () => $cartManager->addItem($cart, $membership2))
        ->toThrow(ValidationException::class);
});

test('A user with an active membership cannot add a membership product to cart', function () {
    $user = User::factory()->create();
    Subscription::factory()->create(['user_id' => $user->id]);

    $plan = SubscriptionPlan::factory()->create();
    $membership = Product::factory()->active()->create([
        'type' => ProductType::Membership,
        'subscription_plan_id' => $plan->id,
    ]);

    $cartManager = app(CartManager::class);
    $cart = Cart::factory()->create(['user_id' => $user->id]);

    expect(fn () => $cartManager->addItem($cart, $membership))
        ->toThrow(ValidationException::class);
});
