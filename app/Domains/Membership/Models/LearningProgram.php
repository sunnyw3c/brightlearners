<?php

namespace App\Domains\Membership\Models;

use App\Domains\Curriculum\Models\SchoolClass;
use App\Domains\Membership\Enums\LearningProgramStatus;
use Database\Factories\LearningProgramFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'class_id', 'month', 'year', 'title', 'status', 'publish_at',
])]
#[UseFactory(LearningProgramFactory::class)]
class LearningProgram extends Model
{
    /** @use HasFactory<LearningProgramFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<SchoolClass, $this>
     */
    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    /**
     * @return HasMany<LearningWeek, $this>
     */
    public function weeks(): HasMany
    {
        return $this->hasMany(LearningWeek::class, 'program_id')->orderBy('week_number');
    }

    protected function casts(): array
    {
        return [
            'month' => 'integer',
            'year' => 'integer',
            'status' => LearningProgramStatus::class,
            'publish_at' => 'datetime',
        ];
    }
}
