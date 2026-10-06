<?php

namespace App\Console\Commands;

use App\Domains\Content\Actions\PublishResourceVersion;
use App\Domains\Content\Enums\ResourceStatus;
use App\Domains\Content\Models\LearningResource;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('resources:publish-scheduled')]
#[Description('Publish resources whose scheduled publish time has come')]
class PublishScheduledResources extends Command
{
    public function handle(PublishResourceVersion $publishResourceVersion): int
    {
        LearningResource::query()
            ->where('status', ResourceStatus::Scheduled)
            ->where('scheduled_for', '<=', now())
            ->each(function (LearningResource $resource) use ($publishResourceVersion): void {
                $version = $resource->pendingVersion();

                if ($version === null) {
                    return;
                }

                $publishResourceVersion->handle($version);
            });

        return self::SUCCESS;
    }
}
