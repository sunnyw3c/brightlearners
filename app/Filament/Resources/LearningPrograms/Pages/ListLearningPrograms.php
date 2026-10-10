<?php

namespace App\Filament\Resources\LearningPrograms\Pages;

use App\Filament\Resources\LearningPrograms\LearningProgramResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLearningPrograms extends ListRecords
{
    protected static string $resource = LearningProgramResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
