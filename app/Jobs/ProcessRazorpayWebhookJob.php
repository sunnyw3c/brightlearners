<?php

namespace App\Jobs;

use App\Domains\Payments\Actions\ProcessRazorpayWebhook;
use App\Domains\Payments\Models\WebhookEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessRazorpayWebhookJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly WebhookEvent $webhookEvent,
    ) {
        $this->onQueue('payments');
    }

    public function handle(ProcessRazorpayWebhook $processRazorpayWebhook): void
    {
        $processRazorpayWebhook->handle($this->webhookEvent);
    }
}
