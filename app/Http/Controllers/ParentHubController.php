<?php

namespace App\Http\Controllers;

use App\Domains\Growth\Models\Article;
use App\Domains\Growth\Models\ArticleCategory;
use App\Domains\Growth\Services\SeoBuilder;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ParentHubController extends Controller
{
    /**
     * Parent Hub root screen (/parents, step 12.1, 12.11).
     */
    public function index(): Response
    {
        $categories = ArticleCategory::orderBy('sort_order')->withCount([
            'articles' => fn ($q) => $q->published(),
        ])->get();

        $featuredArticles = Article::published()
            ->with(['category', 'author'])
            ->latest('published_at')
            ->take(6)
            ->get();

        $breadcrumbs = [
            ['label' => 'Home', 'href' => route('home')],
            ['label' => 'Parent Hub'],
        ];

        return Inertia::render('parents/index', [
            'categories' => $categories,
            'featuredArticles' => $featuredArticles,
            'breadcrumbs' => $breadcrumbs,
            'seo' => SeoBuilder::forParentHub(),
        ]);
    }

    /**
     * Guide category listing (/parents/{category}, step 12.1).
     */
    public function category(Request $request, ArticleCategory $category): Response
    {
        $articles = Article::published()
            ->where('category_id', $category->id)
            ->with(['author', 'category'])
            ->latest('published_at')
            ->paginate(12)
            ->withQueryString();

        $breadcrumbs = [
            ['label' => 'Home', 'href' => route('home')],
            ['label' => 'Parent Hub', 'href' => route('parents.index')],
            ['label' => $category->name],
        ];

        $allCategories = ArticleCategory::orderBy('sort_order')->get();
        $hasFilters = count($request->query()) > 0;

        return Inertia::render('parents/category', [
            'category' => $category,
            'articles' => $articles,
            'categories' => $allCategories,
            'breadcrumbs' => $breadcrumbs,
            'seo' => SeoBuilder::forCategory($category, noindex: $hasFilters, breadcrumbs: $breadcrumbs),
        ]);
    }
}
