<?php

namespace App\Domains\Learning\Actions;

use App\Domains\Accounts\Models\LearningProfile;
use App\Domains\Curriculum\Models\Skill;
use App\Domains\Learning\Models\ParentGuidanceLog;
use App\Models\User;

class GenerateParentGuidance
{
    private const RESTRICTED_TERMS = [
        'adhd', 'autism', 'dyslexia', 'disorder', 'clinical',
        'diagnosis', 'diagnose', 'therapy', 'medication', 'psychiatrist',
    ];

    /**
     * Generate safe, curriculum-grounded guidance for a parent.
     * Restricts scope to reviewed curriculum and rejects diagnosis/medical questions.
     * Logs response and includes clear route to human support.
     */
    public function handle(User $user, string $query, ?LearningProfile $profile = null): ParentGuidanceLog
    {
        $normalizedQuery = strtolower($query);

        // 1. Safeguard check: Diagnostic / Medical restriction
        foreach (self::RESTRICTED_TERMS as $term) {
            if (str_contains($normalizedQuery, $term)) {
                $guidance = 'BrightLearners provides informal curriculum worksheets and educational practice. We do not provide clinical assessments, developmental diagnoses, or medical evaluations. For developmental or learning condition concerns, please consult a qualified healthcare professional or educational psychologist. If you would like guidance on how to use our worksheets with your child, please contact our support team at support@brightlearners.in.';

                return ParentGuidanceLog::query()->create([
                    'user_id' => $user->id,
                    'profile_id' => $profile?->id,
                    'query' => $query,
                    'guidance' => $guidance,
                    'safety_flags' => [
                        'flag' => 'clinical_diagnostic_restriction',
                        'detected_term' => $term,
                    ],
                    'escalated' => true,
                ]);
            }
        }

        // 2. Curriculum-grounded pedagogical guidance
        $className = $profile?->schoolClass?->name ?? 'Primary';
        $childName = $profile?->nickname ?? 'your child';

        // Search for relevant curriculum skill
        $skill = Skill::query()
            ->where(function ($q) use ($normalizedQuery) {
                $words = explode(' ', $normalizedQuery);
                foreach ($words as $word) {
                    if (strlen($word) > 3) {
                        $q->orWhere('name', 'like', "%{$word}%");
                    }
                }
            })
            ->first();

        $guidance = "For {$childName} in {$className}: We recommend establishing a regular 15-minute daily practice routine. ";
        if ($skill) {
            $guidance .= "Focus on the foundational skill '{$skill->name}' ({$skill->learning_objective}). Start with concrete examples before moving to paper exercises. ";
        } else {
            $guidance .= 'Focus on foundational concept reinforcement using short, hands-on worksheets. ';
        }
        $guidance .= 'Avoid screen fatigue and celebrate small milestones. Need tailored assistance? Contact our team anytime at support@brightlearners.in.';

        return ParentGuidanceLog::query()->create([
            'user_id' => $user->id,
            'profile_id' => $profile?->id,
            'query' => $query,
            'guidance' => $guidance,
            'safety_flags' => null,
            'escalated' => false,
        ]);
    }
}
