<?php

namespace App\Domains\Growth\Data;

use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

class SeoData implements Arrayable, JsonSerializable
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
