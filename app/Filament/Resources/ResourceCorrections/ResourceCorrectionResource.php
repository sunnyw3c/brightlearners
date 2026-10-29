<?php

namespace App\Filament\Resources\ResourceCorrections;

use App\Domains\Content\Actions\SendCorrectionNoticesAction;
use App\Domains\Content\Enums\CorrectionSeverity;
use App\Domains\Content\Models\ResourceCorrection;
use App\Domains\Content\Queries\AffectedCustomers;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class ResourceCorrectionResource extends Resource
{
    protected static ?string $model = ResourceCorrection::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedExclamationTriangle;

    protected static string|UnitEnum|null $navigationGroup = 'Content & Library';

    protected static ?string $navigationLabel = 'Published Corrections';

    protected static ?int $navigationSort = 10;

    public static function canViewAny(): bool
    {
        return (bool) auth()->user()?->can('resources.view');
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('resource.title')
                    ->label('Resource')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('version_from')
                    ->label('From')
                    ->badge(),
                TextColumn::make('version_to')
                    ->label('To')
                    ->badge(),
                TextColumn::make('severity')
                    ->badge()
                    ->color(fn (CorrectionSeverity $state): string => match ($state) {
                        CorrectionSeverity::Material => 'danger',
                        CorrectionSeverity::Minor => 'warning',
                        CorrectionSeverity::Cosmetic => 'gray',
                    }),
                IconColumn::make('customer_notice_required')
                    ->label('Notice Req.')
                    ->boolean(),
                TextColumn::make('notified_at')
                    ->label('Notified')
                    ->dateTime('M j, Y H:i')
                    ->placeholder('Pending'),
                TextColumn::make('creator.name')
                    ->label('Created By')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('M j, Y')
                    ->sortable(),
            ])
            ->filters([
                Filter::make('pending_notice')
                    ->label('Pending Notice')
                    ->query(fn (Builder $query): Builder => $query->where('customer_notice_required', true)->whereNull('notified_at')),
            ])
            ->actions([
                Action::make('sendNotice')
                    ->label('Send Notice')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('warning')
                    ->visible(fn (ResourceCorrection $record): bool => $record->customer_notice_required && $record->notified_at === null)
                    ->requiresConfirmation()
                    ->modalHeading('Send Correction Notices')
                    ->modalDescription(function (ResourceCorrection $record, AffectedCustomers $affectedCustomers): string {
                        $count = $affectedCustomers->forCorrection($record)->count();

                        return "This will send an email notification to {$count} affected customer(s) who downloaded or have access to version {$record->version_from}. Proceed?";
                    })
                    ->action(function (ResourceCorrection $record, SendCorrectionNoticesAction $action): void {
                        $recipients = $action->handle($record);

                        Notification::make()
                            ->title('Correction Notices Sent')
                            ->body("Successfully notified {$recipients->count()} customer(s).")
                            ->success()
                            ->send();
                    }),
            ]);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListResourceCorrections::route('/'),
        ];
    }
}
