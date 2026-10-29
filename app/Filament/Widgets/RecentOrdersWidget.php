<?php

namespace App\Filament\Widgets;

use App\Domains\Commerce\Models\Order;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentOrdersWidget extends BaseWidget
{
    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(Order::query()->latest()->limit(6))
            ->heading('Recent Customer Orders')
            ->description('Latest commercial purchases and checkout activity.')
            ->columns([
                TextColumn::make('order_number')
                    ->label('Order #')
                    ->searchable()
                    ->weight('bold'),
                TextColumn::make('user.email')
                    ->label('Customer Email')
                    ->searchable(),
                TextColumn::make('total')
                    ->label('Total Paid')
                    ->formatStateUsing(fn ($state) => '₹'.number_format($state / 100, 2))
                    ->weight('semibold')
                    ->color('success'),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => is_object($state) && method_exists($state, 'label') ? $state->label() : ucfirst((string) $state))
                    ->color(fn ($state) => match (is_object($state) ? $state->value : (string) $state) {
                        'paid' => 'success',
                        'pending_payment' => 'warning',
                        'failed', 'cancelled' => 'danger',
                        'refunded', 'partially_refunded' => 'info',
                        default => 'gray',
                    }),
                TextColumn::make('items_count')
                    ->counts('items')
                    ->label('Items'),
                TextColumn::make('created_at')
                    ->label('Placed')
                    ->since(),
            ])
            ->recordActions([
                Action::make('view')
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Order $record): string => "/admin/orders/{$record->id}"),
            ])
            ->paginated(false);
    }
}
