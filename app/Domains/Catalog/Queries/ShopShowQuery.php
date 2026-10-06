<?php

namespace App\Domains\Catalog\Queries;

use App\Domains\Catalog\Models\Product;
use App\Support\Data\Breadcrumb;
use App\Support\Data\ProductCard;
use Illuminate\Support\Collection;

/**
 * The product detail page (docs/plan/phase-06-product-catalogue.md, step
 * 6.7). An archived product still resolves here — it is only kept out of
 * the browse listings (`ShopIndexQuery`) and out of search.
 */
class ShopShowQuery
{
    public function __construct(private readonly RelatedProductsQuery $relatedProducts) {}

    public function forSlug(string $slug): Product
    {
        return Product::query()
            ->where('slug', $slug)
            ->with([
                'primaryClass',
                'resources.currentVersion.previews',
                'resources.skillMappings.schoolClass',
                'resources.skillMappings.skill.topic.subject',
                'childProducts.resources.currentVersion.previews',
                'childProducts.resources.skillMappings.schoolClass',
                'childProducts.resources.skillMappings.skill.topic.subject',
            ])
            ->firstOrFail();
    }

    /**
     * @return list<Breadcrumb>
     */
    public function breadcrumbs(Product $product): array
    {
        $crumbs = [
            new Breadcrumb('Home', route('home')),
            new Breadcrumb('Shop', route('shop.index')),
            new Breadcrumb(ucwords(str_replace('-', ' ', $product->type->routeSegment())), route('shop.type', ['type' => $product->type->routeSegment()])),
        ];

        if ($product->primaryClass !== null) {
            $crumbs[] = new Breadcrumb($product->primaryClass->name, route('shop.index').'?class='.$product->primaryClass->slug);
        }

        $crumbs[] = new Breadcrumb($product->name, null);

        return $crumbs;
    }

    /**
     * @return Collection<int, ProductCard>
     */
    public function related(Product $product, int $limit = 6): Collection
    {
        return $this->relatedProducts->forProduct($product, $limit)->map(ProductCard::fromProduct(...));
    }
}
