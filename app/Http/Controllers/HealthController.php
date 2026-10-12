<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class HealthController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $checks = [
            'database' => 'ok',
            'cache' => 'ok',
            'queue' => 'ok',
            'storage' => 'ok',
        ];

        $healthy = true;

        // 1. Database check
        try {
            DB::select('SELECT 1');
        } catch (Throwable) {
            $checks['database'] = 'error';
            $healthy = false;
        }

        // 2. Cache check
        try {
            $cacheKey = 'health:'.Str::uuid();
            Cache::put($cacheKey, true, 10);
            if (Cache::pull($cacheKey) !== true) {
                $checks['cache'] = 'error';
                $healthy = false;
            }
        } catch (Throwable) {
            $checks['cache'] = 'error';
            $healthy = false;
        }

        // 3. Queue check
        try {
            Queue::connection()->size();
        } catch (Throwable) {
            $checks['queue'] = 'error';
            $healthy = false;
        }

        // 4. Storage check
        try {
            $disk = Storage::disk('resources');
            $testFile = '.health-check-'.Str::uuid();
            $disk->put($testFile, 'ok');
            if ($disk->get($testFile) !== 'ok') {
                $checks['storage'] = 'error';
                $healthy = false;
            }
            $disk->delete($testFile);
        } catch (Throwable) {
            $checks['storage'] = 'error';
            $healthy = false;
        }

        return response()->json([
            'status' => $healthy ? 'ok' : 'error',
            'checks' => $checks,
        ], $healthy ? 200 : 503);
    }
}
