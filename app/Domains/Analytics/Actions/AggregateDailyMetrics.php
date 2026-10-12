<?php

namespace App\Domains\Analytics\Actions;

use App\Domains\Accounts\Models\LearningProfile;
use App\Domains\Analytics\Enums\EventName;
use App\Domains\Analytics\Models\AnalyticsEvent;
use App\Domains\Analytics\Models\DailyMetric;
use App\Domains\Commerce\Enums\OrderStatus;
use App\Domains\Commerce\Models\Order;
use App\Domains\Learning\Models\ResourceProgress;
use App\Domains\Membership\Models\Subscription;
use App\Domains\Payments\Enums\PaymentStatus;
use App\Domains\Payments\Enums\RefundStatus;
use App\Domains\Payments\Models\Payment;
use App\Domains\Payments\Models\Refund;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AggregateDailyMetrics
{
    /**
     * Compute and persist daily metrics and funnel KPIs for the given date.
     *
     * @return Collection<int, DailyMetric>
     */
    public function handle(CarbonInterface|string|null $date = null): Collection
    {
        $targetDate = $date instanceof CarbonInterface
            ? $date
            : ($date ? Carbon::parse($date) : Carbon::yesterday());

        $dateStr = $targetDate->format('Y-m-d');
        $startOfDay = $targetDate->copy()->startOfDay();
        $endOfDay = $targetDate->copy()->endOfDay();
        $weekStart = $targetDate->copy()->subDays(6)->startOfDay();

        // 1. Funnel Metrics
        // Visitors: distinct anonymous or user IDs with any event that day
        $rawEventsForDate = AnalyticsEvent::query()
            ->whereBetween('occurred_at', [$startOfDay, $endOfDay]);

        $visitors = (int) (clone $rawEventsForDate)
            ->distinct()
            ->count(DB::raw("COALESCE(anonymous_id, CONCAT('u_', user_id))"));

        // Free downloaders
        $freeDownloaders = (int) AnalyticsEvent::query()
            ->where('event_name', EventName::FreeDownload->value)
            ->whereBetween('occurred_at', [$startOfDay, $endOfDay])
            ->distinct()
            ->count(DB::raw("COALESCE(anonymous_id, CONCAT('u_', user_id))"));

        // Product viewers
        $productViewers = (int) AnalyticsEvent::query()
            ->where('event_name', EventName::ProductView->value)
            ->whereBetween('occurred_at', [$startOfDay, $endOfDay])
            ->distinct()
            ->count(DB::raw("COALESCE(anonymous_id, CONCAT('u_', user_id))"));

        // Paid orders and purchasers on that date (from orders table for accuracy)
        $paidOrdersQuery = Order::query()
            ->where('status', OrderStatus::Paid)
            ->where(function ($q) use ($startOfDay, $endOfDay) {
                $q->whereBetween('paid_at', [$startOfDay, $endOfDay])
                    ->orWhere(function ($q2) use ($startOfDay, $endOfDay) {
                        $q2->whereNull('paid_at')
                            ->whereBetween('created_at', [$startOfDay, $endOfDay]);
                    });
            });

        $paidOrdersCount = (int) (clone $paidOrdersQuery)->count();
        $purchasers = (int) (clone $paidOrdersQuery)->distinct('user_id')->count('user_id');

        // Membership starts on that date
        $membersStarted = (int) Subscription::query()
            ->whereBetween('starts_at', [$startOfDay, $endOfDay])
            ->count();

        // Active members at end of day (must separate active from expired)
        $activeSubscriptions = Subscription::query()
            ->where('status', 'active')
            ->where('starts_at', '<=', $endOfDay)
            ->where(function ($q) use ($endOfDay) {
                $q->whereNull('ends_at')
                    ->orWhere('ends_at', '>=', $endOfDay);
            });

        $activeMembers = (int) (clone $activeSubscriptions)->count();
        $activeMemberUserIds = (clone $activeSubscriptions)->pluck('user_id')->all();

        // Weekly active members: active members who opened, downloaded, or completed in the 7 days ending on target date
        $activeProfileIds = LearningProfile::query()
            ->whereIn('user_id', $activeMemberUserIds)
            ->pluck('id')
            ->all();

        $weeklyActiveMembers = 0;
        if (! empty($activeProfileIds)) {
            $weeklyActiveMembers = (int) ResourceProgress::query()
                ->whereIn('profile_id', $activeProfileIds)
                ->where(function ($q) use ($weekStart, $endOfDay) {
                    $q->whereBetween('last_opened_at', [$weekStart, $endOfDay])
                        ->orWhereBetween('completed_at', [$weekStart, $endOfDay])
                        ->orWhereBetween('updated_at', [$weekStart, $endOfDay]);
                })
                ->distinct('profile_id')
                ->count('profile_id');
        }

        // 2. Financial Metrics (reconciled strictly from payments and orders)
        $capturedPayments = (int) Payment::query()
            ->where('status', PaymentStatus::Captured)
            ->where(function ($q) use ($startOfDay, $endOfDay) {
                $q->whereBetween('paid_at', [$startOfDay, $endOfDay])
                    ->orWhere(function ($q2) use ($startOfDay, $endOfDay) {
                        $q2->whereNull('paid_at')
                            ->whereBetween('created_at', [$startOfDay, $endOfDay]);
                    });
            })
            ->sum('amount');

        $refundedAmount = (int) Refund::query()
            ->where('status', RefundStatus::Processed)
            ->where(function ($q) use ($startOfDay, $endOfDay) {
                $q->whereBetween('processed_at', [$startOfDay, $endOfDay])
                    ->orWhere(function ($q2) use ($startOfDay, $endOfDay) {
                        $q2->whereNull('processed_at')
                            ->whereBetween('created_at', [$startOfDay, $endOfDay]);
                    });
            })
            ->sum('amount');

        $netRevenue = $capturedPayments - $refundedAmount;

        // AOV in paise
        $aov = $paidOrdersCount > 0 ? (float) round($capturedPayments / $paidOrdersCount, 2) : 0.0;

        // Paid conversion (purchasers / visitors)
        $paidConversion = $visitors > 0 ? (float) round($purchasers / $visitors, 4) : 0.0;

        // Member activation: members who opened at least 1 pack within 7 days of joining / new members
        $newMembers = Subscription::query()
            ->whereBetween('starts_at', [$startOfDay, $endOfDay])
            ->get();

        $activatedCount = 0;
        foreach ($newMembers as $sub) {
            $userProfiles = LearningProfile::query()->where('user_id', $sub->user_id)->pluck('id');
            $hasOpened = ResourceProgress::query()
                ->whereIn('profile_id', $userProfiles)
                ->where('last_opened_at', '>=', $sub->starts_at)
                ->where('last_opened_at', '<=', Carbon::parse($sub->starts_at)->addDays(7))
                ->exists();

            if ($hasOpened) {
                $activatedCount++;
            }
        }
        $memberActivation = $newMembers->isNotEmpty()
            ? (float) round($activatedCount / $newMembers->count(), 4)
            : 0.0;

        // Repeat weekly use: members active in 2 or more consecutive weeks / active members
        $repeatWeeklyUse = 0.0;
        if ($activeMembers > 0 && ! empty($activeProfileIds)) {
            $prevWeekStart = $targetDate->copy()->subDays(13)->startOfDay();
            $prevWeekEnd = $targetDate->copy()->subDays(7)->endOfDay();

            $activeInThisWeek = ResourceProgress::query()
                ->whereIn('profile_id', $activeProfileIds)
                ->whereBetween('last_opened_at', [$weekStart, $endOfDay])
                ->pluck('profile_id')
                ->unique();

            $activeInBoth = ResourceProgress::query()
                ->whereIn('profile_id', $activeInThisWeek)
                ->whereBetween('last_opened_at', [$prevWeekStart, $prevWeekEnd])
                ->distinct('profile_id')
                ->count('profile_id');

            $repeatWeeklyUse = (float) round($activeInBoth / $activeMembers, 4);
        }

        // Refund rate: refunded orders / paid orders
        $refundedOrdersCount = (int) Order::query()
            ->whereIn('status', [OrderStatus::Refunded, OrderStatus::PartiallyRefunded])
            ->whereBetween('updated_at', [$startOfDay, $endOfDay])
            ->count();
        $refundRate = $paidOrdersCount > 0 ? (float) round($refundedOrdersCount / $paidOrdersCount, 4) : 0.0;

        // Support rate & Renewal intent (baseline 0 until support/survey records exist)
        $supportRate = 0.0;
        $renewalIntent = 0.0;

        // Assemble metric map
        $metrics = [
            // Funnel
            'visitors' => $visitors,
            'free_downloaders' => $freeDownloaders,
            'product_viewers' => $productViewers,
            'purchasers' => $purchasers,
            'members_started' => $membersStarted,
            'weekly_active_members' => $weeklyActiveMembers,

            // KPIs
            'aov' => $aov,
            'paid_conversion' => $paidConversion,
            'active_members' => $activeMembers,
            'member_activation' => $memberActivation,
            'repeat_weekly_use' => $repeatWeeklyUse,
            'refund_rate' => $refundRate,
            'support_rate' => $supportRate,
            'renewal_intent' => $renewalIntent,

            // Revenue reconciliation
            'paid_orders' => $paidOrdersCount,
            'captured_revenue' => $capturedPayments,
            'refunded_amount' => $refundedAmount,
            'net_revenue' => $netRevenue,
        ];

        $persisted = collect();

        foreach ($metrics as $metricKey => $val) {
            $model = DailyMetric::query()->updateOrCreate(
                [
                    'date' => $dateStr,
                    'metric_key' => $metricKey,
                    'dimension_hash' => '',
                ],
                [
                    'dimension' => null,
                    'value' => $val,
                ]
            );

            $persisted->push($model);
        }

        return $persisted;
    }
}
