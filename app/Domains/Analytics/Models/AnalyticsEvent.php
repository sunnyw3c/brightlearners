<?php

namespace App\Domains\Analytics\Models;

use App\Domains\Analytics\Enums\EventName;
use App\Models\User;
use Database\Factories\AnalyticsEventFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[UseFactory(AnalyticsEventFactory::class)]
class AnalyticsEvent extends Model
{
    use HasFactory;
    use Prunable;

    protected $table = 'analytics_events';

    protected $fillable = [
        'event_name',
        'anonymous_id',
        'user_id',
        'session_id',
        'occurred_at',
        'properties',
        'source',
        'dedupe_key',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'event_name' => EventName::class,
            'occurred_at' => 'datetime',
            'properties' => 'array',
        ];
    }

    /**
     * Get the prunable model query.
     */
    public function prunable(): Builder
    {
        $days = (int) config('analytics.retention_days', 90);

        return static::query()->where('occurred_at', '<', now()->subDays($days));
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
