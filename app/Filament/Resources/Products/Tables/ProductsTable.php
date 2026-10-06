<?php

namespace App\Filament\Resources\Products\Tables;

use App\Domains\Catalog\Actions\ActivateProduct;
use App\Domains\Catalog\Enums\ProductStatus;
use App\Domains\Catalog\Enums\ProductType;
use App\Domains\Catalog\Models\Product;
use App\Domains\Curriculum\Models\SchoolClass;
use App\Support\Money;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\ValidationException;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('type')
                    ->badge(),
                TextColumn::make('primaryClass.name')
                    ->label('Class')
                    ->placeholder('All classes'),
                TextColumn::make('regular_price')
                    ->label('Price')
                    ->formatStateUsing(fn (Product $record): string => Money::format($record->regular_price, $record->currency)),
                TextColumn::make('status')
                    ->badge(),
                IconColumn::make('featured')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(ProductStatus::class),
                SelectFilter::make('type')
                    ->options(ProductType::class),
                SelectFilter::make('class')
                    ->options(fn (): array => SchoolClass::query()->active()->orderBy('sort_order')->pluck('name', 'id')->all())
                    ->query(fn (Builder $query, array $data): Builder => $query->when(
                        $data['value'],
                        fn (Builder $query, $classId): Builder => $query->where('primary_class_id', $classId),
                    )),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('activate')
                    ->label('Activate')
                    ->icon('heroicon-o-check-circle')
                    ->requiresConfirmation()
                    ->visible(fn (Product $record): bool => $record->status === ProductStatus::Draft
                        && auth()->user()->can('update', $record))
                    ->action(function (Product $record): void {
                        try {
                            app(ActivateProduct::class)->handle($record);
                        } catch (ValidationException $exception) {
                            Notification::make()
                                ->title('Cannot activate yet')
                                ->body($exception->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
                Action::make('archive')
                    ->label('Archive')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (Product $record): bool => $record->status === ProductStatus::Active
                        && auth()->user()->can('update', $record))
                    ->action(fn (Product $record) => $record->update(['status' => ProductStatus::Archived])),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
