<?php

namespace App\Domains\Content\Models;

use App\Domains\Content\Enums\CorrectionSeverity;
use App\Models\User;
use App\Support\Concerns\Auditable;
use Database\Factories\ResourceCorrectionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['resource_id', 'version_from', 'version_to', 'severity', 'customer_notice_required', 'notes', 'notified_at', 'created_by'])]
#[UseFactory(ResourceCorrectionFactory::class)]
class ResourceCorrection extends Model
{
    use Auditable;

    /** @use HasFactory<ResourceCorrectionFactory> */
    use HasFactory;

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

    protected function casts(): array
    {
        return [
            'severity' => CorrectionSeverity::class,
            'customer_notice_required' => 'boolean',
            'notified_at' => 'datetime',
        ];
    }
}
