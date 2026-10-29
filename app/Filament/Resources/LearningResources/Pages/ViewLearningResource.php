<?php

namespace App\Filament\Resources\LearningResources\Pages;

use App\Domains\Content\Actions\UnarchiveResource;
use App\Domains\Content\Enums\ResourceStatus;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Content\Services\SamplePdfGenerator;
use App\Filament\Resources\LearningResources\LearningResourceResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Storage;

class ViewLearningResource extends ViewRecord
{
    protected static string $resource = LearningResourceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('openPdf')
                ->label('Open PDF')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->color('primary')
                ->visible(fn (): bool => $this->record->currentVersion !== null)
                ->url(function (): ?string {
                    $version = $this->record->currentVersion;
                    if ($version === null) {
                        return null;
                    }
                    app(SamplePdfGenerator::class)->ensureVersionFiles($version);

                    return Storage::disk('resources')->temporaryUrl($version->file_path, now()->addMinutes(15));
                })
                ->openUrlInNewTab(),
            Action::make('viewLive')
                ->label('View on website')
                ->icon('heroicon-o-globe-alt')
                ->color('gray')
                ->visible(fn (): bool => $this->record->canonicalUrl() !== null)
                ->url(fn (): ?string => $this->record->canonicalUrl())
                ->openUrlInNewTab(),
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
            Action::make('unarchive')
                ->label('Unarchive')
                ->icon('heroicon-o-arrow-uturn-left')
                ->requiresConfirmation()
                ->modalDescription('A resource with a published version goes live again straight away. One without returns to Draft.')
                ->visible(fn (LearningResource $record): bool => $record->status === ResourceStatus::Archived
                    && auth()->user()?->can('publish', $record))
                ->action(function (LearningResource $record): void {
                    app(UnarchiveResource::class)->handle($record);
                    Notification::make()->title("Resource restored as {$record->status->value}")->success()->send();
                }),
        ];
    }
}
