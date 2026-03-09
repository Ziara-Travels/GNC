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
    
    'amadeus' => [
        'client_id' => env('AMADEUS_API_KEY'),
        'client_secret' => env('AMADEUS_API_SECRET'),
        'base_url' => env('AMADEUS_BASE_URL', 'https://test.api.amadeus.com/v1'),
        'test_mode' => env('AMADEUS_TEST_MODE', true),
         'token_url' => env('AMADEUS_TOKEN_URL', 'https://test.api.amadeus.com/v1/security/oauth2/token'),

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
