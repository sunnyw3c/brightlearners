<?php

namespace App\Domains\Membership\Models;

use App\Domains\Content\Models\LearningResource;
use Database\Factories\LearningItemFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'week_id', 'resource_id', 'sort_order', 'required', 'guidance',
])]
#[UseFactory(LearningItemFactory::class)]
class LearningItem extends Model
{
    /** @use HasFactory<LearningItemFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<LearningWeek, $this>
     */
    public function week(): BelongsTo
    {
        return $this->belongsTo(LearningWeek::class, 'week_id');
    }

    /**
     * @return BelongsTo<LearningResource, $this>
     */
    public function resource(): BelongsTo
    {
        return $this->belongsTo(LearningResource::class, 'resource_id');
    }

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'required' => 'boolean',
        ];
    }
}
