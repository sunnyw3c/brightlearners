<?php

namespace App\Domains\Curriculum\Models;

use App\Domains\Curriculum\Policies\SubjectPolicy;
use App\Support\Concerns\Auditable;
use Database\Factories\SubjectFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'slug', 'sort_order', 'active'])]
#[UseFactory(SubjectFactory::class)]
#[UsePolicy(SubjectPolicy::class)]
class Subject extends Model
{
    use Auditable;

    /** @use HasFactory<SubjectFactory> */
    use HasFactory;

    /**
     * @return HasMany<Topic, $this>
     */
    public function topics(): HasMany
    {
        return $this->hasMany(Topic::class);
    }

    /**
     * @return BelongsToMany<SchoolClass, $this, ClassSubject>
     */
    public function classes(): BelongsToMany
    {
        return $this->belongsToMany(SchoolClass::class, 'class_subject', 'subject_id', 'class_id')
            ->using(ClassSubject::class)
            ->withPivot(['sort_order', 'active', 'intro'])
            ->withTimestamps();
    }

    /**
     * @param  Builder<Subject>  $query
     * @return Builder<Subject>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('active'), true);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }
}
