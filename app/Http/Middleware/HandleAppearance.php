<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class HandleAppearance
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $appearance = $request->query('appearance') ?? $request->cookie('appearance') ?? 'system';
        View::share('appearance', $appearance);

        $response = $next($request);

        if ($request->has('appearance') && in_array($request->query('appearance'), ['light', 'dark', 'system'], true)) {
            $response->cookie('appearance', (string) $request->query('appearance'), 60 * 24 * 365);
        }

        return $response;
    }
}
