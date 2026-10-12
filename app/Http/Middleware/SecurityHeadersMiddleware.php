<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    /**
     * Handle an incoming request and apply production-grade security headers.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        // CSP in Report-Only mode allowing Razorpay Checkout and Google Analytics
        $csp = "default-src 'self'; "
            ."script-src 'self' 'unsafe-inline' 'unsafe-eval' https://checkout.razorpay.com https://api.razorpay.com https://www.googletagmanager.com https://www.google-analytics.com; "
            ."style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; "
            ."img-src 'self' data: blob: https://*.amazonaws.com https://images.unsplash.com https://www.google-analytics.com https://www.googletagmanager.com; "
            ."font-src 'self' data: https://fonts.gstatic.com; "
            ."connect-src 'self' https://api.razorpay.com https://lumberjack.razorpay.com https://www.google-analytics.com https://analytics.google.com https://*.google-analytics.com; "
            ."frame-src 'self' https://api.razorpay.com; "
            ."object-src 'none'; "
            ."base-uri 'self';";

        $response->headers->set('Content-Security-Policy-Report-Only', $csp);

        return $response;
    }
}
