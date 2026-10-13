<?php

namespace App\Domains\Organisations\Actions;

use App\Domains\Organisations\Models\Organisation;
use App\Domains\Organisations\Models\OrganisationMember;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateOrganisation
{
    public function handle(User $owner, string $name, string $type = 'school'): Organisation
    {
        return DB::transaction(function () use ($owner, $name, $type) {
            $slug = Str::slug($name).'-'.Str::random(5);

            $organisation = Organisation::query()->create([
                'name' => $name,
                'slug' => $slug,
                'type' => $type,
                'owner_id' => $owner->id,
            ]);

            OrganisationMember::query()->create([
                'organisation_id' => $organisation->id,
                'user_id' => $owner->id,
                'role' => 'admin',
                'joined_at' => now(),
            ]);

            return $organisation;
        });
    }
}
