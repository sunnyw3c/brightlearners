<?php

namespace App\Domains\Growth\Observers;

use App\Domains\Catalog\Models\Product;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Growth\Models\Article;
use App\Domains\Growth\Models\Redirect;
use App\Domains\Growth\Services\RedirectValidator;
use Illuminate\Database\Eloquent\Model;

class SlugRedirectObserver
{
    /**
     * When a published slug changes, create a 301 redirect automatically (step 12.10).
     */
    public function updating(Model $model): void
    {
        if (! $model->isDirty('slug')) {
            return;
        }

        $oldSlug = (string) $model->getOriginal('slug');
        $newSlug = (string) $model->slug;

        if ($oldSlug === '' || $oldSlug === $newSlug) {
            return;
        }

        $oldPath = null;
        $newPath = null;

        if ($model instanceof Article && $model->isPublished()) {
            $oldPath = "/blog/{$oldSlug}";
            $newPath = "/blog/{$newSlug}";
        } elseif ($model instanceof Product && $model->isPublished()) {
            $class = $model->classSlug();
            $oldPath = "/shop/{$class}/{$oldSlug}";
            $newPath = "/shop/{$class}/{$newSlug}";
        } elseif ($model instanceof LearningResource && $model->isPublished()) {
            $class = $model->primaryClassSlug() ?? 'class-1';
            $subject = $model->primarySubjectSlug() ?? 'maths';
            $oldPath = "/free/{$class}/{$subject}/{$oldSlug}";
            $newPath = "/free/{$class}/{$subject}/{$newSlug}";
        }

        if ($oldPath && $newPath && $oldPath !== $newPath) {
            $from = RedirectValidator::normalize($oldPath);
            $to = RedirectValidator::normalize($newPath);

            Redirect::updateOrCreate(
                ['from_path' => $from],
                [
                    'to_path' => $to,
                    'status_code' => 301,
                    'active' => true,
                ]
            );
        }
    }
}
