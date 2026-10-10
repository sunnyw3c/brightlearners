<?php

namespace App\Filament\Resources\Subscriptions;

use App\Domains\Membership\Enums\SubscriptionStatus;
use App\Domains\Membership\Models\Subscription;
use App\Filament\Resources\Subscriptions\Pages\ListSubscriptions;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class SubscriptionResource extends Resource
{
    protected static ?string $model = Subscription::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;

    protected static string|UnitEnum|null $navigationGroup = 'Membership';

    protected static ?string $navigationLabel = 'Subscriptions';

    protected static ?int $navigationSort = 2;

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('customers.view') || auth()->user()?->hasRole('super-admin') ?? false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.email')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('plan.name')
                    ->label('Plan')
                    ->sortable(),
                Tables\Columns\TextColumn::make('sourceOrder.order_number')
                    ->label('Order #')
                    ->searchable()
                    ->placeholder('Manual/None'),
                Tables\Columns\TextColumn::make('starts_at')
                    ->dateTime('M d, Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('ends_at')
                    ->dateTime('M d, Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (Subscription $record) => match ($record->status) {
                        SubscriptionStatus::Active => 'success',
                        SubscriptionStatus::Expired => 'warning',
                        SubscriptionStatus::Cancelled => 'danger',
                        default => 'gray',
                    }),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSubscriptions::route('/'),
        ];
    }
}
