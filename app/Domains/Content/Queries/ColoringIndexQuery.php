<?php

namespace App\Domains\Content\Queries;

use App\Domains\Catalog\Enums\ProductStatus;
use App\Domains\Catalog\Enums\ProductType;
use App\Domains\Catalog\Models\Product;
use App\Domains\Content\Enums\ResourceType;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Curriculum\Models\SchoolClass;
use App\Support\Data\ProductCard;
use App\Support\Data\ResourceCard;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Handles querying coloring resources for the dedicated /coloring discovery hub.
 */
class ColoringIndexQuery
{
    /**
     * @param  array{theme?: ?string, class?: ?string, q?: ?string}  $filters
     * @return LengthAwarePaginator<int, ResourceCard>
     */
    public function handle(array $filters, int $page = 1, int $perPage = 12): LengthAwarePaginator
    {
        $query = LearningResource::query()
            ->published()
            ->free()
            ->where('type', ResourceType::Coloring->value)
            ->with([
                'currentVersion.previews',
                'skillMappings.schoolClass',
                'skillMappings.skill.topic.subject',
            ]);

        if (filled($filters['class'] ?? null)) {
            $query->whereHas('skillMappings.schoolClass', fn ($q) => $q->where('slug', $filters['class']));
        }

        if (filled($filters['theme'] ?? null)) {
            $theme = strtolower($filters['theme']);
            $query->where(function ($q) use ($theme) {
                $q->where('title', 'like', "%{$theme}%")
                    ->orWhere('summary', 'like', "%{$theme}%")
                    ->orWhere('description', 'like', "%{$theme}%")
                    ->orWhereHas('skillMappings.skill.topic', function ($tq) use ($theme) {
                        $tq->where('slug', 'like', "%{$theme}%")
                            ->orWhere('name', 'like', "%{$theme}%");
                    });
            });
        }

        if (filled($filters['q'] ?? null)) {
            $term = $filters['q'];
            $query->where(fn ($q) => $q->where('title', 'like', "%{$term}%")
                ->orWhere('summary', 'like', "%{$term}%")
                ->orWhere('description', 'like', "%{$term}%"));
        }

        $paginator = $query
            ->orderByDesc('featured')
            ->orderBy('title')
            ->paginate($perPage, ['*'], 'page', $page);

        return $paginator->setCollection(
            $paginator->getCollection()->map(ResourceCard::fromLearningResource(...)),
        );
    }

    /**
     * @return list<array{slug: string, name: string, description: string, icon: string}>
     */
    public function themeOptions(): array
    {
        return [
            [
                'slug' => 'animals',
                'name' => 'Animals & Birds',
                'description' => 'Cute wildlife, pets, birds and ocean animals with bold borders.',
                'icon' => 'PawPrint',
            ],
            [
                'slug' => 'alphabet',
                'name' => 'Alphabet & Phonics',
                'description' => 'Letter tracing, initial sound pictures and vocabulary building.',
                'icon' => 'Type',
            ],
            [
                'slug' => 'numbers',
                'name' => 'Numbers & Math',
                'description' => 'Count & color, number outlines and color-by-math facts.',
                'icon' => 'Binary',
            ],
            [
                'slug' => 'nature',
                'name' => 'Nature & Plants',
                'description' => 'Flowers, trees, seasons, rain, and environmental awareness.',
                'icon' => 'Flower2',
            ],
            [
                'slug' => 'vehicles',
                'name' => 'Vehicles & Transport',
                'description' => 'Cars, rockets, trains, planes, and community helper vehicles.',
                'icon' => 'Car',
            ],
            [
                'slug' => 'festivals',
                'name' => 'Festivals & Culture',
                'description' => 'Diwali diyas, Holi colors, Pongal pots, Indian heritage and joy.',
                'icon' => 'Sparkles',
            ],
            [
                'slug' => 'space',
                'name' => 'Space & Dinosaurs',
                'description' => 'Planets, friendly dinosaurs, astronauts and cosmic adventures.',
                'icon' => 'Rocket',
            ],
        ];
    }

    /**
     * @return Collection<int, SchoolClass>
     */
    public function classOptions(): Collection
    {
        return SchoolClass::query()->active()->orderBy('sort_order')->get(['id', 'name', 'slug']);
    }

    /**
     * Up to 3 active commercial workbooks from the shop to cross-promote.
     *
     * @return Collection<int, ProductCard>
     */
    public function featuredWorkbooks(): Collection
    {
        return Product::query()
            ->where('status', ProductStatus::Active->value)
            ->whereIn('type', [ProductType::Workbook->value, ProductType::ActivityEbook->value, ProductType::Bundle->value])
            ->where(function ($q) {
                $q->where('name', 'like', '%color%')
                    ->orWhere('name', 'like', '%activity%')
                    ->orWhere('name', 'like', '%workbook%')
                    ->orWhere('featured', true);
            })
            ->with(['primaryClass'])
            ->limit(3)
            ->get()
            ->map(ProductCard::fromProduct(...));
    }
}
