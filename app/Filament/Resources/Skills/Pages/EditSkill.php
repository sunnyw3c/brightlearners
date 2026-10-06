<?php

namespace App\Filament\Resources\Skills\Pages;

use App\Filament\Actions\ArchiveAction;
use App\Filament\Resources\Skills\SkillResource;
use Filament\Resources\Pages\EditRecord;

class EditSkill extends EditRecord
{
    protected static string $resource = SkillResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ArchiveAction::make(),
        ];
    }
}
