<?php

use App\Domains\Catalog\Enums\ProductStatus;
use App\Domains\Catalog\Models\Product;
use App\Domains\Curriculum\Models\SchoolClass;
use App\Domains\Growth\Enums\ArticleStatus;
use App\Domains\Growth\Models\Article;
use App\Domains\Growth\Models\ArticleCategory;
use App\Domains\Growth\Services\StructuredData;
use App\Models\User;
use App\Support\Data\Breadcrumb;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('organization structured data produces valid schema', function () {
    $schema = StructuredData::organization();

    expect($schema)->toBeArray()
        ->and($schema['@context'])->toBe('https://schema.org')
        ->and($schema['@type'])->toBe('Organization')
        ->and($schema['name'])->toBe('BrightLearners')
        ->and($schema['url'])->toBe(url('/'))
        ->and($schema['logo'])->toBe(url('/images/logo.png'));

    $json = json_encode($schema);
    expect($json)->not->toBeFalse();
    expect(json_decode($json, true))->toBe($schema);
});

test('breadcrumb list structured data handles array and object breadcrumbs', function () {
    $breadcrumbs = [
        ['label' => 'Home', 'href' => url('/')],
        new Breadcrumb('Class 1', url('/class-1')),
        ['label' => 'Maths', 'url' => url('/class-1/maths')],
    ];

    $schema = StructuredData::breadcrumbList($breadcrumbs);

    expect($schema)->toBeArray()
        ->and($schema['@context'])->toBe('https://schema.org')
        ->and($schema['@type'])->toBe('BreadcrumbList')
        ->and($schema['itemListElement'])->toHaveCount(3);

    expect($schema['itemListElement'][0])->toBe([
        '@type' => 'ListItem',
        'position' => 1,
        'name' => 'Home',
        'item' => url('/'),
    ]);

    expect($schema['itemListElement'][1])->toBe([
        '@type' => 'ListItem',
        'position' => 2,
        'name' => 'Class 1',
        'item' => url('/class-1'),
    ]);

    expect($schema['itemListElement'][2])->toBe([
        '@type' => 'ListItem',
        'position' => 3,
        'name' => 'Maths',
        'item' => url('/class-1/maths'),
    ]);

    $json = json_encode($schema);
    expect($json)->not->toBeFalse();
});

test('article structured data produces valid schema with correct author and publisher', function () {
    $author = User::factory()->create(['name' => 'Jane Educator']);
    $category = ArticleCategory::firstOrCreate(
        ['slug' => 'parent-guides'],
        ['name' => 'Parent Guides', 'description' => 'Helpful guides', 'sort_order' => 1]
    );

    $article = Article::factory()->create([
        'category_id' => $category->id,
        'author_id' => $author->id,
        'title' => 'How to Teach Addition at Home',
        'slug' => 'teach-addition-at-home',
        'excerpt' => 'A guide to early addition techniques.',
        'status' => ArticleStatus::Published,
        'published_at' => now()->subDays(2),
    ]);

    $schema = StructuredData::article($article);

    expect($schema)->toBeArray()
        ->and($schema['@context'])->toBe('https://schema.org')
        ->and($schema['@type'])->toBe('Article')
        ->and($schema['headline'])->toBe('How to Teach Addition at Home')
        ->and($schema['description'])->toBe('A guide to early addition techniques.')
        ->and($schema['url'])->toBe($article->canonicalUrl())
        ->and($schema['author']['name'])->toBe('Jane Educator')
        ->and($schema['publisher']['name'])->toBe('BrightLearners');

    $json = json_encode($schema);
    expect($json)->not->toBeFalse();
});

test('product structured data matches server price and contains no fake ratings', function () {
    $schoolClass = SchoolClass::firstOrCreate(
        ['slug' => 'class-2'],
        ['name' => 'Class 2', 'sort_order' => 2, 'active' => true]
    );

    $product = Product::factory()->create([
        'primary_class_id' => $schoolClass->id,
        'name' => 'Class 2 Maths Workbook',
        'slug' => 'class-2-maths-workbook',
        'sku' => 'WB-C2-MATH',
        'regular_price' => 29900, // 299.00 INR in paise
        'sale_price' => 19900, // 199.00 INR in paise
        'currency' => 'INR',
        'status' => ProductStatus::Active,
        'publish_at' => now()->subDay(),
    ]);

    $schema = StructuredData::product($product);

    expect($schema)->toBeArray()
        ->and($schema['@context'])->toBe('https://schema.org')
        ->and($schema['@type'])->toBe('Product')
        ->and($schema['name'])->toBe('Class 2 Maths Workbook')
        ->and($schema['sku'])->toBe('WB-C2-MATH')
        ->and($schema['offers']['@type'])->toBe('Offer')
        ->and($schema['offers']['price'])->toBe('199.00')
        ->and($schema['offers']['priceCurrency'])->toBe('INR')
        ->and($schema['offers']['availability'])->toBe('https://schema.org/InStock')
        ->and($schema['offers']['url'])->toBe($product->canonicalUrl());

    // Critical: No rating data is included unless real reviews exist (step 12.9)
    expect($schema)->not->toHaveKey('aggregateRating');

    $json = json_encode($schema);
    expect($json)->not->toBeFalse();
});
