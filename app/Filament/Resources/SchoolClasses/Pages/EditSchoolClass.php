<?php

namespace App\Filament\Resources\SchoolClasses\Pages;

use App\Filament\Actions\ArchiveAction;
use App\Filament\Resources\SchoolClasses\SchoolClassResource;
use Filament\Resources\Pages\EditRecord;

class EditSchoolClass extends EditRecord
{
    protected static string $resource = SchoolClassResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ArchiveAction::make(),
        ];
    }
}
