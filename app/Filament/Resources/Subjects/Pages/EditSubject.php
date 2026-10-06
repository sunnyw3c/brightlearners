<?php

namespace App\Filament\Resources\Subjects\Pages;

use App\Filament\Actions\ArchiveAction;
use App\Filament\Resources\Subjects\SubjectResource;
use Filament\Resources\Pages\EditRecord;

class EditSubject extends EditRecord
{
    protected static string $resource = SubjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ArchiveAction::make(),
        ];
    }
}
