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
}
