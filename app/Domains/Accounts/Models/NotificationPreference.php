<?php

namespace App\Domains\Accounts\Models;

use App\Models\User;
use Database\Factories\NotificationPreferenceFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'learning_reminders', 'membership_notices', 'marketing_consent', 'marketing_consent_at', 'marketing_consent_source'])]
#[UseFactory(NotificationPreferenceFactory::class)]
class NotificationPreference extends Model
{
    /** @use HasFactory<NotificationPreferenceFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'learning_reminders' => 'boolean',
            'membership_notices' => 'boolean',
            'marketing_consent' => 'boolean',
            'marketing_consent_at' => 'datetime',
        ];
    }
}
