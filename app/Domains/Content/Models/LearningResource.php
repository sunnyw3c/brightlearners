<?php

namespace App\Domains\Content\Models;

use App\Domains\Content\Enums\ResourceStatus;
use App\Domains\Content\Enums\ResourceType;
use App\Domains\Content\Policies\LearningResourcePolicy;
use App\Models\User;
use App\Support\Concerns\Auditable;
use Database\Factories\LearningResourceFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Builder as ScoutBuilder;
use Laravel\Scout\Searchable;

/**
 * The table is `resources`; the model is `LearningResource` because
 * `resource` is a soft-reserved word in PHP and clashes with Filament's
 * and Laravel's own `Resource` classes (docs/reference/architecture.md).
 */
#[Table('resources')]
#[Fillable([
    'title', 'slug', 'type', 'summary', 'description', 'learning_objective',
    'difficulty', 'estimated_minutes', 'page_count', 'supplies', 'language',
    'has_answer_key', 'low_ink_available', 'licence_type', 'is_free',
    'ai_assisted', 'featured', 'status', 'scheduled_for', 'published_at',
    'created_by',
])]
#[UseFactory(LearningResourceFactory::class)]
#[UsePolicy(LearningResourcePolicy::class)]
class LearningResource extends Model
{
    use Auditable;

    /** @use HasFactory<LearningResourceFactory> */
    use HasFactory;

    use Searchable;
    use SoftDeletes;

    /**
     * Only a published, free resource has a public page to link to
     * (docs/plan/phase-05-public-library-ssr-search.md, step 5.8). A
     * published paid resource becomes searchable once Phase 6 gives it a
     * product page.
     */
    public function shouldBeSearchable(): bool
    {
        return $this->status === ResourceStatus::Published && $this->is_free;
    }

    /**
     * @return array<string, mixed>
     */
    public function toSearchableArray(): array
    {
        return [
            'title' => $this->title,
            'summary' => (string) $this->summary,
            'description' => (string) $this->description,
            'learning_objective' => (string) $this->learning_objective,
        ];
    }

    /**
     * The `database` Scout driver queries this table directly on every
     * search instead of reading a separate index, so `shouldBeSearchable()`
     * alone cannot keep a draft or paid resource out of results — this is
     * the constraint that actually does.
     *
     * @param  Builder<LearningResource>  $builder
     * @return Builder<LearningResource>
     */
    public function newScoutQuery(ScoutBuilder $builder): Builder
    {
        return static::query()->published()->free();
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * @return HasMany<ResourceSkill, $this>
     */
    public function skillMappings(): HasMany
    {
        return $this->hasMany(ResourceSkill::class, 'resource_id');
    }

    /**
     * @return HasMany<ResourceVersion, $this>
     */
    public function versions(): HasMany
    {
        return $this->hasMany(ResourceVersion::class, 'resource_id');
    }

    /**
     * @return HasOne<ResourceVersion, $this>
     */
    public function currentVersion(): HasOne
    {
        return $this->hasOne(ResourceVersion::class, 'resource_id')->where('is_current', true);
    }

    /**
     * The most recently created version, published or not — what the
     * staff preview screen shows (step 4.11).
     *
     * @return HasOne<ResourceVersion, $this>
     */
    public function latestVersion(): HasOne
    {
        return $this->hasOne(ResourceVersion::class, 'resource_id')->latestOfMany('id');
    }

    /**
     * @return HasMany<ResourceCorrection, $this>
     */
    public function corrections(): HasMany
    {
        return $this->hasMany(ResourceCorrection::class, 'resource_id');
    }

    /**
     * The mapping that decides the canonical URL (Phase 5).
     */
    public function primaryMapping(): ?ResourceSkill
    {
        return $this->skillMappings->firstWhere('is_primary', true)
            ?? $this->skillMappings->first();
    }

    /**
     * The `{class}` segment of the canonical `/free/{class}/{subject}/{slug}`
     * URL (docs/plan/phase-05-public-library-ssr-search.md, step 5.4).
     */
    public function primaryClassSlug(): ?string
    {
        return $this->primaryMapping()?->schoolClass?->slug;
    }

    /**
     * The `{subject}` segment of the canonical URL, read from the primary
     * mapping's skill -> topic -> subject chain.
     */
    public function primarySubjectSlug(): ?string
    {
        return $this->primaryMapping()?->skill?->topic?->subject?->slug;
    }

    /**
     * The topic slug for breadcrumb and related-resource purposes. Not
     * part of the canonical URL.
     */
    public function primaryTopicSlug(): ?string
    {
        return $this->primaryMapping()?->skill?->topic?->slug;
    }

    /**
     * The version currently moving through the review/publish pipeline
     * (not yet published) — the one `resources:publish-scheduled`
     * publishes once `scheduled_for` arrives.
     */
    public function pendingVersion(): ?ResourceVersion
    {
        return $this->versions()->whereNull('published_at')->latest('id')->first();
    }

    /**
     * @param  Builder<LearningResource>  $query
     * @return Builder<LearningResource>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('status'), ResourceStatus::Published->value);
    }

    /**
     * @param  Builder<LearningResource>  $query
     * @return Builder<LearningResource>
     */
    public function scopeFree(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('is_free'), true);
    }

    /**
     * The required metadata for publishing: class, skill, learning
     * objective, type and page count (docs/plan/phase-04-resource-engine.md,
     * step 4.6).
     */
    public function hasRequiredMetadata(): bool
    {
        return filled($this->learning_objective)
            && $this->page_count !== null
            && $this->skillMappings->isNotEmpty();
    }

    protected function casts(): array
    {
        return [
            'type' => ResourceType::class,
            'status' => ResourceStatus::class,
            'has_answer_key' => 'boolean',
            'low_ink_available' => 'boolean',
            'is_free' => 'boolean',
            'ai_assisted' => 'boolean',
            'featured' => 'boolean',
            'scheduled_for' => 'datetime',
            'published_at' => 'datetime',
        ];
    }
}
