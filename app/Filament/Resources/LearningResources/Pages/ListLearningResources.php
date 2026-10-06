<?php

namespace App\Filament\Resources\LearningResources\Pages;

use App\Filament\Resources\LearningResources\LearningResourceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLearningResources extends ListRecords
{
    protected static string $resource = LearningResourceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
