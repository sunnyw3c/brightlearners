<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    /**
     * `status` is deliberately outside User's mass-assignable attributes
     * (public forms must never set it), so this trusted staff screen
     * force-fills it instead of relying on fillable.
     */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        /** @var User $record */
        $status = $data['status'] ?? null;
        unset($data['status']);

        $record->fill($data);

        if ($status !== null) {
            $record->forceFill(['status' => $status]);
        }

        $record->save();

        return $record;
    }
}
