<?php

namespace App\Domains\Access\Models;

use App\Domains\Accounts\Models\LearningProfile;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Content\Models\ResourceVersion;
use App\Models\User;
use Database\Factories\DownloadFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id', 'learning_profile_id', 'resource_id', 'resource_version_id',
    'entitlement_id', 'access_source', 'variant', 'ip_hash',
    'user_agent', 'downloaded_at',
])]
#[UseFactory(DownloadFactory::class)]
class Download extends Model
{
    /** @use HasFactory<DownloadFactory> */
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

    /**
     * @return BelongsTo<ResourceVersion, $this>
     */
    public function resourceVersion(): BelongsTo
    {
        return $this->belongsTo(ResourceVersion::class);
    }

    /**
     * @return BelongsTo<Entitlement, $this>
     */
    public function entitlement(): BelongsTo
    {
        return $this->belongsTo(Entitlement::class);
    }

    protected function casts(): array
    {
        return [
            'downloaded_at' => 'datetime',
        ];
    }
}
