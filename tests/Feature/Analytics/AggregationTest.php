<?php

use App\Domains\Analytics\Actions\AggregateDailyMetrics;
use App\Domains\Analytics\Enums\EventName;
use App\Domains\Analytics\Models\AnalyticsEvent;
use App\Domains\Analytics\Models\DailyMetric;
use App\Domains\Commerce\Enums\OrderStatus;
use App\Domains\Commerce\Models\Order;
use App\Domains\Payments\Enums\PaymentStatus;
use App\Domains\Payments\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('aggregation is repeatable: running a date twice produces the same rows and values without duplication', function () {
    $date = Carbon::parse('2026-10-10');
    $user = User::factory()->create();

    // 1. Seed some events and orders for that date
    AnalyticsEvent::factory()->create([
        'event_name' => EventName::ResourceView,
        'occurred_at' => $date->copy()->setTime(9, 30),
        'anonymous_id' => 'anon_repeat_1',
        'properties' => [
            'resource_id' => 1,
            'class' => 'class-1',
            'subject' => 'maths',
            'skill' => 'counting',
            'source' => 'direct',
        ],
    ]);

    AnalyticsEvent::factory()->create([
        'event_name' => EventName::FreeDownload,
        'occurred_at' => $date->copy()->setTime(10, 0),
        'anonymous_id' => 'anon_repeat_1',
        'properties' => [
            'resource_id' => 1,
            'version_id' => 1,
        ],
    ]);

    $order = Order::factory()->create([
        'user_id' => $user->id,
        'total' => 29900,
        'status' => OrderStatus::Paid,
        'paid_at' => $date->copy()->setTime(11, 0),
    ]);

    Payment::factory()->create([
        'order_id' => $order->id,
        'amount' => 29900,
        'status' => PaymentStatus::Captured,
        'paid_at' => $date->copy()->setTime(11, 0),
    ]);

    $action = app(AggregateDailyMetrics::class);

    // First run
    $firstRun = $action->handle($date);
    $countFirstRun = DailyMetric::query()->where('date', '2026-10-10')->count();
    $valuesFirstRun = DailyMetric::query()->where('date', '2026-10-10')->pluck('value', 'metric_key')->toArray();

    expect($countFirstRun)->toBeGreaterThan(0);

    // Second run for the same date
    $secondRun = $action->handle($date);
    $countSecondRun = DailyMetric::query()->where('date', '2026-10-10')->count();
    $valuesSecondRun = DailyMetric::query()->where('date', '2026-10-10')->pluck('value', 'metric_key')->toArray();

    expect($countSecondRun)->toBe($countFirstRun)
        ->and($valuesSecondRun)->toEqual($valuesFirstRun);
});
