<?php

namespace App\Domains\Access\Policies;

use App\Domains\Access\Models\Entitlement;
use App\Models\User;

class EntitlementPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('entitlements.override') || $user->can('customers.view') || $user->hasRole('super-admin');
    }

    public function view(User $user, Entitlement $entitlement): bool
    {
        return $user->id === $entitlement->user_id || $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('entitlements.override') || $user->can('entitlements.override-limited');
    }

    public function update(User $user, Entitlement $entitlement): bool
    {
        return $user->can('entitlements.override') || $user->can('entitlements.override-limited');
    }

    public function delete(User $user, Entitlement $entitlement): bool
    {
        return false;
    }

    public function forceDelete(User $user, Entitlement $entitlement): bool
    {
        return false;
    }
}
