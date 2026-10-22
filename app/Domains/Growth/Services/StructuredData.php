<?php

namespace App\Domains\Growth\Services;

use App\Domains\Catalog\Models\Product;
use App\Domains\Catalog\Services\ProductPrice;
use App\Domains\Growth\Models\Article;
use App\Support\Data\Breadcrumb;

class StructuredData
{
    /**
     * Site-wide Organization structured data (step 12.8).
     *
     * @return array<string, mixed>
     */
    public static function organization(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => config('app.name', 'KiddoZen'),
            'url' => url('/'),
            'logo' => url('/images/logo.png'),
        ];
    }

    /**
     * BreadcrumbList structured data (step 12.7).
     *
     * @param  list<array{label: string, href?: string|null, url?: string|null}|Breadcrumb>  $breadcrumbs
     * @return array<string, mixed>
     */
    public static function breadcrumbList(array $breadcrumbs): array
    {
        $items = [];
        $position = 1;

        foreach ($breadcrumbs as $crumb) {
            $name = $crumb instanceof Breadcrumb ? $crumb->label : ($crumb['label'] ?? '');
            $url = $crumb instanceof Breadcrumb ? $crumb->url : ($crumb['url'] ?? $crumb['href'] ?? null);

            $item = [
                '@type' => 'ListItem',
                'position' => $position++,
                'name' => $name,
            ];

            if (! empty($url)) {
                $item['item'] = str_starts_with((string) $url, 'http')
                    ? $url
                    : url($url);
            }

            $items[] = $item;
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $items,
        ];
    }

    /**
     * Article structured data (step 12.8).
     *
     * @return array<string, mixed>
     */
    public static function article(Article $article): array
    {
        $siteName = (string) config('app.name', 'KiddoZen');

        return [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $article->title,
            'description' => $article->excerpt,
            'url' => $article->canonicalUrl(),
            'datePublished' => $article->published_at?->toIso8601String(),
            'dateModified' => $article->updated_at?->toIso8601String(),
            'author' => [
                '@type' => 'Person',
                'name' => $article->author?->name ?? "{$siteName} Editorial Team",
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => $siteName,
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => url('/images/logo.png'),
                ],
            ],
            'image' => $article->ogImageUrl() ?? url('/images/og-default.png'),
        ];
    }

    /**
     * Product / Offer structured data (step 12.9).
     * No rating data is included unless real reviews exist.
     *
     * @return array<string, mixed>
     */
    public static function product(Product $product): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->name,
            'description' => $product->short_description ?? strip_tags((string) $product->description),
            'sku' => $product->sku,
            'image' => $product->coverImageUrl() ?? url('/images/og-default.png'),
            'offers' => [
                '@type' => 'Offer',
                'price' => number_format(ProductPrice::for($product) / 100, 2, '.', ''),
                'priceCurrency' => $product->currency,
                'availability' => $product->isPublished() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'url' => $product->canonicalUrl(),
            ],
        ];
    }
}
