<?php

namespace App\Domains\Learning\Models;

use App\Domains\Content\Models\LearningResource;
use App\Domains\Curriculum\Models\Skill;
use Database\Factories\InteractiveExerciseFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Table('interactive_exercises')]
#[Fillable(['resource_id', 'skill_id', 'title', 'slug', 'question_config', 'active'])]
#[UseFactory(InteractiveExerciseFactory::class)]
class InteractiveExercise extends Model
{
    /** @use HasFactory<InteractiveExerciseFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<LearningResource, $this>
     */
    public function resource(): BelongsTo
    {
        return $this->belongsTo(LearningResource::class, 'resource_id');
    }

    /**
     * @return BelongsTo<Skill, $this>
     */
    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skill::class);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'question_config' => 'array',
            'active' => 'boolean',
        ];
    }
}
