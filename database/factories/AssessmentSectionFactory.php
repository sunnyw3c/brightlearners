<?php

namespace Database\Factories;

use App\Domains\Learning\Models\Assessment;
use App\Domains\Learning\Models\AssessmentSection;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AssessmentSection>
 */
class AssessmentSectionFactory extends Factory
{
    protected $model = AssessmentSection::class;

    public function definition(): array
    {
        return [
            'assessment_id' => Assessment::factory(),
            'title' => fake()->words(3, true),
            'sort_order' => 1,
        ];
    }
}
