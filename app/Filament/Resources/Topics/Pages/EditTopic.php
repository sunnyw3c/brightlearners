<?php

namespace App\Filament\Resources\Topics\Pages;

use App\Filament\Actions\ArchiveAction;
use App\Filament\Resources\Topics\TopicResource;
use Filament\Resources\Pages\EditRecord;

class EditTopic extends EditRecord
{
    protected static string $resource = TopicResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ArchiveAction::make(),
        ];
    }
}
