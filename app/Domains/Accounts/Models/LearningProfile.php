<?php

namespace App\Domains\Accounts\Models;

use App\Domains\Accounts\Policies\LearningProfilePolicy;
use App\Domains\Curriculum\Models\SchoolClass;
use App\Domains\Learning\Models\AssessmentAttempt;
use App\Domains\Learning\Models\Recommendation;
use App\Domains\Learning\Models\ResourceProgress;
use App\Domains\Learning\Models\SkillScore;
use App\Models\User;
use Database\Factories\LearningProfileFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nickname', 'class_id', 'avatar_key', 'interests', 'active'])]
#[UseFactory(LearningProfileFactory::class)]
#[UsePolicy(LearningProfilePolicy::class)]
class LearningProfile extends Model
{
    /** @use HasFactory<LearningProfileFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<SchoolClass, $this>
     */
    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    /**
     * @return HasMany<AssessmentAttempt, $this>
     */
    public function assessmentAttempts(): HasMany
    {
        return $this->hasMany(AssessmentAttempt::class, 'learning_profile_id');
    }

    /**
     * @return HasMany<SkillScore, $this>
     */
    public function skillScores(): HasMany
    {
        return $this->hasMany(SkillScore::class, 'learning_profile_id');
    }

    /**
     * @return HasMany<Recommendation, $this>
     */
    public function recommendations(): HasMany
    {
        return $this->hasMany(Recommendation::class, 'learning_profile_id');
    }

    /**
     * @return HasMany<ResourceProgress, $this>
     */
    public function resourceProgress(): HasMany
    {
        return $this->hasMany(ResourceProgress::class, 'learning_profile_id');
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'interests' => 'array',
            'active' => 'boolean',
        ];
    }
}
