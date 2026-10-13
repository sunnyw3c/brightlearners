<?php

namespace App\Domains\Learning\Enums;

enum RecommendationReason: string
{
    case WeakSkillPractice = 'weak_skill_practice';
    case NextStepLearning = 'next_step_learning';
    case CurriculumCore = 'curriculum_core';

    public function toSentence(string $skillName, ?string $className = null): string
    {
        return match ($this) {
            self::WeakSkillPractice => "Recommended to strengthen practice in {$skillName} based on recent skill check.",
            self::NextStepLearning => "Great progress on {$skillName}! Here is the next activity to continue learning.",
            self::CurriculumCore => $className
                ? "Core foundational activity for Class {$className}."
                : 'Core foundational learning activity.',
        };
    }
}
