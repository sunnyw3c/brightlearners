<?php

namespace App\Domains\Learning\Models;

use App\Domains\Curriculum\Models\Skill;
use App\Domains\Learning\Enums\QuestionDifficulty;
use App\Domains\Learning\Enums\QuestionType;
use Database\Factories\AssessmentQuestionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Table('assessment_questions')]
#[Fillable(['section_id', 'skill_id', 'type', 'prompt', 'difficulty', 'answer_config', 'points', 'sort_order'])]
#[UseFactory(AssessmentQuestionFactory::class)]
class AssessmentQuestion extends Model
{
    /** @use HasFactory<AssessmentQuestionFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<AssessmentSection, $this>
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(AssessmentSection::class, 'section_id');
    }

    /**
     * @return BelongsTo<Skill, $this>
     */
    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skill::class);
    }

    /**
     * @return HasMany<AssessmentAnswer, $this>
     */
    public function answers(): HasMany
    {
        return $this->hasMany(AssessmentAnswer::class, 'question_id');
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => QuestionType::class,
            'difficulty' => QuestionDifficulty::class,
            'answer_config' => 'array',
            'points' => 'integer',
            'sort_order' => 'integer',
        ];
    }
}
