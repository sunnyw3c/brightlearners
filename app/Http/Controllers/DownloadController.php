<?php

namespace App\Http\Controllers;

use App\Domains\Access\Actions\GenerateSignedDownload;
use App\Domains\Access\Enums\AccessDecisionReason;
use App\Domains\Access\Services\AccessService;
use App\Domains\Content\Models\LearningResource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DownloadController extends Controller
{
    public function __invoke(
        Request $request,
        LearningResource $resource,
        AccessService $access,
        GenerateSignedDownload $generateSignedDownload,
    ): RedirectResponse {
        $decision = $access->canAccess($request->user(), $resource);

        abort_unless(
            $decision->allowed,
            $decision->reason === AccessDecisionReason::NotPublished ? 404 : 403,
        );

        $variant = $request->query('variant', 'colour');

        $temporaryUrl = $generateSignedDownload->handle(
            decision: $decision,
            resource: $resource,
            variant: is_string($variant) ? $variant : 'colour',
            user: $request->user(),
        );

        return redirect()->away($temporaryUrl);
    }
}
