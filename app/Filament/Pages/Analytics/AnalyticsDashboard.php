<?php

namespace App\Filament\Pages\Analytics;

use App\Domains\Analytics\Models\DailyMetric;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;
use UnitEnum;

class AnalyticsDashboard extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBarSquare;

    protected static string|UnitEnum|null $navigationGroup = 'Analytics';

    protected static ?string $navigationLabel = 'Analytics';

    protected static ?string $title = 'Business Analytics & KPI Funnel';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.analytics.dashboard';

    public int $days = 30;

    public static function canAccess(): bool
    {
        $user = Auth::user();

        if ($user === null) {
            return false;
        }

        if ($user->hasRole('super-admin') || $user->hasRole('business-admin')) {
            return true;
        }

        return $user->can('analytics.view');
    }

    /**
     * @return array<string, mixed>
     */
    public function getSummaryData(): array
    {
        $startDate = Carbon::today()->subDays($this->days)->format('Y-m-d');
        $endDate = Carbon::today()->format('Y-m-d');

        $metrics = DailyMetric::query()
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        $visitors = (int) $metrics->where('metric_key', 'visitors')->sum('value');
        $freeDownloaders = (int) $metrics->where('metric_key', 'free_downloaders')->sum('value');
        $productViewers = (int) $metrics->where('metric_key', 'product_viewers')->sum('value');
        $purchasers = (int) $metrics->where('metric_key', 'purchasers')->sum('value');
        $paidOrders = (int) $metrics->where('metric_key', 'paid_orders')->sum('value');
        $membersStarted = (int) $metrics->where('metric_key', 'members_started')->sum('value');
        $capturedRevenuePaise = (int) $metrics->where('metric_key', 'captured_revenue')->sum('value');
        $refundedAmountPaise = (int) $metrics->where('metric_key', 'refunded_amount')->sum('value');
        $netRevenuePaise = (int) $metrics->where('metric_key', 'net_revenue')->sum('value');

        $latestActiveMembers = (int) ($metrics->where('metric_key', 'active_members')->sortByDesc('date')->first()?->value ?? 0);
        $latestWeeklyActive = (int) ($metrics->where('metric_key', 'weekly_active_members')->sortByDesc('date')->first()?->value ?? 0);

        $aovPaise = $paidOrders > 0 ? (int) round($capturedRevenuePaise / $paidOrders) : 0;
        $conversionRate = $visitors > 0 ? round(($purchasers / $visitors) * 100, 2) : 0.0;

        // Daily breakdown grouped by date
        $dailyRows = $metrics->groupBy(fn ($item) => $item->date->format('Y-m-d'))
            ->map(function ($group, $date) {
                return [
                    'date' => $date,
                    'visitors' => (int) ($group->firstWhere('metric_key', 'visitors')?->value ?? 0),
                    'free_downloaders' => (int) ($group->firstWhere('metric_key', 'free_downloaders')?->value ?? 0),
                    'product_viewers' => (int) ($group->firstWhere('metric_key', 'product_viewers')?->value ?? 0),
                    'paid_orders' => (int) ($group->firstWhere('metric_key', 'paid_orders')?->value ?? 0),
                    'net_revenue_inr' => round(((int) ($group->firstWhere('metric_key', 'net_revenue')?->value ?? 0)) / 100, 2),
                    'active_members' => (int) ($group->firstWhere('metric_key', 'active_members')?->value ?? 0),
                ];
            })
            ->sortByDesc('date')
            ->values();

        return [
            'visitors' => $visitors,
            'freeDownloaders' => $freeDownloaders,
            'productViewers' => $productViewers,
            'purchasers' => $purchasers,
            'paidOrders' => $paidOrders,
            'membersStarted' => $membersStarted,
            'activeMembers' => $latestActiveMembers,
            'weeklyActiveMembers' => $latestWeeklyActive,
            'grossRevenueInr' => round($capturedRevenuePaise / 100, 2),
            'refundedInr' => round($refundedAmountPaise / 100, 2),
            'netRevenueInr' => round($netRevenuePaise / 100, 2),
            'aovInr' => round($aovPaise / 100, 2),
            'conversionRate' => $conversionRate,
            'dailyRows' => $dailyRows,
        ];
    }

    /**
     * @return array<Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            Action::make('export_csv')
                ->label('Export Metrics (CSV)')
                ->icon(Heroicon::OutlinedArrowDownTray)
                ->visible(fn () => Auth::user()?->hasPermissionTo('analytics.export') || Auth::user()?->hasRole('super-admin'))
                ->action(function (): StreamedResponse {
                    $metrics = DailyMetric::query()->orderByDesc('date')->get();

                    return response()->streamDownload(function () use ($metrics) {
                        $handle = fopen('php://output', 'w');
                        fputcsv($handle, ['Date', 'Metric Key', 'Value']);

                        foreach ($metrics as $metric) {
                            fputcsv($handle, [
                                $metric->date->format('Y-m-d'),
                                $metric->metric_key,
                                (string) $metric->value,
                            ]);
                        }

                        fclose($handle);
                    }, 'brightlearners-daily-metrics-'.now()->format('Y-m-d').'.csv', [
                        'Content-Type' => 'text/csv',
                    ]);
                }),
        ];
    }
}
