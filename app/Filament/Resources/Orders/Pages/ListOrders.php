<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Domains\Commerce\Models\Order;
use App\Filament\Resources\Orders\OrderResource;
use App\Support\Audit;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('exportCsv')
                ->label('Export CSV')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->visible(fn (): bool => auth()->user()?->can('orders.view') || auth()->user()?->hasRole('finance'))
                ->action(function (): StreamedResponse {
                    $orders = Order::query()->with('user')->latest()->get();

                    Audit::record(
                        'orders.exported',
                        new Order,
                        null,
                        ['count' => $orders->count()],
                        ['actor_id' => auth()->id()]
                    );

                    return response()->streamDownload(function () use ($orders) {
                        $handle = fopen('php://output', 'w');
                        fputcsv($handle, ['Order Number', 'Customer Email', 'Status', 'Total (Paise)', 'Total (INR)', 'Coupon', 'Placed At']);

                        foreach ($orders as $order) {
                            fputcsv($handle, [
                                $order->order_number,
                                $order->user?->email ?? $order->billing_email,
                                is_object($order->status) ? $order->status->value : $order->status,
                                $order->total,
                                number_format($order->total / 100, 2),
                                $order->coupon_code ?? 'None',
                                $order->created_at->toIso8601String(),
                            ]);
                        }

                        fclose($handle);
                    }, 'orders-export-'.now()->format('Y-m-d').'.csv', [
                        'Content-Type' => 'text/csv',
                    ]);
                }),
        ];
    }
}
