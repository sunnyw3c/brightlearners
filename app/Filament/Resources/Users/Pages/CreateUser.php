<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Carbon;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    /**
     * `status` is deliberately outside User's mass-assignable attributes
     * (public forms must never set it), so this trusted staff screen
     * force-fills it instead of relying on fillable.
     */
    protected function handleRecordCreation(array $data): User
    {
        $status = $data['status'] ?? null;
        unset($data['status']);

        $user = new User;
        $user->fill($data);
        $user->email_verified_at ??= Carbon::now();

        if ($status !== null) {
            $user->forceFill(['status' => $status]);
        }

        $user->save();

        return $user;
    }
}
