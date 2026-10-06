<?php

namespace App\Domains\Content\Enums;

enum ReviewType: string
{
    case Educational = 'educational';
    case AnswerVerification = 'answer_verification';
    case DesignPrint = 'design_print';
}
