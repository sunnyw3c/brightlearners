<?php

namespace App\Domains\Content\Models;

use App\Domains\Curriculum\Models\SchoolClass;
use App\Domains\Curriculum\Models\Skill;
use Database\Factories\ResourceSkillFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * A resource's (class, skill) mapping. The pair must already exist and be
 * active in `class_skill` (Phase 3) — enforced by the `SkillBelongsToClass`
 * rule wherever this row is created, not by a database constraint.
 */
#[Table('resource_skill')]
#[Fillable(['resource_id', 'skill_id', 'class_id', 'is_primary'])]
#[UseFactory(ResourceSkillFactory::class)]
class ResourceSkill extends Model
{
    /** @use HasFactory<ResourceSkillFactory> */
    use HasFactory;

    /**
     * One mapping is marked primary; it decides the canonical URL
     * (Phase 5). Saving a mapping as primary demotes any other primary
     * mapping on the same resource.
     */
    protected static function booted(): void
    {
        static::saved(function (ResourceSkill $mapping): void {
            if (! $mapping->is_primary) {
                return;
            }

            static::query()
                ->where('resource_id', $mapping->resource_id)
                ->whereKeyNot($mapping->id)
                ->update(['is_primary' => false]);
        });
    }

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
     * @return BelongsTo<SchoolClass, $this>
     */
    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
        ];
    }
}
