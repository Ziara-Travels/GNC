<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

/**
 * RapidAPI Hotels.com client.
 *
 * Talks to https://hotels-com6.p.rapidapi.com - the "Hotels COM" provider by
 * ntd119 (https://rapidapi.com/ntd119/api/hotels-com6). Endpoints used:
 *   GET /regions          -> resolve a typed city/place into a regionId
 *   GET /hotels/search    -> list propertySearchListings; poll until
 *                             completionPercentage == 100 (or status completed)
 *
 * Searching is metasearch only - the actual reservation happens on
 * Booking.com (see App\Services\BookingService).
 *
 * When no RAPIDAPI_KEY is configured (or RAPIDAPI_MOCK=true) the service
 * returns locally generated mock hotels so the flow stays testable.
 */
class HotelService
{
    protected $client;

    protected $apiKey;

    protected $host;

    protected $locale;

    public $currency;

    protected $mock;

    public function __construct()
    {
        $this->apiKey = (string) config('services.hotels.api_key', '');
        $this->host = (string) config('services.hotels.host', 'hotels-com6.p.rapidapi.com');
        $this->locale = (string) config('services.hotels.locale', 'en_US');
        $this->currency = (string) config('services.hotels.currency', 'KES');
        $this->mock = (bool) config('services.hotels.mock', false);

        if ($this->apiKey === '') {
            $this->apiKey = (string) config('services.skyscanner.api_key', '');
        }

        $this->client = new Client([
            'base_uri' => 'https://' . $this->host . '/',
            'timeout' => 35,
            'http_errors' => false,
        ]);
    }

    public function isMock()
    {
        return $this->mock || empty($this->apiKey);
    }

    protected function headers()
    {
        return [
            'X-RapidAPI-Key' => $this->apiKey,
            'X-RapidAPI-Host' => $this->host,
            'Accept' => 'application/json',
        ];
    }

