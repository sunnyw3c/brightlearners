<?php

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
Schedule::command('orders:expire-pending')->everyFiveMinutes()->onOneServer();
