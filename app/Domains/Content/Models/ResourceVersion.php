<?php

namespace App\Domains\Content\Models;

use App\Domains\Content\Enums\CorrectionSeverity;
use App\Domains\Content\Enums\PreviewStatus;
use App\Models\User;
use App\Support\Concerns\Auditable;
use Database\Factories\ResourceVersionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use RuntimeException;

/**
 * A published PDF is never overwritten (docs/plan/phase-04-resource-engine.md,
 * step 4.3). Once `published_at` is set, the file columns are immutable —
 * enforced below, not just by convention.
 */
#[Fillable([
    'resource_id', 'version', 'file_path', 'low_ink_path', 'answer_file_path',
    'checksum', 'change_notes', 'preview_status', 'correction_severity',
    'customer_notice_required', 'reviewed_by', 'reviewed_at', 'published_at',
    'is_current', 'created_by',
])]
#[UseFactory(ResourceVersionFactory::class)]
class ResourceVersion extends Model
{
    use Auditable;

    /** @use HasFactory<ResourceVersionFactory> */
    use HasFactory;

    /**
     * File columns a published version refuses to change.
     *
     * @var list<string>
     */
    private const IMMUTABLE_ONCE_PUBLISHED = [
        'resource_id', 'version', 'file_path', 'low_ink_path',
        'answer_file_path', 'checksum', 'published_at',
    ];

    protected static function booted(): void
    {
        static::updating(function (ResourceVersion $version): void {
            if ($version->getOriginal('published_at') === null) {
                return;
            }

            $changedProtectedColumns = array_intersect(
                self::IMMUTABLE_ONCE_PUBLISHED,
                array_keys($version->getDirty()),
            );

            if ($changedProtectedColumns !== []) {
                throw new RuntimeException('A published resource version cannot be changed.');
            }
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
     * @return BelongsTo<User, $this>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * @return HasMany<ResourceReview, $this>
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(ResourceReview::class);
    }

    /**
     * @return HasMany<ResourcePreview, $this>
     */
    public function previews(): HasMany
    {
        return $this->hasMany(ResourcePreview::class);
    }

    public function isPublished(): bool
    {
        return $this->published_at !== null;
    }

    protected function casts(): array
    {
        return [
            'preview_status' => PreviewStatus::class,
            'correction_severity' => CorrectionSeverity::class,
            'customer_notice_required' => 'boolean',
            'reviewed_at' => 'datetime',
            'published_at' => 'datetime',
            'is_current' => 'boolean',
        ];
    }
}
