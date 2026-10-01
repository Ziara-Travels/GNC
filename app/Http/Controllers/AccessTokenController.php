<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class AccessTokenController extends Controller
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function __invoke()
    {
        // Read credentials from config, not env(): env() returns null once the
        // config is cached (production runs `config:cache` on boot).
        $clientId = config('services.amadeus.client_id');
        $clientSecret = config('services.amadeus.client_secret');
        $baseUrl = config('services.amadeus.base_url');

        if (empty($clientId) || empty($clientSecret)) {
            Log::warning('Amadeus credentials are not configured; cannot fetch an access token.');

            return response()->json(['error' => 'Amadeus API is not configured.'], 503);
        }

        try {
            $response = $this->client->post($baseUrl.'/v1/security/oauth2/token', [
                'headers' => [
                    'Accept' => 'application/json'
                ],
                'form_params' => [
                    'grant_type' => 'client_credentials',
                    'client_id' => $clientId,
                    'client_secret' => $clientSecret
                ]
            ]);

            return json_decode($response->getBody(), true);
        } catch (GuzzleException $exception) {
            // Never dd()/dump() here: this endpoint is publicly reachable and
            // dd() ignores APP_DEBUG, leaking a full stack trace to anyone.
            Log::error('Failed to fetch Amadeus access token.', [
                'message' => $exception->getMessage(),
            ]);

            return response()->json(['error' => 'Could not reach the Amadeus API.'], 502);
        }
    }
}