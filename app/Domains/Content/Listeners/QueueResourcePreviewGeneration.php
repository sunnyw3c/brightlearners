<?php

namespace App\Domains\Content\Listeners;

use App\Domains\Content\Events\ResourceVersionUploaded;
use App\Jobs\GenerateResourcePreviews;

class QueueResourcePreviewGeneration
{
    public function handle(ResourceVersionUploaded $event): void
    {
        GenerateResourcePreviews::dispatch($event->version);
    }
}
