<?php

namespace App\Domains\Content\Policies;

use App\Domains\Content\Enums\ResourceStatus;
use App\Domains\Content\Models\LearningResource;
use App\Models\User;

class LearningResourcePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('resources.view');
    }

    public function view(User $user, LearningResource $resource): bool
    {
        return $user->can('resources.view');
    }

    public function create(User $user): bool
    {
        return $user->can('resources.edit');
    }

    public function update(User $user, LearningResource $resource): bool
    {
        return $user->can('resources.edit');
    }

    public function delete(User $user, LearningResource $resource): bool
    {
        return $user->can('resources.edit') && $resource->status === ResourceStatus::Draft;
    }

    public function restore(User $user, LearningResource $resource): bool
    {
        return $user->can('resources.edit');
    }

    public function forceDelete(User $user, LearningResource $resource): bool
    {
        return false;
    }

    /**
     * Record a review verdict (approve or request changes) for a version.
     */
    public function review(User $user, LearningResource $resource): bool
    {
        return $user->can('resources.review');
    }

    /**
     * Approve a version's final (design/print) review gate.
     */
    public function approve(User $user, LearningResource $resource): bool
    {
        return $user->can('resources.approve');
    }

    public function publish(User $user, LearningResource $resource): bool
    {
        return $user->can('resources.publish');
    }
}
