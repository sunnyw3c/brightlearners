<?php

namespace Database\Factories;

use App\Domains\Accounts\Models\LearningProfile;
use App\Domains\Curriculum\Models\Skill;
use App\Domains\Learning\Enums\SkillBand;
use App\Domains\Learning\Models\AssessmentAttempt;
use App\Domains\Learning\Models\SkillScore;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SkillScore>
 */
class SkillScoreFactory extends Factory
{
    protected $model = SkillScore::class;

    public function definition(): array
    {
        return [
            'learning_profile_id' => LearningProfile::factory(),
            'skill_id' => Skill::factory(),
            'score' => 80,
            'band' => SkillBand::Comfortable,
            'source_attempt_id' => AssessmentAttempt::factory(),
            'calculated_at' => now(),
        ];
    }
}
