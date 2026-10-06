<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Learning profiles
    |--------------------------------------------------------------------------
    |
    | A parent account may hold a limited number of learning profiles (one
    | per child). The class list comes from the real `classes` table
    | (Phase 3); the avatar list is a set of built-in keys, not uploaded
    | images.
    |
    */

    'max_learning_profiles' => (int) env('MAX_LEARNING_PROFILES', 5),

    'avatar_keys' => [
        'fox',
        'owl',
        'panda',
        'rabbit',
        'lion',
        'elephant',
    ],

    /*
    |--------------------------------------------------------------------------
    | Support owner
    |--------------------------------------------------------------------------
    |
    | Where an account-deletion request is sent. D-10 (support owner
    | assigned) is still open, so this is a placeholder address until the
    | business names one; see docs/tracking/decisions.md.
    |
    */

    'support_owner_email' => env('SUPPORT_OWNER_EMAIL', 'support@brightlearners.test'),

    /*
    |--------------------------------------------------------------------------
    | Auth rate limits
    |--------------------------------------------------------------------------
    |
    | Fortify only ships a configurable limiter for login, two-factor and
    | passkeys. Registration and password-reset requests are throttled by
    | these named limiters, attached to Fortify's routes via a RouteMatched
    | listener (see FortifyServiceProvider). Keyed by IP and email.
    |
    */

    'rate_limits' => [
        'registration' => [
            'max_attempts' => (int) env('REGISTRATION_RATE_LIMIT', 5),
            'decay_minutes' => 1,
        ],
        'password_reset' => [
            'max_attempts' => (int) env('PASSWORD_RESET_RATE_LIMIT', 5),
            'decay_minutes' => 1,
        ],
    ],

];
