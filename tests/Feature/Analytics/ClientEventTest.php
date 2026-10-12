<?php

use App\Domains\Analytics\Enums\EventName;
use App\Domains\Analytics\Models\AnalyticsEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    cache()->flush();
});

test('the client endpoint accepts allow-listed events and records them', function () {
    $response = $this->postJson(route('events.store'), [
        'event_name' => 'resource_preview',
        'properties' => [
            'resource_id' => 42,
            'preview_page' => 1,
        ],
    ]);

    $response->assertCreated();
    expect(AnalyticsEvent::query()->where('event_name', EventName::ResourcePreview->value)->count())->toBe(1);
});

test('a server-only event name is refused by client endpoint with 403', function () {
    $response = $this->postJson(route('events.store'), [
        'event_name' => 'purchase_completed',
        'properties' => [
            'order_id' => 10,
            'revenue' => 50000,
            'items' => [],
        ],
    ]);

    $response->assertForbidden();
    expect(AnalyticsEvent::query()->where('event_name', EventName::PurchaseCompleted->value)->count())->toBe(0);
});

test('rate limiting triggers when exceeding client event request threshold', function () {
    $throttled = false;

    for ($i = 0; $i < 65; $i++) {
        $response = $this->postJson(route('events.store'), [
            'event_name' => 'membership_view',
            'properties' => [
                'plan_context' => 'annual_pilot',
            ],
        ]);

        if ($response->status() === 429) {
            $throttled = true;
            break;
        }
    }

    expect($throttled)->toBeTrue();
});
