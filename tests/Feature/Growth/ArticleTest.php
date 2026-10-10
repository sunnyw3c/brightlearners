<?php

use App\Domains\Catalog\Enums\ProductStatus;
use App\Domains\Catalog\Models\Product;
use App\Domains\Content\Enums\ResourceStatus;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Curriculum\Models\SchoolClass;
use App\Domains\Growth\Enums\ArticleStatus;
use App\Domains\Growth\Models\Article;
use App\Domains\Growth\Models\ArticleCategory;
use App\Domains\Growth\Models\ArticleRelation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->category = ArticleCategory::firstOrCreate(
        ['slug' => 'maths-help'],
        ['name' => 'Maths Help', 'description' => 'Help with maths', 'sort_order' => 1]
    );

    $this->author = User::factory()->create(['name' => 'Sarah Teacher']);
});

test('parent hub index renders categories and published articles', function () {
    $published = Article::factory()->create([
        'category_id' => $this->category->id,
        'author_id' => $this->author->id,
        'title' => 'Top 5 Tips for Kindergarten Maths',
        'status' => ArticleStatus::Published,
        'published_at' => now()->subDay(),
    ]);

    $draft = Article::factory()->create([
        'category_id' => $this->category->id,
        'title' => 'Unpublished Draft Article',
        'status' => ArticleStatus::Draft,
    ]);

    $this->get('/parents')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('parents/index')
            ->has('categories')
            ->has('featuredArticles', 1)
            ->where('featuredArticles.0.id', $published->id)
        );
});

test('parent hub category page renders category articles and breadcrumbs', function () {
    $article = Article::factory()->create([
        'category_id' => $this->category->id,
        'author_id' => $this->author->id,
        'title' => 'Addition Strategies for Parents',
        'status' => ArticleStatus::Published,
        'published_at' => now()->subDay(),
    ]);

    $this->get("/parents/{$this->category->slug}")
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('parents/category')
            ->where('category.slug', $this->category->slug)
            ->has('articles.data', 1)
            ->where('articles.data.0.id', $article->id)
            ->has('breadcrumbs')
        );
});

test('article detail renders sanitized html and related items', function () {
    $schoolClass = SchoolClass::firstOrCreate(
        ['slug' => 'class-1'],
        ['name' => 'Class 1', 'sort_order' => 1, 'active' => true]
    );

    $resource = LearningResource::factory()->create([
        'title' => 'Addition Flashcards',
        'status' => ResourceStatus::Published,
    ]);

    $product = Product::factory()->create([
        'primary_class_id' => $schoolClass->id,
        'name' => 'Class 1 Workbook',
        'status' => ProductStatus::Active,
        'publish_at' => now()->subDay(),
    ]);

    $article = Article::factory()->create([
        'category_id' => $this->category->id,
        'author_id' => $this->author->id,
        'title' => 'Mastering Early Addition',
        'slug' => 'mastering-early-addition',
        'body' => '<p>Learn addition easily.</p><script>alert("xss")</script><img src="x" onerror="alert(1)">',
        'status' => ArticleStatus::Published,
        'published_at' => now()->subDay(),
    ]);

    ArticleRelation::create([
        'article_id' => $article->id,
        'related_type' => LearningResource::class,
        'related_id' => $resource->id,
        'sort_order' => 1,
    ]);

    ArticleRelation::create([
        'article_id' => $article->id,
        'related_type' => Product::class,
        'related_id' => $product->id,
        'sort_order' => 2,
    ]);

    $this->get("/blog/{$article->slug}")
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('articles/show')
            ->where('article.id', $article->id)
            // Verify HTML sanitizer stripped script and onerror
            ->where('article.body', fn ($body) => ! str_contains((string) $body, '<script>') && ! str_contains((string) $body, 'onerror'))
            ->has('tryThis', 2)
            ->where('tryThis.0.title', 'Addition Flashcards')
            ->where('tryThis.1.title', 'Class 1 Workbook')
        );
});

test('draft or archived articles return 404 for guests', function () {
    $draft = Article::factory()->create([
        'category_id' => $this->category->id,
        'slug' => 'secret-draft',
        'status' => ArticleStatus::Draft,
        'published_at' => null,
    ]);

    $archived = Article::factory()->create([
        'category_id' => $this->category->id,
        'slug' => 'retired-post',
        'status' => ArticleStatus::Archived,
        'published_at' => now()->subYear(),
    ]);

    $this->get("/blog/{$draft->slug}")->assertNotFound();
    $this->get("/blog/{$archived->slug}")->assertNotFound();
});

test('article is searchable in unified search when published', function () {
    $published = Article::factory()->create([
        'category_id' => $this->category->id,
        'title' => 'UniquePhonicsKeyword Guide for Parents',
        'slug' => 'phonics-guide',
        'status' => ArticleStatus::Published,
        'published_at' => now()->subDay(),
    ]);

    $draft = Article::factory()->create([
        'category_id' => $this->category->id,
        'title' => 'UniquePhonicsKeyword Draft for Review',
        'slug' => 'phonics-draft',
        'status' => ArticleStatus::Draft,
        'published_at' => null,
    ]);

    $response = $this->get('/search?q=UniquePhonicsKeyword');

    $response->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('search/index')
            ->has('results.data', 1)
            ->where('results.data.0.title', 'UniquePhonicsKeyword Guide for Parents')
        );
});
