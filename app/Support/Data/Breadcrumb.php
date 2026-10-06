<?php

namespace App\Support\Data;

/**
 * One crumb in a server-built navigation trail (Home -> Class -> Subject ->
 * Topic -> Resource). Phase 12 reuses the same trail for structured data.
 */
final class Breadcrumb
{
    public function __construct(
        public readonly string $label,
        public readonly ?string $url,
    ) {}
}
