<?php

use App\Domains\Catalog\Enums\ProductType;
use App\Domains\Catalog\Models\Product;
use App\Domains\Commerce\Models\Order;
use App\Domains\Commerce\Models\OrderItem;
use App\Domains\Membership\Actions\ActivateMembership;
use App\Domains\Membership\Enums\SubscriptionStatus;
use App\Domains\Membership\Models\Subscription;
use App\Domains\Membership\Models\SubscriptionPlan;
use App\Domains\Payments\Events\OrderPaid;
use App\Domains\Payments\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('A pilot purchase creates exactly one membership and listener run twice is idempotent', function () {
    $plan = SubscriptionPlan::factory()->pilot()->create();
    $product = Product::factory()->create([
        'type' => ProductType::Membership,
        'subscription_plan_id' => $plan->id,
    ]);

    $user = User::factory()->create();
    $order = Order::factory()->create(['user_id' => $user->id, 'paid_at' => now()]);
    OrderItem::factory()->create(['order_id' => $order->id, 'product_id' => $product->id]);

    $payment = Payment::factory()->create(['order_id' => $order->id]);

    // Dispatch OrderPaid event
    OrderPaid::dispatch($order, $payment);

    expect(Subscription::where('user_id', $user->id)->count())->toBe(1);

    $subscription = Subscription::where('user_id', $user->id)->first();
    expect($subscription)->not->toBeNull();
    expect($subscription->status)->toBe(SubscriptionStatus::Active);
    expect($subscription->auto_renew)->toBeFalse();
    expect($subscription->plan_id)->toBe($plan->id);

    // Running listener again does not create a duplicate subscription
    OrderPaid::dispatch($order, $payment);
    expect(Subscription::where('user_id', $user->id)->count())->toBe(1);
});

test('Membership dates equal the purchased 90-day term and starts_at reflects payment or pilot start', function () {
    $plan = SubscriptionPlan::factory()->pilot()->create([
        'duration_days' => 90,
    ]);
    $product = Product::factory()->create([
        'type' => ProductType::Membership,
        'subscription_plan_id' => $plan->id,
    ]);

    $user = User::factory()->create();
    $order = Order::factory()->create(['user_id' => $user->id, 'paid_at' => now()]);
    OrderItem::factory()->create(['order_id' => $order->id, 'product_id' => $product->id]);

    $action = app(ActivateMembership::class);
    $subscription = $action->handle($order);

    expect($subscription)->not->toBeNull();
    expect((int) $subscription->starts_at->diffInDays($subscription->ends_at))->toBe(90);
});
