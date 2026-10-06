<?php

namespace App\Filament\Resources\LearningResources\Pages;

use App\Filament\Resources\LearningResources\LearningResourceResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewLearningResource extends ViewRecord
{
    protected static string $resource = LearningResourceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
