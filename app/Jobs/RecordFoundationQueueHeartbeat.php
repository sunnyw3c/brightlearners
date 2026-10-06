<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cache;

class RecordFoundationQueueHeartbeat implements ShouldQueue
{
    use Queueable;

    public const CACHE_KEY = 'foundation:queue-heartbeat';

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Cache::put(self::CACHE_KEY, now()->toIso8601String(), now()->addDay());
    }
}
