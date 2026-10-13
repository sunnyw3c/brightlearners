<?php

namespace App\Domains\Learning\Enums;

enum QuestionType: string
{
    case SingleChoice = 'single_choice';
    case MultipleChoice = 'multiple_choice';
    case Text = 'text';

    public function label(): string
    {
        return match ($this) {
            self::SingleChoice => 'Single Choice',
            self::MultipleChoice => 'Multiple Choice',
            self::Text => 'Text Answer',
        };
    }
}
