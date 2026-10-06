<?php

namespace App\Http\Controllers\Learn;

use App\Domains\Curriculum\Queries\SubjectLandingQuery;
use App\Http\Controllers\Controller;
use App\Support\Data\Seo;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

/**
 * `/{class}/{subject}` (step 5.3).
 */
class SubjectController extends Controller
{
    public function __invoke(Request $request, string $class, string $subject, SubjectLandingQuery $query): Response
    {
        $schoolClass = $query->schoolClass($class);

        abort_if($schoolClass === null, 404);

        $subjectModel = $query->forSlugs($class, $subject);

        abort_if($subjectModel === null, 404);

        $resources = $query->resources($schoolClass, $subjectModel, (int) $request->integer('page', 1));
        $intro = $query->intro($schoolClass, $subjectModel);
        $hasContent = filled($intro) || $resources->total() > 0;

        return Inertia::render('learn/subject', [
            'schoolClass' => $schoolClass,
            'subject' => $subjectModel,
            'intro' => $intro,
            'topics' => $query->topics($schoolClass, $subjectModel),
            'resources' => $resources,
            'breadcrumbs' => $query->breadcrumbs($schoolClass, $subjectModel),
            'seo' => new Seo(
                title: "{$subjectModel->name} — {$schoolClass->name}",
                description: $intro,
                canonical: route('learn.subject', ['class' => $schoolClass->slug, 'subject' => $subjectModel->slug]),
                noindex: ! $hasContent,
            ),
        ]);
    }
}
