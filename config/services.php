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

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'payment' => [
        'default' => env('DEFAULT_PAYMENT_GATEWAY', 'clickpay'),
        'clickpay' => [
            'base_url' => env('CLICKPAY_BASE_URL'),
            'profile_id' => env('CLICKPAY_PROFILE_ID'),
            'server_key' => env('CLICKPAY_SERVER_KEY'),
            'client_key' => env('CLICKPAY_CLIENT_KEY'),
        ],
        'interpay' => [
            'public_key' => env('INTERPAY_PUBLIC_KEY'),
            'secret_key' => env('INTERPAY_SECRET_KEY'),
            'base_url' => env('INTERPAY_BASE_URL', 'https://ecomspghostedpage.softpos-ksa.com/'),
        ],
    ],
    'delivery' => [
        'default' => env('DEFAULT_DELIVERY_GATEWAY', 'oto'),
        'oto' => [
            'base_url' => env('OTO_BASE_URL'),
            'refresh_token' => env('OTO_REFRESH_TOKEN')
        ]

    ],
    'sms' => [
        'default' => env('SMS_PROVIDER', 'provider1'),

        'provider1' => [
            'url' => env('PROVIDER1_BASE_URL'),
            'secret' => env('PROVIDER1_SECRET'),
            // other provider1 specific configs
        ],

        'provider2' => [
            'username' => env('PROVIDER2_SMS_USERNAME'),
            'password' => env('PROVIDER2_SMS_PASSWORD'),
            // other provider2 specific configs
        ],
    ],

];
