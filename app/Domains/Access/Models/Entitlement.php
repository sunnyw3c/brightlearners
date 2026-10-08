<?php

namespace App\Domains\Access\Models;

use App\Domains\Accounts\Models\LearningProfile;
use App\Domains\Content\Models\LearningResource;
use App\Models\User;
use Database\Factories\EntitlementFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id', 'learning_profile_id', 'resource_id', 'source_type',
    'source_id', 'starts_at', 'ends_at', 'revoked_at', 'metadata',
])]
#[UseFactory(EntitlementFactory::class)]
class Entitlement extends Model
{
    /** @use HasFactory<EntitlementFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<LearningProfile, $this>
     */
    public function learningProfile(): BelongsTo
    {
        return $this->belongsTo(LearningProfile::class);
    }

    /**
     * @return BelongsTo<LearningResource, $this>
     */
    public function resource(): BelongsTo
    {
        return $this->belongsTo(LearningResource::class, 'resource_id');
    }

    public function isValid(): bool
    {
        if ($this->revoked_at !== null) {
            return false;
        }

        if ($this->starts_at && $this->starts_at->isFuture()) {
            return false;
        }

        if ($this->ends_at && $this->ends_at->isPast()) {
            return false;
        }

        return true;
    }

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'revoked_at' => 'datetime',
            'metadata' => 'array',
        ];
    }
}
