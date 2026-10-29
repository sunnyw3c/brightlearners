<?php

namespace App\Console\Commands;

use App\Domains\Content\Models\ResourceVersion;
use App\Domains\Content\Services\SamplePdfGenerator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class EnsureResourceFilesExistCommand extends Command
{
    protected $signature = 'resources:ensure-files {--force : Regenerate even if files already exist}';

    protected $description = 'Ensure physical PDF files exist on the private resources disk for all resource versions';

    public function handle(SamplePdfGenerator $pdfGenerator): int
    {
        $versions = ResourceVersion::with('resource')->get();
        $disk = Storage::disk('resources');
        $force = (bool) $this->option('force');

        $this->info("Checking {$versions->count()} resource versions...");

        $created = 0;
        $skipped = 0;

        foreach ($versions as $version) {
            $missing = ! $disk->exists($version->file_path);

            if ($missing || $force) {
                $pdfGenerator->ensureVersionFiles($version);
                $created++;
            } else {
                $skipped++;
            }
        }

        $this->info("Completed: {$created} files generated/ensured, {$skipped} files already present.");

        return self::SUCCESS;
    }
}
