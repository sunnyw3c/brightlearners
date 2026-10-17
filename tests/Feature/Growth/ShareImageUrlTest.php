<?php

use App\Domains\Catalog\Models\Product;
use App\Domains\Growth\Models\Article;
use App\Domains\Growth\Services\SeoBuilder;
use Illuminate\Support\Facades\Storage;

test('an uploaded article share image resolves to its public disk url', function () {
    $article = Article::factory()->make(['og_image_path' => 'og-images/cover.png']);

    expect($article->ogImageUrl())->toBe(Storage::disk('public')->url('og-images/cover.png'));
});

test('an older hand-typed article image path is still used as it is', function () {
    $article = Article::factory()->make(['og_image_path' => '/images/guide.png']);

    expect($article->ogImageUrl())->toBe(url('/images/guide.png'));
});

test('a product share image uses the cover on the previews disk', function () {
    $product = Product::factory()->create(['cover_path' => 'covers/maths.png']);

    expect(SeoBuilder::forProduct($product)->ogImage)->toBe(Storage::disk('previews')->url('covers/maths.png'));
});
