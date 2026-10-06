<?php

namespace App\Domains\Curriculum\Models;

use App\Domains\Accounts\Models\LearningProfile;
use App\Domains\Curriculum\Policies\SchoolClassPolicy;
use App\Support\Concerns\Auditable;
use Database\Factories\SchoolClassFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Table('classes')]
#[Fillable(['name', 'slug', 'sort_order', 'active', 'intro', 'seo_title', 'seo_description'])]
#[UseFactory(SchoolClassFactory::class)]
#[UsePolicy(SchoolClassPolicy::class)]
class SchoolClass extends Model
{
    use Auditable;

    /** @use HasFactory<SchoolClassFactory> */
    use HasFactory;

    /**
     * @return BelongsToMany<Subject, $this, ClassSubject>
     */
    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'class_subject', 'class_id', 'subject_id')
            ->using(ClassSubject::class)
            ->withPivot(['sort_order', 'active', 'intro'])
            ->withTimestamps();
    }

    /**
     * @return BelongsToMany<Skill, $this, ClassSkill>
     */
    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'class_skill', 'class_id', 'skill_id')
            ->using(ClassSkill::class)
            ->withPivot(['learning_objective', 'difficulty_band', 'sort_order', 'active'])
            ->withTimestamps();
    }

    /**
     * The per-class landing copy for topic pages (`class_topic`,
     * docs/plan/phase-05-public-library-ssr-search.md, step 5.3).
     *
     * @return BelongsToMany<Topic, $this, ClassTopic>
     */
    public function topics(): BelongsToMany
    {
        return $this->belongsToMany(Topic::class, 'class_topic', 'class_id', 'topic_id')
            ->using(ClassTopic::class)
            ->withPivot(['intro', 'seo_title', 'seo_description', 'sort_order', 'active'])
            ->withTimestamps();
    }

    /**
     * @return HasMany<LearningProfile, $this>
     */
    public function learningProfiles(): HasMany
    {
        return $this->hasMany(LearningProfile::class, 'class_id');
    }

    /**
     * @param  Builder<SchoolClass>  $query
     * @return Builder<SchoolClass>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('active'), true);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }
}
