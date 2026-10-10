<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class RobotsController extends Controller
{
    /**
     * In production allows crawling and points to sitemap.
     * Everywhere else disallows everything (step 12.6).
     */
    public function __invoke(): Response
    {
        if (app()->isProduction()) {
            $sitemapUrl = route('sitemap.index');
            $content = implode("\n", [
                'User-agent: *',
                'Allow: /',
                'Disallow: /admin',
                'Disallow: /account',
                'Disallow: /cart',
                'Disallow: /checkout',
                'Disallow: /order/',
                'Disallow: /search',
                '',
                "Sitemap: {$sitemapUrl}",
            ]);
        } else {
            $content = implode("\n", [
                'User-agent: *',
                'Disallow: /',
            ]);
        }

        return response($content, 200, [
            'Content-Type' => 'text/plain; charset=UTF-8',
        ]);
    }
}
