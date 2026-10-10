<?php

namespace Database\Factories;

use App\Domains\Content\Models\LearningResource;
use App\Domains\Membership\Models\LearningItem;
use App\Domains\Membership\Models\LearningWeek;
use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LearningItem>
 */
#[UseModel(LearningItem::class)]
class LearningItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'week_id' => LearningWeek::factory(),
            'resource_id' => LearningResource::factory(),
            'sort_order' => 1,
            'required' => true,
            'guidance' => 'Complete this worksheet with 15–20 minutes of guided study.',
        ];
    }
}
