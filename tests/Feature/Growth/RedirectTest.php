<?php

use App\Domains\Growth\Enums\ArticleStatus;
use App\Domains\Growth\Models\Article;
use App\Domains\Growth\Models\ArticleCategory;
use App\Domains\Growth\Models\Redirect;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;

uses(RefreshDatabase::class);

test('a valid 301 redirect intercepts 404 and increments hit count', function () {
    $redirect = Redirect::create([
        'from_path' => '/old-parent-guide',
        'to_path' => '/parents',
        'status_code' => 301,
        'active' => true,
    ]);

    expect($redirect->hits)->toBe(0);

    $response = $this->get('/old-parent-guide');

    $response->assertStatus(301);
    $response->assertRedirect('/parents');

    expect($redirect->fresh()->hits)->toBe(1);
});

test('inactive redirect does not intercept 404', function () {
    Redirect::create([
        'from_path' => '/inactive-legacy-url',
        'to_path' => '/parents',
        'status_code' => 301,
        'active' => false,
    ]);

    $this->get('/inactive-legacy-url')->assertNotFound();
});

test('self-referencing redirect is refused on save', function () {
    expect(function () {
        Redirect::create([
            'from_path' => '/same-page',
            'to_path' => '/same-page',
            'status_code' => 301,
            'active' => true,
        ]);
    })->toThrow(ValidationException::class);
});

test('redirect chain is refused when destination is already the source of another redirect', function () {
    // Step 1: create B -> C
    Redirect::create([
        'from_path' => '/page-b',
        'to_path' => '/page-c',
        'status_code' => 301,
        'active' => true,
    ]);

    // Step 2: attempt to create A -> B (which would cause A -> B -> C chain)
    expect(function () {
        Redirect::create([
            'from_path' => '/page-a',
            'to_path' => '/page-b',
            'status_code' => 301,
            'active' => true,
        ]);
    })->toThrow(ValidationException::class);
});

test('redirect chain is refused when another redirect already points to the source', function () {
    // Step 1: create A -> B
    Redirect::create([
        'from_path' => '/page-a',
        'to_path' => '/page-b',
        'status_code' => 301,
        'active' => true,
    ]);

    // Step 2: attempt to create B -> C (which would cause A -> B -> C chain)
    expect(function () {
        Redirect::create([
            'from_path' => '/page-b',
            'to_path' => '/page-c',
            'status_code' => 301,
            'active' => true,
        ]);
    })->toThrow(ValidationException::class);
});

test('redirect loop is refused on save', function () {
    Redirect::create([
        'from_path' => '/alpha',
        'to_path' => '/beta',
        'status_code' => 301,
        'active' => true,
    ]);

    expect(function () {
        Redirect::create([
            'from_path' => '/beta',
            'to_path' => '/alpha',
            'status_code' => 301,
            'active' => true,
        ]);
    })->toThrow(ValidationException::class);
});

test('updating slug on a published article automatically creates a 301 redirect', function () {
    $category = ArticleCategory::firstOrCreate(
        ['slug' => 'parent-guides'],
        ['name' => 'Parent Guides', 'description' => 'Helpful guides', 'sort_order' => 1]
    );

    $article = Article::factory()->create([
        'category_id' => $category->id,
        'slug' => 'original-slug',
        'status' => ArticleStatus::Published,
        'published_at' => now()->subDay(),
    ]);

    $article->update(['slug' => 'renamed-slug']);

    $redirect = Redirect::where('from_path', '/blog/original-slug')->first();

    expect($redirect)->not->toBeNull()
        ->and($redirect->to_path)->toBe('/blog/renamed-slug')
        ->and($redirect->status_code)->toBe(301);

    $this->get('/blog/original-slug')
        ->assertStatus(301)
        ->assertRedirect('/blog/renamed-slug');
});
