<?php

namespace App\Filament\Resources\AuditLogs;

use App\Models\User;
use App\Support\Models\AuditLog;
use BackedEnum;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class AuditLogResource extends Resource
{
    protected static ?string $model = AuditLog::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static string|UnitEnum|null $navigationGroup = 'System';

    protected static ?string $navigationLabel = 'Audit Log';

    protected static ?int $navigationSort = 3;

    public static function canViewAny(): bool
    {
        $user = auth()->user();

        if ($user === null) {
            return false;
        }

        return $user->can('audit.view')
            || $user->can('audit.view-own-area')
            || $user->can('audit.view-limited')
            || $user->hasRole('super-admin');
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()->with(['actor'])->latest('occurred_at');
        $user = auth()->user();

        if ($user === null) {
            return $query->whereRaw('1 = 0');
        }

        if ($user->can('audit.view') || $user->hasRole('super-admin')) {
            return $query;
        }

        if ($user->can('audit.view-own-area')) {
            return $query->where(function (Builder $q) {
                $q->where('action', 'like', 'resource%')
                    ->orWhere('action', 'like', 'topic%')
                    ->orWhere('action', 'like', 'subject%')
                    ->orWhere('action', 'like', 'skill%')
                    ->orWhere('action', 'like', 'school_class%')
                    ->orWhere('action', 'like', 'curriculum%')
                    ->orWhere('action', 'like', 'article%');
            });
        }

        if ($user->can('audit.view-limited')) {
            return $query->where(function (Builder $q) {
                $q->where('action', 'like', 'refund%')
                    ->orWhere('action', 'like', 'entitlement%')
                    ->orWhere('action', 'like', 'order%')
                    ->orWhere('action', 'like', 'payment%');
            });
        }

        return $query->whereRaw('1 = 0');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('occurred_at')
                    ->label('Timestamp')
                    ->dateTime('M j, Y H:i:s')
                    ->sortable(),
                TextColumn::make('actor.email')
                    ->label('Actor')
                    ->placeholder('System')
                    ->searchable(),
                TextColumn::make('action')
                    ->label('Action')
                    ->badge()
                    ->searchable(),
                TextColumn::make('subject_type')
                    ->label('Subject Type')
                    ->formatStateUsing(fn (?string $state): string => $state ? class_basename($state) : '—')
                    ->searchable(),
                TextColumn::make('subject_id')
                    ->label('Subject ID')
                    ->searchable(),
                TextColumn::make('ip_address')
                    ->label('IP')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('actor_id')
                    ->label('Actor')
                    ->options(fn () => User::query()->whereHas('roles')->pluck('email', 'id')),
                SelectFilter::make('action')
                    ->label('Action')
                    ->options(fn () => AuditLog::query()->distinct()->pluck('action', 'action')),
            ])
            ->actions([
                ViewAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Placeholder::make('occurred_at')
                ->label('Timestamp')
                ->content(fn (?AuditLog $record): string => $record?->occurred_at?->format('M j, Y H:i:s') ?? '—'),
            Placeholder::make('actor')
                ->label('Actor')
                ->content(fn (?AuditLog $record): string => $record?->actor?->email ?? 'System'),
            TextInput::make('action')
                ->disabled(),
            TextInput::make('subject_type')
                ->disabled(),
            TextInput::make('subject_id')
                ->disabled(),
            KeyValue::make('before')
                ->disabled(),
            KeyValue::make('after')
                ->disabled(),
            KeyValue::make('metadata')
                ->disabled(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAuditLogs::route('/'),
            'view' => Pages\ViewAuditLog::route('/{record}'),
        ];
    }
}
