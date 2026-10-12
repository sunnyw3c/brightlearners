<?php

namespace App\Filament\Resources\Products\Pages;

use App\Domains\Catalog\Enums\ProductStatus;
use App\Domains\Catalog\Models\Product;
use App\Filament\Resources\Products\ProductResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewProduct extends ViewRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            Action::make('archive')
                ->label('Archive')
                ->color('danger')
                ->requiresConfirmation()
                ->visible(fn (Product $record): bool => $record->status === ProductStatus::Active
                    && auth()->user()?->can('update', $record))
                ->action(function (Product $record): void {
                    $record->update(['status' => ProductStatus::Archived]);
                    Notification::make()->title('Product archived')->success()->send();
                }),
        ];
    }
}
