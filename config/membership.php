<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Membership Redownload Policy
    |--------------------------------------------------------------------------
    |
    | Dictates whether a member whose subscription has expired can still
    | re-download packs released during their active membership period.
    |
    */
    'redownload_after_expiry' => env('MEMBERSHIP_REDOWNLOAD_AFTER_EXPIRY', false),
    'pilot_starts_at' => env('MEMBERSHIP_PILOT_STARTS_AT', null),
    'require_next_month_readiness' => env('MEMBERSHIP_REQUIRE_NEXT_MONTH_READINESS', false),
];
