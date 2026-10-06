<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Uploads
    |--------------------------------------------------------------------------
    |
    | PDF only, checked by real MIME type, not the extension. Size is in
    | megabytes (docs/plan/phase-04-resource-engine.md, "Upload rules").
    |
    */

    'max_upload_mb' => (int) env('CONTENT_MAX_UPLOAD_MB', 50),

    /*
    |--------------------------------------------------------------------------
    | Previews
    |--------------------------------------------------------------------------
    |
    | Only the first pages of a PDF are rendered to a preview image. A paid
    | resource is never previewed in full.
    |
    */

    'preview_pages' => (int) env('CONTENT_PREVIEW_PAGES', 3),

    /*
    |--------------------------------------------------------------------------
    | Review queue
    |--------------------------------------------------------------------------
    |
    | A version waiting longer than this for any review appears on the
    | admin "overdue review" list.
    |
    */

    'overdue_review_days' => (int) env('CONTENT_OVERDUE_REVIEW_DAYS', 5),

];
