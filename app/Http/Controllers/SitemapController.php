<?php

namespace App\Http\Controllers;

use App\Domains\Growth\Services\SitemapGenerator;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Sitemap index pointing at segmented sitemaps (step 12.6).
     */
    public function index(): Response
    {
        return response(SitemapGenerator::indexXml(), 200, [
            'Content-Type' => 'text/xml; charset=UTF-8',
        ]);
    }

    /**
     * Segmented sitemap section (pages, resources, products, articles).
     */
    public function section(string $section): Response
    {
        $xml = SitemapGenerator::sectionXml($section);

        abort_if($xml === null, 404);

        return response($xml, 200, [
            'Content-Type' => 'text/xml; charset=UTF-8',
        ]);
    }
}
