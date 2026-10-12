<?php

namespace App\Console\Commands;

use App\Domains\Analytics\Actions\AggregateDailyMetrics;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AggregateDailyMetricsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'analytics:aggregate-daily {--date= : The date to aggregate in Y-m-d format}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Aggregate daily analytics funnel metrics and business KPIs';

    public function handle(AggregateDailyMetrics $action): int
    {
        $dateOption = $this->option('date');
        $date = $dateOption ? Carbon::parse($dateOption) : Carbon::yesterday();

        $this->info("Aggregating analytics metrics for {$date->format('Y-m-d')}...");

        $metrics = $action->handle($date);

        $tableData = $metrics->map(fn ($m) => [
            'date' => $m->date->format('Y-m-d'),
            'metric_key' => $m->metric_key,
            'value' => (string) $m->value,
        ])->toArray();

        $this->table(['Date', 'Metric Key', 'Value'], $tableData);
        $this->info('Metrics successfully aggregated and saved.');

        return self::SUCCESS;
    }
}
