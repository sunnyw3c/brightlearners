<?php

namespace App\Http\Controllers;

use App\Domains\Access\Enums\AccessDecisionReason;
use App\Domains\Access\Events\ResourceDownloaded;
use App\Domains\Access\Services\AccessService;
use App\Domains\Content\Models\LearningResource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * `/download/{resource}` (step 5.5): an access check, then a redirect to
 * a short-lived temporary URL. The page never contains a storage URL.
 * Today `AccessService` only knows the free-resource rule; Phase 9 adds
 * entitlements to the same service, and this controller does not change.
 */
class DownloadController extends Controller
{
    public function __invoke(Request $request, LearningResource $resource, AccessService $access): RedirectResponse
    {
        $decision = $access->canAccess($request->user(), $resource);

        abort_unless(
            $decision->allowed,
            $decision->reason === AccessDecisionReason::NotPublished ? 404 : 403,
        );

        $version = $resource->currentVersion;

        abort_if($version === null, 404);

        $temporaryUrl = Storage::disk('resources')->temporaryUrl($version->file_path, now()->addMinutes(5));

        ResourceDownloaded::dispatch(
            $resource,
            $version,
            $request->user()?->id,
            $request->user() === null ? $request->session()->getId() : null,
        );

        return redirect()->away($temporaryUrl);
    }
}
