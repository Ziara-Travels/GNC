<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AmadeusService
{
    protected string $baseUrl;
    protected string $clientId;
    protected string $clientSecret;
    protected string $tokenUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.amadeus.base_url', 'https://test.api.amadeus.com/v1');
        $this->clientId = config('services.amadeus.client_id', env('AMADEUS_API_KEY', ''));
        $this->clientSecret = config('services.amadeus.client_secret', env('AMADEUS_API_SECRET', ''));
        $this->tokenUrl = config('services.amadeus.token_url', 'https://test.api.amadeus.com/v1/security/oauth2/token');
    }

    /**
     * Get a valid access token, cached for its lifetime minus 60s buffer.
     */
    public function getAccessToken(): string
    {
        return Cache::remember('amadeus_access_token', 1500, function () {
            $response = Http::asForm()->post($this->tokenUrl, [
                'grant_type'    => 'client_credentials',
                'client_id'     => $this->clientId,
                'client_secret' => $this->clientSecret,
            ]);

            if (!$response->successful()) {
                Log::error('Amadeus token request failed', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
                throw new \RuntimeException('Failed to obtain Amadeus access token');
            }

            return $response->json('access_token');
        });
    }

    /**
     * Search airports/cities by keyword (Amadeus Location API).
     */
    public function searchLocations(string $keyword, string $subType = 'AIRPORT,CITY'): array
    {
        $cacheKey = 'amadeus_locations_' . md5($keyword . $subType);

        return Cache::remember($cacheKey, 3600, function () use ($keyword, $subType) {
            $response = Http::withToken($this->getAccessToken())
                ->get($this->baseUrl . '/reference-data/locations', [
                    'keyword' => $keyword,
                    'subType' => $subType,
                    'view'    => 'LIGHT',
                    'page[limit]' => 10,
                ]);

            if (!$response->successful()) {
                Log::warning('Amadeus location search failed', [
                    'keyword' => $keyword,
                    'status'  => $response->status(),
                ]);
                return [];
            }

            return $response->json('data', []);
        });
    }

    /**
     * Search flights using Amadeus Flight Offers Search v2 (POST).
     */
    public function searchFlights(array $params): array
    {
        $body = [
            'currencyCode'       => $params['currency'] ?? 'USD',
            'originDestinations' => $params['originDestinations'],
            'travelers'          => $params['travelers'],
            'sources'            => ['GDS'],
        ];

        if (!empty($params['searchCriteria'])) {
            $body['searchCriteria'] = $params['searchCriteria'];
        }

        $response = Http::withToken($this->getAccessToken())
            ->timeout(30)
            ->post('https://test.api.amadeus.com/v2/shopping/flight-offers', $body);

        if (!$response->successful()) {
            Log::error('Amadeus flight search failed', [
                'status' => $response->status(),
                'body'   => substr($response->body(), 0, 500),
            ]);
            return ['data' => [], 'dictionaries' => []];
        }

        return $response->json();
    }

    /**
     * Confirm flight pricing before booking.
     */
    public function confirmFlightPrice(array $flightOffer): array
    {
        $response = Http::withToken($this->getAccessToken())
            ->timeout(30)
            ->post('https://test.api.amadeus.com/v1/shopping/flight-offers/pricing', [
                'data' => [
                    'type'         => 'flight-offers-pricing',
                    'flightOffers' => [$flightOffer],
                ],
            ]);

        if (!$response->successful()) {
            Log::error('Amadeus flight pricing failed', [
                'status' => $response->status(),
                'body'   => substr($response->body(), 0, 500),
            ]);
            return [];
        }

        return $response->json('data', []);
    }

    /**
     * Create a flight order (booking) via Amadeus.
     */
    public function createFlightOrder(array $flightOffer, array $travelers, array $contact): array
    {
        $response = Http::withToken($this->getAccessToken())
            ->timeout(30)
            ->post('https://test.api.amadeus.com/v1/booking/flight-orders', [
                'data' => [
                    'type'         => 'flight-order',
                    'flightOffers' => [$flightOffer],
                    'travelers'    => $travelers,
                    'contacts'     => [$contact],
                ],
            ]);

        if (!$response->successful()) {
            Log::error('Amadeus flight booking failed', [
                'status' => $response->status(),
                'body'   => substr($response->body(), 0, 500),
            ]);
            return [];
        }

        return $response->json('data', []);
    }

    /**
     * Search hotels by city code.
     */
    public function searchHotelsByCity(string $cityCode, array $params = []): array
    {
        $query = array_merge([
            'cityCode' => $cityCode,
        ], $params);

        $response = Http::withToken($this->getAccessToken())
            ->timeout(30)
            ->get('https://test.api.amadeus.com/v1/reference-data/locations/hotels/by-city', $query);

        if (!$response->successful()) {
            Log::warning('Amadeus hotel city search failed', [
                'cityCode' => $cityCode,
                'status'   => $response->status(),
            ]);
            return [];
        }

        return $response->json('data', []);
    }

    /**
     * Get hotel offers (availability + pricing).
     */
    public function getHotelOffers(array $hotelIds, array $params = []): array
    {
        $query = array_merge([
            'hotelIds' => implode(',', $hotelIds),
        ], $params);

        $response = Http::withToken($this->getAccessToken())
            ->timeout(30)
            ->get('https://test.api.amadeus.com/v3/shopping/hotel-offers', $query);

        if (!$response->successful()) {
            Log::warning('Amadeus hotel offers failed', [
                'status' => $response->status(),
                'body'   => substr($response->body(), 0, 500),
            ]);
            return ['data' => []];
        }

        return $response->json();
    }

    /**
     * Get single hotel offer details.
     */
    public function getHotelOfferById(string $offerId): array
    {
        $response = Http::withToken($this->getAccessToken())
            ->timeout(30)
            ->get("https://test.api.amadeus.com/v3/shopping/hotel-offers/{$offerId}");

        if (!$response->successful()) {
            return [];
        }

        return $response->json('data', []);
    }

    /**
     * Book a hotel via Amadeus.
     */
    public function createHotelBooking(string $offerId, array $guests, array $payment): array
    {
        $response = Http::withToken($this->getAccessToken())
            ->timeout(30)
            ->post('https://test.api.amadeus.com/v1/booking/hotel-bookings', [
                'data' => [
                    'offerId' => $offerId,
                    'guests'  => $guests,
                    'payments' => [$payment],
                ],
            ]);

        if (!$response->successful()) {
            Log::error('Amadeus hotel booking failed', [
                'status' => $response->status(),
                'body'   => substr($response->body(), 0, 500),
            ]);
            return [];
        }

        return $response->json('data', []);
    }

    /**
     * Flight Inspiration Search — cheapest destinations from origin.
     */
    public function getFlightInspirations(string $origin, array $params = []): array
    {
        $cacheKey = 'amadeus_inspiration_' . $origin . '_' . md5(json_encode($params));

        return Cache::remember($cacheKey, 3600, function () use ($origin, $params) {
            $query = array_merge(['origin' => $origin], $params);

            $response = Http::withToken($this->getAccessToken())
                ->timeout(15)
                ->get($this->baseUrl . '/shopping/flight-destinations', $query);

            if (!$response->successful()) {
                return [];
            }

            return $response->json('data', []);
        });
    }

    /**
     * Get cheapest flight date from origin to destination.
     */
    public function getCheapestFlightDates(string $origin, string $destination): array
    {
        $cacheKey = "amadeus_cheapest_{$origin}_{$destination}";

        return Cache::remember($cacheKey, 3600, function () use ($origin, $destination) {
            $response = Http::withToken($this->getAccessToken())
                ->timeout(15)
                ->get($this->baseUrl . '/shopping/flight-dates', [
                    'origin'      => $origin,
                    'destination' => $destination,
                ]);

            if (!$response->successful()) {
                return [];
            }

            return $response->json('data', []);
        });
    }

    /**
     * Search cities by keyword for hotel searches.
     */
    public function searchCities(string $keyword): array
    {
        $cacheKey = 'amadeus_cities_' . md5($keyword);

        return Cache::remember($cacheKey, 3600, function () use ($keyword) {
            $response = Http::withToken($this->getAccessToken())
                ->get($this->baseUrl . '/reference-data/locations', [
                    'keyword' => $keyword,
                    'subType' => 'CITY',
                    'view'    => 'LIGHT',
                    'page[limit]' => 8,
                ]);

            if (!$response->successful()) {
                return [];
            }

            return $response->json('data', []);
        });
    }
}
