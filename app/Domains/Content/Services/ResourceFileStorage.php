<?php

namespace App\Domains\Content\Services;

use App\Domains\Content\Models\LearningResource;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use InvalidArgumentException;
use RuntimeException;

/**
 * Stores a resource version's files on the private `resources` disk under
 * a random name — never the original filename — so a path never has to
 * change (step 4.3, "Upload rules").
 */
class ResourceFileStorage
{
    /**
     * @throws InvalidArgumentException if the file is not really a PDF
     */
    public function store(LearningResource $resource, string $version, UploadedFile $file, string $suffix = ''): string
    {
        $this->assertPdf($file);

        $name = Str::uuid().($suffix !== '' ? "-{$suffix}" : '').'.pdf';
        $path = "{$resource->id}/v{$version}/{$name}";

        Storage::disk('resources')->put($path, $file);

        return $path;
    }

    public function checksum(UploadedFile $file): string
    {
        $checksum = hash_file('sha256', $file->getRealPath());

        if ($checksum === false) {
            throw new RuntimeException('Could not read the uploaded file to checksum it.');
        }

        return $checksum;
    }

    public function absolutePath(string $storedPath): string
    {
        return Storage::disk('resources')->path($storedPath);
    }

    /**
     * @throws InvalidArgumentException if the file is not really a PDF
     */
    private function assertPdf(UploadedFile $file): void
    {
        if ($file->getMimeType() !== 'application/pdf') {
            throw new InvalidArgumentException('Only PDF files are accepted.');
        }
    }
}
