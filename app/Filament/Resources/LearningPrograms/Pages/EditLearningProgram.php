<?php

namespace App\Filament\Resources\LearningPrograms\Pages;

use App\Filament\Resources\LearningPrograms\LearningProgramResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLearningProgram extends EditRecord
{
    protected static string $resource = LearningProgramResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
