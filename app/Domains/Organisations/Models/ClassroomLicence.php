<?php

namespace App\Domains\Organisations\Models;

use App\Models\User;
use Database\Factories\ClassroomLicenceFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Table('classroom_licences')]
#[Fillable([
    'organisation_id',
    'teacher_id',
    'name',
    'seat_limit',
    'printable_rights',
    'redistribution_terms_accepted',
    'starts_at',
    'ends_at',
    'status',
])]
#[UseFactory(ClassroomLicenceFactory::class)]
class ClassroomLicence extends Model
{
    /** @use HasFactory<ClassroomLicenceFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<Organisation, $this>
     */
    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function isActive(): bool
    {
        return $this->status === 'active'
            && $this->starts_at->isPast()
            && $this->ends_at->isFuture();
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'seat_limit' => 'integer',
            'printable_rights' => 'boolean',
            'redistribution_terms_accepted' => 'boolean',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }
}
