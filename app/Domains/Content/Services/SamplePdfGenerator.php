<?php

namespace App\Domains\Content\Services;

use App\Domains\Content\Models\ResourceVersion;
use Illuminate\Support\Facades\Storage;

class SamplePdfGenerator
{
    /**
     * Generate a valid PDF-1.4 binary string with specified title, subtitle and page count.
     */
    public function generate(string $title, string $subtitle = '', int $pageCount = 1, string $variant = 'standard'): string
    {
        $pageCount = max(1, $pageCount);
        $objIndex = 3;
        $pageRefs = [];
        $contentObjects = [];

        $badgeText = match ($variant) {
            'answer_key' => 'ANSWER KEY & TEACHER SOLUTIONS',
            'low_ink' => 'ECO PRINT / LOW-INK EDITION',
            default => 'BRIGHTLEARNERS PRINTABLE ACTIVITY SHEET',
        };

        for ($p = 1; $p <= $pageCount; $p++) {
            $pageObjId = $objIndex++;
            $contentObjId = $objIndex++;
            $pageRefs[] = "{$pageObjId} 0 R";

            $stream = 'BT /F1 18 Tf 50 780 Td ('.addcslashes($title, '()\\').") Tj ET\n";
            if ($subtitle !== '') {
                $stream .= 'BT /F1 11 Tf 50 758 Td ('.addcslashes($subtitle, '()\\').") Tj ET\n";
            }
            $stream .= "BT /F1 10 Tf 50 735 Td ({$badgeText} - Page {$p} of {$pageCount}) Tj ET\n";
            $stream .= "0.6 0.6 0.6 RG 1 w 50 720 m 545 720 l S\n";

            if ($variant === 'answer_key') {
                $stream .= "BT /F1 14 Tf 50 685 Td (Step-by-step Answer & Verification Guide) Tj ET\n";
                $stream .= "0.95 0.95 0.95 rg 50 200 495 460 re f\n";
                $stream .= "0.3 0.3 0.3 RG 1 w 50 200 495 460 re S\n";
                $stream .= "BT /F1 12 Tf 70 630 Td (1. Review core concepts and phonetic sound matches.) Tj ET\n";
                $stream .= "BT /F1 12 Tf 70 590 Td (2. Verified answer key items for letter prompts A through E.) Tj ET\n";
                $stream .= "BT /F1 12 Tf 70 550 Td (3. Suggested teacher & parent extension questions.) Tj ET\n";
            } elseif ($variant === 'low_ink') {
                $stream .= "0.2 0.2 0.2 RG 1 w 50 150 495 530 re S\n";
                $stream .= "BT /F1 14 Tf 70 640 Td (Low-Ink Work & Activity Area) Tj ET\n";
                $stream .= "BT /F1 11 Tf 70 610 Td (Designed for crisp black & white printing without heavy ink fills.) Tj ET\n";
            } else {
                $stream .= "0.92 0.95 0.98 rg 50 150 495 530 re f\n";
                $stream .= "0.2 0.4 0.7 RG 2 w 50 150 495 530 re S\n";
                $stream .= "BT /F1 16 Tf 160 480 Td (Learning & Activity Work Area) Tj ET\n";
                $stream .= "BT /F1 11 Tf 140 450 Td (Complete the exercises and color inside the lines!) Tj ET\n";
            }

            $stream .= "0.6 0.6 0.6 RG 1 w 50 80 m 545 80 l S\n";
            $stream .= "BT /F1 9 Tf 50 60 Td (BrightLearners - Licensed for educational and household use. All rights reserved.) Tj ET\n";

            $contentLen = strlen($stream);
            $contentObjects[$contentObjId] = "{$contentObjId} 0 obj\n<< /Length {$contentLen} >>\nstream\n{$stream}\nendstream\nendobj\n";
            $contentObjects[$pageObjId] = "{$pageObjId} 0 obj\n<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Contents {$contentObjId} 0 R /Resources << /Font << /F1 ".($pageCount * 2 + 3)." 0 R >> >> >>\nendobj\n";
        }

        $fontObjId = $pageCount * 2 + 3;
        $fontObj = "{$fontObjId} 0 obj\n<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>\nendobj\n";

        $catalogObj = "1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n";
        $pagesObj = "2 0 obj\n<< /Type /Pages /Kids [".implode(' ', $pageRefs)."] /Count {$pageCount} >>\nendobj\n";

        $out = "%PDF-1.4\n";
        $allObjs = [
            1 => $catalogObj,
            2 => $pagesObj,
        ];
        foreach ($contentObjects as $id => $obj) {
            $allObjs[$id] = $obj;
        }
        $allObjs[$fontObjId] = $fontObj;

        ksort($allObjs);

        $xrefOffsets = [];
        foreach ($allObjs as $id => $objStr) {
            $xrefOffsets[$id] = strlen($out);
            $out .= $objStr;
        }

        $xrefPos = strlen($out);
        $totalObjs = count($allObjs) + 1;
        $out .= "xref\n0 {$totalObjs}\n0000000000 65535 f \n";
        for ($i = 1; $i < $totalObjs; $i++) {
            $out .= sprintf("%010d 00000 n \n", $xrefOffsets[$i]);
        }
        $out .= "trailer\n<< /Size {$totalObjs} /Root 1 0 R >>\nstartxref\n{$xrefPos}\n%%EOF\n";

        return $out;
    }

    /**
     * Ensure that the physical PDF file exists on the resources disk for a given ResourceVersion.
     * If missing, generates a valid sample PDF and saves it.
     */
    public function ensureVersionFiles(ResourceVersion $version): void
    {
        $disk = Storage::disk('resources');
        $resource = $version->resource;
        $title = $resource?->title ?? 'Learning Resource';
        $subtitle = $resource?->summary ?? 'Printable Activity Sheet';
        $pages = max(1, (int) ($resource?->page_count ?? 1));

        if (! empty($version->file_path) && ! $disk->exists($version->file_path)) {
            $pdf = $this->generate($title, $subtitle, $pages, 'standard');
            $disk->put($version->file_path, $pdf);

            if (empty($version->checksum)) {
                $version->update(['checksum' => hash('sha256', $pdf)]);
            }
        }

        if (! empty($version->answer_file_path) && ! $disk->exists($version->answer_file_path)) {
            $answerPdf = $this->generate("{$title} - Answer Key", $subtitle, max(1, (int) ceil($pages / 2)), 'answer_key');
            $disk->put($version->answer_file_path, $answerPdf);
        }

        if (! empty($version->low_ink_path) && ! $disk->exists($version->low_ink_path)) {
            $lowInkPdf = $this->generate("{$title} (Low-Ink)", $subtitle, $pages, 'low_ink');
            $disk->put($version->low_ink_path, $lowInkPdf);
        }
    }
}
