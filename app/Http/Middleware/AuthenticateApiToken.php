<?php

namespace App\Http\Middleware;

use App\Models\ApiToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateApiToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (! $token) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $apiToken = ApiToken::query()
            ->where('token', hash('sha256', $token))
            ->orWhere('token', $token)
            ->with('user')
            ->first();

        if (! $apiToken || ($apiToken->expires_at && $apiToken->expires_at->isPast())) {
            return response()->json(['message' => 'Invalid or expired API token.'], 401);
        }

        $apiToken->last_used_at = now();
        $apiToken->save();

        auth()->setUser($apiToken->user);
        $request->setUserResolver(fn () => $apiToken->user);

        return $next($request);
    }
}
