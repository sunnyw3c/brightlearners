<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

#[Signature('foundation:heartbeat')]
#[Description('Record that the application scheduler is running')]
class RecordFoundationHeartbeat extends Command
{
    public const CACHE_KEY = 'foundation:scheduler-heartbeat';

    public function handle(): int
    {
        Cache::put(self::CACHE_KEY, now()->toIso8601String(), now()->addDay());

        return self::SUCCESS;
    }
}
