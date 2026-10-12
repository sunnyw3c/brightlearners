<?php

namespace App\Http\Controllers\Analytics;

use App\Domains\Analytics\Enums\EventName;
use App\Domains\Analytics\Services\Analytics;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use InvalidArgumentException;
use ValueError;

class ClientEventController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $eventNameInput = $request->input('event_name');

        if (! is_string($eventNameInput)) {
            return response()->json(['message' => 'Invalid event name.'], 422);
        }

        try {
            $eventName = EventName::from($eventNameInput);
        } catch (ValueError) {
            return response()->json(['message' => 'Unknown event name.'], 422);
        }

        if (! $eventName->isClientAllowed()) {
            return response()->json([
                'message' => "Event '{$eventName->value}' cannot be dispatched from the client.",
            ], 403);
        }

        $properties = $request->input('properties');
        if (! is_array($properties)) {
            return response()->json(['message' => 'Properties must be an array.'], 422);
        }

        try {
            Analytics::track(
                name: $eventName,
                properties: $properties,
                userId: $request->user()?->id,
                anonymousId: $request->input('anonymous_id') ?? $request->cookie('bl_anon_id') ?? $request->attributes->get('anonymous_id'),
                source: 'client',
            );
        } catch (InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Event recorded.'], 201);
    }
}
