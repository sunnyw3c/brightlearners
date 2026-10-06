<?php

use App\Console\Commands\RecordFoundationHeartbeat;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

test('the scheduler executes the foundation heartbeat task', function () {
    Cache::forget(RecordFoundationHeartbeat::CACHE_KEY);

    Artisan::call('schedule:run');

    expect(Cache::get(RecordFoundationHeartbeat::CACHE_KEY))->not->toBeNull();
});
