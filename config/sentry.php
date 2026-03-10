<?php

use Sentry\Tracing\SamplingContext;

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
    */

    'traces_sampler' => function (SamplingContext $context): float {

        $transactionContext = $context->getTransactionContext();

        if ($transactionContext !== null) {
            $name = $transactionContext->getName();

            // Ignore health check routes
            if ($name && str_contains($name, 'health')) {
                return 0.0;
            }
        }

        return (float) env('SENTRY_TRACES_SAMPLE_RATE', 0.2);
    },

];