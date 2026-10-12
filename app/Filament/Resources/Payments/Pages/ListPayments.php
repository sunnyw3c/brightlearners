<?php

namespace App\Filament\Resources\Payments\Pages;

use App\Domains\Payments\Models\Payment;
use App\Filament\Resources\Payments\PaymentResource;
use App\Support\Audit;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ListPayments extends ListRecords
{
    protected static string $resource = PaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('exportCsv')
                ->label('Export CSV')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->visible(fn (): bool => auth()->user()?->can('orders.view') || auth()->user()?->hasRole('finance'))
                ->action(function (): StreamedResponse {
                    $payments = Payment::query()->with(['order', 'refunds'])->latest()->get();

                    Audit::record(
                        'payments.exported',
                        new Payment,
                        null,
                        ['count' => $payments->count()],
                        ['actor_id' => auth()->id()]
                    );

                    return response()->streamDownload(function () use ($payments) {
                        $handle = fopen('php://output', 'w');
                        fputcsv($handle, ['Payment ID', 'Order Number', 'Status', 'Amount (Paise)', 'Amount (INR)', 'Refunded (Paise)', 'Provider', 'Provider Payment ID', 'Paid At']);

                        foreach ($payments as $payment) {
                            fputcsv($handle, [
                                $payment->id,
                                $payment->order?->order_number ?? '—',
                                is_object($payment->status) ? $payment->status->value : $payment->status,
                                $payment->amount,
                                number_format($payment->amount / 100, 2),
                                $payment->refundedAmount(),
                                $payment->provider,
                                $payment->provider_payment_id ?? '—',
                                $payment->paid_at?->toIso8601String() ?? '—',
                            ]);
                        }

                        fclose($handle);
                    }, 'payments-export-'.now()->format('Y-m-d').'.csv', [
                        'Content-Type' => 'text/csv',
                    ]);
                }),
        ];
    }
}
