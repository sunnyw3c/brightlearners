<?php

namespace App\Domains\Growth\Models;

use App\Domains\Growth\Enums\ArticleStatus;
use App\Domains\Growth\Policies\ArticlePolicy;
use App\Domains\Growth\Services\HtmlSanitizer;
use App\Models\User;
use App\Support\Concerns\Auditable;
use Database\Factories\ArticleFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

#[Table('articles')]
#[Fillable([
    'title',
    'slug',
    'excerpt',
    'body',
    'author_id',
    'category_id',
    'status',
    'published_at',
    'seo_title',
    'seo_description',
    'og_image_path',
])]
#[UseFactory(ArticleFactory::class)]
#[UsePolicy(ArticlePolicy::class)]
class Article extends Model
{
    use Auditable;

    /** @use HasFactory<ArticleFactory> */
    use HasFactory;

    use Searchable;
    use SoftDeletes;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => ArticleStatus::class,
            'published_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * @return BelongsTo<ArticleCategory, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ArticleCategory::class, 'category_id');
    }

    /**
     * @return HasMany<ArticleRelation, $this>
     */
    public function relations(): HasMany
    {
        return $this->hasMany(ArticleRelation::class, 'article_id')->orderBy('sort_order');
    }

    /**
     * @param  Builder<Article>  $query
     * @return Builder<Article>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', ArticleStatus::Published)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function isPublished(): bool
    {
        return $this->status === ArticleStatus::Published
            && $this->published_at !== null
            && $this->published_at->isPast();
    }

    /**
     * Canonical URL for this article (step 12.4).
     */
    public function canonicalUrl(): string
    {
        return route('articles.show', ['slug' => $this->slug]);
    }

    /**
     * Sanitised rich-text HTML for rendering on the public site (step 12.2).
     */
    public function sanitizedBody(): string
    {
        return HtmlSanitizer::clean($this->body);
    }

    /**
     * Get the indexable data array for the model (step 12.2).
     *
     * @return array<string, mixed>
     */
    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'excerpt' => $this->excerpt,
            'body' => strip_tags((string) $this->body),
            'status' => $this->status instanceof ArticleStatus ? $this->status->value : $this->status,
        ];
    }

    /**
     * Determine if the model should be searchable.
     */
    public function shouldBeSearchable(): bool
    {
        return $this->isPublished();
    }

    /**
     * The database Scout driver queries this table directly.
     *
     * @return Builder<Article>
     */
    public function newScoutQuery(\Laravel\Scout\Builder $builder): Builder
    {
        return static::query()->published();
    }
}
