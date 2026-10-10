<?php

namespace App\Domains\Content\Enums;

enum ResourceType: string
{
    case Worksheet = 'worksheet';
    case Activity = 'activity';
    case Game = 'game';
    case Reading = 'reading';
    case ParentGuide = 'parent_guide';
    case StarterCheck = 'starter_check';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Worksheet => 'Worksheet',
            self::Activity => 'Activity',
            self::Game => 'Game',
            self::Reading => 'Reading',
            self::ParentGuide => 'Parent Guide',
            self::StarterCheck => 'Starter Check',
            self::Other => 'Other',
        };
    }
}
