<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Google Tag Manager
    |--------------------------------------------------------------------------
    |
    | Single source of truth for the GTM container id. Set GTM_ID in .env to
    | override per-environment; leaving it unset falls back to the production
    | container. Set to null/empty to disable GTM output everywhere.
    |
    */
    'gtm_id' => env('GTM_ID', 'GTM-WB39G2B7'),

    /*
    |--------------------------------------------------------------------------
    | Default currency for ecommerce events
    |--------------------------------------------------------------------------
    */
    'currency' => env('TRACKING_CURRENCY', 'BDT'),

    /*
    |--------------------------------------------------------------------------
    | Server-side (Meta CAPI / server GTM) readiness
    |--------------------------------------------------------------------------
    |
    | Flags/credentials consumed by the (optional) server-side layer. The
    | browser dataLayer already emits an `event_id` for every event so the
    | server can deduplicate. Fill these in when the VPS endpoint is live.
    |
    */
    'capi' => [
        'enabled' => (bool) env('META_CAPI_ENABLED', false),
        'pixel_id' => env('META_PIXEL_ID'),
        'access_token' => env('META_CAPI_TOKEN'),
        'test_code' => env('META_CAPI_TEST_CODE'),
        // Server-side GTM (sGTM) endpoint, e.g. https://sgtm.yourdomain.com
        'sgtm_endpoint' => env('SGTM_ENDPOINT'),
    ],
];
