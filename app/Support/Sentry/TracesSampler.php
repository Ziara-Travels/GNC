<?php

namespace App\Support\Sentry;

use Sentry\Tracing\SamplingContext;

class TracesSampler
{
    /**
     * Referenced from config/sentry.php as [TracesSampler::class, 'sample'].
     *
     * This lives in a class rather than inline in the config because a closure
     * in a config file cannot be serialized, which makes `config:cache` fail
     * and therefore breaks container start-up.
     */
    public static function sample(SamplingContext $context): float
    {
        $transactionContext = $context->getTransactionContext();

        if ($transactionContext !== null) {
            $name = $transactionContext->getName();

            // Ignore health check routes
            if ($name && str_contains($name, 'health')) {
                return 0.0;
            }
        }

        return (float) env('SENTRY_TRACES_SAMPLE_RATE', 0.2);
    }
}