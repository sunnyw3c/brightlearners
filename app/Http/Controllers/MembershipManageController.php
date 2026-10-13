<?php

namespace App\Http\Controllers;

use App\Domains\Membership\Actions\CancelRecurringSubscription;
use App\Domains\Membership\Enums\SubscriptionStatus;
use App\Domains\Membership\Models\Subscription;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MembershipManageController extends Controller
{
    public function __construct(
        private readonly CancelRecurringSubscription $cancelRecurringSubscription,
    ) {}

    /**
     * Show membership management and recurring renewal status.
     */
    public function show(Request $request): Response
    {
        $user = $request->user();

        $subscription = Subscription::query()
            ->where('user_id', $user->id)
            ->whereIn('status', [SubscriptionStatus::Active, SubscriptionStatus::Expired, SubscriptionStatus::Cancelled])
            ->with(['plan', 'events'])
            ->latest('id')
            ->first();

        return Inertia::render('membership/manage', [
            'subscription' => $subscription ? [
                'id' => $subscription->id,
                'plan_name' => $subscription->plan?->name ?? 'Standard Plan',
                'plan_code' => $subscription->plan?->code,
                'price_formatted' => $subscription->plan ? '₹'.number_format($subscription->plan->price / 100, 2) : null,
                'status' => $subscription->status->value,
                'is_active' => $subscription->isActive(),
                'auto_renew' => $subscription->auto_renew,
                'cancel_at_period_end' => $subscription->cancel_at_period_end,
                'starts_at' => $subscription->starts_at->toFormattedDateString(),
                'ends_at' => $subscription->ends_at->toFormattedDateString(),
                'cancelled_at' => $subscription->cancelled_at?->toFormattedDateString(),
                'grace_ends_at' => $subscription->grace_ends_at?->toFormattedDateString(),
                'is_in_grace' => $subscription->isWithinGracePeriod(),
            ] : null,
        ]);
    }

    /**
     * Cancel the recurring subscription with terms compliance.
     */
    public function cancel(Request $request): RedirectResponse
    {
        $user = $request->user();

        $subscription = Subscription::query()
            ->where('user_id', $user->id)
            ->where('status', SubscriptionStatus::Active)
            ->latest('id')
            ->firstOrFail();

        $validated = $request->validate([
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

        $this->cancelRecurringSubscription->handle(
            subscription: $subscription,
            immediately: false,
            reason: $validated['reason'] ?? 'Customer requested cancellation via portal',
        );

        return redirect()->route('membership.manage')->with(
            'success',
            'Your recurring subscription has been cancelled. You will continue to have full access until the end of your current billing period ('.$subscription->ends_at->toFormattedDateString().').'
        );
    }
}
