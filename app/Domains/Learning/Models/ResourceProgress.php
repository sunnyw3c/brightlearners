<?php

namespace App\Domains\Learning\Models;

use App\Domains\Accounts\Models\LearningProfile;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Learning\Enums\ProgressStatus;
use App\Models\User;
use Database\Factories\ResourceProgressFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Table('resource_progress')]
#[Fillable(['user_id', 'learning_profile_id', 'resource_id', 'status', 'opened_at', 'downloaded_at', 'completed_at', 'source_context'])]
#[UseFactory(ResourceProgressFactory::class)]
class ResourceProgress extends Model
{
    /** @use HasFactory<ResourceProgressFactory> */
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
        return $this->belongsTo(LearningProfile::class, 'learning_profile_id');
    }

    /**
     * @return BelongsTo<LearningProfile, $this>
     */
    public function profile(): BelongsTo
    {
        return $this->learningProfile();
    }

    /**
     * @return BelongsTo<LearningResource, $this>
     */
    public function resource(): BelongsTo
    {
        return $this->belongsTo(LearningResource::class, 'resource_id');
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => ProgressStatus::class,
            'opened_at' => 'datetime',
            'downloaded_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }
}
