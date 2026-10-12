<?php

namespace App\Domains\Accounts\Actions;

use App\Models\User;
use App\Support\Audit;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class SyncStaffRoles
{
    /**
     * @param  list<string|Role>|string|Role  $roles
     */
    public function handle(User $user, array|string|Role $roles, ?User $actor = null): User
    {
        $actor = $actor ?? Auth::user();

        if ($actor !== null && ! $actor->can('roles.manage')) {
            throw new AuthorizationException('Only super-admin and business-admin can manage staff roles.');
        }

        $oldRoles = $user->getRoleNames()->values()->all();

        $user->syncRoles($roles);

        $newRoles = $user->fresh()->getRoleNames()->values()->all();

        Audit::record(
            'role.changed',
            $user,
            ['roles' => $oldRoles],
            ['roles' => $newRoles],
            ['actor_id' => $actor?->id]
        );

        return $user;
    }
}
