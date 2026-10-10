<?php

namespace App\Domains\Growth\Policies;

use App\Domains\Growth\Models\Article;
use App\Models\User;

class ArticlePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['super-admin', 'business-admin']) || $user->can('articles.edit');
    }

    public function view(User $user, Article $article): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $this->viewAny($user);
    }

    public function update(User $user, Article $article): bool
    {
        return $this->viewAny($user);
    }

    public function delete(User $user, Article $article): bool
    {
        return $this->viewAny($user);
    }
}
