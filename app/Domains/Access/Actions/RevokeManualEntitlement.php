<?php

namespace App\Domains\Access\Actions;

use App\Domains\Access\Models\Entitlement;
use App\Support\Audit;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;

class RevokeManualEntitlement
{
    public function handle(Entitlement $entitlement, string $reason, ?int $revokedBy = null): Entitlement
    {
        if (trim($reason) === '') {
            throw new InvalidArgumentException('A reason is mandatory when revoking manual access.');
        }

        $revokedBy = $revokedBy ?? Auth::id() ?? 1;

        $metadata = $entitlement->metadata ?? [];
        $metadata['revocation_reason'] = $reason;
        $metadata['revoked_by'] = $revokedBy;

        $entitlement->update([
            'revoked_at' => now(),
            'metadata' => $metadata,
        ]);

        Audit::record(
            'entitlement.revoked',
            $entitlement,
            null,
            [
                'reason' => $reason,
                'revoked_at' => now()->toIso8601String(),
            ],
            ['revoked_by' => $revokedBy]
        );

        return $entitlement;
    }
}
