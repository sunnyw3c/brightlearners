<?php

use App\Domains\Analytics\Actions\AggregateDailyMetrics;
use App\Domains\Analytics\Models\DailyMetric;
use App\Domains\Membership\Models\Subscription;
use App\Domains\Membership\Models\SubscriptionPlan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('the member count separates active from expired with time travel across an expiry', function () {
    $plan = SubscriptionPlan::factory()->create();
    $user = User::factory()->create();

    // Subscription active from Oct 1 to Oct 15
    Subscription::factory()->create([
        'user_id' => $user->id,
        'plan_id' => $plan->id,
        'starts_at' => Carbon::parse('2026-10-01 00:00:00'),
        'ends_at' => Carbon::parse('2026-10-15 23:59:59'),
        'status' => 'active',
    ]);

    $action = app(AggregateDailyMetrics::class);

    // 1. Snapshot on Oct 10 (active window)
    $action->handle(Carbon::parse('2026-10-10'));
    $activeBeforeExpiry = DailyMetric::query()
        ->where('date', '2026-10-10')
        ->where('metric_key', 'active_members')
        ->first();

    expect((int) $activeBeforeExpiry->value)->toBe(1);

    // 2. Snapshot on Oct 16 (post expiry date)
    $action->handle(Carbon::parse('2026-10-16'));
    $activeAfterExpiry = DailyMetric::query()
        ->where('date', '2026-10-16')
        ->where('metric_key', 'active_members')
        ->first();

    expect((int) $activeAfterExpiry->value)->toBe(0);
});
