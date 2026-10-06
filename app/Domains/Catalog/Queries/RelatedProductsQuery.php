<?php

namespace App\Domains\Catalog\Queries;

use App\Domains\Catalog\Models\Product;
use App\Domains\Curriculum\Models\Skill;
use Illuminate\Support\Collection;

/**
 * Related-product logic (docs/plan/phase-06-product-catalogue.md, step
 * 6.8), in order:
 * 1. bundles that contain this product (the upsell);
 * 2. other active products for the same class and subject;
 * 3. products for the next skill in the topic's order.
 */
class RelatedProductsQuery
{
    /**
     * @return Collection<int, Product>
     */
    public function forProduct(Product $product, int $limit = 6): Collection
    {
        $results = $product->bundlesContaining()->active()->limit($limit)->get();
        $excludeIds = $results->pluck('id')->push($product->id)->all();

        $primaryMapping = $product->deliverableResources()->first()?->primaryMapping();
        $classId = $product->primary_class_id ?? $primaryMapping?->class_id;
        $skill = $primaryMapping?->skill;
        $subjectId = $skill?->topic?->subject_id;

        if ($classId !== null && $subjectId !== null && $results->count() < $limit) {
            $remaining = $limit - $results->count();

            $batch = Product::query()
                ->active()
                ->whereKeyNot($excludeIds)
                ->where(fn ($query) => $query->where('primary_class_id', $classId)->orWhereNull('primary_class_id'))
                ->whereHas(
                    'resources.skillMappings',
                    fn ($query) => $query->where('class_id', $classId)
                        ->whereHas('skill.topic', fn ($q) => $q->where('subject_id', $subjectId)),
                )
                ->limit($remaining)
                ->get();

            $results = $results->merge($batch);
            $excludeIds = $results->pluck('id')->push($product->id)->all();
        }

        if ($skill !== null && $results->count() < $limit) {
            $nextSkill = Skill::query()
                ->where('topic_id', $skill->topic_id)
                ->where('sort_order', '>', $skill->sort_order)
                ->orderBy('sort_order')
                ->first();

            if ($nextSkill !== null) {
                $remaining = $limit - $results->count();

                $batch = Product::query()
                    ->active()
                    ->whereKeyNot($excludeIds)
                    ->whereHas('resources.skillMappings', fn ($query) => $query->where('skill_id', $nextSkill->id))
                    ->limit($remaining)
                    ->get();

                $results = $results->merge($batch);
            }
        }

        return $results->take($limit)->values();
    }
}
