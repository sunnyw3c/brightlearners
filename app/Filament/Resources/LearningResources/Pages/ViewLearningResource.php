<?php

namespace App\Filament\Resources\LearningResources\Pages;

use App\Domains\Content\Enums\ResourceStatus;
use App\Domains\Content\Models\LearningResource;
use App\Filament\Resources\LearningResources\LearningResourceResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewLearningResource extends ViewRecord
{
    protected static string $resource = LearningResourceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            Action::make('archive')
                ->label('Archive')
                ->color('danger')
                ->requiresConfirmation()
                ->visible(fn (LearningResource $record): bool => $record->status === ResourceStatus::Published
                    && auth()->user()?->can('publish', $record))
                ->action(function (LearningResource $record): void {
                    $record->update(['status' => ResourceStatus::Archived]);
                    Notification::make()->title('Resource archived')->success()->send();
                }),
        ];
    }
}
