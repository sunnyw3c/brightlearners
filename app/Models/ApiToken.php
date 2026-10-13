<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

#[Table('api_tokens')]
#[Fillable(['user_id', 'name', 'token', 'abilities', 'last_used_at', 'expires_at'])]
class ApiToken extends Model
{
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
            'abilities' => 'array',
            'last_used_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    public static function generateForUser(User $user, string $name = 'default', ?\DateTimeInterface $expiresAt = null): string
    {
        $plainTextToken = Str::random(40);

        static::query()->create([
            'user_id' => $user->id,
            'name' => $name,
            'token' => hash('sha256', $plainTextToken),
            'abilities' => ['*'],
            'expires_at' => $expiresAt,
        ]);

        return $plainTextToken;
    }
}
