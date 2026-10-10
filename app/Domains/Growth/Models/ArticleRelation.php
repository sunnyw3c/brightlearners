<?php

namespace App\Domains\Growth\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

#[Table('article_relations')]
#[Fillable(['article_id', 'related_type', 'related_id', 'sort_order'])]
class ArticleRelation extends Model
{
    /**
     * @return BelongsTo<Article, $this>
     */
    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class, 'article_id');
    }

    /**
     * The related entity (SchoolClass, Skill, LearningResource, Product).
     */
    public function related(): MorphTo
    {
        return $this->morphTo();
    }
}
