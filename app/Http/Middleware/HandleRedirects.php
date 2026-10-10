<?php

namespace App\Http\Middleware;

use App\Domains\Growth\Models\Redirect;
use App\Domains\Growth\Services\RedirectValidator;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class HandleRedirects
{
    /**
     * Handle an incoming request.
     *
     * Looks up the path only when the request would otherwise be a 404 (step 12.10).
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $response = $next($request);
        } catch (NotFoundHttpException $e) {
            $redirect = $this->findRedirect($request);
            if ($redirect !== null) {
                return $redirect;
            }

            throw $e;
        }

        if ($response->getStatusCode() === 404) {
            $redirect = $this->findRedirect($request);
            if ($redirect !== null) {
                return $redirect;
            }
        }

        return $response;
    }

    private function findRedirect(Request $request): ?Response
    {
        $path = RedirectValidator::normalize($request->path());

        $redirect = Redirect::where('active', true)
            ->where(function ($q) use ($path) {
                $q->where('from_path', $path)
                    ->orWhere('from_path', ltrim($path, '/'));
            })
            ->first();

        if ($redirect) {
            $redirect->increment('hits');

            return redirect()->to($redirect->to_path, $redirect->status_code);
        }

        return null;
    }
}
