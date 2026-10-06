<?php

namespace App\Http\Controllers\Learn;

use App\Domains\Curriculum\Queries\TopicLandingQuery;
use App\Http\Controllers\Controller;
use App\Support\Data\Seo;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

/**
 * `/{class}/{subject}/{topic}` (step 5.3).
 */
class TopicController extends Controller
{
    public function __invoke(Request $request, string $class, string $subject, string $topic, TopicLandingQuery $query): Response
    {
        $schoolClass = $query->schoolClass($class);

        abort_if($schoolClass === null, 404);

        $subjectModel = $query->subject($schoolClass, $subject);

        abort_if($subjectModel === null, 404);

        $topicModel = $query->topic($subjectModel, $schoolClass, $topic);

        abort_if($topicModel === null, 404);

        $resources = $query->resources($schoolClass, $topicModel, (int) $request->integer('page', 1));
        $intro = $query->intro($schoolClass, $topicModel);
        $hasContent = filled($intro) || $resources->total() > 0;

        return Inertia::render('learn/topic', [
            'schoolClass' => $schoolClass,
            'subject' => $subjectModel,
            'topic' => $topicModel,
            'intro' => $intro,
            'skills' => $query->skills($schoolClass, $topicModel),
            'resources' => $resources,
            'breadcrumbs' => $query->breadcrumbs($schoolClass, $subjectModel, $topicModel),
            'seo' => new Seo(
                title: "{$topicModel->name} — {$schoolClass->name} {$subjectModel->name}",
                description: $intro,
                canonical: route('learn.topic', [
                    'class' => $schoolClass->slug,
                    'subject' => $subjectModel->slug,
                    'topic' => $topicModel->slug,
                ]),
                noindex: ! $hasContent,
            ),
        ]);
    }
}
