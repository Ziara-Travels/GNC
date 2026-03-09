<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExchangeRateController extends Controller
{
    private $client;
    private $apiKey; // Get your API key from https://www.exchangerate-api.com/

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->apiKey = env('EXCHANGE_RATE_API_KEY');
    }

    public function getUsdToKesRate()
    {
        try {
            $response = $this->client->get("https://v6.exchangerate-api.com/v6/{$this->apiKey}/latest/USD");
            $data = json_decode($response->getBody());
            return $data->conversion_rates->KES ?? 130.50; // Fallback rate if API fails
        } catch (\Exception $e) {
            return 130.50; // Fallback rate
        }
    }
}
