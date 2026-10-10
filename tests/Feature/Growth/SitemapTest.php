<?php

use App\Domains\Catalog\Enums\ProductStatus;
use App\Domains\Catalog\Models\Product;
use App\Domains\Content\Enums\ResourceStatus;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Content\Models\ResourceSkill;
use App\Domains\Curriculum\Models\SchoolClass;
use App\Domains\Curriculum\Models\Skill;
use App\Domains\Curriculum\Models\Subject;
use App\Domains\Curriculum\Models\Topic;
use App\Domains\Growth\Enums\ArticleStatus;
use App\Domains\Growth\Models\Article;
use App\Domains\Growth\Models\ArticleCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

uses(RefreshDatabase::class);

beforeEach(function () {
    Cache::flush();

    $this->schoolClass = SchoolClass::firstOrCreate(
        ['slug' => 'class-1'],
        ['name' => 'Class 1', 'sort_order' => 1, 'active' => true]
    );

    $this->subject = Subject::firstOrCreate(
        ['slug' => 'maths'],
        ['name' => 'Maths', 'sort_order' => 1, 'active' => true]
    );

    $this->schoolClass->subjects()->syncWithoutDetaching([$this->subject->id => ['sort_order' => 1, 'active' => true]]);

    $this->topic = Topic::firstOrCreate(
        ['subject_id' => $this->subject->id, 'slug' => 'counting'],
        ['name' => 'Counting', 'sort_order' => 1, 'active' => true]
    );

    $this->skill = Skill::firstOrCreate(
        ['topic_id' => $this->topic->id, 'slug' => 'count-to-10'],
        ['name' => 'Count to 10', 'learning_objective' => 'Count', 'sort_order' => 1, 'active' => true]
    );

    $this->category = ArticleCategory::firstOrCreate(
        ['slug' => 'maths-help'],
        ['name' => 'Maths Help', 'description' => 'Help with maths', 'sort_order' => 1]
    );
});

test('sitemap index returns XML with valid section links', function () {
    $response = $this->get('/sitemap.xml');

    $response->assertOk();
    $response->assertHeader('Content-Type', 'text/xml; charset=UTF-8');
    $content = $response->getContent();

    expect($content)->toContain('<sitemapindex')
        ->toContain(route('sitemap.section', ['section' => 'pages']))
        ->toContain(route('sitemap.section', ['section' => 'resources']))
        ->toContain(route('sitemap.section', ['section' => 'products']))
        ->toContain(route('sitemap.section', ['section' => 'articles']));
});

test('invalid sitemap section returns 404', function () {
    $this->get('/sitemaps/unknown.xml')->assertNotFound();
});

test('resources sitemap only includes published resources and excludes drafts', function () {
    $publishedResource = LearningResource::factory()->create([
        'slug' => 'published-sheet',
        'status' => ResourceStatus::Published,
    ]);
    ResourceSkill::create([
        'resource_id' => $publishedResource->id,
        'skill_id' => $this->skill->id,
        'class_id' => $this->schoolClass->id,
        'is_primary' => true,
    ]);

    $draftResource = LearningResource::factory()->create([
        'slug' => 'draft-sheet',
        'status' => ResourceStatus::Draft,
    ]);
    ResourceSkill::create([
        'resource_id' => $draftResource->id,
        'skill_id' => $this->skill->id,
        'class_id' => $this->schoolClass->id,
        'is_primary' => true,
    ]);

    $response = $this->get('/sitemaps/resources.xml');

    $response->assertOk();
    $content = $response->getContent();

    expect($content)->toContain($publishedResource->canonicalUrl())
        ->not->toContain('draft-sheet');
});

test('products sitemap only includes active published products and excludes drafts or archived', function () {
    $activeProduct = Product::factory()->create([
        'primary_class_id' => $this->schoolClass->id,
        'slug' => 'active-workbook',
        'status' => ProductStatus::Active,
        'publish_at' => now()->subDay(),
    ]);

    $draftProduct = Product::factory()->create([
        'primary_class_id' => $this->schoolClass->id,
        'slug' => 'draft-workbook',
        'status' => ProductStatus::Draft,
    ]);

    $archivedProduct = Product::factory()->create([
        'primary_class_id' => $this->schoolClass->id,
        'slug' => 'archived-workbook',
        'status' => ProductStatus::Archived,
    ]);

    $futureProduct = Product::factory()->create([
        'primary_class_id' => $this->schoolClass->id,
        'slug' => 'scheduled-workbook',
        'status' => ProductStatus::Active,
        'publish_at' => now()->addDays(5),
    ]);

    $response = $this->get('/sitemaps/products.xml');

    $response->assertOk();
    $content = $response->getContent();

    expect($content)->toContain($activeProduct->canonicalUrl())
        ->not->toContain('draft-workbook')
        ->not->toContain('archived-workbook')
        ->not->toContain('scheduled-workbook');
});

test('articles sitemap only includes published articles and excludes drafts or archived', function () {
    $publishedArticle = Article::factory()->create([
        'category_id' => $this->category->id,
        'slug' => 'published-guide',
        'status' => ArticleStatus::Published,
        'published_at' => now()->subDay(),
    ]);

    $draftArticle = Article::factory()->create([
        'category_id' => $this->category->id,
        'slug' => 'draft-guide',
        'status' => ArticleStatus::Draft,
        'published_at' => null,
    ]);

    $archivedArticle = Article::factory()->create([
        'category_id' => $this->category->id,
        'slug' => 'archived-guide',
        'status' => ArticleStatus::Archived,
        'published_at' => now()->subMonths(2),
    ]);

    $response = $this->get('/sitemaps/articles.xml');

    $response->assertOk();
    $content = $response->getContent();

    expect($content)->toContain($publishedArticle->canonicalUrl())
        ->not->toContain('draft-guide')
        ->not->toContain('archived-guide');
});

test('artisan sitemap:generate warms cache successfully', function () {
    $this->artisan('sitemap:generate')
        ->assertSuccessful();

    expect(Cache::has('sitemap:index'))->toBeTrue()
        ->and(Cache::has('sitemap:pages'))->toBeTrue()
        ->and(Cache::has('sitemap:resources'))->toBeTrue()
        ->and(Cache::has('sitemap:products'))->toBeTrue()
        ->and(Cache::has('sitemap:articles'))->toBeTrue();
});
