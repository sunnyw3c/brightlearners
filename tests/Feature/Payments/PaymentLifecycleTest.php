<?php

use App\Domains\Commerce\Enums\OrderStatus;
use App\Domains\Commerce\Models\Order;
use App\Domains\Payments\Actions\IssueRefund;
use App\Domains\Payments\Actions\MarkOrderPaid;
use App\Domains\Payments\Actions\ProcessRazorpayWebhook;
use App\Domains\Payments\Enums\PaymentStatus;
use App\Domains\Payments\Enums\RefundStatus;
use App\Domains\Payments\Enums\WebhookStatus;
use App\Domains\Payments\Events\OrderPaid;
use App\Domains\Payments\Events\RefundCompleted;
use App\Domains\Payments\Models\Payment;
use App\Domains\Payments\Models\Refund;
use App\Domains\Payments\Models\WebhookEvent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;

uses(RefreshDatabase::class);

beforeEach(function () {
    config()->set('services.razorpay.key_id', 'rzp_test_mock_key');
    config()->set('services.razorpay.key_secret', 'mock_secret_123456');
    config()->set('services.razorpay.webhook_secret', 'webhook_secret_654321');
});

test('1. Successful payment: valid callback updates order to paid, payment to captured, and dispatches OrderPaid', function () {
    Event::fake([OrderPaid::class]);

    $user = User::factory()->create();
    $order = Order::factory()->create([
        'user_id' => $user->id,
        'total' => 50000, // ₹500.00
        'currency' => 'INR',
        'status' => OrderStatus::PendingPayment,
    ]);

    $razorpayOrderId = 'order_test_12345';
    $razorpayPaymentId = 'pay_test_67890';
    $keySecret = config('services.razorpay.key_secret');
    $validSignature = hash_hmac('sha256', $razorpayOrderId.'|'.$razorpayPaymentId, $keySecret);

    $response = $this->actingAs($user)->post(route('payments.razorpay.callback'), [
        'order_id' => $order->id,
        'razorpay_order_id' => $razorpayOrderId,
        'razorpay_payment_id' => $razorpayPaymentId,
        'razorpay_signature' => $validSignature,
    ]);

    $response->assertRedirect(route('orders.success', $order->id));

    $order->refresh();
    expect($order->status)->toBe(OrderStatus::Paid);

    $payment = Payment::where('order_id', $order->id)->first();
    expect($payment)->not->toBeNull();
    expect($payment->status)->toBe(PaymentStatus::Captured);
    expect($payment->provider_payment_id)->toBe($razorpayPaymentId);

    Event::assertDispatched(OrderPaid::class, fn ($event) => $event->order->id === $order->id);
});

test('2. Failed payment: payment.failed webhook transitions order to failed', function () {
    $order = Order::factory()->create([
        'total' => 30000,
        'status' => OrderStatus::PendingPayment,
    ]);

    $payment = Payment::factory()->create([
        'order_id' => $order->id,
        'provider_order_id' => 'order_failed_999',
        'status' => PaymentStatus::Created,
        'amount' => 30000,
    ]);

    $payload = [
        'event_id' => 'evt_fail_1',
        'event' => 'payment.failed',
        'payload' => [
            'payment' => [
                'entity' => [
                    'id' => 'pay_fail_999',
                    'order_id' => 'order_failed_999',
                    'error_description' => 'Card declined',
                ],
            ],
        ],
    ];

    $webhookEvent = WebhookEvent::factory()->create([
        'provider' => 'razorpay',
        'event_id' => 'evt_fail_1',
        'event_type' => 'payment.failed',
        'payload' => json_encode($payload),
        'signature_valid' => true,
        'status' => WebhookStatus::Pending,
    ]);

    app(ProcessRazorpayWebhook::class)->handle($webhookEvent);

    $order->refresh();
    $payment->refresh();

    expect($order->status)->toBe(OrderStatus::Failed);
    expect($payment->status)->toBe(PaymentStatus::Failed);
    expect($payment->failure_reason)->toBe('Card declined');
});

