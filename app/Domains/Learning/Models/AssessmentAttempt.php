<?php

namespace App\Domains\Learning\Models;

use App\Domains\Accounts\Models\LearningProfile;
use App\Domains\Learning\Enums\AttemptStatus;
use App\Models\User;
use Database\Factories\AssessmentAttemptFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Table('assessment_attempts')]
#[Fillable([
    'user_id',
    'learning_profile_id',
    'assessment_id',
    'assessment_version',
    'status',
    'parent_feedback',
    'started_at',
    'completed_at',
])]
#[UseFactory(AssessmentAttemptFactory::class)]
class AssessmentAttempt extends Model
{
    /** @use HasFactory<AssessmentAttemptFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<LearningProfile, $this>
     */
    public function learningProfile(): BelongsTo
    {
        return $this->belongsTo(LearningProfile::class, 'learning_profile_id');
    }

    /**
     * @return BelongsTo<Assessment, $this>
     */
    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }

    /**
     * @return HasMany<AssessmentAnswer, $this>
     */
    public function answers(): HasMany
    {
        return $this->hasMany(AssessmentAnswer::class, 'attempt_id');
    }

    /**
     * @return HasMany<SkillScore, $this>
     */
    public function skillScores(): HasMany
    {
        return $this->hasMany(SkillScore::class, 'source_attempt_id');
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'assessment_version' => 'integer',
            'status' => AttemptStatus::class,
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }
}
