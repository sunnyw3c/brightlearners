<?php

use App\Domains\Analytics\Enums\EventName;
use App\Domains\Analytics\Models\AnalyticsEvent;
use App\Domains\Commerce\Enums\OrderStatus;
use App\Domains\Commerce\Models\Order;
use App\Domains\Commerce\Models\OrderItem;
use App\Domains\Payments\Enums\PaymentStatus;
use App\Domains\Payments\Events\OrderPaid;
use App\Domains\Payments\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('the purchase event fires once despite webhook retries (callback, then webhook, then retry)', function () {
    $user = User::factory()->create();
    $order = Order::factory()->create([
        'user_id' => $user->id,
        'total' => 49900,
        'currency' => 'INR',
        'status' => OrderStatus::PendingPayment,
    ]);

    OrderItem::factory()->create([
        'order_id' => $order->id,
        'unit_price' => 49900,
        'quantity' => 1,
    ]);

    $payment = Payment::factory()->create([
        'order_id' => $order->id,
        'provider' => 'razorpay',
        'provider_order_id' => 'order_rzp_dedupe_1',
        'provider_payment_id' => 'pay_rzp_dedupe_1',
        'amount' => 49900,
        'currency' => 'INR',
        'status' => PaymentStatus::Created,
    ]);

    // 1. Initial callback completion triggers OrderPaid
    OrderPaid::dispatch($order, $payment);
    expect(AnalyticsEvent::query()->where('event_name', EventName::PurchaseCompleted->value)->count())->toBe(1);

    // 2. Webhook delivery triggers OrderPaid again (simulating duplicate webhook arrival)
    OrderPaid::dispatch($order->fresh(), $payment->fresh());
    expect(AnalyticsEvent::query()->where('event_name', EventName::PurchaseCompleted->value)->count())->toBe(1);

    // 3. Webhook retry again
    OrderPaid::dispatch($order->fresh(), $payment->fresh());
    expect(AnalyticsEvent::query()->where('event_name', EventName::PurchaseCompleted->value)->count())->toBe(1);

    $event = AnalyticsEvent::query()->where('event_name', EventName::PurchaseCompleted->value)->first();
    expect($event)->not->toBeNull()
        ->and($event->dedupe_key)->toBe((string) $order->id)
        ->and($event->properties['revenue'])->toBe(49900)
        ->and($event->properties['order_id'])->toBe($order->id);
});
