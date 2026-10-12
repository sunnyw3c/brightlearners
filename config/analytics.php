<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Analytics Data Retention
    |--------------------------------------------------------------------------
    |
    | Number of days to retain raw first-party analytics events. Daily metrics
    | are kept permanently.
    |
    */
    'retention_days' => (int) env('ANALYTICS_RETENTION_DAYS', 90),

    /*
    |--------------------------------------------------------------------------
    | Google Analytics 4 Measurement ID
    |--------------------------------------------------------------------------
    |
    | Secondary view external analytics tag. Loaded in production only.
    |
    */
    'ga4_id' => env('GA4_MEASUREMENT_ID', env('GOOGLE_ANALYTICS_ID', null)),

    /*
    |--------------------------------------------------------------------------
    | Queue Configuration
    |--------------------------------------------------------------------------
    |
    | Queue connection and queue name for async analytics processing.
    |
    */
    'queue' => env('ANALYTICS_QUEUE', 'default'),
];
