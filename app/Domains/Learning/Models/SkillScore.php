<?php

namespace App\Domains\Learning\Models;

use App\Domains\Accounts\Models\LearningProfile;
use App\Domains\Curriculum\Models\Skill;
use App\Domains\Learning\Enums\SkillBand;
use Database\Factories\SkillScoreFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Table('skill_scores')]
#[Fillable(['learning_profile_id', 'skill_id', 'score', 'band', 'source_attempt_id', 'calculated_at'])]
#[UseFactory(SkillScoreFactory::class)]
class SkillScore extends Model
{
    /** @use HasFactory<SkillScoreFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<LearningProfile, $this>
     */
    public function learningProfile(): BelongsTo
    {
        return $this->belongsTo(LearningProfile::class, 'learning_profile_id');
    }

    /**
     * @return BelongsTo<Skill, $this>
     */
    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skill::class);
    }

    /**
     * @return BelongsTo<AssessmentAttempt, $this>
     */
    public function sourceAttempt(): BelongsTo
    {
        return $this->belongsTo(AssessmentAttempt::class, 'source_attempt_id');
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'score' => 'integer',
            'band' => SkillBand::class,
            'calculated_at' => 'datetime',
        ];
    }
}
