<?php

namespace Database\Factories;

use App\Domains\Accounts\Models\LearningProfile;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Curriculum\Models\Skill;
use App\Domains\Learning\Enums\RecommendationReason;
use App\Domains\Learning\Models\Recommendation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Recommendation>
 */
class RecommendationFactory extends Factory
{
    protected $model = Recommendation::class;

    public function definition(): array
    {
        return [
            'learning_profile_id' => LearningProfile::factory(),
            'skill_id' => Skill::factory(),
            'target_type' => 'resource',
            'target_id' => LearningResource::factory(),
            'reason_code' => RecommendationReason::WeakSkillPractice->value,
            'generated_at' => now(),
            'clicked_at' => null,
            'acted_at' => null,
        ];
    }
}
