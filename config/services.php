<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    // BD Courier fraud / courier-history lookup (shared with admin fraud checker).
    'bdcourier' => [
        'url' => env('BDCOURIER_URL', 'https://bdcourier.com/api/courier-check'),
        'key' => env('BDCOURIER_API_KEY'),
    ],

    // Google Sheets order log via a deployed Apps Script Web App webhook.
    // Leave the URL empty to disable logging (the integration no-ops).
    'google_sheets' => [
        'webhook_url' => env('GOOGLE_SHEETS_WEBHOOK_URL'),
        'secret' => env('GOOGLE_SHEETS_SECRET'),
    ],

];
