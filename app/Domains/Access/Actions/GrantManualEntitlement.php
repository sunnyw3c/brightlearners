<?php

namespace App\Domains\Access\Actions;

use App\Domains\Access\Models\Entitlement;
use App\Domains\Content\Models\LearningResource;
use App\Models\User;
use App\Support\Audit;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;

class GrantManualEntitlement
{
    public function handle(
        User $user,
        LearningResource $resource,
        string $reason,
        ?CarbonInterface $endsAt = null,
        ?int $grantedBy = null
    ): Entitlement {
        if (trim($reason) === '') {
            throw new InvalidArgumentException('A reason is mandatory when granting manual access.');
        }

        $grantedBy = $grantedBy ?? Auth::id() ?? 1;

        $entitlement = Entitlement::query()->create([
            'user_id' => $user->id,
            'resource_id' => $resource->id,
            'source_type' => 'admin_grant',
            'starts_at' => now(),
            'ends_at' => $endsAt,
            'metadata' => [
                'reason' => $reason,
                'granted_by' => $grantedBy,
            ],
        ]);

        Audit::record(
            'entitlement.granted',
            $entitlement,
            null,
            [
                'user_id' => $user->id,
                'resource_id' => $resource->id,
                'ends_at' => $endsAt?->toIso8601String(),
                'reason' => $reason,
            ],
            ['granted_by' => $grantedBy]
        );

        return $entitlement;
    }
}
