<?php

namespace App\Domains\Accounts\Models;

use App\Domains\Accounts\Enums\FeedbackCategory;
use App\Domains\Content\Models\LearningResource;
use App\Models\User;
use Database\Factories\FeedbackFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[UseFactory(FeedbackFactory::class)]
class Feedback extends Model
{
    /** @use HasFactory<FeedbackFactory> */
    use HasFactory;

    protected $table = 'feedback';

    protected $fillable = [
        'user_id',
        'learning_resource_id',
        'category',
        'rating',
        'message',
        'url',
        'status',
        'admin_response',
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<LearningResource, $this>
     */
    public function learningResource(): BelongsTo
    {
        return $this->belongsTo(LearningResource::class, 'learning_resource_id');
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'category' => FeedbackCategory::class,
            'rating' => 'integer',
        ];
    }
}
