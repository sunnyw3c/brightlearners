<?php

use App\Domains\Content\Enums\PreviewStatus;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Content\Models\ResourceVersion;
use App\Domains\Content\Services\PdfPreviewGenerator;
use App\Models\User;
use Illuminate\Process\FakeProcessResult;
use Illuminate\Process\PendingProcess;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;

function tinyPngBytes(): string
{
    $image = imagecreatetruecolor(2, 2);
    ob_start();
    imagepng($image);
    $bytes = ob_get_clean();
    imagedestroy($image);

    return $bytes;
}

test('generating previews stores a WebP image per rendered page and marks the version ready', function () {
    Storage::fake('resources');
    Storage::fake('previews');
    config(['content.preview_pages' => 2]);

    $resource = LearningResource::factory()->create(['created_by' => User::factory()->create()->id]);
    $version = ResourceVersion::factory()->create([
        'resource_id' => $resource->id,
        'file_path' => 'source.pdf',
        'created_by' => $resource->created_by,
    ]);
    Storage::disk('resources')->put($version->file_path, 'not a real pdf, process is faked');

    Process::fake(function (PendingProcess $process) {
        $command = $process->command;

        if (is_array($command) && $command[0] === 'pdfinfo') {
            return Process::result(output: "Pages: 2\n");
        }

        if (is_array($command) && $command[0] === 'pdftoppm') {
            file_put_contents(end($command).'.png', tinyPngBytes());

            return Process::result();
        }

        return Process::result();
    });

    app(PdfPreviewGenerator::class)->generate($version);

    $version->refresh();
    expect($version->preview_status)->toBe(PreviewStatus::Ready);
    expect($version->previews()->count())->toBe(2);

    $version->previews->each(fn ($preview) => Storage::disk('previews')->assertExists($preview->image_path));
});

test('a pdftoppm failure marks the version preview_status as failed', function () {
    Storage::fake('resources');
    Storage::fake('previews');

    $resource = LearningResource::factory()->create(['created_by' => User::factory()->create()->id]);
    $version = ResourceVersion::factory()->create([
        'resource_id' => $resource->id,
        'file_path' => 'source.pdf',
        'created_by' => $resource->created_by,
    ]);
    Storage::disk('resources')->put($version->file_path, 'not a real pdf');

    Process::fake(fn (): FakeProcessResult => Process::result(errorOutput: 'pdftoppm: command not found', exitCode: 1));

    app(PdfPreviewGenerator::class)->generate($version);

    $version->refresh();
    expect($version->preview_status)->toBe(PreviewStatus::Failed);
    expect($version->previews()->count())->toBe(0);
});
