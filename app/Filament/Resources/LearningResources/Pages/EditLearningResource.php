<?php

namespace App\Filament\Resources\LearningResources\Pages;

use App\Domains\Content\Services\SamplePdfGenerator;
use App\Filament\Resources\LearningResources\LearningResourceResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;

class EditLearningResource extends EditRecord
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
            ViewAction::make(),
            DeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
