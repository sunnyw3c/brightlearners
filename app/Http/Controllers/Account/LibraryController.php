<?php

namespace App\Http\Controllers\Account;

use App\Domains\Access\Models\Download;
use App\Domains\Access\Models\Entitlement;
use App\Domains\Access\Services\AccessService;
use App\Domains\Accounts\Services\ActiveProfileManager;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Learning\Actions\RecordProgress;
use App\Domains\Learning\Enums\ProgressStatus;
use App\Domains\Learning\Models\ResourceProgress;
use App\Domains\Membership\Contracts\MembershipChecker;
use App\Domains\Membership\Enums\LearningProgramStatus;
use App\Domains\Membership\Models\LearningItem;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class LibraryController extends Controller
{
    public function index(Request $request, MembershipChecker $membershipChecker): Response
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

        // 2. Membership Resources (Released weekly items for user's active children)
        $membershipResources = collect();
        if ($membershipChecker->hasActiveMembership($user)) {
            $userClassIds = $user->learningProfiles()
                ->where('active', true)
                ->whereNotNull('class_id')
                ->pluck('class_id');

            $items = LearningItem::query()
                ->whereHas('week', function ($q) use ($userClassIds) {
                    $q->where('release_at', '<=', now())
                        ->whereNotNull('released_at')
                        ->whereHas('program', fn ($pq) => $pq->where('status', LearningProgramStatus::Published)->whereIn('class_id', $userClassIds));
                })
                ->with(['resource.currentVersion'])
                ->get();

            $membershipResources = $items->pluck('resource')->filter()->unique('id')->values()->map(function ($resource) use ($user) {
                $currentVersion = $resource->currentVersion;
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
                    'download_url' => route('downloads.show', $resource->slug),
                ];
            });
        }

        // 3. Completed Resources
        $completedResourceIds = ResourceProgress::query()
            ->where('user_id', $user->id)
            ->where('status', ProgressStatus::Completed)
            ->pluck('resource_id')
            ->unique();

        $completedResources = LearningResource::query()
            ->whereIn('id', $completedResourceIds)
            ->with('currentVersion')
            ->get()
            ->map(function ($resource) {
                return [
                    'id' => $resource->id,
                    'title' => $resource->title,
                    'slug' => $resource->slug,
                    'type' => $resource->type->value,
                    'type_label' => $resource->type->label(),
                    'summary' => $resource->summary,
                    'current_version' => $resource->currentVersion?->version,
                    'has_answer_key' => $resource->has_answer_key,
                    'low_ink_available' => $resource->low_ink_available,
                    'download_url' => route('downloads.show', $resource->slug),
                ];
            });

        // 4. Free Resources Downloaded
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
            'membership' => $membershipResources,
            'completed' => $completedResources,
            'free' => $freeResources,
        ]);
    }

    public function show(
        Request $request,
        LearningResource $resource,
        AccessService $access,
        ActiveProfileManager $profileManager,
        RecordProgress $recordProgress,
    ): Response {
        $user = $request->user();
        $decision = $access->canAccess($user, $resource);

        abort_unless($decision->allowed, 403, 'You do not have access to this resource.');

        $resource->loadMissing(['currentVersion', 'versions']);

        // Record 'opened' status for the active profile if available
        $profile = $profileManager->getActiveProfile($user);
        if ($profile !== null) {
            try {
                $recordProgress->handle(
                    $user,
                    $profile,
                    $resource,
                    ProgressStatus::Opened,
                    'library_detail'
                );
            } catch (Throwable) {
            }
        }

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
