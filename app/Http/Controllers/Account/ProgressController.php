<?php

namespace App\Http\Controllers\Account;

use App\Domains\Accounts\Services\ActiveProfileManager;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Learning\Actions\RecordProgress;
use App\Domains\Learning\Enums\ProgressStatus;
use App\Domains\Learning\Models\ResourceProgress;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class ProgressController extends Controller
{
    public function index(Request $request, ActiveProfileManager $profileManager): Response
    {
        $user = $request->user();
        $requestedProfileId = $request->query('profile_id');
        $activeProfile = $profileManager->getActiveProfile(
            $user,
            $requestedProfileId ? (int) $requestedProfileId : null,
        );

        $profiles = $user->learningProfiles()
            ->where('active', true)
            ->with('schoolClass')
            ->get()
            ->map(fn ($p) => [
                'id' => $p->id,
                'nickname' => $p->nickname,
                'class_name' => $p->schoolClass?->name,
                'is_active' => $activeProfile?->id === $p->id,
            ]);

        $progressList = [];
        $stats = [
            'completed' => 0,
            'downloaded' => 0,
            'opened' => 0,
        ];

        if ($activeProfile !== null) {
            $records = ResourceProgress::query()
                ->where('learning_profile_id', $activeProfile->id)
                ->with(['resource.currentVersion'])
                ->latest('updated_at')
                ->get();

            $stats['completed'] = $records->where('status', ProgressStatus::Completed)->count();
            $stats['downloaded'] = $records->where('status', ProgressStatus::Downloaded)->count();
            $stats['opened'] = $records->where('status', ProgressStatus::Opened)->count();

            $progressList = $records->map(fn (ResourceProgress $rp) => [
                'id' => $rp->id,
                'resource_id' => $rp->resource_id,
                'title' => $rp->resource?->title,
                'slug' => $rp->resource?->slug,
                'subject' => $rp->resource?->primarySubjectSlug() ? ucfirst($rp->resource->primarySubjectSlug()) : 'General',
                'type_label' => $rp->resource?->type?->label() ?? 'Resource',
                'status' => $rp->status->value,
                'status_label' => $rp->status->label(),
                'opened_at' => $rp->opened_at?->format('M d, Y'),
                'downloaded_at' => $rp->downloaded_at?->format('M d, Y'),
                'completed_at' => $rp->completed_at?->format('M d, Y'),
                'download_url' => $rp->resource ? route('downloads.show', $rp->resource->slug) : null,
            ]);
        }

        return Inertia::render('account/progress', [
            'active_profile' => $activeProfile ? [
                'id' => $activeProfile->id,
                'nickname' => $activeProfile->nickname,
                'class_name' => $activeProfile->schoolClass?->name,
            ] : null,
            'profiles' => $profiles,
            'stats' => $stats,
            'progress' => $progressList,
        ]);
    }

    public function store(
        Request $request,
        RecordProgress $recordProgress,
        ActiveProfileManager $profileManager,
    ): RedirectResponse {
        $validated = $request->validate([
            'resource_id' => ['required', 'integer', 'exists:resources,id'],
            'status' => ['required', 'string', Rule::in(['opened', 'downloaded', 'completed'])],
            'profile_id' => ['nullable', 'integer', 'exists:learning_profiles,id'],
        ]);

        $user = $request->user();
        $profile = isset($validated['profile_id'])
            ? $profileManager->getActiveProfile($user, (int) $validated['profile_id'])
            : $profileManager->getActiveProfile($user);

        if ($profile === null) {
            abort(422, 'A learning profile is required to record progress.');
        }

        $resource = LearningResource::findOrFail($validated['resource_id']);
        $status = ProgressStatus::from($validated['status']);

        $recordProgress->handle($user, $profile, $resource, $status, 'parent_action');

        return redirect()->back();
    }
}
