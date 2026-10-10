<?php

namespace App\Domains\Membership\Actions;

use App\Domains\Catalog\Enums\ProductType;
use App\Domains\Commerce\Models\Order;
use App\Domains\Membership\Enums\SubscriptionEventType;
use App\Domains\Membership\Enums\SubscriptionStatus;
use App\Domains\Membership\Events\MembershipActivated;
use App\Domains\Membership\Models\Subscription;
use App\Domains\Membership\Models\SubscriptionEvent;
use App\Domains\Membership\Models\SubscriptionPlan;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

class ActivateMembership
{
    public function handle(Order $order): ?Subscription
    {
        $order->loadMissing('items.product.subscriptionPlan');

        $membershipItem = $order->items->first(function ($item) {
            return $item->product?->type === ProductType::Membership
                || $item->product?->subscription_plan_id !== null;
        });

        if ($membershipItem === null) {
            return null;
        }

        return DB::transaction(function () use ($order, $membershipItem): Subscription {
            // Idempotency: return existing subscription if already activated for this order
            $existing = Subscription::query()->where('source_order_id', $order->id)->first();
            if ($existing !== null) {
                return $existing;
            }

            /** @var SubscriptionPlan $plan */
            $plan = $membershipItem->product?->subscriptionPlan
                ?? SubscriptionPlan::query()->where('code', 'pilot_90')->firstOrFail();

            $pilotConfigStart = config('membership.pilot_starts_at');
            $startsAt = now();

            if ($pilotConfigStart) {
                $configuredDate = CarbonImmutable::parse($pilotConfigStart);
                if ($configuredDate->isFuture()) {
                    $startsAt = $configuredDate;
                }
            } elseif ($order->paid_at) {
                $startsAt = CarbonImmutable::parse($order->paid_at);
            }

            $endsAt = $plan->duration_days
                ? $startsAt->addDays($plan->duration_days)
                : $startsAt->addMonths($plan->duration_months ?? 3);

            $subscription = Subscription::query()->create([
                'user_id' => $order->user_id,
                'plan_id' => $plan->id,
                'source_order_id' => $order->id,
                'starts_at' => $startsAt,
                'ends_at' => $endsAt,
                'status' => SubscriptionStatus::Active,
                'auto_renew' => false,
            ]);

            SubscriptionEvent::query()->create([
                'subscription_id' => $subscription->id,
                'type' => SubscriptionEventType::Activated,
                'occurred_at' => now(),
                'metadata' => [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'plan_code' => $plan->code,
                ],
            ]);

            MembershipActivated::dispatch($subscription);

            return $subscription;
        });
    }
}
