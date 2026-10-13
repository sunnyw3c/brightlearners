<?php

namespace App\Domains\Learning\Models;

use App\Domains\Accounts\Models\LearningProfile;
use App\Models\User;
use Database\Factories\ParentGuidanceLogFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Table('parent_guidance_logs')]
#[Fillable(['user_id', 'profile_id', 'query', 'guidance', 'safety_flags', 'escalated'])]
#[UseFactory(ParentGuidanceLogFactory::class)]
class ParentGuidanceLog extends Model
{
    /** @use HasFactory<ParentGuidanceLogFactory> */
    use HasFactory;

    public const UPDATED_AT = null;

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
        return $this->belongsTo(LearningProfile::class, 'profile_id');
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'safety_flags' => 'array',
            'escalated' => 'boolean',
        ];
    }
}
