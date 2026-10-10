<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Google Search Console Verification
    |--------------------------------------------------------------------------
    |
    | Used to verify ownership in Google Search Console (step 12.13).
    |
    */
    'google_site_verification' => env('GOOGLE_SITE_VERIFICATION'),

    /*
    |--------------------------------------------------------------------------
    | Default Open Graph Image
    |--------------------------------------------------------------------------
    */
    'default_og_image' => env('DEFAULT_OG_IMAGE', '/images/og-default.png'),
];
