<?php

namespace App\Http\Controllers\Learn;

use App\Domains\Curriculum\Queries\CurriculumTree;
use App\Domains\Growth\Services\StructuredData;
use App\Http\Controllers\Controller;
use App\Support\Data\Breadcrumb;
use App\Support\Data\Seo;
use Inertia\Inertia;
use Inertia\Response;

/**
 * `/learn` (step 5.2): the curriculum browse page, one section per class.
 */
class LearnController extends Controller
{
    public function __invoke(CurriculumTree $tree): Response
    {
        $breadcrumbs = [
            new Breadcrumb('Home', route('home')),
            new Breadcrumb('Learn', null),
        ];

        return Inertia::render('learn/index', [
            'classes' => $tree->get(),
            'breadcrumbs' => $breadcrumbs,
            'seo' => new Seo(
                title: 'Primary Curriculum & Topics (Class 1-3) | '.config('app.name', 'KiddoZen'),
                description: 'Explore the complete curriculum, subjects, topics, and foundational skills covered for Class 1 to Class 3. Concept-driven worksheets and activities.',
                canonical: route('learn.index'),
                jsonLd: StructuredData::breadcrumbList($breadcrumbs),
            ),
        ]);
    }
}
