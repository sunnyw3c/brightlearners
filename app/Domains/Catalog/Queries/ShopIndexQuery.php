<?php

namespace App\Domains\Catalog\Queries;

use App\Domains\Catalog\Enums\ProductType;
use App\Domains\Catalog\Models\Product;
use App\Domains\Curriculum\Models\SchoolClass;
use App\Domains\Curriculum\Models\Subject;
use App\Support\Data\ProductCard;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * The shop browse filters (docs/plan/phase-06-product-catalogue.md, step
 * 6.11): class, subject, type and price band.
 */
class ShopIndexQuery
{
    /**
     * Price bands in paise. `null` means "no upper bound".
     *
     * @var array<string, array{0: int, 1: ?int}>
     */
    public const PRICE_BANDS = [
        'under_100' => [0, 9_999],
        '100_200' => [10_000, 19_999],
        '200_500' => [20_000, 49_999],
        '500_plus' => [50_000, null],
    ];

    /**
     * @param  array{class?: ?string, subject?: ?string, type?: ?string, price_band?: ?string, q?: ?string}  $filters
     * @return LengthAwarePaginator<int, ProductCard>
     */
    public function handle(array $filters, int $page = 1, int $perPage = 12): LengthAwarePaginator
    {
        $query = $this->baseQuery();

        if (filled($filters['type'] ?? null)) {
            $type = ProductType::fromRouteSegment($filters['type']) ?? ProductType::tryFrom($filters['type']);

            if ($type !== null) {
                $query->where('type', $type->value);
            }
        }

        if (filled($filters['class'] ?? null)) {
            $classSlug = $filters['class'];
            $query->where(function ($q) use ($classSlug): void {
                $q->whereHas('primaryClass', fn ($qq) => $qq->where('slug', $classSlug))
                    ->orWhereHas('resources.skillMappings.schoolClass', fn ($qq) => $qq->where('slug', $classSlug));
            });
        }

        if (filled($filters['subject'] ?? null)) {
            $subjectSlug = $filters['subject'];
            $query->whereHas(
                'resources.skillMappings.skill.topic.subject',
                fn ($q) => $q->where('slug', $subjectSlug),
            );
        }

        if (filled($filters['price_band'] ?? null) && array_key_exists($filters['price_band'], self::PRICE_BANDS)) {
            [$min, $max] = self::PRICE_BANDS[$filters['price_band']];
            $query->where('regular_price', '>=', $min);

            if ($max !== null) {
                $query->where('regular_price', '<=', $max);
            }
        }

        if (filled($filters['q'] ?? null)) {
            $term = $filters['q'];
            $query->where(fn ($q) => $q->where('name', 'like', "%{$term}%")
                ->orWhere('short_description', 'like', "%{$term}%"));
        }

        $paginator = $query
            ->orderByDesc('featured')
            ->orderBy('name')
            ->paginate($perPage, ['*'], 'page', $page);

        return $paginator->setCollection(
            $paginator->getCollection()->map(ProductCard::fromProduct(...)),
        );
    }

    /**
     * @return array{classes: Collection<int, SchoolClass>, subjects: Collection<int, Subject>, types: list<array{value: string, segment: string}>, price_bands: list<string>}
     */
    public function filterOptions(): array
    {
        return [
            'classes' => SchoolClass::query()->active()->orderBy('sort_order')->get(['id', 'name', 'slug']),
            'subjects' => Subject::query()->active()->orderBy('sort_order')->get(['id', 'name', 'slug']),
            'types' => array_map(
                fn (ProductType $case): array => ['value' => $case->value, 'segment' => $case->routeSegment()],
                ProductType::cases(),
            ),
            'price_bands' => array_keys(self::PRICE_BANDS),
        ];
    }

    /**
     * @return Builder<Product>
     */
    private function baseQuery(): Builder
    {
        return Product::query()
            ->active()
            ->with(['primaryClass', 'resources.skillMappings.schoolClass', 'resources.skillMappings.skill.topic.subject']);
    }
}
