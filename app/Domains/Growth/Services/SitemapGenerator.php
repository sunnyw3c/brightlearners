<?php

namespace App\Domains\Growth\Services;

use App\Domains\Catalog\Enums\ProductStatus;
use App\Domains\Catalog\Models\Product;
use App\Domains\Content\Enums\ResourceStatus;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Curriculum\Models\SchoolClass;
use App\Domains\Growth\Models\Article;
use App\Domains\Growth\Models\ArticleCategory;
use Illuminate\Support\Facades\Cache;

class SitemapGenerator
{
    public const CACHE_TTL = 86400; // 24 hours

    /**
     * Get sitemap index XML.
     */
    public static function indexXml(): string
    {
        return Cache::remember('sitemap:index', self::CACHE_TTL, function () {
            $sections = ['pages', 'resources', 'products', 'articles'];
            $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
            $xml .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";

            foreach ($sections as $section) {
                $loc = route('sitemap.section', ['section' => $section]);
                $xml .= "  <sitemap>\n";
                $xml .= "    <loc>{$loc}</loc>\n";
                $xml .= '    <lastmod>'.now()->toIso8601String()."</lastmod>\n";
                $xml .= "  </sitemap>\n";
            }

            $xml .= '</sitemapindex>';

            return $xml;
        });
    }

    /**
     * Get section sitemap XML.
     */
    public static function sectionXml(string $section): ?string
    {
        return match ($section) {
            'pages' => self::pagesXml(),
            'resources' => self::resourcesXml(),
            'products' => self::productsXml(),
            'articles' => self::articlesXml(),
            default => null,
        };
    }

    /**
     * Pages sitemap: static routes and canonical curriculum/category landing pages.
     */
    public static function pagesXml(): string
    {
        return Cache::remember('sitemap:pages', self::CACHE_TTL, function () {
            $urls = [
                route('home'),
                route('learn.index'),
                route('free.index'),
                route('shop.index'),
                route('membership.index'),
                route('parents.index'),
            ];

            // Shop types
            $shopTypes = ['topic-packs', 'workbooks', 'ebooks', 'holiday-packs', 'revision-packs', 'bundles'];
            foreach ($shopTypes as $type) {
                $urls[] = route('shop.type', ['type' => $type]);
            }

            // Classes & subjects & topics
            $classes = SchoolClass::active()->with(['subjects.topics'])->get();
            foreach ($classes as $schoolClass) {
                $urls[] = $schoolClass->canonicalUrl();

                foreach ($schoolClass->subjects as $subject) {
                    $urls[] = $subject->canonicalUrl($schoolClass);

                    foreach ($subject->topics as $topic) {
                        $urls[] = $topic->canonicalUrl($schoolClass);
                    }
                }
            }

            // Article categories
            $categories = ArticleCategory::all();
            foreach ($categories as $category) {
                $urls[] = $category->canonicalUrl();
            }

            return self::buildUrlset(array_unique($urls));
        });
    }

    /**
     * Resources sitemap: only published, canonical URLs.
     */
    public static function resourcesXml(): string
    {
        return Cache::remember('sitemap:resources', self::CACHE_TTL, function () {
            $resources = LearningResource::where('status', ResourceStatus::Published)
                ->with(['skillMappings.schoolClass', 'skillMappings.skill.topic.subject'])
                ->get();

            $entries = [];
            foreach ($resources as $resource) {
                $url = $resource->canonicalUrl();
                if ($url) {
                    $entries[] = [
                        'loc' => $url,
                        'lastmod' => $resource->updated_at?->toIso8601String(),
                    ];
                }
            }

            return self::buildUrlsetWithLastmod($entries);
        });
    }

    /**
     * Products sitemap: only published, active products.
     */
    public static function productsXml(): string
    {
        return Cache::remember('sitemap:products', self::CACHE_TTL, function () {
            $products = Product::where('status', ProductStatus::Active)
                ->where(function ($q) {
                    $q->whereNull('publish_at')->orWhere('publish_at', '<=', now());
                })
                ->with('primaryClass')
                ->get();

            $entries = [];
            foreach ($products as $product) {
                $entries[] = [
                    'loc' => $product->canonicalUrl(),
                    'lastmod' => $product->updated_at?->toIso8601String(),
                ];
            }

            return self::buildUrlsetWithLastmod($entries);
        });
    }

    /**
     * Articles sitemap: only published articles.
     */
    public static function articlesXml(): string
    {
        return Cache::remember('sitemap:articles', self::CACHE_TTL, function () {
            $articles = Article::published()->get();

            $entries = [];
            foreach ($articles as $article) {
                $entries[] = [
                    'loc' => $article->canonicalUrl(),
                    'lastmod' => $article->updated_at?->toIso8601String(),
                ];
            }

            return self::buildUrlsetWithLastmod($entries);
        });
    }

    /**
     * Clear and warm all sitemap caches.
     */
    public static function warmCache(): void
    {
        Cache::forget('sitemap:index');
        Cache::forget('sitemap:pages');
        Cache::forget('sitemap:resources');
        Cache::forget('sitemap:products');
        Cache::forget('sitemap:articles');

        self::indexXml();
        self::pagesXml();
        self::resourcesXml();
        self::productsXml();
        self::articlesXml();
    }

    /**
     * Build urlset XML from a list of URLs.
     *
     * @param  list<string>  $urls
     */
    protected static function buildUrlset(array $urls): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";

        foreach ($urls as $url) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>{$url}</loc>\n";
            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>';

        return $xml;
    }

    /**
     * Build urlset XML from list of entries with optional lastmod.
     *
     * @param  list<array{loc: string, lastmod?: string|null}>  $entries
     */
    protected static function buildUrlsetWithLastmod(array $entries): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";

        foreach ($entries as $entry) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>{$entry['loc']}</loc>\n";
            if (! empty($entry['lastmod'])) {
                $xml .= "    <lastmod>{$entry['lastmod']}</lastmod>\n";
            }
            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>';

        return $xml;
    }
}
