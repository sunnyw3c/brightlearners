<?php

use App\Domains\Catalog\Enums\ProductStatus;
use App\Domains\Catalog\Models\Product;
use App\Domains\Content\Enums\ResourceStatus;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Content\Models\ResourceSkill;
use App\Domains\Content\Models\ResourceVersion;
use App\Domains\Curriculum\Models\SchoolClass;
use App\Domains\Curriculum\Models\Skill;
use App\Domains\Curriculum\Models\Subject;
use App\Domains\Curriculum\Models\Topic;
use App\Domains\Growth\Models\Article;
use App\Domains\Growth\Models\ArticleCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

beforeEach(function () {
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
        ['subject_id' => $this->subject->id, 'slug' => 'addition'],
        ['name' => 'Addition', 'sort_order' => 1, 'active' => true]
    );

    $this->skill = Skill::firstOrCreate(
        ['topic_id' => $this->topic->id, 'slug' => 'single-digit-addition'],
        ['name' => 'Single digit addition', 'learning_objective' => 'Add numbers', 'sort_order' => 1, 'active' => true]
    );

    $this->schoolClass->topics()->syncWithoutDetaching([$this->topic->id => ['intro' => 'Addition intro', 'sort_order' => 1, 'active' => true]]);
});

test('canonical points to correct URL for every page type', function () {
    // 1. Home
    $this->get('/')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('seo.canonical', route('home'))
        );

    // 2. Class
    $this->get("/{$this->schoolClass->slug}")
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('seo.canonical', route('learn.class', ['class' => $this->schoolClass->slug]))
        );

    // 3. Subject
    $this->get("/{$this->schoolClass->slug}/{$this->subject->slug}")
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('seo.canonical', route('learn.subject', ['class' => $this->schoolClass->slug, 'subject' => $this->subject->slug]))
        );

    // 4. Topic
    $this->get("/{$this->schoolClass->slug}/{$this->subject->slug}/{$this->topic->slug}")
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('seo.canonical', route('learn.topic', [
                'class' => $this->schoolClass->slug,
                'subject' => $this->subject->slug,
                'topic' => $this->topic->slug,
            ]))
        );

    // 5. Free resource hub
    $this->get('/free')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('seo.canonical', route('free.index'))
            ->where('seo.noindex', false)
        );

    // Filtered free hub points to unfiltered canonical and is noindex
    $this->get('/free?class=class-1')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('seo.canonical', route('free.index'))
            ->where('seo.noindex', true)
        );

    // 6. Free resource detail
    $resource = LearningResource::factory()->create([
        'slug' => 'test-worksheet',
        'is_free' => true,
        'status' => ResourceStatus::Published,
    ]);
    ResourceSkill::create([
        'resource_id' => $resource->id,
        'skill_id' => $this->skill->id,
        'class_id' => $this->schoolClass->id,
        'is_primary' => true,
    ]);
    ResourceVersion::factory()->create([
        'resource_id' => $resource->id,
        'version' => '1.0',
    ]);

    $this->get("/free/{$this->schoolClass->slug}/{$this->subject->slug}/{$resource->slug}")
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('seo.canonical', route('free.show', [
                'class' => $this->schoolClass->slug,
                'subject' => $this->subject->slug,
                'slug' => $resource->slug,
            ]))
        );

    // 7. Shop index
    $this->get('/shop')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('seo.canonical', route('shop.index'))
            ->where('seo.noindex', false)
        );

    // Filtered shop points to unfiltered canonical and is noindex
    $this->get('/shop?class=class-1')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('seo.canonical', route('shop.index'))
            ->where('seo.noindex', true)
        );

    // 8. Shop type
    $this->get('/shop/workbooks')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('seo.canonical', route('shop.type', ['type' => 'workbooks']))
        );

    // 9. Product detail
    $product = Product::factory()->create([
        'primary_class_id' => $this->schoolClass->id,
        'status' => ProductStatus::Active,
        'publish_at' => now()->subDay(),
    ]);

    $this->get("/shop/{$this->schoolClass->slug}/{$product->slug}")
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('seo.canonical', route('shop.show', [
                'class' => $this->schoolClass->slug,
                'slug' => $product->slug,
            ]))
        );

    // 10. Parent Hub index
    $this->get('/parents')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('seo.canonical', route('parents.index'))
            ->where('seo.noindex', false)
        );

    // 11. Parent Hub category
    $category = ArticleCategory::factory()->create(['slug' => 'maths-help', 'name' => 'Maths Help']);

    $this->get("/parents/{$category->slug}")
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('seo.canonical', route('parents.category', ['category' => $category->slug]))
            ->where('seo.noindex', false)
        );

    // Filtered category is marked noindex
    $this->get("/parents/{$category->slug}?filter=recent")
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('seo.canonical', route('parents.category', ['category' => $category->slug]))
            ->where('seo.noindex', true)
        );

    // 12. Article detail
    $article = Article::factory()->published()->create([
        'category_id' => $category->id,
        'slug' => 'how-to-teach-addition',
    ]);

    $this->get("/blog/{$article->slug}")
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('seo.canonical', route('articles.show', ['slug' => $article->slug]))
        );
});
