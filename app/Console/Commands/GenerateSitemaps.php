<?php

namespace App\Console\Commands;

use App\Domains\Growth\Services\SitemapGenerator;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('sitemap:generate')]
#[Description('Generate and cache segmented sitemaps (step 12.6)')]
class GenerateSitemaps extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Generating and caching sitemaps...');
        SitemapGenerator::warmCache();
        $this->info('Segmented sitemaps generated successfully.');

        return self::SUCCESS;
    }
}
