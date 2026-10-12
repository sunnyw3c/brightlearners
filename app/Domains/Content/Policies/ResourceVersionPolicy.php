<?php

namespace App\Domains\Content\Policies;

use App\Domains\Content\Models\ResourceVersion;
use App\Models\User;

class ResourceVersionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('resources.view');
    }

    public function view(User $user, ResourceVersion $version): bool
    {
        return $user->can('resources.view');
    }

    public function create(User $user): bool
    {
        return $user->can('resources.edit');
    }

    public function update(User $user, ResourceVersion $version): bool
    {
        return $user->can('resources.edit');
    }

    public function delete(User $user, ResourceVersion $version): bool
    {
        return false;
    }

    public function forceDelete(User $user, ResourceVersion $version): bool
    {
        return false;
    }
}
