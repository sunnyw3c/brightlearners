<?php

use App\Domains\Access\Actions\GrantPurchasedEntitlements;
use App\Domains\Access\Models\Entitlement;
use App\Domains\Catalog\Models\Product;
use App\Domains\Commerce\Actions\TransitionOrder;
use App\Domains\Commerce\Enums\OrderStatus;
use App\Domains\Commerce\Models\Order;
use App\Domains\Commerce\Models\OrderItem;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Payments\Events\RefundCompleted;
use App\Domains\Payments\Models\Payment;
use App\Domains\Payments\Models\Refund;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('Full refund revokes order entitlements without deleting rows from database', function () {
    $user = User::factory()->create();
    $resource = LearningResource::factory()->create();

    $product = Product::factory()->create();
    $product->resources()->attach($resource->id);

    $order = Order::factory()->create(['user_id' => $user->id, 'status' => OrderStatus::Paid]);
    $item = OrderItem::factory()->create(['order_id' => $order->id, 'product_id' => $product->id]);

    app(GrantPurchasedEntitlements::class)->handle($order);
    expect(Entitlement::where('user_id', $user->id)->count())->toBe(1);

    $payment = Payment::factory()->create(['order_id' => $order->id]);
    $refund = Refund::factory()->create(['payment_id' => $payment->id]);

    // Transition order to Refunded
    app(TransitionOrder::class)->handle($order, OrderStatus::Refunded);

    // Fire RefundCompleted event
    RefundCompleted::dispatch($refund);

    // Entitlement row remains in DB (count is still 1) but revoked_at is populated
    $entitlement = Entitlement::where('user_id', $user->id)->first();
    expect($entitlement)->not->toBeNull();
    expect($entitlement->revoked_at)->not->toBeNull();
    expect($entitlement->isValid())->toBeFalse();
});
