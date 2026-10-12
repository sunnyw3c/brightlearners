<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class EnsureAnonymousId
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $hasCookie = $request->hasCookie('bl_anon_id');
        $anonymousId = $request->cookie('bl_anon_id') ?? (string) Str::uuid();

        $request->attributes->set('anonymous_id', $anonymousId);

        $response = $next($request);

        if (! $hasCookie && method_exists($response, 'withCookie')) {
            // Store unencrypted 1-year first-party cookie
            $response->withCookie(cookie(
                'bl_anon_id',
                $anonymousId,
                60 * 24 * 365,
                null,
                null,
                false,
                false
            ));
        }

        return $response;
    }
}
