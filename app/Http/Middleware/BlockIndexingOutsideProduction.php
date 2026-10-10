<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlockIndexingOutsideProduction
{
    /**
     * Handle an incoming request.
     *
     * Adds X-Robots-Tag: noindex, nofollow to every response when the
     * environment is not production (docs/plan/phase-12-parent-hub-seo.md, step 12.12).
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! app()->isProduction() && ! app()->environment('local')) {
            $response->headers->set('X-Robots-Tag', 'noindex, nofollow');
        }

        return $response;
    }
}
