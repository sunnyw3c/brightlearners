<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

class HealthController extends Controller
{
    public function __invoke(): JsonResponse|Response
    {
        try {
            DB::select('SELECT 1');

            $cacheKey = 'health:'.Str::uuid();
            Cache::put($cacheKey, true, 10);

            if (Cache::pull($cacheKey) !== true) {
                throw new RuntimeException('The cache health check did not return the expected value.');
            }
        } catch (Throwable) {
            return response()->noContent(503);
        }

        return response()->json(['status' => 'ok']);
    }
}
