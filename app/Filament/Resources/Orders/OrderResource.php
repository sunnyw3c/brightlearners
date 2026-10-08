<?php

namespace App\Filament\Resources\Orders;

use App\Domains\Commerce\Models\Order;
use App\Filament\Resources\Orders\Pages\ListOrders;
use App\Filament\Resources\Orders\Pages\ViewOrder;
use BackedEnum;
use Filament\Infolists;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingBag;

    protected static string|UnitEnum|null $navigationGroup = 'Commerce';

    protected static ?string $navigationLabel = 'Orders';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'order_number';

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('orders.view') ?? false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.email')
                    ->label('Customer')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => is_object($state) && method_exists($state, 'label') ? $state->label() : (string) $state)
                    ->color(fn ($state) => match (is_object($state) ? $state->value : (string) $state) {
                        'paid' => 'success',
                        'pending_payment' => 'warning',
                        'failed', 'cancelled' => 'danger',
                        'refunded', 'partially_refunded' => 'info',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('total')
                    ->formatStateUsing(fn ($state) => '₹'.number_format($state / 100, 2))
                    ->sortable(),
                Tables\Columns\TextColumn::make('coupon_code')
                    ->label('Coupon')
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('M d, Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Infolists\Components\TextEntry::make('order_number')->label('Order Number'),
                Infolists\Components\TextEntry::make('user.email')->label('Customer Email'),
                Infolists\Components\TextEntry::make('status')->label('Status')->formatStateUsing(fn ($state) => is_object($state) && method_exists($state, 'label') ? $state->label() : (string) $state),
                Infolists\Components\TextEntry::make('total')->label('Total Payable')->formatStateUsing(fn ($state) => '₹'.number_format($state / 100, 2)),
                Infolists\Components\TextEntry::make('coupon_code')->label('Coupon Code')->placeholder('None'),
                Infolists\Components\TextEntry::make('billing_name')->label('Billing Name'),
                Infolists\Components\TextEntry::make('billing_email')->label('Billing Email'),
                Infolists\Components\TextEntry::make('billing_phone')->label('Billing Phone')->placeholder('N/A'),
                Infolists\Components\TextEntry::make('created_at')->label('Placed At')->dateTime('M d, Y H:i'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOrders::route('/'),
            'view' => ViewOrder::route('/{record}'),
        ];
    }
}
