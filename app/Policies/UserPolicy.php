<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Only super-admin and business-admin manage staff (roles.manage).
     * super-admin also passes via Gate::before in AppServiceProvider.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('roles.manage');
    }

    public function view(User $user, User $model): bool
    {
        return $user->can('roles.manage');
    }

    public function create(User $user): bool
    {
        return $user->can('roles.manage');
    }

    public function update(User $user, User $model): bool
    {
        return $user->can('roles.manage');
    }

    public function delete(User $user, User $model): bool
    {
        return $user->can('roles.manage') && $user->isNot($model);
    }

    public function restore(User $user, User $model): bool
    {
        return $user->can('roles.manage');
    }

    public function forceDelete(User $user, User $model): bool
    {
        return false;
    }
}
