<?php

namespace App\Domains\Learning\Models;

use App\Domains\Curriculum\Models\SchoolClass;
use App\Domains\Curriculum\Models\Subject;
use App\Domains\Learning\Enums\AssessmentStatus;
use Database\Factories\AssessmentFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

#[Table('assessments')]
#[Fillable(['class_id', 'subject_id', 'title', 'slug', 'description', 'status', 'version'])]
#[UseFactory(AssessmentFactory::class)]
class Assessment extends Model
{
    /** @use HasFactory<AssessmentFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<SchoolClass, $this>
     */
    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    /**
     * @return BelongsTo<Subject, $this>
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * @return HasMany<AssessmentSection, $this>
     */
    public function sections(): HasMany
    {
        return $this->hasMany(AssessmentSection::class)->orderBy('sort_order');
    }

    /**
     * @return HasManyThrough<AssessmentQuestion, AssessmentSection, $this>
     */
    public function questions(): HasManyThrough
    {
        return $this->hasManyThrough(
            AssessmentQuestion::class,
            AssessmentSection::class,
            'assessment_id',
            'section_id',
        );
    }

    /**
     * @return HasMany<AssessmentAttempt, $this>
     */
    public function attempts(): HasMany
    {
        return $this->hasMany(AssessmentAttempt::class);
    }

    /**
     * @param  Builder<Assessment>  $query
     * @return Builder<Assessment>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', AssessmentStatus::Published);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => AssessmentStatus::class,
            'version' => 'integer',
        ];
    }
}
