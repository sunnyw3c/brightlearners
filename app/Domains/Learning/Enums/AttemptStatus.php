<?php

namespace App\Domains\Learning\Enums;

enum AttemptStatus: string
{
    case InProgress = 'in_progress';
    case Completed = 'completed';
    case Abandoned = 'abandoned';

    public function label(): string
    {
        return match ($this) {
            self::InProgress => 'In Progress',
            self::Completed => 'Completed',
            self::Abandoned => 'Abandoned',
        };
    }
}
