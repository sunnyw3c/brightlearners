<?php

use App\Jobs\RecordFoundationQueueHeartbeat;
use Illuminate\Support\Facades\Cache;

test('a queued foundation job executes', function () {
    Cache::forget(RecordFoundationQueueHeartbeat::CACHE_KEY);

    RecordFoundationQueueHeartbeat::dispatch();

    expect(Cache::get(RecordFoundationQueueHeartbeat::CACHE_KEY))->not->toBeNull();
});
