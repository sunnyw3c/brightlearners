<?php

namespace App\Domains\Content\Queries;

use App\Domains\Catalog\Enums\ProductStatus;
use App\Domains\Catalog\Enums\ProductType;
use App\Domains\Catalog\Models\Product;
use App\Domains\Content\Enums\ResourceType;
use App\Domains\Content\Models\LearningResource;
use App\Support\Data\Breadcrumb;
use App\Support\Data\ProductCard;
use Illuminate\Support\Collection;

class ColoringShowQuery
{
    public function forSlug(string $slug): LearningResource
    {
        return LearningResource::query()
            ->published()
            ->free()
            ->where('type', ResourceType::Coloring->value)
            ->where('slug', $slug)
            ->with([
                'currentVersion.previews',
                'skillMappings.schoolClass',
                'skillMappings.skill.topic.subject',
            ])
            ->firstOrFail();
    }

    /**
     * @return list<Breadcrumb>
     */
    public function breadcrumbs(LearningResource $resource): array
    {
        $schoolClass = $resource->primaryMapping()?->schoolClass;

        $crumbs = [
            new Breadcrumb('Home', route('home')),
            new Breadcrumb('Coloring Hub', route('coloring.index')),
        ];

        if ($schoolClass !== null) {
            $crumbs[] = new Breadcrumb($schoolClass->name, route('coloring.index', ['class' => $schoolClass->slug]));
        }

        $crumbs[] = new Breadcrumb($resource->title, null);

        return $crumbs;
    }

    /**
     * @return Collection<int, LearningResource>
     */
    public function related(LearningResource $resource, int $limit = 4): Collection
    {
        return LearningResource::query()
            ->published()
            ->free()
            ->where('type', ResourceType::Coloring->value)
            ->whereKeyNot($resource->id)
            ->with([
                'currentVersion.previews',
                'skillMappings.schoolClass',
                'skillMappings.skill.topic.subject',
            ])
            ->limit($limit)
            ->get();
    }

    /**
     * @return Collection<int, ProductCard>
     */
    public function recommendedProducts(LearningResource $resource, int $limit = 2): Collection
    {
        $classId = $resource->primaryMapping()?->class_id;

        $query = Product::query()
            ->where('status', ProductStatus::Active->value)
            ->whereIn('type', [ProductType::Workbook->value, ProductType::ActivityEbook->value, ProductType::Bundle->value])
            ->with(['primaryClass']);

        if ($classId !== null) {
            $query->where(function ($q) use ($classId) {
                $q->where('primary_class_id', $classId)
                    ->orWhereNull('primary_class_id');
            });
        }

        return $query->limit($limit)->get()->map(ProductCard::fromProduct(...));
    }
}
