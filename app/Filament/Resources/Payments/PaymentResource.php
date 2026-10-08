<?php

namespace App\Filament\Resources\Payments;

use App\Domains\Payments\Actions\IssueRefund;
use App\Domains\Payments\Models\Payment;
use App\Filament\Resources\Payments\Pages\ListPayments;
use BackedEnum;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Throwable;
use UnitEnum;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCreditCard;

    protected static string|UnitEnum|null $navigationGroup = 'Commerce';

    protected static ?string $navigationLabel = 'Payments';

    protected static ?int $navigationSort = 3;

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('payments.view') || auth()->user()?->can('orders.view') ?? false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order.order_number')
                    ->label('Order Number')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('provider')
                    ->badge(),
                Tables\Columns\TextColumn::make('provider_payment_id')
                    ->label('Gateway Payment ID')
                    ->searchable()
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('amount')
                    ->formatStateUsing(fn ($state) => '₹'.number_format($state / 100, 2))
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => is_object($state) && method_exists($state, 'label') ? $state->label() : (string) $state)
                    ->color(fn ($state) => match (is_object($state) ? $state->value : (string) $state) {
                        'captured' => 'success',
                        'created', 'authorized' => 'warning',
                        'failed' => 'danger',
                        'refunded', 'partially_refunded' => 'info',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('method')
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('M d, Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\Action::make('refund')
                    ->label('Issue Refund')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->visible(fn (Payment $record) => (auth()->user()?->can('refunds.issue') ?? false) && $record->status->value === 'captured' && ($record->amount - $record->refundedAmount()) > 0)
                    ->form([
                        Forms\Components\TextInput::make('amount')
                            ->label('Refund Amount (in Paise)')
                            ->numeric()
                            ->required()
                            ->helperText(fn (Payment $record) => 'Max refundable: ₹'.number_format(($record->amount - $record->refundedAmount()) / 100, 2))
                            ->default(fn (Payment $record) => $record->amount - $record->refundedAmount()),
                        Forms\Components\Textarea::make('reason')
                            ->label('Refund Reason')
                            ->nullable(),
                    ])
                    ->action(function (Payment $record, array $data, IssueRefund $issueRefund): void {
                        try {
                            $issueRefund->handle($record, (int) $data['amount'], $data['reason'] ?? null);
                            Notification::make()
                                ->title('Refund Issued Successfully')
                                ->success()
                                ->send();
                        } catch (Throwable $e) {
                            Notification::make()
                                ->title('Refund Failed')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPayments::route('/'),
        ];
    }
}
