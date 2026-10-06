<?php

namespace App\Domains\Content\Services;

use Illuminate\Support\Facades\Process;

/**
 * Reads page count from a PDF with poppler's `pdfinfo` (T-05 in
 * docs/tracking/decisions.md). The count can still be corrected by hand on
 * the resource.
 */
class PdfInfoReader
{
    public function pageCount(string $absolutePath): ?int
    {
        $result = Process::run(['pdfinfo', $absolutePath]);

        if ($result->failed()) {
            return null;
        }

        foreach (explode("\n", $result->output()) as $line) {
            if (str_starts_with($line, 'Pages:') && preg_match('/\d+/', $line, $matches)) {
                return (int) $matches[0];
            }
        }

        return null;
    }
}
