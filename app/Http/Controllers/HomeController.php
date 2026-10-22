<?php

namespace App\Http\Controllers;

use App\Domains\Content\Queries\HomeContentQuery;
use App\Domains\Growth\Services\StructuredData;
use App\Support\Data\ResourceCard;
use App\Support\Data\Seo;
use Inertia\Inertia;
use Inertia\Response;

/**
 * The commercial homepage (step 5.1). "Browse Workbooks" and the
 * membership card link to pages that arrive in Phases 6 and 10; the page
 * itself marks them disabled until then.
 */
class HomeController extends Controller
{
    public function __invoke(HomeContentQuery $query): Response
    {
        return Inertia::render('home', [
            'classes' => $query->classes(),
            'featuredResources' => $query->featuredFreeResources()
                ->map(ResourceCard::fromLearningResource(...))
                ->all(),
            'seo' => new Seo(
                title: config('app.name', 'KiddoZen').' — Fun & Smart Learning for Class 1 to 3',
                description: 'Free printable worksheets, skill-building activities, and structured learning programmes for Class 1 to 3. Designed for parents to support joyful home learning.',
                canonical: route('home'),
                jsonLd: StructuredData::organization(),
            ),
        ]);
    }
}
