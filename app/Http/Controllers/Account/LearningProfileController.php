<?php

namespace App\Http\Controllers\Account;

use App\Domains\Accounts\Models\LearningProfile;
use App\Domains\Curriculum\Models\SchoolClass;
use App\Http\Controllers\Controller;
use App\Http\Requests\Account\StoreLearningProfileRequest;
use App\Http\Requests\Account\UpdateLearningProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class LearningProfileController extends Controller
{
    /**
     * List the authenticated parent's active learning profiles.
     */
    public function index(Request $request): Response
    {
        Gate::authorize('viewAny', LearningProfile::class);

        return Inertia::render('account/profiles/index', [
            'profiles' => $request->user()->learningProfiles()
                ->where('active', true)
                ->latest()
                ->get(),
            'classes' => SchoolClass::query()->active()->orderBy('sort_order')->get(['id', 'name']),
            'avatarKeys' => config('account.avatar_keys'),
            'maxProfiles' => config('account.max_learning_profiles'),
        ]);
    }

    /**
     * Create a new learning profile for the authenticated parent.
     */
    public function store(StoreLearningProfileRequest $request): RedirectResponse
    {
        $request->user()->learningProfiles()->create($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Learning profile added.')]);

        return to_route('account.profiles.index');
    }

    /**
     * Update an existing learning profile.
     */
    public function update(UpdateLearningProfileRequest $request, LearningProfile $profile): RedirectResponse
    {
        $profile->update($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Learning profile updated.')]);

        return to_route('account.profiles.index');
    }

    /**
     * Deactivate a learning profile. Profiles are never hard-deleted.
     */
    public function destroy(Request $request, LearningProfile $profile): RedirectResponse
    {
        Gate::authorize('delete', $profile);

        $profile->update(['active' => false]);

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Learning profile removed.')]);

        return to_route('account.profiles.index');
    }
}
