<?php

namespace App\Http\Controllers;

use App\Domains\Analytics\Enums\EventName;
use App\Domains\Analytics\Services\Analytics;
use App\Domains\Learning\Actions\ScoreAssessmentAttempt;
use App\Domains\Learning\Enums\AssessmentStatus;
use App\Domains\Learning\Enums\AttemptStatus;
use App\Domains\Learning\Events\AssessmentStarted;
use App\Domains\Learning\Models\Assessment;
use App\Domains\Learning\Models\AssessmentAttempt;
use App\Domains\Learning\Models\Recommendation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AssessmentController extends Controller
{
    public function __construct(
        private readonly ScoreAssessmentAttempt $scoreAssessmentAttempt,
    ) {}

    /**
     * Show starter check / skill assessment.
     */
    public function show(Request $request, string $slug): Response
    {
        $assessment = Assessment::query()
            ->where('slug', $slug)
            ->where('status', AssessmentStatus::Published)
            ->with(['schoolClass', 'subject', 'sections.questions'])
            ->firstOrFail();

        $user = $request->user();
        $profiles = $user ? $user->learningProfiles()->where('active', true)->get() : collect();

        // Transform questions to hide answers from the taking screen
        $sections = $assessment->sections->map(function ($section) {
            return [
                'id' => $section->id,
                'title' => $section->title,
                'sort_order' => $section->sort_order,
                'questions' => $section->questions->map(function ($question) {
                    $options = $question->answer_config['options'] ?? [];

                    return [
                        'id' => $question->id,
                        'prompt' => $question->prompt,
                        'type' => $question->type->value,
                        'difficulty' => $question->difficulty->value,
                        'points' => $question->points,
                        'options' => $options,
                    ];
                }),
            ];
        });

        return Inertia::render('assessments/show', [
            'assessment' => [
                'id' => $assessment->id,
                'title' => $assessment->title,
                'slug' => $assessment->slug,
                'description' => $assessment->description,
                'version' => $assessment->version,
                'class_name' => $assessment->schoolClass->name,
                'subject_name' => $assessment->subject->name,
                'sections' => $sections,
            ],
            'profiles' => $profiles->map(fn ($p) => [
                'id' => $p->id,
                'nickname' => $p->nickname,
                'class_id' => $p->class_id,
            ]),
        ]);
    }

    /**
     * Start an assessment attempt for a child profile.
     */
    public function startAttempt(Request $request, string $slug): RedirectResponse
    {
        $assessment = Assessment::query()
            ->where('slug', $slug)
            ->where('status', AssessmentStatus::Published)
            ->firstOrFail();

        $validated = $request->validate([
            'profile_id' => ['required', 'exists:learning_profiles,id'],
        ]);

        $profile = $request->user()->learningProfiles()->findOrFail($validated['profile_id']);

        $attempt = AssessmentAttempt::query()->create([
            'user_id' => $request->user()->id,
            'learning_profile_id' => $profile->id,
            'assessment_id' => $assessment->id,
            'assessment_version' => $assessment->version,
            'status' => AttemptStatus::InProgress,
            'started_at' => now(),
        ]);

        Analytics::track(
            name: EventName::AssessmentStarted,
            properties: [
                'assessment_id' => $assessment->id,
                'profile_id' => $profile->id,
            ],
            userId: $request->user()->id,
            source: 'server',
        );

        AssessmentStarted::dispatch($attempt);

        return redirect()->route('assessments.show', ['slug' => $slug, 'attempt' => $attempt->id]);
    }

    /**
     * Submit and score the attempt.
     */
    public function submitAttempt(Request $request, AssessmentAttempt $attempt): RedirectResponse
    {
        if ($attempt->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized access to this attempt.');
        }

        $validated = $request->validate([
            'answers' => ['present', 'array'],
        ]);

        $this->scoreAssessmentAttempt->handle($attempt, $validated['answers']);

        return redirect()->route('assessments.result', ['attempt' => $attempt->id]);
    }

    /**
     * View assessment result and recommendations.
     */
    public function result(Request $request, AssessmentAttempt $attempt): Response
    {
        // Security check: an assessment result cannot be reached from another user's profile
        if ($attempt->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized access to this assessment result.');
        }

        $attempt->loadMissing([
            'assessment.schoolClass',
            'assessment.subject',
            'learningProfile',
            'skillScores.skill',
            'answers.question',
        ]);

        $recommendations = Recommendation::query()
            ->where('learning_profile_id', $attempt->learning_profile_id)
            ->with(['skill'])
            ->latest('generated_at')
            ->take(6)
            ->get()
            ->map(function (Recommendation $rec) {
                $target = $rec->target();

                return [
                    'id' => $rec->id,
                    'skill_name' => $rec->skill?->name ?? 'Skill',
                    'reason_code' => $rec->reason_code,
                    'reason_sentence' => $rec->reasonSentence(),
                    'target_type' => $rec->target_type,
                    'target_id' => $rec->target_id,
                    'target_title' => $target?->title ?? $target?->name ?? 'Learning Resource',
                    'target_url' => $rec->target_type === 'resource'
                        ? "/free/{$target?->primaryClassSlug()}/{$target?->primarySubjectSlug()}/{$target?->slug}"
                        : "/shop/{$target?->primaryClass?->slug}/{$target?->slug}",
                    'clicked_at' => $rec->clicked_at?->toIso8601String(),
                    'acted_at' => $rec->acted_at?->toIso8601String(),
                ];
            });

        return Inertia::render('assessments/result', [
            'attempt' => [
                'id' => $attempt->id,
                'assessment_title' => $attempt->assessment->title,
                'class_name' => $attempt->assessment->schoolClass->name,
                'subject_name' => $attempt->assessment->subject->name,
                'child_nickname' => $attempt->learningProfile->nickname,
                'completed_at' => $attempt->completed_at?->toFormattedDateString(),
                'parent_feedback' => $attempt->parent_feedback,
                'skill_scores' => $attempt->skillScores->map(fn ($s) => [
                    'id' => $s->id,
                    'skill_name' => $s->skill->name,
                    'band' => $s->band->value,
                    'band_label' => $s->band->label(),
                    'band_description' => $s->band->description(),
                    'color_class' => $s->band->colorClass(),
                ]),
                'answers_count' => $attempt->answers->count(),
                'correct_count' => $attempt->answers->where('is_correct', true)->count(),
            ],
            'recommendations' => $recommendations,
        ]);
    }
}
