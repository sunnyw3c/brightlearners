<?php

namespace App\Filament\Resources\WebhookEvents;

use App\Domains\Payments\Models\WebhookEvent;
use App\Filament\Resources\WebhookEvents\Pages\ListWebhookEvents;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class WebhookEventResource extends Resource
{
    protected static ?string $model = WebhookEvent::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInboxStack;

    protected static string|UnitEnum|null $navigationGroup = 'System';

    protected static ?string $navigationLabel = 'Webhook Events';

    protected static ?int $navigationSort = 10;

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('webhooks.view') || auth()->user()?->hasRole('super-admin') ?? false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('event_id')
                    ->label('Event ID')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('provider')
                    ->badge(),
                Tables\Columns\TextColumn::make('event_type')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('signature_valid')
                    ->boolean(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => is_object($state) && method_exists($state, 'label') ? $state->label() : (string) $state)
                    ->color(fn ($state) => match (is_object($state) ? $state->value : (string) $state) {
                        'processed' => 'success',
                        'pending' => 'warning',
                        'failed' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('attempts')
                    ->sortable(),
                Tables\Columns\TextColumn::make('error')
                    ->limit(30)
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('M d, Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWebhookEvents::route('/'),
        ];
    }
}
