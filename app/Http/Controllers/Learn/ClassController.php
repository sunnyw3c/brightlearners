<?php

namespace App\Http\Controllers\Learn;

use App\Domains\Curriculum\Queries\ClassLandingQuery;
use App\Http\Controllers\Controller;
use App\Support\Data\ResourceCard;
use App\Support\Data\Seo;
use Inertia\Inertia;
use Inertia\Response;

/**
 * `/{class}` (step 5.2, design-reference.md "Class page").
 */
class ClassController extends Controller
{
    public function __invoke(string $class, ClassLandingQuery $query): Response
    {
        $schoolClass = $query->forSlug($class);

        abort_if($schoolClass === null, 404);

        $hasContent = filled($schoolClass->intro) || $query->hasPublishedResource($schoolClass);

        return Inertia::render('learn/class', [
            'schoolClass' => $schoolClass,
            'subjects' => $query->subjects($schoolClass),
            'popularTopics' => $query->popularTopics($schoolClass),
            'featuredResources' => $query->featuredFreeResources($schoolClass)
                ->map(ResourceCard::fromLearningResource(...))
                ->all(),
            'breadcrumbs' => $query->breadcrumbs($schoolClass),
            'seo' => new Seo(
                title: "{$schoolClass->name} learning resources",
                description: $schoolClass->seo_description ?? $schoolClass->intro,
                canonical: route('learn.class', ['class' => $schoolClass->slug]),
                noindex: ! $hasContent,
            ),
        ]);
    }
}
