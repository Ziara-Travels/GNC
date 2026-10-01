<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * The app runs behind a proxy in production (Render terminates TLS and
     * forwards over HTTP). Without this, X-Forwarded-Proto is ignored and
     * Laravel treats every request as plain HTTP: generated URLs use http://,
     * secure cookies are never flagged Secure, and CSRF/session breakage follows.
     *
     * @var array<int, string>|string|null
     */
    protected $proxies = '*';

    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    protected $headers =
        Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO |
        Request::HEADER_X_FORWARDED_PREFIX |
        Request::HEADER_X_FORWARDED_AWS_ELB;

    /**
     * Resolve the trusted proxies, allowing TRUSTED_PROXIES to narrow or
     * disable trust. Read from config rather than env() so it still works
     * once the config is cached (production runs `config:cache` on boot).
     *
     * @return array<int, string>|string|null
     */
    protected function proxies()
    {
        $configured = config('services.trusted_proxies', $this->proxies);

        if ($configured === 'REMOTE_ADDR' || is_string($configured) || is_array($configured)) {
            return $configured === '' ? $this->proxies : $configured;
        }

        return $this->proxies;
    }
}