<?php

namespace Database\Seeders;

use App\Domains\Catalog\Models\Product;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Curriculum\Models\SchoolClass;
use App\Domains\Growth\Enums\ArticleStatus;
use App\Domains\Growth\Models\Article;
use App\Domains\Growth\Models\ArticleCategory;
use App\Domains\Growth\Models\ArticleRelation;
use App\Models\User;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $author = User::where('email', 'admin@brightlearners.in')->first()
            ?? User::where('email', 'admin@example.com')->first()
            ?? User::first();

        $mathsCat = ArticleCategory::where('slug', 'maths-help')->first();
        $readingCat = ArticleCategory::where('slug', 'reading')->first();
        $routinesCat = ArticleCategory::where('slug', 'learning-routines')->first();

        $class1 = SchoolClass::where('slug', 'class-1')->first();
        $resource = LearningResource::first();
        $product = Product::first();

        if ($mathsCat) {
            $article1 = Article::firstOrCreate(
                ['slug' => 'top-5-tips-for-kindergarten-maths'],
                [
                    'category_id' => $mathsCat->id,
                    'author_id' => $author?->id,
                    'title' => 'Top 5 Tips for Kindergarten Maths',
                    'excerpt' => 'Help your young learner develop early number sense and visual counting skills with practical everyday home routines.',
                    'body' => '<p>Early numeracy is not about memorising multiplication tables or speed drills. For kindergarten and early primary children, maths understanding develops best through concrete physical objects, visual representations, and stress-free everyday exploration.</p><h3>1. Use tangible counting objects</h3><p>Before introducing abstract numerals on a page, practice with physical counters. Use buttons, colourful blocks, or dry pasta shells. Group them into sets of 5 and 10 to establish early spatial number awareness.</p><h3>2. Turn daily routines into playful maths</h3><p>Count stairs as you climb them, pair up socks while folding laundry, and ask your child to lay out four spoons for dinner. These subtle moments reinforce counting with genuine context.</p><h3>3. Emphasise pattern recognition</h3><p>Patterns are the heartbeat of mathematics. Challenge your child to build alternating colour towers or clap rhythmic sequences (clap-clap-tap, clap-clap-tap).</p><h3>4. Connect quantities to visual number lines</h3><p>Visualising a number line helps children grasp that numbers grow progressively bigger and smaller, laying the groundwork for addition and subtraction.</p><h3>5. Keep practice bite-sized and enjoyable</h3><p>Ten minutes of joyful, focused activity is infinitely better than an hour of frustration. Celebrate effort, curiosity, and problem-solving.</p>',
                    'status' => ArticleStatus::Published,
                    'published_at' => now()->subDays(3),
                    'seo_title' => 'Top 5 Tips for Kindergarten Maths | BrightLearners Parent Hub',
                    'seo_description' => 'Help your child build strong number sense and early maths confidence with five practical, proven home learning tips.',
                ]
            );

            if ($resource && ! $article1->relations()->where('related_type', LearningResource::class)->exists()) {
                ArticleRelation::create([
                    'article_id' => $article1->id,
                    'related_type' => LearningResource::class,
                    'related_id' => $resource->id,
                    'sort_order' => 1,
                ]);
            }

            if ($product && ! $article1->relations()->where('related_type', Product::class)->exists()) {
                ArticleRelation::create([
                    'article_id' => $article1->id,
                    'related_type' => Product::class,
                    'related_id' => $product->id,
                    'sort_order' => 2,
                ]);
            }

            if ($class1 && ! $article1->relations()->where('related_type', SchoolClass::class)->exists()) {
                ArticleRelation::create([
                    'article_id' => $article1->id,
                    'related_type' => SchoolClass::class,
                    'related_id' => $class1->id,
                    'sort_order' => 3,
                ]);
            }
        }

        if ($readingCat) {
            Article::firstOrCreate(
                ['slug' => 'how-to-build-a-daily-reading-habit'],
                [
                    'category_id' => $readingCat->id,
                    'author_id' => $author?->id,
                    'title' => 'How to Build a Daily Reading Habit',
                    'excerpt' => 'Practical strategies to nurture a genuine love of books in young readers without turning reading into a chore.',
                    'body' => '<p>Reading together is one of the most powerful gifts a parent can offer. Here is how to create a calm, delightful reading routine that lasts.</p><p>Set a dedicated quiet reading window, read aloud with expressive voices, and let your child choose their own stories.</p>',
                    'status' => ArticleStatus::Published,
                    'published_at' => now()->subDays(5),
                    'seo_title' => 'How to Build a Daily Reading Habit | BrightLearners',
                    'seo_description' => 'Discover how to nurture a lifelong love of reading at home with simple, sustainable daily book routines.',
                ]
            );
        }

        if ($routinesCat) {
            Article::firstOrCreate(
                ['slug' => 'creating-a-calm-after-school-learning-routine'],
                [
                    'category_id' => $routinesCat->id,
                    'author_id' => $author?->id,
                    'title' => 'Creating a Calm After-School Learning Routine',
                    'excerpt' => 'Transition peacefully from the classroom to home with structured, comforting routines that avoid homework battles.',
                    'body' => '<p>Children often arrive home cognitively exhausted. Before opening any worksheets, provide a wholesome snack and allow 30 minutes of unstructured downtime.</p><p>Keep learning spaces organized, consistent, and well-lit.</p>',
                    'status' => ArticleStatus::Published,
                    'published_at' => now()->subWeek(),
                    'seo_title' => 'Creating a Calm After-School Learning Routine | BrightLearners',
                    'seo_description' => 'Tips for parents to build a calm, productive after-school routine without stress or homework friction.',
                ]
            );
        }
    }
}
