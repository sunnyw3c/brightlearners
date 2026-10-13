<?php

namespace App\Domains\Learning\Enums;

enum SkillBand: string
{
    case NeedsPractice = 'needs_practice';
    case Comfortable = 'comfortable';
    case Confident = 'confident';

    public static function fromPercentage(float|int $percentage): self
    {
        if ($percentage < 60) {
            return self::NeedsPractice;
        }

        if ($percentage < 85) {
            return self::Comfortable;
        }

        return self::Confident;
    }

    public function label(): string
    {
        return match ($this) {
            self::NeedsPractice => 'Needs Practice',
            self::Comfortable => 'Comfortable',
            self::Confident => 'Confident',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::NeedsPractice => 'Benefits from focused revision and guided practice worksheets.',
            self::Comfortable => 'Good working understanding. Regular reinforcement keeps skills sharp.',
            self::Confident => 'Strong mastery demonstrated. Ready for extension and challenge questions.',
        };
    }

    public function colorClass(): string
    {
        return match ($this) {
            self::NeedsPractice => 'amber',
            self::Comfortable => 'blue',
            self::Confident => 'emerald',
        };
    }
}
