<?php

namespace App\Domains\Learning\Models;

use Database\Factories\AssessmentAnswerFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Table('assessment_answers')]
#[Fillable(['attempt_id', 'question_id', 'response', 'is_correct', 'score'])]
#[UseFactory(AssessmentAnswerFactory::class)]
class AssessmentAnswer extends Model
{
    /** @use HasFactory<AssessmentAnswerFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<AssessmentAttempt, $this>
     */
    public function attempt(): BelongsTo
    {
        return $this->belongsTo(AssessmentAttempt::class, 'attempt_id');
    }

    /**
     * @return BelongsTo<AssessmentQuestion, $this>
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(AssessmentQuestion::class, 'question_id');
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'response' => 'array',
            'is_correct' => 'boolean',
            'score' => 'integer',
        ];
    }
}
