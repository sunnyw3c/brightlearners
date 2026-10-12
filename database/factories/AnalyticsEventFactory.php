<?php

namespace Database\Factories;

use App\Domains\Analytics\Enums\EventName;
use App\Domains\Analytics\Models\AnalyticsEvent;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<AnalyticsEvent>
 */
class AnalyticsEventFactory extends Factory
{
    protected $model = AnalyticsEvent::class;

    public function definition(): array
    {
        return [
            'event_name' => EventName::ResourceView,
            'anonymous_id' => (string) Str::uuid(),
            'user_id' => null,
            'session_id' => Str::random(40),
            'occurred_at' => now(),
            'properties' => [
                'resource_id' => 1,
                'class' => 'class-1',
                'subject' => 'maths',
                'skill' => 'counting',
                'source' => 'direct',
            ],
            'source' => 'server',
            'dedupe_key' => null,
        ];
    }
}
