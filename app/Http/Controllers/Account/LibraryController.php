<?php

namespace App\Http\Controllers\Account;

use App\Domains\Access\Models\Download;
use App\Domains\Access\Models\Entitlement;
use App\Domains\Access\Services\AccessService;
use App\Domains\Content\Models\LearningResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class LibraryController extends Controller
{
    public function index(Request $request): Response
    {
        $user = $request->user();

        // 1. Purchased / Entitled Resources
        $entitlements = Entitlement::query()
            ->where('user_id', $user->id)
            ->whereNull('revoked_at')
            ->where('starts_at', '<=', now())
            ->where(fn ($q) => $q->whereNull('ends_at')->orWhere('ends_at', '>', now()))
            ->with(['resource.currentVersion'])
            ->latest('id')
            ->get();

        $purchasedResources = $entitlements->map(function (Entitlement $entitlement) use ($user) {
            $resource = $entitlement->resource;
            $currentVersion = $resource?->currentVersion;

            $lastDownload = Download::query()
                ->where('user_id', $user->id)
                ->where('resource_id', $resource->id)
                ->latest('downloaded_at')
                ->first();

            $hasCorrectionNotice = false;
            if ($lastDownload && $currentVersion && $lastDownload->resource_version_id !== $currentVersion->id) {
                $hasCorrectionNotice = true;
            }

            return [
                'id' => $resource->id,
                'title' => $resource->title,
                'slug' => $resource->slug,
                'type' => $resource->type->value,
                'type_label' => $resource->type->label(),
                'summary' => $resource->summary,
                'current_version' => $currentVersion?->version,
                'has_answer_key' => $resource->has_answer_key,
                'low_ink_available' => $resource->low_ink_available,
                'has_correction_notice' => $hasCorrectionNotice,
                'granted_at' => $entitlement->starts_at->format('M d, Y'),
                'download_url' => route('downloads.show', $resource->slug),
            ];
        });

        // 2. Free Resources Downloaded
        $freeDownloads = Download::query()
            ->where('user_id', $user->id)
            ->where('access_source', 'free')
            ->with(['resource.currentVersion'])
            ->latest('downloaded_at')
            ->get()
            ->unique('resource_id')
            ->values();

        $freeResources = $freeDownloads->map(function (Download $download) {
            $resource = $download->resource;
            $currentVersion = $resource?->currentVersion;

            return [
                'id' => $resource->id,
                'title' => $resource->title,
                'slug' => $resource->slug,
                'type' => $resource->type->value,
                'type_label' => $resource->type->label(),
                'summary' => $resource->summary,
                'current_version' => $currentVersion?->version,
                'downloaded_at' => $download->downloaded_at->format('M d, Y'),
                'download_url' => route('downloads.show', $resource->slug),
            ];
        });

        return Inertia::render('account/library/index', [
            'purchased' => $purchasedResources,
            'membership' => [], // Reserved for Phase 10
            'free' => $freeResources,
        ]);
    }

    public function show(Request $request, LearningResource $resource, AccessService $access): Response
    {
        $user = $request->user();
        $decision = $access->canAccess($user, $resource);

        abort_unless($decision->allowed, 403, 'You do not have access to this resource.');

        $resource->loadMissing(['currentVersion', 'versions']);

        $lastDownload = Download::query()
            ->where('user_id', $user->id)
            ->where('resource_id', $resource->id)
            ->latest('downloaded_at')
            ->first();

        $hasCorrectionNotice = false;
        if ($lastDownload && $resource->currentVersion && $lastDownload->resource_version_id !== $resource->currentVersion->id) {
            $hasCorrectionNotice = true;
        }

        return Inertia::render('account/library/show', [
            'resource' => [
                'id' => $resource->id,
                'title' => $resource->title,
                'slug' => $resource->slug,
                'type' => $resource->type->value,
                'type_label' => $resource->type->label(),
                'summary' => $resource->summary,
                'description' => $resource->description,
                'learning_objective' => $resource->learning_objective,
                'difficulty' => $resource->difficulty,
                'estimated_minutes' => $resource->estimated_minutes,
                'page_count' => $resource->page_count,
                'has_answer_key' => $resource->has_answer_key,
                'low_ink_available' => $resource->low_ink_available,
                'current_version' => $resource->currentVersion?->version,
                'has_correction_notice' => $hasCorrectionNotice,
                'access_source' => $decision->source,
                'download_url' => route('downloads.show', $resource->slug),
            ],
        ]);
    }
}
