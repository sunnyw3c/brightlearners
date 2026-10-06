<?php

namespace App\Http\Controllers;

use App\Domains\Content\Queries\HomeContentQuery;
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
                title: config('app.name').' — Make Learning Simple, Fun and Meaningful',
                description: 'Free worksheets and activities for Class 1 to Class 3, plus workbooks and a weekly learning programme to go further.',
                canonical: route('home'),
            ),
        ]);
    }
}
