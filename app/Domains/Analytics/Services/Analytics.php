<?php

namespace App\Domains\Analytics\Services;

use App\Domains\Analytics\Enums\EventName;
use App\Domains\Analytics\Jobs\RecordAnalyticsEventJob;
use App\Domains\Analytics\Models\AnalyticsEvent;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;

class Analytics
{
    /**
     * Track an analytics event.
     *
     * @param  array<string, mixed>  $properties
     */
    public static function track(
        EventName|string $name,
        array $properties = [],
        ?string $dedupeKey = null,
        ?int $userId = null,
        ?string $anonymousId = null,
        ?string $sessionId = null,
        string $source = 'server',
    ): ?AnalyticsEvent {
        $eventName = is_string($name) ? EventName::from($name) : $name;

        // 1. Validate required properties
        foreach ($eventName->requiredProperties() as $requiredProperty) {
            if (! array_key_exists($requiredProperty, $properties) || $properties[$requiredProperty] === null || $properties[$requiredProperty] === '') {
                throw new InvalidArgumentException("Missing required property '{$requiredProperty}' for event '{$eventName->value}'.");
            }
        }

        // 2. Reject personally identifiable information or child nicknames
        $forbiddenKeys = ['nickname', 'name', 'child_name', 'email', 'parent_email'];
        foreach ($forbiddenKeys as $forbiddenKey) {
            if (array_key_exists($forbiddenKey, $properties)) {
                throw new InvalidArgumentException("Events must not contain personal identifying data ('{$forbiddenKey}').");
            }
        }

        // 3. Resolve identity context if not explicitly provided
        if ($userId === null && Auth::check()) {
            $userId = (int) Auth::id();
        }

        if ($anonymousId === null && request()) {
            $anonymousId = request()->cookie('bl_anon_id')
                ?? request()->attributes->get('anonymous_id')
                ?? request()->header('X-Anonymous-ID');
        }

        if ($sessionId === null && request() && request()->hasSession()) {
            $sessionId = request()->session()->getId();
        }

        // 4. Quick idempotency check before dispatch
        if ($dedupeKey !== null) {
            $existing = AnalyticsEvent::query()->where('dedupe_key', $dedupeKey)->first();
            if ($existing !== null) {
                return $existing;
            }
        }

        $job = new RecordAnalyticsEventJob(
            eventName: $eventName->value,
            properties: $properties,
            dedupeKey: $dedupeKey,
            userId: $userId,
            anonymousId: $anonymousId,
            sessionId: $sessionId,
            source: $source,
            occurredAt: now(),
        );

        if (defined('PHPUNIT_COMPOSER_INSTALL') || defined('__PEST__') || app()->environment('testing') || config('queue.default') === 'sync') {
            return $job->handle();
        }

        dispatch($job)->onQueue(config('analytics.queue', 'default'));

        return null;
    }
}
