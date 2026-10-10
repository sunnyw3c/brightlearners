<?php

namespace App\Domains\Growth\Policies;

use App\Domains\Growth\Models\Redirect;
use App\Models\User;

class RedirectPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['super-admin', 'business-admin']) || $user->can('redirects.manage');
    }

    public function view(User $user, Redirect $redirect): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $this->viewAny($user);
    }

    public function update(User $user, Redirect $redirect): bool
    {
        return $this->viewAny($user);
    }

    public function delete(User $user, Redirect $redirect): bool
    {
        return $this->viewAny($user);
    }
}
