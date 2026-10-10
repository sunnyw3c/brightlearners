<?php

namespace App\Domains\Growth\Services;

class HtmlSanitizer
{
    /**
     * Allowed HTML tags for rich-text article body.
     */
    protected const ALLOWED_TAGS = '<p><br><strong><b><em><i><u><s><h1><h2><h3><h4><h5><h6><ul><ol><li><blockquote><a><img><code><pre><table><thead><tbody><tr><th><td><hr><div><span>';

    /**
     * Sanitise HTML before rendering on the public site (step 12.2).
     */
    public static function clean(?string $html): string
    {
        if ($html === null || $html === '') {
            return '';
        }

        // Strip disallowed tags
        $stripped = strip_tags($html, self::ALLOWED_TAGS);

        // Remove dangerous attributes like onclick, onerror, onload, etc.
        $cleaned = preg_replace('/\s*on\w+\s*=\s*(["\']).*?\1/i', '', $stripped);
        $cleaned = preg_replace('/\s*on\w+\s*=\s*[^"\'>\s]+/i', '', (string) $cleaned);

        // Remove javascript: href/src
        $cleaned = preg_replace('/\s*(href|src)\s*=\s*(["\'])\s*javascript:[^"\']*?\2/i', ' $1="#"', (string) $cleaned);

        return (string) $cleaned;
    }
}
