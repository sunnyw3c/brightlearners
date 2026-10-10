<?php

namespace App\Domains\Learning\Enums;

enum ProgressStatus: string
{
    case NotStarted = 'not_started';
    case Opened = 'opened';
    case Downloaded = 'downloaded';
    case Completed = 'completed';

    public function rank(): int
    {
        return match ($this) {
            self::NotStarted => 0,
            self::Opened => 1,
            self::Downloaded => 2,
            self::Completed => 3,
        };
    }

    public function canAdvanceTo(self $target): bool
    {
        return $target->rank() > $this->rank();
    }

    public function label(): string
    {
        return match ($this) {
            self::NotStarted => 'Not started',
            self::Opened => 'Opened',
            self::Downloaded => 'Downloaded',
            self::Completed => 'Completed',
        };
    }
}
