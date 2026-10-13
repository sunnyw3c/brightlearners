<?php

namespace App\Http\Controllers\Account;

use App\Domains\Analytics\Enums\EventName;
use App\Domains\Analytics\Services\Analytics;
use App\Domains\Learning\Actions\GenerateRecommendations;
use App\Domains\Learning\Models\Recommendation;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class RecommendationController extends Controller
{
    public function __construct(
        private readonly GenerateRecommendations $generateRecommendations,
    ) {}

    /**
     * Display personal recommendations feed.
     */
    public function index(Request $request): Response
    {
        $user = $request->user();
        $profiles = $user->learningProfiles()->with('schoolClass')->get();

        $selectedProfileId = $request->query('profile_id')
            ? (int) $request->query('profile_id')
            : $profiles->firstWhere('active', true)?->id ?? $profiles->first()?->id;

        $profile = $profiles->firstWhere('id', $selectedProfileId);

        if ($profile !== null) {
            // Generate or refresh recommendations if none exist
            $existingCount = Recommendation::query()
                ->where('learning_profile_id', $profile->id)
                ->count();

            if ($existingCount === 0) {
                $this->generateRecommendations->handle($profile);
            }
        }

        $recommendations = Recommendation::query()
            ->when($selectedProfileId, fn ($q) => $q->where('learning_profile_id', $selectedProfileId))
            ->with(['skill', 'learningProfile.schoolClass'])
            ->latest('generated_at')
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
                    'target_title' => $target?->title ?? $target?->name ?? 'Recommended Worksheet',
                    'target_summary' => $target?->summary ?? $target?->short_description ?? 'Practice worksheet for skill building.',
                    'target_url' => $rec->target_type === 'resource'
                        ? "/free/{$target?->primaryClassSlug()}/{$target?->primarySubjectSlug()}/{$target?->slug}"
                        : "/shop/{$target?->primaryClass?->slug}/{$target?->slug}",
                    'clicked_at' => $rec->clicked_at?->toIso8601String(),
                    'acted_at' => $rec->acted_at?->toIso8601String(),
                ];
            });

        return Inertia::render('account/recommendations', [
            'profiles' => $profiles->map(fn ($p) => [
                'id' => $p->id,
                'nickname' => $p->nickname,
                'class_name' => $p->schoolClass?->name,
                'is_active' => $p->id === $selectedProfileId,
            ]),
            'selected_profile_id' => $selectedProfileId,
            'recommendations' => $recommendations,
        ]);
    }

    /**
     * Record click on recommendation.
     */
    public function recordClick(Request $request, Recommendation $recommendation): JsonResponse
    {
        // Authorization: profile must belong to authenticated user
        if ($recommendation->learningProfile?->user_id !== $request->user()->id) {
            abort(403);
        }

        $recommendation->clicked_at = now();
        $recommendation->save();

        Analytics::track(
            name: EventName::RecommendationClicked,
            properties: [
                'recommendation_id' => $recommendation->id,
                'target' => $recommendation->target_type.':'.$recommendation->target_id,
            ],
            userId: $request->user()->id,
            source: 'server',
        );

        return response()->json(['status' => 'ok']);
    }

    /**
     * Record recommendation acted on.
     */
    public function recordActed(Request $request, Recommendation $recommendation): JsonResponse
    {
        if ($recommendation->learningProfile?->user_id !== $request->user()->id) {
            abort(403);
        }

        $recommendation->acted_at = now();
        $recommendation->save();

        return response()->json(['status' => 'ok']);
    }
}
