<?php

namespace App\Support;

use App\Support\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class Audit
{
    /**
     * Record a change for the admin audit log (viewer added in Phase 14).
     *
     * @param  array<string, mixed>|null  $before
     * @param  array<string, mixed>|null  $after
     * @param  array<string, mixed>|null  $metadata
     */
    public static function record(string $action, Model $subject, ?array $before = null, ?array $after = null, ?array $metadata = null): AuditLog
    {
        return AuditLog::query()->create([
            'actor_id' => Auth::id(),
            'action' => $action,
            'subject_type' => $subject->getMorphClass(),
            'subject_id' => $subject->getKey(),
            'before' => $before,
            'after' => $after,
            'metadata' => $metadata,
            'ip_address' => Request::ip(),
            'occurred_at' => now(),
        ]);
    }
}
