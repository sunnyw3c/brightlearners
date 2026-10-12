<?php

use App\Domains\Analytics\Actions\AggregateDailyMetrics;
use App\Domains\Analytics\Models\DailyMetric;
use App\Domains\Commerce\Enums\OrderStatus;
use App\Domains\Commerce\Models\Order;
use App\Domains\Payments\Enums\PaymentStatus;
use App\Domains\Payments\Enums\RefundStatus;
use App\Domains\Payments\Models\Payment;
use App\Domains\Payments\Models\Refund;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('dashboard totals reconcile to orders and payments: aggregated revenue equals sum of captured payments minus refunds', function () {
    $date = Carbon::parse('2026-10-10');
    $user = User::factory()->create();

    // 1. Order and Payment 1: ₹500 captured
    $order1 = Order::factory()->create([
        'user_id' => $user->id,
        'total' => 50000,
        'status' => OrderStatus::Paid,
        'paid_at' => $date->copy()->setTime(10, 0),
    ]);
    $payment1 = Payment::factory()->create([
        'order_id' => $order1->id,
        'amount' => 50000,
        'status' => PaymentStatus::Captured,
        'paid_at' => $date->copy()->setTime(10, 0),
    ]);

    // 2. Order and Payment 2: ₹300 captured
    $order2 = Order::factory()->create([
        'user_id' => $user->id,
        'total' => 30000,
        'status' => OrderStatus::Paid,
        'paid_at' => $date->copy()->setTime(11, 0),
    ]);
    $payment2 = Payment::factory()->create([
        'order_id' => $order2->id,
        'amount' => 30000,
        'status' => PaymentStatus::Captured,
        'paid_at' => $date->copy()->setTime(11, 0),
    ]);

    // 3. Order and Payment 3: ₹200 captured
    $order3 = Order::factory()->create([
        'user_id' => $user->id,
        'total' => 20000,
        'status' => OrderStatus::Paid,
        'paid_at' => $date->copy()->setTime(12, 0),
    ]);
    $payment3 = Payment::factory()->create([
        'order_id' => $order3->id,
        'amount' => 20000,
        'status' => PaymentStatus::Captured,
        'paid_at' => $date->copy()->setTime(12, 0),
    ]);

    // 4. Processed Refund of ₹100 on Payment 2
    Refund::factory()->create([
        'payment_id' => $payment2->id,
        'amount' => 10000,
        'status' => RefundStatus::Processed,
        'processed_at' => $date->copy()->setTime(15, 0),
    ]);

    // Run aggregation
    $action = app(AggregateDailyMetrics::class);
    $action->handle($date);

    $capturedMetric = DailyMetric::query()->where('date', '2026-10-10')->where('metric_key', 'captured_revenue')->first();
    $refundedMetric = DailyMetric::query()->where('date', '2026-10-10')->where('metric_key', 'refunded_amount')->first();
    $netRevenueMetric = DailyMetric::query()->where('date', '2026-10-10')->where('metric_key', 'net_revenue')->first();
    $aovMetric = DailyMetric::query()->where('date', '2026-10-10')->where('metric_key', 'aov')->first();
    $paidOrdersMetric = DailyMetric::query()->where('date', '2026-10-10')->where('metric_key', 'paid_orders')->first();

    expect((float) $capturedMetric->value)->toBe(100000.0) // 50000 + 30000 + 20000
        ->and((float) $refundedMetric->value)->toBe(10000.0)
        ->and((float) $netRevenueMetric->value)->toBe(90000.0) // 100000 - 10000
        ->and((int) $paidOrdersMetric->value)->toBe(3)
        ->and(round((float) $aovMetric->value, 2))->toBe(round(100000 / 3, 2));
});
