<?php

namespace App\Support\Data;

use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

/**
 * Per-page SEO props, set through Inertia's `<Head>` so they are present
 * in the server-rendered HTML (step 5.11). `noindex` is set whenever a
 * filter combination or thin page should not be indexed (step 5.9, 5.3).
 */
final class Seo implements Arrayable, JsonSerializable
{
    /**
     * @param  array<string, mixed>|list<array<string, mixed>>|null  $jsonLd
     */
    public function __construct(
        public readonly string $title,
        public readonly ?string $description = null,
        public readonly string $canonical = '',
        public readonly bool $noindex = false,
        public readonly ?string $ogTitle = null,
        public readonly ?string $ogDescription = null,
        public readonly ?string $ogImage = null,
        public readonly string $ogType = 'website',
        public readonly ?array $jsonLd = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'canonical' => $this->canonical,
            'noindex' => $this->noindex,
            'og_title' => $this->ogTitle ?? $this->title,
            'og_description' => $this->ogDescription ?? $this->description,
            'og_image' => $this->ogImage ?? url('/images/og-default.png'),
            'og_type' => $this->ogType,
            'json_ld' => $this->jsonLd,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
