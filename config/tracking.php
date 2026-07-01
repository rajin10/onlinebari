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
    | server can deduplicate.
    |
    | Pixel id is public (it also ships in the browser Pixel), so it is safe to
    | keep here. The access token is a SECRET — set META_CAPI_TOKEN only in the
    | server .env, never in git. CAPI stays a no-op until that token exists.
    |
    */
    'capi' => [
        'enabled' => (bool) env('META_CAPI_ENABLED', true),
        'pixel_id' => env('META_PIXEL_ID', '2233285230778423'),
        'access_token' => env('META_CAPI_TOKEN'),
        'test_code' => env('META_CAPI_TEST_CODE'),
        // Server-side GTM (sGTM) endpoint, e.g. https://sgtm.yourdomain.com
        'sgtm_endpoint' => env('SGTM_ENDPOINT'),
    ],
];
