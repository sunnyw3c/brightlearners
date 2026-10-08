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
];
