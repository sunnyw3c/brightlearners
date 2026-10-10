<?php

namespace Database\Factories;

use App\Domains\Growth\Enums\ArticleStatus;
use App\Domains\Growth\Models\Article;
use App\Domains\Growth\Models\ArticleCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Article>
 */
#[UseModel(Article::class)]
class ArticleFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->sentence(4);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'excerpt' => fake()->paragraph(),
            'body' => '<p>'.fake()->paragraphs(3, true).'</p>',
            'author_id' => User::factory(),
            'category_id' => ArticleCategory::factory(),
            'status' => ArticleStatus::Draft,
            'published_at' => null,
            'seo_title' => null,
            'seo_description' => null,
            'og_image_path' => null,
        ];
    }

    public function published(): static
    {
        return $this->state(fn () => [
            'status' => ArticleStatus::Published,
            'published_at' => now()->subDay(),
        ]);
    }

    public function archived(): static
    {
        return $this->state(fn () => [
            'status' => ArticleStatus::Archived,
            'published_at' => now()->subMonth(),
        ]);
    }
}
