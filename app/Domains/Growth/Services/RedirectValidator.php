<?php

namespace App\Domains\Growth\Services;

use App\Domains\Growth\Models\Redirect;
use Illuminate\Validation\ValidationException;

class RedirectValidator
{
    /**
     * Normalize path for redirect comparison.
     */
    public static function normalize(string $path): string
    {
        $trimmed = trim($path);
        // If it's a full URL with domain, extract the path
        if (str_starts_with($trimmed, 'http://') || str_starts_with($trimmed, 'https://')) {
            $parsed = parse_url($trimmed, PHP_URL_PATH);
            $trimmed = $parsed ?: '/';
        }

        return '/'.ltrim($trimmed, '/');
    }

    /**
     * Validate that a redirect does not point to itself, cause a chain, or create a loop (step 12.10).
     *
     * @throws ValidationException
     */
    public static function validate(string $from, string $to, ?int $ignoreId = null): void
    {
        $fromNorm = self::normalize($from);
        $toNorm = self::normalize($to);

        // 1. Refuse redirect pointing to itself
        if ($fromNorm === $toNorm) {
            throw ValidationException::withMessages([
                'to_path' => 'A redirect cannot point to its own source path.',
            ]);
        }

        // 2. Refuse redirect pointing at another redirect's source (chaining: from -> to -> third)
        $targetsAnotherSource = Redirect::where('active', true)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->where(function ($q) use ($toNorm) {
                $q->where('from_path', $toNorm)
                    ->orWhere('from_path', ltrim($toNorm, '/'));
            })
            ->exists();

        if ($targetsAnotherSource) {
            throw ValidationException::withMessages([
                'to_path' => "The destination '{$to}' is already the source of another redirect (redirect chaining is not allowed).",
            ]);
        }

        // Also check if another redirect already targets this 'from_path' (source chaining: previous -> from -> to)
        $anotherTargetsSource = Redirect::where('active', true)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->where(function ($q) use ($fromNorm) {
                $q->where('to_path', $fromNorm)
                    ->orWhere('to_path', ltrim($fromNorm, '/'));
            })
            ->exists();

        if ($anotherTargetsSource) {
            throw ValidationException::withMessages([
                'from_path' => "Another redirect already points to '{$from}'. Redirect chains are not allowed.",
            ]);
        }

        // 3. Refuse redirect looping back to its own source (to -> from)
        $createsLoop = Redirect::where('active', true)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->where(function ($q) use ($fromNorm, $toNorm) {
                $q->where(function ($sub) use ($fromNorm, $toNorm) {
                    $sub->where('from_path', $toNorm)
                        ->where('to_path', $fromNorm);
                })->orWhere(function ($sub) use ($fromNorm, $toNorm) {
                    $sub->where('from_path', ltrim($toNorm, '/'))
                        ->where('to_path', ltrim($fromNorm, '/'));
                });
            })
            ->exists();

        if ($createsLoop) {
            throw ValidationException::withMessages([
                'to_path' => 'This redirect would create a loop with an existing redirect.',
            ]);
        }
    }
}
