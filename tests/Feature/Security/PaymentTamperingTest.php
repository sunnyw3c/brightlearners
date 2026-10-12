<?php

use App\Domains\Catalog\Models\Product;
use App\Domains\Commerce\Enums\OrderStatus;
use App\Domains\Commerce\Models\Cart;
use App\Domains\Commerce\Models\Order;
use App\Domains\Payments\Actions\MarkOrderPaid;
use App\Domains\Payments\Actions\ProcessRazorpayWebhook;
use App\Domains\Payments\Enums\PaymentStatus;
use App\Domains\Payments\Enums\WebhookStatus;
use App\Domains\Payments\Models\Payment;
use App\Domains\Payments\Models\WebhookEvent;
use App\Models\User;

test('manipulating price parameters during add to cart or checkout is completely ignored by server', function () {
    $user = User::factory()->create();
    $product = Product::factory()->active()->create([
        'regular_price' => 25_000,
    ]);

    // Attacker crafts add-to-cart request with a cheap price
    $this->actingAs($user)
        ->post(route('cart.items.store'), [
            'product_id' => $product->id,
            'price' => 100,
            'total' => 100,
        ])
        ->assertRedirect();

    $cart = Cart::query()->where('user_id', $user->id)->first();
    expect($cart)->not->toBeNull();

    // Attacker submits checkout with tampered low amount
    $this->actingAs($user)
        ->post(route('checkout.store'), [
            'billing_name' => 'Attacker',
            'billing_email' => $user->email,
            'terms' => true,
            'total' => 100,
            'subtotal' => 100,
        ])
        ->assertRedirect();

    $order = $user->orders()->latest('id')->first();
    expect($order)->not->toBeNull();
    // Server strictly enforces catalog price: 25000 paise (₹250), not ₹1
    expect($order->total)->toBe(25_000);
    expect($order->subtotal)->toBe(25_000);
});

test('tampered captured payment amount throws and marks payment failed', function () {
    $user = User::factory()->create();
    $order = Order::factory()->create([
        'user_id' => $user->id,
        'total' => 25_000,
        'currency' => 'INR',
        'status' => OrderStatus::PendingPayment,
    ]);

    $payment = Payment::factory()->create([
        'order_id' => $order->id,
        'amount' => 25_000,
        'status' => PaymentStatus::Created,
    ]);

    $markOrderPaid = app(MarkOrderPaid::class);

    // Tampered captured amount (attacker claims payment was captured for 100 paise)
    expect(fn () => $markOrderPaid->handle(
        order: $order,
        payment: $payment,
        capturedAmount: 100,
        currency: 'INR',
        providerPaymentId: 'pay_tampered_123',
    ))->toThrow(InvalidArgumentException::class);

    expect($payment->refresh()->status)->toBe(PaymentStatus::Failed);
    expect($order->refresh()->status)->toBe(OrderStatus::PendingPayment);
});

test('replaying a payment webhook is idempotent and prevents double processing', function () {
    $user = User::factory()->create();
    $order = Order::factory()->create([
        'user_id' => $user->id,
        'total' => 15_000,
        'currency' => 'INR',
        'status' => OrderStatus::PendingPayment,
    ]);

    $payment = Payment::factory()->create([
        'order_id' => $order->id,
        'amount' => 15_000,
        'provider_order_id' => 'order_test_replay_123',
        'status' => PaymentStatus::Created,
    ]);

    $payload = [
        'event' => 'payment.captured',
        'payload' => [
            'payment' => [
                'entity' => [
                    'id' => 'pay_test_replay_999',
                    'order_id' => 'order_test_replay_123',
                    'amount' => 15_000,
                    'currency' => 'INR',
                    'method' => 'upi',
                ],
            ],
        ],
    ];

    $webhookEvent = WebhookEvent::create([
        'provider' => 'razorpay',
        'event_id' => 'evt_test_replay_1',
        'event_type' => 'payment.captured',
        'payload' => json_encode($payload),
        'status' => WebhookStatus::Pending,
        'attempts' => 0,
        'received_at' => now(),
    ]);

    $processor = app(ProcessRazorpayWebhook::class);

    // First run processes cleanly
    $processor->handle($webhookEvent);
    expect($webhookEvent->refresh()->status)->toBe(WebhookStatus::Processed);
    expect($order->refresh()->status)->toBe(OrderStatus::Paid);

    // Replay run is idempotent and does not throw or double-credit
    $processor->handle($webhookEvent);
    expect($webhookEvent->refresh()->status)->toBe(WebhookStatus::Processed);
    expect($order->refresh()->status)->toBe(OrderStatus::Paid);
});
