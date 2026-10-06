<?php

namespace App\Domains\Content\Services;

use App\Domains\Content\Enums\PreviewStatus;
use App\Domains\Content\Models\ResourcePreview;
use App\Domains\Content\Models\ResourceVersion;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Renders the first pages of a version's PDF to WebP preview images:
 * poppler's `pdftoppm` through the Process facade (T-05), then PHP's GD
 * extension to convert each PNG page to WebP. Only the first
 * config('content.preview_pages') pages are ever rendered — a paid
 * resource is never previewed in full (step 4.5).
 */
class PdfPreviewGenerator
{
    public function __construct(private readonly PdfInfoReader $pdfInfoReader) {}

    public function generate(ResourceVersion $version): void
    {
        $version->previews()->delete();

        $sourcePath = Storage::disk('resources')->path($version->file_path);
        $workingDirectory = storage_path('app/tmp/previews-'.Str::uuid());
        File::ensureDirectoryExists($workingDirectory);

        try {
            $pagesGenerated = 0;

            foreach (range(1, $this->pagesToRender($sourcePath)) as $pageNo) {
                $pngPath = $this->renderPage($sourcePath, $workingDirectory, $pageNo);

                if ($pngPath === null || ! $this->storePreview($version, $pngPath, $pageNo)) {
                    break;
                }

                $pagesGenerated++;
            }

            $version->update([
                'preview_status' => $pagesGenerated > 0 ? PreviewStatus::Ready : PreviewStatus::Failed,
            ]);
        } finally {
            File::deleteDirectory($workingDirectory);
        }
    }

    private function pagesToRender(string $sourcePath): int
    {
        $pageCount = $this->pdfInfoReader->pageCount($sourcePath) ?? (int) config('content.preview_pages');

        return max(0, min($pageCount, (int) config('content.preview_pages')));
    }

    private function renderPage(string $sourcePath, string $workingDirectory, int $pageNo): ?string
    {
        $prefix = "{$workingDirectory}/page-{$pageNo}";

        $result = Process::run(['pdftoppm', '-png', '-r', '150', '-f', (string) $pageNo, '-l', (string) $pageNo, $sourcePath, $prefix]);

        $generated = File::glob("{$prefix}*.png");

        if ($result->failed() || $generated === []) {
            return null;
        }

        return $generated[0];
    }

    private function storePreview(ResourceVersion $version, string $pngPath, int $pageNo): bool
    {
        $dimensions = getimagesize($pngPath);
        [$width, $height] = $dimensions !== false ? $dimensions : [0, 0];

        $image = imagecreatefrompng($pngPath);

        if ($image === false) {
            return false;
        }

        $webpPath = $pngPath.'.webp';
        imagewebp($image, $webpPath, 80);
        imagedestroy($image);

        $storedPath = "{$version->resource_id}/v{$version->version}/page-{$pageNo}.webp";
        Storage::disk('previews')->put($storedPath, File::get($webpPath));

        ResourcePreview::query()->create([
            'resource_version_id' => $version->id,
            'page_no' => $pageNo,
            'image_path' => $storedPath,
            'width' => $width,
            'height' => $height,
            'sort_order' => $pageNo,
        ]);

        return true;
    }
}
