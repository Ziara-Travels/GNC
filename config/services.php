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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'exchange_rate' => [
        'api_key' => env('EXCHANGE_RATE_API_KEY'),
    ],
    'jenga' => [
        'base_url' => env('JENGA_BASE_URL', 'https://api.finserve.africa/v3-apis/account-api/v3.0/'),
        'merchant_code' => env('JENGA_MERCHANT_CODE'),
        'api_key' => env('JENGA_API_KEY'),
        'consumer_secret' => env('Consumer_Secret'),
        'callback_url' => env('JENGA_CALLBACK_URL', 'https://your-domain.com/api/payments/jenga/callback'),
    ],

    'skyscanner' => [
        'api_key' => env('RAPIDAPI_KEY'),
        'host' => env('RAPIDAPI_HOST_FLIGHTS', 'flights-sky.p.rapidapi.com'),
        'market' => env('SKYSCANNER_MARKET', 'KE'),
        'locale' => env('SKYSCANNER_LOCALE', 'en-KE'),
        'currency' => env('SKYSCANNER_CURRENCY', 'KES'),
        'mock' => env('SKYSCANNER_MOCK', env('RAPIDAPI_MOCK', false)),
        'timeout' => env('SKYSCANNER_TIMEOUT', 30),
    ],

    'hotels' => [
        'api_key' => env('RAPIDAPI_KEY'),
        'host' => env('RAPIDAPI_HOST_HOTELS', 'hotels-com6.p.rapidapi.com'),
        'locale' => env('HOTELS_LOCALE', 'en_US'),
        'currency' => env('HOTELS_CURRENCY', 'KES'),
        'mock' => env('HOTELS_MOCK', env('RAPIDAPI_MOCK', false)),
        'timeout' => env('HOTELS_TIMEOUT', 35),
    ],

    'booking' => [
        'api_key' => env('RAPIDAPI_KEY'),
        'host' => env('RAPIDAPI_HOST_BOOKING', 'booking-com18.p.rapidapi.com'),
        'locale' => env('BOOKING_LOCALE', 'en-gb'),
        'currency' => env('BOOKING_CURRENCY', 'KES'),
        'affiliate_id' => env('BOOKINGCOM_AFFILIATE_ID'),
        'deeplink' => env('BOOKINGCOM_DEEPLINK', 'https://www.booking.com'),
        'mock' => env('BOOKING_MOCK', env('RAPIDAPI_MOCK', false)),
        'timeout' => env('BOOKING_TIMEOUT', 35),
    ],
    'openweathermap' => [
    'key' => env('OPENWEATHERMAP_API_KEY'),
],
'google' => [
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect' => env('GOOGLE_REDIRECT_URI', '/auth/google/callback'),
],

'facebook' => [
    'client_id' => env('FACEBOOK_CLIENT_ID'),
    'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
    'redirect' => env('FACEBOOK_REDIRECT_URI', '/auth/facebook/callback'),
],

'twitter' => [
    'client_id' => env('TWITTER_CLIENT_ID'),
    'client_secret' => env('TWITTER_CLIENT_SECRET'),
    'redirect' => env('TWITTER_REDIRECT_URI', '/auth/twitter/callback'),
    'use_oauth_2' => true,
],

'x' => [
    'client_id' => env('X_CLIENT_ID'),
    'client_secret' => env('X_CLIENT_SECRET'),
    'redirect' => env('X_REDIRECT_URI', '/auth/X/callback'),
],

'openai' => [
        'api_key' => env('OPENAI_API_KEY'),
        'organization' => env('OPENAI_ORGANIZATION'),
    ],

    'whatsapp' => [
    'api_url' => env('WHATSAPP_API_URL', 'https://graph.facebook.com/v18.0'),
    'access_token' => env('WHATSAPP_ACCESS_TOKEN'),
    'phone_number_id' => env('WHATSAPP_PHONE_NUMBER_ID'),
    'verify_token' => env('WHATSAPP_VERIFY_TOKEN'),
],

'hubspot' => [
    'api_key' => env('HUBSPOT_API_KEY'),
],

];