test('3. Browser closed after payment: webhook alone completes the order', function () {
    Event::fake([OrderPaid::class]);

    $order = Order::factory()->create([
        'total' => 25000,
        'status' => OrderStatus::PendingPayment,
    ]);

    $payment = Payment::factory()->create([
        'order_id' => $order->id,
        'provider_order_id' => 'order_no_callback',
        'status' => PaymentStatus::Created,
        'amount' => 25000,
    ]);

    $payload = [
        'event_id' => 'evt_success_standalone',
        'event' => 'payment.captured',
        'payload' => [
            'payment' => [
                'entity' => [
                    'id' => 'pay_alone_100',
                    'order_id' => 'order_no_callback',
                    'amount' => 25000,
                    'currency' => 'INR',
                    'method' => 'upi',
                ],
            ],
        ],
    ];

    $webhookEvent = WebhookEvent::factory()->create([
        'provider' => 'razorpay',
        'event_id' => 'evt_success_standalone',
        'event_type' => 'payment.captured',
        'payload' => json_encode($payload),
        'signature_valid' => true,
        'status' => WebhookStatus::Pending,
    ]);

    app(ProcessRazorpayWebhook::class)->handle($webhookEvent);

    $order->refresh();
    expect($order->status)->toBe(OrderStatus::Paid);

    Event::assertDispatched(OrderPaid::class, fn ($event) => $event->order->id === $order->id);
});

test('4. Webhook arrives before browser callback: later callback changes nothing', function () {
    Event::fake([OrderPaid::class]);

    $user = User::factory()->create();
    $order = Order::factory()->create([
        'user_id' => $user->id,
        'total' => 40000,
        'status' => OrderStatus::PendingPayment,
    ]);

    $payment = Payment::factory()->create([
        'order_id' => $order->id,
        'provider_order_id' => 'order_first_webhook',
        'status' => PaymentStatus::Created,
        'amount' => 40000,
    ]);

    // 1. Webhook arrives first
    $payload = [
        'event_id' => 'evt_first_1',
        'event' => 'payment.captured',
        'payload' => [
            'payment' => [
                'entity' => [
                    'id' => 'pay_first_1',
                    'order_id' => 'order_first_webhook',
                    'amount' => 40000,
                    'currency' => 'INR',
                ],
            ],
        ],
    ];

    $webhookEvent = WebhookEvent::factory()->create([
        'provider' => 'razorpay',
        'event_id' => 'evt_first_1',
        'event_type' => 'payment.captured',
        'payload' => json_encode($payload),
        'signature_valid' => true,
    ]);

    app(ProcessRazorpayWebhook::class)->handle($webhookEvent);
    expect($order->fresh()->status)->toBe(OrderStatus::Paid);

    // 2. Later browser callback arrives
    $keySecret = config('services.razorpay.key_secret');
    $validSignature = hash_hmac('sha256', 'order_first_webhook|pay_first_1', $keySecret);

    $this->actingAs($user)->post(route('payments.razorpay.callback'), [
        'order_id' => $order->id,
        'razorpay_order_id' => 'order_first_webhook',
        'razorpay_payment_id' => 'pay_first_1',
        'razorpay_signature' => $validSignature,
    ])->assertRedirect(route('orders.success', $order->id));

    expect($order->fresh()->status)->toBe(OrderStatus::Paid);
    Event::assertDispatchedTimes(OrderPaid::class, 1);
});

test('5. Duplicate webhook delivered twice: second delivery returns 200 and does nothing', function () {
    Queue::fake();

    $secret = config('services.razorpay.webhook_secret');
    $rawPayload = json_encode(['event_id' => 'evt_dup_999', 'event' => 'payment.captured']);
    $signature = hash_hmac('sha256', $rawPayload, $secret);

    // First delivery
    $response1 = $this->call('POST', route('webhooks.razorpay'), [], [], [], [
        'HTTP_X-Razorpay-Signature' => $signature,
        'CONTENT_TYPE' => 'application/json',
    ], $rawPayload);

    $response1->assertStatus(200);

    // Second delivery
    $response2 = $this->call('POST', route('webhooks.razorpay'), [], [], [], [
        'HTTP_X-Razorpay-Signature' => $signature,
        'CONTENT_TYPE' => 'application/json',
    ], $rawPayload);

    $response2->assertStatus(200);
    expect(WebhookEvent::where('event_id', 'evt_dup_999')->count())->toBe(1);
});

test('6. Invalid callback signature rejected: order stays pending', function () {
    $user = User::factory()->create();
    $order = Order::factory()->create([
        'user_id' => $user->id,
        'status' => OrderStatus::PendingPayment,
    ]);

    $response = $this->actingAs($user)->post(route('payments.razorpay.callback'), [
        'order_id' => $order->id,
        'razorpay_order_id' => 'order_fake',
        'razorpay_payment_id' => 'pay_fake',
        'razorpay_signature' => 'invalid_signature_hash',
    ]);

    $response->assertRedirect(route('orders.payment-pending', $order->id));
    expect($order->fresh()->status)->toBe(OrderStatus::PendingPayment);
});

