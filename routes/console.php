<?php

use App\Domains\Analytics\Models\AnalyticsEvent;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('foundation:heartbeat')->everyMinute();
Schedule::command('queue:prune-failed', ['--hours' => 336])->daily()->onOneServer();
Schedule::command('resources:publish-scheduled')->everyFiveMinutes()->onOneServer();
Schedule::command('products:publish-scheduled')->everyFiveMinutes()->onOneServer();
Schedule::command('payments:reconcile')->everyTenMinutes()->onOneServer();
Schedule::command('orders:expire-pending')->everyFiveMinutes()->onOneServer();
Schedule::command('membership:release-weeks')->everyFiveMinutes()->onOneServer();
Schedule::command('membership:expire')->hourly()->onOneServer();
Schedule::command('sitemap:generate')->daily()->onOneServer();
Schedule::command('analytics:aggregate-daily')->dailyAt('00:05')->onOneServer();
Schedule::command('model:prune', ['--model' => [AnalyticsEvent::class]])->dailyAt('01:00')->onOneServer();
