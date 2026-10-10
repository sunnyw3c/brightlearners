<?php

namespace App\Domains\Membership\Models;

use Database\Factories\LearningWeekFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'program_id', 'week_number', 'title', 'emphasis', 'release_at', 'released_at',
])]
#[UseFactory(LearningWeekFactory::class)]
class LearningWeek extends Model
{
    /** @use HasFactory<LearningWeekFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<LearningProgram, $this>
     */
    public function program(): BelongsTo
    {
        return $this->belongsTo(LearningProgram::class, 'program_id');
    }

    /**
     * @return HasMany<LearningItem, $this>
     */
    public function items(): HasMany
    {
        return $this->hasMany(LearningItem::class, 'week_id')->orderBy('sort_order');
    }

    public function isReleased(): bool
    {
        return $this->released_at !== null && ! $this->released_at->isFuture();
    }

    protected function casts(): array
    {
        return [
            'week_number' => 'integer',
            'release_at' => 'datetime',
            'released_at' => 'datetime',
        ];
    }
}
