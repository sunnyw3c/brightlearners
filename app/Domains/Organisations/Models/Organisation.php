<?php

namespace App\Domains\Organisations\Models;

use App\Models\User;
use Database\Factories\OrganisationFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Table('organisations')]
#[Fillable(['name', 'slug', 'type', 'owner_id'])]
#[UseFactory(OrganisationFactory::class)]
class Organisation extends Model
{
    /** @use HasFactory<OrganisationFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<User, $this>
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * @return HasMany<OrganisationMember, $this>
     */
    public function members(): HasMany
    {
        return $this->hasMany(OrganisationMember::class);
    }

    /**
     * @return HasMany<ClassroomLicence, $this>
     */
    public function licences(): HasMany
    {
        return $this->hasMany(ClassroomLicence::class);
    }
}
