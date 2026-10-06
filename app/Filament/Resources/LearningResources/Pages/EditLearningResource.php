<?php

namespace App\Filament\Resources\LearningResources\Pages;

use App\Filament\Resources\LearningResources\LearningResourceResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditLearningResource extends EditRecord
{
    protected static string $resource = LearningResourceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
