<?php

namespace App\Domains\Organisations\Models;

use App\Models\User;
use Database\Factories\OrganisationMemberFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Table('organisation_members')]
#[Fillable(['organisation_id', 'user_id', 'role', 'joined_at'])]
#[UseFactory(OrganisationMemberFactory::class)]
class OrganisationMember extends Model
{
    /** @use HasFactory<OrganisationMemberFactory> */
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
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'joined_at' => 'datetime',
        ];
    }
}
