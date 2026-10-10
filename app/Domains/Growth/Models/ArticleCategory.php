<?php

namespace App\Domains\Growth\Models;

use Database\Factories\ArticleCategoryFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Table('article_categories')]
#[Fillable(['name', 'slug', 'description', 'sort_order'])]
#[UseFactory(ArticleCategoryFactory::class)]
class ArticleCategory extends Model
{
    /** @use HasFactory<ArticleCategoryFactory> */
    use HasFactory;

    /**
     * @return HasMany<Article, $this>
     */
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'category_id');
    }

    /**
     * The canonical URL for this guide category (step 12.4).
     */
    public function canonicalUrl(): string
    {
        return route('parents.category', ['category' => $this->slug]);
    }
}
