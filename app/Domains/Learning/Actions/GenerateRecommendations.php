<?php

namespace App\Domains\Learning\Actions;

use App\Domains\Accounts\Models\LearningProfile;
use App\Domains\Content\Enums\ResourceStatus;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Learning\Enums\RecommendationReason;
use App\Domains\Learning\Enums\SkillBand;
use App\Domains\Learning\Models\Recommendation;
use App\Domains\Learning\Models\SkillScore;
use Illuminate\Support\Collection;

class GenerateRecommendations
{
    /**
     * Generate deterministic recommendations for a learning profile.
     * Rule: Class + weak skill + available reviewed resource.
     * Never recommends another class's content or unpublished items.
     *
     * @return Collection<int, Recommendation>
     */
    public function handle(LearningProfile $profile): Collection
    {
        $classId = $profile->class_id;
        if (! $classId) {
            return collect();
        }

        // Fetch the latest skill scores for this profile
        $latestScores = SkillScore::query()
            ->where('learning_profile_id', $profile->id)
            ->with('skill')
            ->orderBy('calculated_at', 'desc')
            ->get()
            ->unique('skill_id');

        $weakScores = $latestScores->filter(fn (SkillScore $score) => $score->band === SkillBand::NeedsPractice);

        $recommendations = collect();

        // 1. Weak skills first
        foreach ($weakScores as $weakScore) {
            $resource = LearningResource::query()
                ->where('status', ResourceStatus::Published)
                ->whereHas('skillMappings', function ($q) use ($classId, $weakScore) {
                    $q->where('class_id', $classId)
                        ->where('skill_id', $weakScore->skill_id);
                })
                ->latest('published_at')
                ->first();

            if ($resource !== null) {
                $rec = Recommendation::query()->updateOrCreate(
                    [
                        'learning_profile_id' => $profile->id,
                        'skill_id' => $weakScore->skill_id,
                        'target_type' => 'resource',
                        'target_id' => $resource->id,
                    ],
                    [
                        'reason_code' => RecommendationReason::WeakSkillPractice->value,
                        'generated_at' => now(),
                    ]
                );

                $recommendations->push($rec);
            }
        }

        // 2. If no weak skills or less than 3 recommendations, add next step or core recommendations for the class
        if ($recommendations->count() < 3) {
            $masteredSkills = $latestScores->filter(fn (SkillScore $score) => $score->band === SkillBand::Confident);

            foreach ($masteredSkills as $masteredScore) {
                if ($recommendations->count() >= 3) {
                    break;
                }

                $resource = LearningResource::query()
                    ->where('status', ResourceStatus::Published)
                    ->whereHas('skillMappings', function ($q) use ($classId, $masteredScore) {
                        $q->where('class_id', $classId)
                            ->where('skill_id', $masteredScore->skill_id);
                    })
                    ->whereNotIn('id', $recommendations->pluck('target_id')->toArray())
                    ->latest('published_at')
                    ->first();

                if ($resource !== null) {
                    $rec = Recommendation::query()->updateOrCreate(
                        [
                            'learning_profile_id' => $profile->id,
                            'skill_id' => $masteredScore->skill_id,
                            'target_type' => 'resource',
                            'target_id' => $resource->id,
                        ],
                        [
                            'reason_code' => RecommendationReason::NextStepLearning->value,
                            'generated_at' => now(),
                        ]
                    );

                    $recommendations->push($rec);
                }
            }
        }

        // 3. Fallback: Core resources for the child's class if still needed
        if ($recommendations->isEmpty()) {
            $coreResources = LearningResource::query()
                ->where('status', ResourceStatus::Published)
                ->whereHas('skillMappings', function ($q) use ($classId) {
                    $q->where('class_id', $classId);
                })
                ->with(['skillMappings.skill'])
                ->latest('published_at')
                ->take(3)
                ->get();

            foreach ($coreResources as $resource) {
                $primarySkill = $resource->skillMappings->firstWhere('class_id', $classId)?->skill;
                if (! $primarySkill) {
                    continue;
                }

                $rec = Recommendation::query()->updateOrCreate(
                    [
                        'learning_profile_id' => $profile->id,
                        'skill_id' => $primarySkill->id,
                        'target_type' => 'resource',
                        'target_id' => $resource->id,
                    ],
                    [
                        'reason_code' => RecommendationReason::CurriculumCore->value,
                        'generated_at' => now(),
                    ]
                );

                $recommendations->push($rec);
            }
        }

        return $recommendations;
    }
}
