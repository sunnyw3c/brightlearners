<?php

use Illuminate\Support\Facades\App;

afterEach(function () {
    App::detectEnvironment(fn () => 'testing');
});

test('non-production environment sends X-Robots-Tag noindex and blocking robots.txt', function () {
    App::detectEnvironment(fn () => 'staging');
    expect(app()->isProduction())->toBeFalse();

    $response = $this->get('/');
    $response->assertOk();
    $response->assertHeader('X-Robots-Tag', 'noindex, nofollow');

    $robots = $this->get('/robots.txt');
    $robots->assertOk();
    $robots->assertHeader('Content-Type', 'text/plain; charset=UTF-8');
    expect($robots->getContent())
        ->toContain('User-agent: *')
        ->toContain('Disallow: /')
        ->not->toContain('Allow: /')
        ->not->toContain('Sitemap:');
});

test('production environment permits crawling and points to sitemap without X-Robots-Tag', function () {
    // Simulate production environment
    App::detectEnvironment(fn () => 'production');
    expect(app()->isProduction())->toBeTrue();

    $response = $this->get('/');
    $response->assertOk();
    $response->assertHeaderMissing('X-Robots-Tag');

    $robots = $this->get('/robots.txt');
    $robots->assertOk();
    $robots->assertHeader('Content-Type', 'text/plain; charset=UTF-8');
    $content = $robots->getContent();

    expect($content)
        ->toContain('User-agent: *')
        ->toContain('Allow: /')
        ->toContain('Disallow: /admin')
        ->toContain('Disallow: /account')
        ->toContain('Disallow: /checkout')
        ->toContain('Sitemap: '.route('sitemap.index'));
});
