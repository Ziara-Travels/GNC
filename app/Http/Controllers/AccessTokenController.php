<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;


class AccessTokenController extends Controller
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function __invoke()
    {
        $url = 'https://test.api.amadeus.com/v1/security/oauth2/token';

        try {
            $response = $this->client->post($url, [
                'headers' => [
                    'Accept' => 'application/json'
                ],
                'form_params' => [
                    'grant_type' => 'client_credentials',
                    'client_id' => env('AMADEUS_API_KEY'),
                    'client_secret' => env('AMADEUS_API_SECRET')
                ]
            ]);

            $response = $response->getBody();
            $response = json_decode($response);

            return $response;
        } catch (GuzzleException $exception) {
            dd($exception);
        }
    }
}