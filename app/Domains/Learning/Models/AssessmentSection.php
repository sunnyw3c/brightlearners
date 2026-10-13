<?php

namespace App\Domains\Learning\Models;

use Database\Factories\AssessmentSectionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Table('assessment_sections')]
#[Fillable(['assessment_id', 'title', 'sort_order'])]
#[UseFactory(AssessmentSectionFactory::class)]
class AssessmentSection extends Model
{
    /** @use HasFactory<AssessmentSectionFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<Assessment, $this>
     */
    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }

    /**
     * @return HasMany<AssessmentQuestion, $this>
     */
    public function questions(): HasMany
    {
        return $this->hasMany(AssessmentQuestion::class, 'section_id')->orderBy('sort_order');
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }
}
