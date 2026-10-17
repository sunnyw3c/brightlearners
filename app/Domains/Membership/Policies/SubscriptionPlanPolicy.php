<?php

namespace App\Domains\Membership\Policies;

use App\Domains\Membership\Models\SubscriptionPlan;
use App\Models\User;

/**
 * Plans are commercial configuration: only `business-admin` (and
 * `super-admin`) may see or change them (docs/plan/phase-10-membership-programme.md,
 * "Admin"). A plan is never deleted — subscriptions point at it — it is
 * switched off with `active` instead.
 */
class SubscriptionPlanPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('plans.edit-price');
    }

    public function view(User $user, SubscriptionPlan $plan): bool
    {
        return $user->can('plans.edit-price');
    }

    public function create(User $user): bool
    {
        return $user->can('plans.edit-price');
    }

    public function update(User $user, SubscriptionPlan $plan): bool
    {
        return $user->can('plans.edit-price');
    }

    public function delete(User $user, SubscriptionPlan $plan): bool
    {
        return false;
    }

    public function forceDelete(User $user, SubscriptionPlan $plan): bool
    {
        return false;
    }
}