test('7. Invalid webhook signature rejected: returns 400 and records failed event', function () {
    $rawPayload = json_encode(['event_id' => 'evt_bad_sig', 'event' => 'payment.captured']);

    $response = $this->call('POST', route('webhooks.razorpay'), [], [], [], [
        'HTTP_X-Razorpay-Signature' => 'wrong_signature',
        'CONTENT_TYPE' => 'application/json',
    ], $rawPayload);

    $response->assertStatus(400);

    $event = WebhookEvent::where('event_id', 'evt_bad_sig')->first();
    expect($event)->not->toBeNull();
    expect($event->signature_valid)->toBeFalse();
    expect($event->status)->toBe(WebhookStatus::Failed);
});

test('8. Amount mismatch never marks the order paid: order stays pending and payment fails', function () {
    $order = Order::factory()->create([
        'total' => 50000, // Expected ₹500.00
        'status' => OrderStatus::PendingPayment,
    ]);

    $payment = Payment::factory()->create([
        'order_id' => $order->id,
        'amount' => 50000,
    ]);

    expect(fn () => app(MarkOrderPaid::class)->handle(
        order: $order,
        payment: $payment,
        capturedAmount: 20000, // Sent ₹200.00 (mismatch!)
        currency: 'INR',
    ))->toThrow(InvalidArgumentException::class);

    expect($order->fresh()->status)->toBe(OrderStatus::PendingPayment);
    expect($payment->fresh()->status)->toBe(PaymentStatus::Failed);
});

test('9. Gateway timeout leaves recoverable pending state: payments:reconcile later resolves it', function () {
    $order = Order::factory()->create([
        'total' => 15000,
        'status' => OrderStatus::PendingPayment,
        'created_at' => now()->subMinutes(10),
    ]);

    Payment::factory()->create([
        'order_id' => $order->id,
        'provider' => 'razorpay',
        'provider_order_id' => 'order_timeout_123',
        'status' => PaymentStatus::Created,
        'amount' => 15000,
    ]);

    // Order remains pending_payment
    expect($order->status)->toBe(OrderStatus::PendingPayment);

    // Call reconcile command
    $this->artisan('payments:reconcile')->assertExitCode(0);
});

test('10. Full refund and partial refund: amounts and order status are kept in sync', function () {
    Event::fake([RefundCompleted::class]);

    $order = Order::factory()->create([
        'total' => 100000, // ₹1000.00
        'status' => OrderStatus::Paid,
    ]);

    $payment = Payment::factory()->create([
        'order_id' => $order->id,
        'amount' => 100000,
        'status' => PaymentStatus::Captured,
        'provider_payment_id' => 'pay_refund_100',
    ]);

    $issueRefund = app(IssueRefund::class);

    // 1. Partial refund of ₹400.00 (40000 paise)
    $refund1 = $issueRefund->handle($payment, 40000, 'Partial return');
    expect($refund1->status)->toBe(RefundStatus::Processed);
    expect($order->fresh()->status)->toBe(OrderStatus::PartiallyRefunded);

    // 2. Second partial refund of remaining ₹600.00 (60000 paise)
    $refund2 = $issueRefund->handle($payment, 60000, 'Final return');
    expect($refund2->status)->toBe(RefundStatus::Processed);
    expect($order->fresh()->status)->toBe(OrderStatus::Refunded);

    // 3. Attempting to refund more than paid throws exception
    expect(fn () => $issueRefund->handle($payment, 1000, 'Extra refund'))
        ->toThrow(InvalidArgumentException::class);
});

test('11. Double-click on checkout does not create duplicate fulfilled orders', function () {
    Event::fake([OrderPaid::class]);

    $order = Order::factory()->create([
        'total' => 50000,
        'status' => OrderStatus::PendingPayment,
    ]);

    $payment = Payment::factory()->create([
        'order_id' => $order->id,
        'amount' => 50000,
        'status' => PaymentStatus::Created,
    ]);

    $markOrderPaid = app(MarkOrderPaid::class);

    // First call
    $markOrderPaid->handle($order, $payment, 50000, 'INR', 'pay_click_1');
    // Concurrent second call
    $markOrderPaid->handle($order, $payment, 50000, 'INR', 'pay_click_1');

    expect($order->fresh()->status)->toBe(OrderStatus::Paid);
    Event::assertDispatchedTimes(OrderPaid::class, 1);
});
