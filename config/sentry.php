<?php

use App\Support\Sentry\TracesSampler;

return [

    /*
    |--------------------------------------------------------------------------
    | Sentry DSN
    |--------------------------------------------------------------------------
    */

    'dsn' => env('SENTRY_LARAVEL_DSN'),

    /*
    |--------------------------------------------------------------------------
    | Environment
    |--------------------------------------------------------------------------
    */

    'environment' => env('SENTRY_ENVIRONMENT', env('APP_ENV', 'production')),

    /*
    |--------------------------------------------------------------------------
    | Release
    |--------------------------------------------------------------------------
    */

    'release' => env('SENTRY_RELEASE'),

    /*
    |--------------------------------------------------------------------------
    | Performance Monitoring Sample Rate
    |--------------------------------------------------------------------------
    |
    | Value between 0 and 1.
    | 0.2 = capture 20% of requests
    |
    */

    'traces_sample_rate' => (float) env('SENTRY_TRACES_SAMPLE_RATE', 0.2),

    /*
    |--------------------------------------------------------------------------
    | Dynamic Tracing Sampler
    |--------------------------------------------------------------------------
    |
    | Kept as a [class, method] pair rather than a closure so that this config
    | stays serializable and `php artisan config:cache` can be used in
    | production (a closure here makes config:cache throw).
    |
    */

    'traces_sampler' => [TracesSampler::class, 'sample'],

];
