<?php

namespace App\Http\Controllers\Learn;

use App\Domains\Curriculum\Queries\ClassLandingQuery;
use App\Domains\Growth\Services\StructuredData;
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
        $breadcrumbs = $query->breadcrumbs($schoolClass);

        $siteName = (string) config('app.name', 'KiddoZen');
        $title = $schoolClass->seo_title
            ?: "{$schoolClass->name} Worksheets, Maths & English | {$siteName}";
        $description = $schoolClass->seo_description
            ?: ($schoolClass->intro ?: "Explore curriculum-aligned printable resources for {$schoolClass->name}. Free worksheets, core concept workbooks, and fun learning activities in Maths and English.");

        return Inertia::render('learn/class', [
            'schoolClass' => $schoolClass,
            'subjects' => $query->subjects($schoolClass),
            'popularTopics' => $query->popularTopics($schoolClass),
            'featuredResources' => $query->featuredFreeResources($schoolClass)
                ->map(ResourceCard::fromLearningResource(...))
                ->all(),
            'breadcrumbs' => $breadcrumbs,
            'seo' => new Seo(
                title: $title,
                description: $description,
                canonical: route('learn.class', ['class' => $schoolClass->slug]),
                noindex: ! $hasContent,
                jsonLd: StructuredData::breadcrumbList($breadcrumbs),
            ),
        ]);
    }
}
