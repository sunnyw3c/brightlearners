<?php

namespace App\Http\Controllers;

use App\Domains\Catalog\Models\Product;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Curriculum\Models\SchoolClass;
use App\Domains\Curriculum\Models\Skill;
use App\Domains\Growth\Models\Article;
use App\Domains\Growth\Services\SeoBuilder;
use Inertia\Inertia;
use Inertia\Response;

class ArticleController extends Controller
{
    /**
     * Article detail screen (/blog/{slug}, steps 12.2, 12.14).
     */
    public function show(string $slug): Response
    {
        $article = Article::published()
            ->where('slug', $slug)
            ->with(['author', 'category', 'relations.related'])
            ->firstOrFail();

        $breadcrumbs = [
            ['label' => 'Home', 'href' => route('home')],
            ['label' => 'Parent Hub', 'href' => route('parents.index')],
            ['label' => $article->category->name, 'href' => $article->category->canonicalUrl()],
            ['label' => $article->title],
        ];

        // "Try this" internal linking block from article_relations (step 12.14)
        $tryThis = $article->relations->map(function ($relation) {
            $related = $relation->related;
            if (! $related) {
                return null;
            }

            if ($related instanceof LearningResource) {
                return [
                    'type' => 'resource',
                    'title' => $related->title,
                    'summary' => (string) $related->summary,
                    'badge' => $related->is_free ? 'Free Worksheet' : 'Learning Resource',
                    'url' => $related->canonicalUrl(),
                ];
            }

            if ($related instanceof Product) {
                return [
                    'type' => 'product',
                    'title' => $related->name,
                    'summary' => (string) $related->short_description,
                    'badge' => 'Workbook Pack',
                    'url' => $related->canonicalUrl(),
                ];
            }

            if ($related instanceof SchoolClass) {
                return [
                    'type' => 'class',
                    'title' => "Explore {$related->name}",
                    'summary' => (string) $related->intro,
                    'badge' => 'Class Curriculum',
                    'url' => $related->canonicalUrl(),
                ];
            }

            if ($related instanceof Skill) {
                return [
                    'type' => 'skill',
                    'title' => $related->name,
                    'summary' => (string) $related->learning_objective,
                    'badge' => 'Core Skill',
                    'url' => route('free.index'),
                ];
            }

            return null;
        })->filter()->values();

        $relatedArticles = Article::published()
            ->where('category_id', $article->category_id)
            ->where('id', '!=', $article->id)
            ->with(['author', 'category'])
            ->latest('published_at')
            ->take(3)
            ->get();

        return Inertia::render('articles/show', [
            'article' => [
                'id' => $article->id,
                'title' => $article->title,
                'slug' => $article->slug,
                'excerpt' => $article->excerpt,
                'body' => $article->sanitizedBody(),
                'published_at' => $article->published_at?->format('F j, Y'),
                'author' => $article->author ? ['name' => $article->author->name] : null,
                'category' => [
                    'name' => $article->category->name,
                    'slug' => $article->category->slug,
                    'url' => $article->category->canonicalUrl(),
                ],
                'og_image_path' => $article->og_image_path,
            ],
            'tryThis' => $tryThis,
            'relatedArticles' => $relatedArticles,
            'breadcrumbs' => $breadcrumbs,
            'seo' => SeoBuilder::forArticle($article, breadcrumbs: $breadcrumbs),
        ]);
    }
}
