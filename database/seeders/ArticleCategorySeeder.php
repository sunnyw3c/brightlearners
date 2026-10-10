<?php

namespace Database\Seeders;

use App\Domains\Growth\Models\ArticleCategory;
use Illuminate\Database\Seeder;

class ArticleCategorySeeder extends Seeder
{
    /**
     * Seed the default parent hub article categories (step 12.1).
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Learning at Home',
                'slug' => 'learning-at-home',
                'description' => 'Practical advice for creating an engaging and supportive learning environment at home.',
                'sort_order' => 1,
            ],
            [
                'name' => 'Maths Help',
                'slug' => 'maths-help',
                'description' => 'Strategies, visual aids, and step-by-step guidance for early maths concepts.',
                'sort_order' => 2,
            ],
            [
                'name' => 'English Help',
                'slug' => 'english-help',
                'description' => 'Phonics, vocabulary, grammar and writing tips to help children build communication skills.',
                'sort_order' => 3,
            ],
            [
                'name' => 'Reading',
                'slug' => 'reading',
                'description' => 'Developing a love for reading with leveled stories, comprehension prompts and routines.',
                'sort_order' => 4,
            ],
            [
                'name' => 'Activities',
                'slug' => 'activities',
                'description' => 'Hands-on offline games, crafts, and interactive learning challenges.',
                'sort_order' => 5,
            ],
            [
                'name' => 'Learning Routines',
                'slug' => 'learning-routines',
                'description' => 'Building consistent, stress-free daily study habits for primary school children.',
                'sort_order' => 6,
            ],
            [
                'name' => 'Parent Guides',
                'slug' => 'parent-guides',
                'description' => 'Comprehensive roadmaps covering curriculum stages, developmental milestones and parenting tips.',
                'sort_order' => 7,
            ],
        ];

        foreach ($categories as $category) {
            ArticleCategory::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
