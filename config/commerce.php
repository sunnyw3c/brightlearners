<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Order Expiry
    |--------------------------------------------------------------------------
    |
    | Minutes before a pending_payment order expires and is automatically
    | cancelled by the scheduled orders:expire-pending command.
    |
    */
    'order_expiry_minutes' => (int) env('COMMERCE_ORDER_EXPIRY_MINUTES', 30),

    /*
    |--------------------------------------------------------------------------
    | Order Number Prefix
    |--------------------------------------------------------------------------
    |
    | Prefix for human-readable order numbers (e.g. BL-2026-000123).
    |
    */
    'order_number_prefix' => env('COMMERCE_ORDER_NUMBER_PREFIX', 'BL'),

    /*
    |--------------------------------------------------------------------------
    | Tax Settings (Reserved - Decision D-05)
    |--------------------------------------------------------------------------
    |
    | GST / tax treatment is reserved until confirmed by an adviser (D-05).
    | Default tax rate percentage is 0.
    |
    */
    'tax_rate_percent' => (float) env('COMMERCE_TAX_RATE_PERCENT', 0.0),
];
