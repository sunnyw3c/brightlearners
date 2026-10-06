<?php

namespace App\Domains\Accounts\Models;

use App\Domains\Accounts\Policies\LearningProfilePolicy;
use App\Domains\Curriculum\Models\SchoolClass;
use App\Models\User;
use Database\Factories\LearningProfileFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['nickname', 'class_id', 'avatar_key', 'interests', 'active'])]
#[UseFactory(LearningProfileFactory::class)]
#[UsePolicy(LearningProfilePolicy::class)]
class LearningProfile extends Model
{
    /** @use HasFactory<LearningProfileFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
            'interests' => 'array',
            'active' => 'boolean',
        ];
    }
}
