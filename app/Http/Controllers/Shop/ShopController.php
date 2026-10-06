<?php

namespace App\Http\Controllers\Shop;

use App\Domains\Catalog\Enums\ProductType;
use App\Domains\Catalog\Queries\ShopIndexQuery;
use App\Domains\Catalog\Queries\ShopShowQuery;
use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\ShopIndexRequest;
use App\Support\Data\ProductCard;
use App\Support\Data\Seo;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ShopController extends Controller
{
    /**
     * `/shop` (step 6.11): the commercial catalogue, filtered by query
     * string.
     */
    public function index(ShopIndexRequest $request, ShopIndexQuery $query): Response
    {
        $filters = $request->filters();
        $hasActiveFilters = $request->hasActiveFilters();
        $page = (int) $request->integer('page', 1);

        $products = $query->handle($filters, $page);

        return Inertia::render('shop/index', [
            'products' => $products,
            'filters' => $filters,
            'filterOptions' => $query->filterOptions(),
            'seo' => new Seo(
                title: 'Shop — workbooks, topic packs and bundles',
                description: 'Printable workbooks, topic packs, ebooks and bundles for Class 1 to Class 3.',
                canonical: route('shop.index'),
                noindex: $hasActiveFilters,
            ),
        ]);
    }

    /**
     * `/shop/{type}` (step 6.11): a filtered commercial landing page for
     * one product type.
     */
    public function type(string $type, ShopIndexRequest $request, ShopIndexQuery $query): Response
    {
        $productType = ProductType::fromRouteSegment($type);

        abort_if($productType === null, 404);

        $filters = array_merge($request->filters(), ['type' => $type]);
        $page = (int) $request->integer('page', 1);

        $products = $query->handle($filters, $page);

        return Inertia::render('shop/type', [
            'type' => $productType->value,
            'typeSegment' => $type,
            'products' => $products,
            'filters' => $filters,
            'filterOptions' => $query->filterOptions(),
            'seo' => new Seo(
                title: ucwords(str_replace('-', ' ', $type)),
                description: null,
                canonical: route('shop.type', ['type' => $type]),
            ),
        ]);
    }

    /**
     * `/shop/{class}/{slug}` (step 6.7): the canonical product detail
     * page. A request on a non-canonical class redirects (step 6.10 gives
     * `all-classes` for a flagship product with no primary class).
     */
    public function show(string $class, string $slug, ShopShowQuery $query): Response|RedirectResponse
    {
        $product = $query->forSlug($slug);
        $canonicalClass = $product->classSlug();

        if ($class !== $canonicalClass) {
            return redirect()->route('shop.show', ['class' => $canonicalClass, 'slug' => $slug], 301);
        }

        $card = ProductCard::fromProduct($product);
        $deliverableResources = $product->deliverableResources();

        return Inertia::render('shop/show', [
            'product' => $card,
            'productDetail' => [
                'description' => $product->description,
                'total_pages' => $product->totalPages(),
                'has_answer_key' => $deliverableResources->contains('has_answer_key', true),
                'language' => $deliverableResources->pluck('language')->unique()->values()->all(),
                'licence_type' => $deliverableResources->pluck('licence_type')->unique()->values()->all(),
                'inclusions' => $deliverableResources->map(fn ($resource) => [
                    'title' => $resource->title,
                    'type' => $resource->type->value,
                    'page_count' => $resource->page_count,
                ])->values()->all(),
                'member_discount_eligible' => $product->member_discount_eligible,
                'cover_image_url' => $card->cover_image_url,
                'previews' => $deliverableResources
                    ->flatMap(fn ($resource) => $resource->currentVersion?->previews ?? collect())
                    ->sortBy('sort_order')
                    ->map(fn ($preview) => [
                        'url' => $preview->url(),
                        'width' => $preview->width,
                        'height' => $preview->height,
                    ])
                    ->values()
                    ->all(),
            ],
            'related' => $query->related($product)->all(),
            'breadcrumbs' => $query->breadcrumbs($product),
            'seo' => new Seo(
                title: $product->name,
                description: $product->seo_description ?? $product->short_description,
                canonical: route('shop.show', ['class' => $canonicalClass, 'slug' => $slug]),
            ),
        ]);
    }

    /**
     * `/products/{slug}` (step: optional alias): 301 to the canonical URL.
     */
    public function alias(string $slug, ShopShowQuery $query): RedirectResponse
    {
        $product = $query->forSlug($slug);

        return redirect()->route('shop.show', ['class' => $product->classSlug(), 'slug' => $slug], 301);
    }
}
