<?php

namespace App\Http\Controllers\Account;

use App\Domains\Learning\Models\AssessmentAttempt;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AccountAssessmentController extends Controller
{
    /**
     * Display assessment history and skill progress per profile.
     */
    public function index(Request $request): Response
    {
        $user = $request->user();
        $profiles = $user->learningProfiles()->with('schoolClass')->get();

        $selectedProfileId = $request->query('profile_id')
            ? (int) $request->query('profile_id')
            : $profiles->firstWhere('active', true)?->id ?? $profiles->first()?->id;

        $attempts = AssessmentAttempt::query()
            ->where('user_id', $user->id)
            ->when($selectedProfileId, fn ($q) => $q->where('learning_profile_id', $selectedProfileId))
            ->with(['assessment.schoolClass', 'assessment.subject', 'learningProfile', 'skillScores.skill'])
            ->latest('created_at')
            ->get()
            ->map(function (AssessmentAttempt $attempt) {
                return [
                    'id' => $attempt->id,
                    'assessment_title' => $attempt->assessment->title,
                    'class_name' => $attempt->assessment->schoolClass->name,
                    'subject_name' => $attempt->assessment->subject->name,
                    'child_nickname' => $attempt->learningProfile->nickname,
                    'status' => $attempt->status->value,
                    'parent_feedback' => $attempt->parent_feedback,
                    'completed_at' => $attempt->completed_at?->toFormattedDateString(),
                    'skill_bands' => $attempt->skillScores->map(fn ($score) => [
                        'skill_name' => $score->skill->name,
                        'band' => $score->band->value,
                        'band_label' => $score->band->label(),
                        'color_class' => $score->band->colorClass(),
                    ]),
                ];
            });

        return Inertia::render('account/assessments', [
            'profiles' => $profiles->map(fn ($p) => [
                'id' => $p->id,
                'nickname' => $p->nickname,
                'class_name' => $p->schoolClass?->name,
                'is_active' => $p->id === $selectedProfileId,
            ]),
            'selected_profile_id' => $selectedProfileId,
            'attempts' => $attempts,
        ]);
    }

    /**
     * Save parent feedback on an attempt (easy, comfortable, needs_practice).
     */
    public function saveFeedback(Request $request, AssessmentAttempt $attempt): RedirectResponse
    {
        if ($attempt->user_id !== $request->user()->id) {
            abort(403);
        }

        $validated = $request->validate([
            'feedback' => ['required', 'string', 'in:easy,comfortable,needs_practice'],
        ]);

        $attempt->parent_feedback = $validated['feedback'];
        $attempt->save();

        return back()->with('success', 'Thank you for your feedback!');
    }
}
