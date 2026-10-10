<?php

namespace App\Http\Controllers\Account;

use App\Domains\Membership\Models\Subscription;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MembershipController extends Controller
{
    public function index(Request $request): Response
    {
        $user = $request->user();

        $activeSubscription = Subscription::query()
            ->where('user_id', $user->id)
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>', now())
            ->where('status', 'active')
            ->with(['plan', 'sourceOrder'])
            ->latest('id')
            ->first();

        $subscriptionHistory = Subscription::query()
            ->where('user_id', $user->id)
            ->with(['plan', 'sourceOrder'])
            ->latest('id')
            ->get()
            ->map(fn (Subscription $sub) => [
                'id' => $sub->id,
                'plan_name' => $sub->plan?->name,
                'plan_code' => $sub->plan?->code,
                'status' => $sub->status->value,
                'status_label' => $sub->status->label(),
                'starts_at' => $sub->starts_at->format('M d, Y'),
                'ends_at' => $sub->ends_at->format('M d, Y'),
                'is_active' => $sub->isActive(),
                'order_number' => $sub->sourceOrder?->order_number,
            ]);

        $daysRemaining = 0;
        if ($activeSubscription) {
            $daysRemaining = max(0, (int) now()->diffInDays($activeSubscription->ends_at, false));
        }

        return Inertia::render('account/membership', [
            'active_subscription' => $activeSubscription ? [
                'id' => $activeSubscription->id,
                'plan_name' => $activeSubscription->plan?->name,
                'plan_code' => $activeSubscription->plan?->code,
                'status' => $activeSubscription->status->value,
                'status_label' => $activeSubscription->status->label(),
                'starts_at' => $activeSubscription->starts_at->format('M d, Y'),
                'ends_at' => $activeSubscription->ends_at->format('M d, Y'),
                'days_remaining' => $daysRemaining,
                'auto_renew' => $activeSubscription->auto_renew,
                'member_discount_percent' => $activeSubscription->plan?->benefits['member_discount_percent'] ?? 15,
                'inclusions' => $activeSubscription->plan?->benefits['inclusions'] ?? null,
            ] : null,
            'history' => $subscriptionHistory,
        ]);
    }
}
