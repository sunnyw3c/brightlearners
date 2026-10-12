<?php

namespace App\Domains\Analytics\Jobs;

use App\Domains\Analytics\Enums\EventName;
use App\Domains\Analytics\Models\AnalyticsEvent;
use DateTimeInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RecordAnalyticsEventJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @param  array<string, mixed>  $properties
     */
    public function __construct(
        public readonly string $eventName,
        public readonly array $properties,
        public readonly ?string $dedupeKey = null,
        public readonly ?int $userId = null,
        public readonly ?string $anonymousId = null,
        public readonly ?string $sessionId = null,
        public readonly string $source = 'server',
        public readonly ?DateTimeInterface $occurredAt = null,
    ) {}

    public function handle(): AnalyticsEvent
    {
        if ($this->dedupeKey !== null) {
            $existing = AnalyticsEvent::query()->where('dedupe_key', $this->dedupeKey)->first();
            if ($existing !== null) {
                return $existing;
            }
        }

        try {
            return AnalyticsEvent::query()->create([
                'event_name' => EventName::from($this->eventName),
                'anonymous_id' => $this->anonymousId,
                'user_id' => $this->userId,
                'session_id' => $this->sessionId,
                'occurred_at' => $this->occurredAt ?? now(),
                'properties' => $this->properties,
                'source' => $this->source,
                'dedupe_key' => $this->dedupeKey,
            ]);
        } catch (UniqueConstraintViolationException $e) {
            if ($this->dedupeKey !== null) {
                return AnalyticsEvent::query()->where('dedupe_key', $this->dedupeKey)->firstOrFail();
            }

            throw $e;
        }
    }
}
