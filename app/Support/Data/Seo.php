<?php

namespace App\Support\Data;

/**
 * Per-page SEO props, set through Inertia's `<Head>` so they are present
 * in the server-rendered HTML (step 5.11). `noindex` is set whenever a
 * filter combination or thin page should not be indexed (step 5.9, 5.3).
 */
final class Seo
{
    public function __construct(
        public readonly string $title,
        public readonly ?string $description,
        public readonly string $canonical,
        public readonly bool $noindex = false,
    ) {}
}
