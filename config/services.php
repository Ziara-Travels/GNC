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
        'base_url' => rtrim(env('JENGA_BASE_URL', 'https://api.finserve.africa/v3-apis/account-api/v3.0'), '/'),
        'merchant_code' => env('JENGA_MERCHANT_CODE'),
        'api_key' => env('JENGA_API_KEY'),
        // Was env('Consumer_Secret'), a name that does not exist in any env
        // file or render.yaml, so the consumer secret was always null and
        // payment auth failed. Render sets JENGA_CONSUMER_SECRET.
        'consumer_secret' => env('JENGA_CONSUMER_SECRET'),
        'callback_url' => env('JENGA_CALLBACK_URL'),
        // Jenga's transaction and payment-gateway products are served from
        // different hosts than the account API. Set both in production or
        // card/bank/mpesa/equity/equitel charges go to the sandbox hosts.
        'transaction_api_url' => rtrim(env('JENGA_TRANSACTION_API_URL', 'https://sandbox.jengahq.io/transaction'), '/'),
        'payment_gateway_url' => rtrim(env('JENGA_PAYMENT_GATEWAY_URL', 'https://sandbox.jengahq.io/payment-gateway'), '/'),
    ],

    'amadeus' => [
        'client_id' => env('AMADEUS_API_KEY'),
        'client_secret' => env('AMADEUS_API_SECRET'),
        // Keep the /v1 suffix: AmadeusService appends /reference-data/locations,
        // /shopping/flight-offers, etc. directly onto this value.
        'base_url' => rtrim(env('AMADEUS_BASE_URL', 'https://test.api.amadeus.com/v1'), '/'),
        'token_url' => env('AMADEUS_TOKEN_URL', 'https://test.api.amadeus.com/v1/security/oauth2/token'),
    ],

    // Consumed by App\Http\Middleware\TrustProxies. Render terminates TLS, so
    // '*' is correct there. Set TRUSTED_PROXIES=REMOTE_ADDR (or a comma
    // separated IP list) to narrow it.
    'trusted_proxies' => env('TRUSTED_PROXIES', '*'),

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
