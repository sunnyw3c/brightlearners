<?php

namespace App\Domains\Curriculum\Models;

use App\Domains\Curriculum\Policies\SkillPolicy;
use App\Support\Concerns\Auditable;
use Database\Factories\SkillFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['topic_id', 'name', 'slug', 'learning_objective', 'difficulty_band', 'sort_order', 'active'])]
#[UseFactory(SkillFactory::class)]
#[UsePolicy(SkillPolicy::class)]
class Skill extends Model
{
    use Auditable;

    /** @use HasFactory<SkillFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<Topic, $this>
     */
    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    /**
     * @return BelongsToMany<SchoolClass, $this, ClassSkill>
     */
    public function classes(): BelongsToMany
    {
        return $this->belongsToMany(SchoolClass::class, 'class_skill', 'skill_id', 'class_id')
            ->using(ClassSkill::class)
            ->withPivot(['learning_objective', 'difficulty_band', 'sort_order', 'active'])
            ->withTimestamps();
    }

    /**
     * @param  Builder<Skill>  $query
     * @return Builder<Skill>
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
