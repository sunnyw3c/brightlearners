<?php

namespace App\Jobs;

use App\Domains\Content\Models\ResourceVersion;
use App\Domains\Content\Services\PdfPreviewGenerator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateResourcePreviews implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(public readonly ResourceVersion $version)
    {
        $this->onQueue('media');
    }

    public function handle(PdfPreviewGenerator $generator): void
    {
        $generator->generate($this->version);
    }
}
