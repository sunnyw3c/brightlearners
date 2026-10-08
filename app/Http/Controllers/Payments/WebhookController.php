<?php

namespace App\Http\Controllers\Payments;

use App\Domains\Payments\Enums\WebhookStatus;
use App\Domains\Payments\Models\WebhookEvent;
use App\Http\Controllers\Controller;
use App\Jobs\ProcessRazorpayWebhookJob;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class WebhookController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $rawPayload = $request->getContent();
        $signature = $request->header('X-Razorpay-Signature');
        $secret = config('services.razorpay.webhook_secret');

        $payload = json_decode($rawPayload, true) ?? [];
        $eventId = $payload['event_id'] ?? $request->header('X-Razorpay-Event-Id') ?? ('evt_'.md5($rawPayload));
        $eventType = $payload['event'] ?? 'unknown';

        // Signature check
        $signatureValid = true;
        if (! empty($secret)) {
            $expectedSignature = hash_hmac('sha256', $rawPayload, $secret);
            $signatureValid = hash_equals($expectedSignature, (string) $signature);
        }

        if (! $signatureValid) {
            try {
                WebhookEvent::query()->create([
                    'provider' => 'razorpay',
                    'event_id' => $eventId,
                    'event_type' => $eventType,
                    'payload' => $rawPayload,
                    'signature_valid' => false,
                    'received_at' => now(),
                    'status' => WebhookStatus::Failed,
                    'error' => 'Invalid HMAC signature',
                ]);
            } catch (QueryException $e) {
                // Ignore duplicate event insert failure for invalid signature
            }

            return response('Invalid webhook signature', 400);
        }

        // Idempotency check: check if event already exists
        $existing = WebhookEvent::query()
            ->where('provider', 'razorpay')
            ->where('event_id', $eventId)
            ->first();

        if ($existing !== null) {
            return response('Event already processed', 200);
        }

        try {
            $webhookEvent = WebhookEvent::query()->create([
                'provider' => 'razorpay',
                'event_id' => $eventId,
                'event_type' => $eventType,
                'payload' => $rawPayload,
                'signature_valid' => true,
                'received_at' => now(),
                'status' => WebhookStatus::Pending,
            ]);
        } catch (QueryException $e) {
            // Duplicate delivery caught by DB unique constraint
            return response('Event already received', 200);
        }

        ProcessRazorpayWebhookJob::dispatch($webhookEvent);

        return response('Webhook received', 200);
    }
}
