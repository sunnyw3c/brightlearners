<?php

namespace App\Domains\Content\Models;

use App\Domains\Content\Enums\ReviewStatus;
use App\Domains\Content\Enums\ReviewType;
use App\Models\User;
use App\Support\Concerns\Auditable;
use Database\Factories\ResourceReviewFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['resource_id', 'resource_version_id', 'review_type', 'reviewer_id', 'status', 'notes', 'reviewed_at'])]
#[UseFactory(ResourceReviewFactory::class)]
class ResourceReview extends Model
{
    use Auditable;

    /** @use HasFactory<ResourceReviewFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<LearningResource, $this>
     */
    public function resource(): BelongsTo
    {
        return $this->belongsTo(LearningResource::class, 'resource_id');
    }

    /**
     * @return BelongsTo<ResourceVersion, $this>
     */
    public function version(): BelongsTo
    {
        return $this->belongsTo(ResourceVersion::class, 'resource_version_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    protected function casts(): array
    {
        return [
            'review_type' => ReviewType::class,
            'status' => ReviewStatus::class,
            'reviewed_at' => 'datetime',
        ];
    }
}
