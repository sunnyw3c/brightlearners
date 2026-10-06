<?php

namespace App\Domains\Curriculum\Models;

use App\Domains\Curriculum\Policies\TopicPolicy;
use App\Support\Concerns\Auditable;
use Database\Factories\TopicFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['subject_id', 'name', 'slug', 'description', 'sort_order', 'active'])]
#[UseFactory(TopicFactory::class)]
#[UsePolicy(TopicPolicy::class)]
class Topic extends Model
{
    use Auditable;

    /** @use HasFactory<TopicFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<Subject, $this>
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * @return HasMany<Skill, $this>
     */
    public function skills(): HasMany
    {
        return $this->hasMany(Skill::class);
    }

    /**
     * The classes that have per-class landing copy for this topic
     * (`class_topic`, step 5.3).
     *
     * @return BelongsToMany<SchoolClass, $this, ClassTopic>
     */
    public function classes(): BelongsToMany
    {
        return $this->belongsToMany(SchoolClass::class, 'class_topic', 'topic_id', 'class_id')
            ->using(ClassTopic::class)
            ->withPivot(['intro', 'seo_title', 'seo_description', 'sort_order', 'active'])
            ->withTimestamps();
    }

    /**
     * @param  Builder<Topic>  $query
     * @return Builder<Topic>
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