    protected function request($method, $endpoint, $options = [])
    {
        $options['headers'] = array_merge($this->headers(), $options['headers'] ?? []);

        try {
            $response = $this->client->request($method, $endpoint, $options);
            $body = (string) $response->getBody();
            $decoded = json_decode($body, true);

            $statusCode = $response->getStatusCode();
            if ($statusCode >= 400 || ($decoded === null && $body !== 'null')) {
                Log::warning('Hotels request failed', [
                    'endpoint' => $endpoint,
                    'status' => $statusCode,
                    'body' => mb_substr($body, 0, 500),
                ]);
                return null;
            }

            return $decoded;
        } catch (GuzzleException $e) {
            Log::warning('Hotels request exception', ['endpoint' => $endpoint, 'error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * /regions - resolve a free text destination into a region.
     * Returns a normalized list: [
     *   'value'      => display code / search term,
     *   'dest_id'    => Hotels.com (Expedia) region id,
     *   'dest_type'  => 'city' | 'landmark' | ...,
     *   'name',
     *   'country'    => ISO country code,
     * ]
     */
    public function regions($term, $language = null)
    {
        $term = trim($term);
        if ($term === '') {
            return [];
        }

        if ($this->isMock()) {
            return $this->mockRegions($term);
        }

        $data = $this->request('get', '/regions', [
            'query' => [
                'term' => $term,
                'language' => $language ?? $this->locale,
                'includeType' => 'city,landmark,region',
            ],
        ]);

        if (!is_array($data)) {
            return [];
        }

        $items = $data['data'] ?? [];
        if (isset($data['data']['value']) && is_array($data['data']['value'])) {
            $items = $data['data']['value'];
        }

        $normalized = [];
        foreach ((array) $items as $item) {
            $normalized[] = $this->normalizeRegion($item);
        }

        return array_values(array_filter($normalized));
    }

    protected function normalizeRegion(array $item)
    {
        $name = $item['name'] ?? ($item['value'] ?? '');
        $destId = $item['destId'] ?? ($item['regionId'] ?? null);
        $destType = $item['destType'] ?? ($item['type'] ?? 'city');

        if ($name === '' && $destId === null) {
            return null;
        }

        return [
            'value' => (string) ($item['value'] ?? $name),
            'dest_id' => (string) $destId,
            'dest_type' => (string) $destType,
            'name' => (string) $name,
            'country' => (string) ($item['country'] ?? ($item['countryCode'] ?? '')),
            'iata' => (string) ($item['iata'] ?? ''),
        ];
    }

    /**
     * /hotels/search - search hotels for a destination + dates.
     * Polls (completionPercentage pattern) until results are ready.
     * Returns a normalized list of offers:
     * [ 'propertyId', 'name', 'city', 'address', 'price' (per night),
     *   'totalPrice', 'currency', 'starRating', 'reviewScore', 'reviewCount',
     *   'imageUrl', 'amenities', 'latitude', 'longitude' ]
     */
    public function searchHotels(array $params)
    {
        if ($this->isMock()) {
            return $this->mockSearch($params);
        }

        $query = array_filter([
            'destId' => $params['dest_id'] ?? null,
            'destType' => $params['dest_type'] ?? 'city',
            'checkin' => $params['checkin'] ?? null,
            'checkout' => $params['checkout'] ?? null,
            'adults' => (int) ($params['adults'] ?? 2),
            'rooms' => (int) ($params['rooms'] ?? 1),
            'children' => (int) ($params['children'] ?? 0),
            'currency' => $params['currency'] ?? $this->currency,
            'nationality' => $params['nationality'] ?? 'KE',
            'pageNumber' => 1,
            'pageSize' => 25,
        ], function ($v) {
            return $v !== null && $v !== '';
        });

        $data = $this->request('get', '/hotels/search', ['query' => $query]);
        if (!is_array($data)) {
            return [];
        }

        // Poll while the search is still building (status.incomplete).
        $attempts = 0;
        while ($this->searchIncomplete($data) && $attempts < 8) {
            $attempts++;
            usleep(1500000);

            $data = $this->request('get', '/hotels/search', ['query' => $query]);
            if (!is_array($data)) {
                break;
            }
        }

        $listings = $data['data']['propertySearchListings'] ?? [];
        if (!is_array($listings)) {
            $listings = [];
        }

        $city = $params['destination_name'] ?? $params['dest_id'] ?? '';
        $checkin = $params['checkin'] ?? null;
        $checkout = $params['checkout'] ?? null;
        $nights = $this->nightsBetween($checkin, $checkout);
        $currency = $params['currency'] ?? $this->currency;

        $results = [];
        foreach ($listings as $listing) {
            $hotel = $this->normalizeListing($listing, $city, $nights, $currency);
            if ($hotel !== null) {
                $results[] = $hotel;
            }
        }

        return $results;
    }

    protected function searchIncomplete(array $data)
    {
        $status = $data['status'] ?? ($data['data']['status'] ?? null);
        $completion = $data['data']['searchMetadata']['completionPercentage'] ?? null;

        if (is_int($completion)) {
            return $completion < 100;
        }

        return in_array($status, ['incomplete', 'pending', 'processing'], true);
    }

    protected function normalizeListing(array $listing, $city, $nights, $currency)
    {
        $propertyId = $listing['propertyId'] ?? null;
        $name = $listing['propertyName'] ?? ($listing['propertyTitle'] ?? '');
        if ($propertyId === null && $name === '') {
            return null;
        }

        $currentPrice = $listing['priceInfo']['currentPrice'] ?? [];
        $perNight = (float) ($currentPrice['priceValue'] ?? 0);
        if ($perNight <= 0) {
            $perNight = (float) ($listing['priceInfo']['oldPrice']['priceValue'] ?? 0);
        }

        $currency = $currentPrice['currencyCode'] ?? $currency;

        $imageUrl = null;
        $cardImage = $listing['hotelCardImage'] ?? [];
        if (!empty($cardImage['image']['url'])) {
            $imageUrl = $cardImage['image']['url'];
        } elseif (!empty($cardImage['image']['cdnUrl'])) {
            $imageUrl = $cardImage['image']['cdnUrl'];
        }

        $amenities = [];
        foreach (($listing['propertyHighlights'] ?? []) as $highlight) {
            if (isset($highlight['name'])) {
                $amenities[] = $highlight['name'];
            }
        }
        if (!empty($listing['badges'])) {
            foreach ((array) $listing['badges'] as $badge) {
                if (is_array($badge) && isset($badge['text'])) {
                    $amenities[] = $badge['text'];
                } elseif (is_scalar($badge)) {
                    $amenities[] = (string) $badge;
                }
            }
        }

        $coords = $listing['coordinates'] ?? [];

        return [
            'propertyId' => (string) $propertyId,
            'name' => (string) $name,
            'city' => (string) $city,
            'address' => (string) ($listing['address']['addressLine'] ?? $listing['address'] ?? ''),
            'price' => $perNight,
            'totalPrice' => $perNight > 0 ? $perNight * max(1, $nights) : 0,
            'currency' => (string) $currency,
            'starRating' => (int) ($listing['starRating'] ?? 0),
            'reviewScore' => (float) ($listing['guestReviews']['brands']['ALL']['overallScore'] ?? 0),
            'reviewCount' => (int) ($listing['guestReviews']['brands']['ALL']['totalCount'] ?? 0),
            'imageUrl' => $imageUrl,
            'amenities' => array_values(array_unique(array_filter($amenities))),
            'latitude' => $coords['lat'] ?? null,
            'longitude' => $coords['lon'] ?? null,
            'raw' => $listing['origin'] ?? null,
        ];
    }

    public function nightsBetween($checkin, $checkout)
    {
        $checkin = $checkin !== null && $checkin !== '' ? $checkin : null;
        $checkout = $checkout !== null && $checkout !== '' ? $checkout : null;

        if ($checkin === null || $checkout === null) {
            return 1;
        }

        try {
            $start = \Carbon\Carbon::parse($checkin);
            $end = \Carbon\Carbon::parse($checkout);
            return max(1, (int) $start->diffInDays($end));
        } catch (\Exception $e) {
            return 1;
        }
    }

    public function formatPrice($amount, $currency = 'KES')
    {
        $currency = strtoupper((string) $currency);
        $symbol = $currency === 'KES' ? 'KSh ' : ($currency === 'USD' ? '$' : $currency . ' ');

        return $symbol . number_format((float) $amount, 2);
    }

    // ---------------------------------------------------------------------
    // Mock mode
    // ---------------------------------------------------------------------

    protected function mockRegions($term)
    {
        $regions = [
            ['Nairobi', '2621', 'city', 'KE'],
            ['Mombasa', '2339', 'city', 'KE'],
            ['Diani Beach', '692', 'city', 'KE'],
            ['Naivasha', '5431', 'city', 'KE'],
            ['Nakuru', '6588', 'city', 'KE'],
            ['Kisumu', '5673', 'city', 'KE'],
            ['Lake Nakuru', '6429', 'city', 'KE'],
            ['Masai Mara', '2669', 'city', 'KE'],
            ['Amboseli', '6629', 'city', 'KE'],
            ['Entebbe', '6266', 'city', 'UG'],
            ['Dar es Salaam', '5178', 'city', 'TZ'],
            ['Zanzibar', '5257', 'city', 'TZ'],
            ['Kigali', '5724', 'city', 'RW'],
            ['Addis Ababa', '5703', 'city', 'ET'],
            ['Dubai', '262', 'city', 'AE'],
            ['London', '855', 'city', 'UK'],
        ];

        $term = strtolower(trim($term));
        $results = [];
        foreach ($regions as $region) {
            $haystack = strtolower($region[0]);
            if ($term === '' || strpos($haystack, $term) !== false || $term === strtolower($region[1])) {
                $results[] = [
                    'value' => $region[0],
                    'dest_id' => $region[1],
                    'dest_type' => $region[2],
                    'name' => $region[0],
                    'country' => $region[3],
                    'iata' => '',
                ];
            }
        }

        return array_slice($results, 0, 8);
    }

    protected function mockSearch(array $params)
    {
        $city = $params['destination_name'] ?? ($params['dest_id'] ?? 'Nairobi');
        $nights = $this->nightsBetween($params['checkin'] ?? null, $params['checkout'] ?? null);
        $adults = (int) ($params['adults'] ?? 2);
        $currency = $params['currency'] ?? $this->currency;

        $names = [
            'Nairobi' => ['The Windsor Golf Hotel', 'Tribeka Urban Hotel', 'Hotel Boulevard', 'The Boma Nairobi', 'Villa Rosa Kempinski', 'Sankara Nairobi', 'Hemingways Nairobi'],
            'Mombasa' => ['Sarova Whitesands', 'Voyager Beach Resort', 'CityBlue Swahili Beach', 'Baobab Beach Resort', 'PrideInn Paradise'],
            'Zanzibar' => ['Zanzi Resort', 'Melia Zanzibar', 'The Residence Zanzibar', 'CoCo Tree Resort'],
            'Diani Beach' => ['Diani Sea Resort', 'Leopard Beach Resort', 'The Sands at Nomad', 'Papillon Lagoon Reef'],
            'Kampala' => ['Sheraton Kampala', 'Speke Resort Munyonyo', 'Protea Hotel Kampala'],
            'Dar es Salaam' => ['Ramada Encore Dar es Salaam', 'Hyatt Regency Dar es Salaam', 'Sea Cliff Hotel'],
            'Dubai' => ['Atlantis The Palm', 'Jumeirah Beach Hotel', 'Address Downtown', 'Five Palm Jumeirah'],
            'London' => ['The Ritz London', 'The Savoy', 'Shangri-La at The Shard', 'London Marriott County Hall'],
        ];

        $pool = $names[$city] ?? $names['Nairobi'];
        $stars = [4, 3, 5, 4, 5, 4, 5];
        $reviews = [8.4, 7.9, 8.8, 8.2, 9.1, 8.0, 8.6];
        $counts = [540, 312, 890, 455, 1200, 610, 760];
        $basePrices = [
            'Nairobi' => 9500, 'Mombasa' => 12000, 'Zanzibar' => 16500, 'Diani Beach' => 14000,
            'Kampala' => 11000, 'Dar es Salaam' => 13500, 'Dubai' => 52000, 'London' => 78000,
        ];

        $base = $basePrices[$city] ?? 12000;
        $hotelCount = count($pool);
        $hotels = [];

        for ($i = 0; $i < $hotelCount; $i++) {
            $price = $base + ($i * 1800) + (($i % 3) * 950);
            $propertyId = 'mock-' . substr(md5($city . $i), 0, 10);

            $hotels[] = [
                'propertyId' => $propertyId,
                'name' => $pool[$i],
                'city' => $city,
                'address' => 'Mock address, ' . $city,
                'price' => $price,
                'totalPrice' => $price * $nights,
                'currency' => $currency,
                'starRating' => $stars[$i % count($stars)],
                'reviewScore' => $reviews[$i % count($reviews)],
                'reviewCount' => $counts[$i % count($counts)],
                'imageUrl' => 'https://picsum.photos/seed/hotel' . $i . '/600/400',
                'amenities' => ['Free WiFi', 'Pool', 'Breakfast', 'Airport Shuttle'],
                'latitude' => null,
                'longitude' => null,
                'raw' => null,
            ];
        }

        // Sort: cheapest first.
        usort($hotels, function ($a, $b) {
            return $a['price'] <=> $b['price'];
        });

        return $hotels;
    }
}