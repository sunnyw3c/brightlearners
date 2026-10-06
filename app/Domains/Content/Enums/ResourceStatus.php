<?php

namespace App\Domains\Content\Enums;

/**
 * The workflow in docs/plan/phase-04-resource-engine.md, step 4.6:
 * Draft -> EducationalReview -> AnswerVerification -> DesignPrintReview ->
 * Approved -> Scheduled -> Published -> Archived. Any review state can
 * fall back to Draft when a reviewer requests changes.
 */
enum ResourceStatus: string
{
    case Draft = 'draft';
    case EducationalReview = 'educational_review';
    case AnswerVerification = 'answer_verification';
    case DesignPrintReview = 'design_print_review';
    case Approved = 'approved';
    case Scheduled = 'scheduled';
    case Published = 'published';
    case Archived = 'archived';
}
