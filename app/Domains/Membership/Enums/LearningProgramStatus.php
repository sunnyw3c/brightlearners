<?php

namespace App\Domains\Membership\Enums;

enum LearningProgramStatus: string
{
    case Draft = 'draft';
    case Ready = 'ready';
    case Published = 'published';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Ready => 'Ready',
            self::Published => 'Published',
        };
    }
}
