<?php

namespace App\Domains\Growth\Services;

use App\Domains\Catalog\Models\Product;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Growth\Data\SeoData;
use App\Domains\Growth\Models\Article;
use App\Domains\Growth\Models\ArticleCategory;

class SeoBuilder
{
    /**
     * SEO data for a learning resource detail page (step 12.3).
     */
    public static function forResource(
        LearningResource $resource,
        ?string $canonicalClass = null,
        ?string $canonicalSubject = null,
        bool $noindex = false,
        ?array $breadcrumbs = null
    ): SeoData {
        $className = $resource->primaryMapping()?->schoolClass?->name ?? 'Primary';
        $subjectName = $resource->primaryMapping()?->skill?->topic?->subject?->name ?? 'Learning';
        $siteName = (string) config('app.name', 'KiddoZen');

        $title = $resource->seo_title
            ?: "{$resource->title} Worksheet ({$className}) | {$siteName}";

        $description = $resource->seo_description
            ?: ($resource->summary ?: "Download free printable {$resource->title} worksheet for {$className} {$subjectName}. Includes concept exercises, answer keys, and parent guidance.");

        $jsonLd = $breadcrumbs ? StructuredData::breadcrumbList($breadcrumbs) : null;

        return new SeoData(
            title: $title,
            description: $description,
            canonical: $resource->canonicalUrl() ?? url()->current(),
            noindex: $noindex,
            ogTitle: $title,
            ogDescription: $description,
            ogImage: url('/images/og-default.png'),
            ogType: 'website',
            jsonLd: $jsonLd,
        );
    }

    /**
     * SEO data for a product page with Offer structured data (step 12.3, 12.9).
     */
    public static function forProduct(Product $product, bool $noindex = false, ?array $breadcrumbs = null): SeoData
    {
        $className = $product->primaryClass?->name ?? 'All Classes';
        $siteName = (string) config('app.name', 'KiddoZen');
        $title = $product->seo_title
            ?: "{$product->name} — {$className} | {$siteName}";

        $description = $product->seo_description
            ?: ($product->short_description ?: strip_tags((string) $product->description));

        $productLd = StructuredData::product($product);
        $jsonLd = $breadcrumbs
            ? [StructuredData::breadcrumbList($breadcrumbs), $productLd]
            : $productLd;

        return new SeoData(
            title: $title,
            description: $description,
            canonical: $product->canonicalUrl(),
            noindex: $noindex,
            ogTitle: $title,
            ogDescription: $description,
            ogImage: $product->coverImageUrl() ?? url('/images/og-default.png'),
            ogType: 'product',
            jsonLd: $jsonLd,
        );
    }

    /**
     * SEO data for an article detail page (step 12.3, 12.8).
     */
    public static function forArticle(Article $article, bool $noindex = false, ?array $breadcrumbs = null): SeoData
    {
        $siteName = (string) config('app.name', 'KiddoZen');
        $title = $article->seo_title ?: "{$article->title} | {$siteName} Parent Hub";
        $description = $article->seo_description ?: $article->excerpt;

        $articleLd = StructuredData::article($article);
        $jsonLd = $breadcrumbs
            ? [StructuredData::breadcrumbList($breadcrumbs), $articleLd]
            : $articleLd;

        return new SeoData(
            title: $title,
            description: $description,
            canonical: $article->canonicalUrl(),
            noindex: $noindex,
            ogTitle: $title,
            ogDescription: $description,
            ogImage: $article->ogImageUrl() ?? url('/images/og-default.png'),
            ogType: 'article',
            jsonLd: $jsonLd,
        );
    }

    /**
     * SEO data for an article category page.
     */
    public static function forCategory(ArticleCategory $category, bool $noindex = false, ?array $breadcrumbs = null): SeoData
    {
        $siteName = (string) config('app.name', 'KiddoZen');
        $title = "{$category->name} Guides — Parent Hub | {$siteName}";
        $description = $category->description ?: "Practical {$category->name} guides and home learning advice for parents of primary school children.";

        $jsonLd = $breadcrumbs ? StructuredData::breadcrumbList($breadcrumbs) : null;

        return new SeoData(
            title: $title,
            description: $description,
            canonical: $category->canonicalUrl(),
            noindex: $noindex,
            ogTitle: $title,
            ogDescription: $description,
            jsonLd: $jsonLd,
        );
    }

    /**
     * SEO data for the main Parent Hub.
     */
    public static function forParentHub(bool $noindex = false): SeoData
    {
        $siteName = (string) config('app.name', 'KiddoZen');
        $title = "Parent Hub: Primary Learning Guides | {$siteName}";
        $description = 'Practical learning guides and home routines for parents of Class 1–3 children. Expert tips on teaching phonics, math concepts, and positive study habits.';

        return new SeoData(
            title: $title,
            description: $description,
            canonical: route('parents.index'),
            noindex: $noindex,
            ogTitle: $title,
            ogDescription: $description,
        );
    }

    /**
     * Generic fallback SEO data.
     */
    public static function make(
        string $title,
        ?string $description = null,
        string $canonical = '',
        bool $noindex = false,
        ?array $jsonLd = null
    ): SeoData {
        return new SeoData(
            title: $title,
            description: $description,
            canonical: $canonical,
            noindex: $noindex,
            ogTitle: $title,
            ogDescription: $description,
            jsonLd: $jsonLd,
        );
    }
}
