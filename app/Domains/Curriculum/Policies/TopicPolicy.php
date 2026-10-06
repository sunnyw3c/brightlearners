<?php

namespace App\Domains\Curriculum\Policies;

use App\Domains\Curriculum\Models\Topic;
use App\Models\User;

class TopicPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('curriculum.view');
    }

    public function view(User $user, Topic $topic): bool
    {
        return $user->can('curriculum.view');
    }

    public function create(User $user): bool
    {
        return $user->can('curriculum.edit');
    }

    public function update(User $user, Topic $topic): bool
    {
        return $user->can('curriculum.edit');
    }

    public function delete(User $user, Topic $topic): bool
    {
        return $user->can('curriculum.edit') && ! $topic->skills()->exists();
    }

    public function restore(User $user, Topic $topic): bool
    {
        return $user->can('curriculum.edit');
    }

    public function forceDelete(User $user, Topic $topic): bool
    {
        return false;
    }
}
