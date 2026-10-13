<?php

namespace App\Domains\Learning\Models;

use App\Domains\Accounts\Models\LearningProfile;
use App\Domains\Catalog\Models\Product;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Curriculum\Models\Skill;
use App\Domains\Learning\Enums\RecommendationReason;
use Database\Factories\RecommendationFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Table('recommendations')]
#[Fillable([
    'learning_profile_id',
    'skill_id',
    'target_type',
    'target_id',
    'reason_code',
    'generated_at',
    'clicked_at',
    'acted_at',
])]
#[UseFactory(RecommendationFactory::class)]
class Recommendation extends Model
{
    /** @use HasFactory<RecommendationFactory> */
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
     * Resolve the target model (LearningResource or Product).
     */
    public function target(): LearningResource|Product|null
    {
        return match ($this->target_type) {
            'resource' => LearningResource::query()->find($this->target_id),
            'product' => Product::query()->find($this->target_id),
            default => null,
        };
    }

    public function reasonSentence(): string
    {
        $skillName = $this->skill?->name ?? 'this skill';
        $className = $this->learningProfile?->schoolClass?->name;

        $reason = RecommendationReason::tryFrom($this->reason_code)
            ?? RecommendationReason::WeakSkillPractice;

        return $reason->toSentence($skillName, $className);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'generated_at' => 'datetime',
            'clicked_at' => 'datetime',
            'acted_at' => 'datetime',
        ];
    }
}
