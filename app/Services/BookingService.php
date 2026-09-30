<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

/**
 * RapidAPI Booking.com client.
 *
 * Talks to https://booking-com18.p.rapidapi.com - the "Booking COM" provider
 * by ntd119 (https://rapidapi.com/ntd119/api/booking-com18). Endpoints used:
 *   GET /web/stays/destinations       -> resolve destination text
 *   GET /web/stays/search             -> search stays (tolerant read)
 *   GET /web/stays/details-by-url     -> enrich a Booking.com property by url
 *
 * Booking.com is the actual booking channel. This service builds the
 * affiliate deeplink (with aid from config) that the "Book on Booking.com"
 * buttons open. It is a marker/metasearch partner: booking happens on the
 * Booking.com site via the deeplink.
 *
 * When no RAPIDAPI_KEY is configured (or RAPIDAPI_MOCK=true) the service
 * returns mock responses so the flow stays testable.
 */
class BookingService
{
    protected $client;

    protected $apiKey;

    protected $host;

    protected $locale;

    protected $currency;

    protected $mock;

    protected $affiliateId;

    protected $deeplink;

    public function __construct()
    {
        $this->apiKey = (string) config('services.booking.api_key', '');
        $this->host = (string) config('services.booking.host', 'booking-com18.p.rapidapi.com');
        $this->locale = (string) config('services.booking.locale', 'en-gb');
        $this->currency = (string) config('services.booking.currency', 'KES');
        $this->affiliateId = (string) config('services.booking.affiliate_id', '');
        $this->deeplink = (string) config('services.booking.deeplink', 'https://www.booking.com');
        $this->mock = (bool) config('services.booking.mock', false);

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
                Log::warning('Booking request failed', [
                    'endpoint' => $endpoint,
                    'status' => $statusCode,
                    'body' => mb_substr($body, 0, 500),
                ]);
                return null;
            }

            return $decoded;
        } catch (GuzzleException $e) {
            Log::warning('Booking request exception', ['endpoint' => $endpoint, 'error' => $e->getMessage()]);
            return null;
        }
    }

    // ---------------------------------------------------------------------
    // Booking.com deeplink
    // ---------------------------------------------------------------------

    /**
     * Build a Booking.com searchresults deeplink for a hotel search.
     * Always points to the real booking.com page; the affiliate id (aid)
     * is appended when configured.
     */
    public function bookingUrl(array $params, $featuredHotelUrl = null)
    {
        $base = $this->deeplink;

        if (is_string($featuredHotelUrl) && $featuredHotelUrl !== '' && strpos($featuredHotelUrl, 'booking.com/hotel/') !== false) {
            $url = preg_replace('/[?#&]*$/', '', $featuredHotelUrl);
        } else {
            $url = $base . '/searchresults.html';
            $query = array_filter([
                'ss' => $params['destination_name'] ?? $params['dest_id'] ?? '',
                'dest_id' => $params['dest_id'] ?? null,
                'dest_type' => $params['dest_type'] ?? 'city',
                'checkin' => $params['checkin'] ?? null,
                'checkout' => $params['checkout'] ?? null,
                'group_adults' => (int) ($params['adults'] ?? 2),
                'no_rooms' => (int) ($params['rooms'] ?? 1),
                'group_children' => (int) ($params['children'] ?? 0),
                'selected_currency' => strtoupper($this->currency),
            ], function ($v) {
                return $v !== null && $v !== '';
            });
            $url .= '?' . http_build_query($query);
        }

        if ($this->affiliateId !== '') {
            $separator = strpos($url, '?') !== false ? '&' : '?';
            $url .= $separator . 'aid=' . urlencode($this->affiliateId);
        }

        return $url;
    }

    /**
     * Build a deep link straight to a single property on Booking.com.
     */
    public function propertyUrl($hotelUrl, array $params = [])
    {
        $url = (string) $hotelUrl;
        if ($url === '' || strpos($url, 'http') !== 0) {
            return $this->bookingUrl($params);
        }

        if ($this->affiliateId !== '') {
            $separator = strpos($url, '?') !== false ? '&' : '?';
            $url .= $separator . 'aid=' . urlencode($this->affiliateId);
        }

        return $url;
    }

    // ---------------------------------------------------------------------
    // /web/stays/* endpoints (tolerant - degrade gracefully)
    // ---------------------------------------------------------------------

    public function destinations($text)
    {
        $text = trim($text);
        if ($text === '') {
            return [];
        }

        if ($this->isMock()) {
            return $this->mockDestinations($text);
        }

        $data = $this->request('get', '/web/stays/destinations', ['query' => ['text' => $text]]);

        $items = [];
        if (is_array($data)) {
            if (isset($data['data']) && is_array($data['data'])) {
                $items = $data['data'];
            } elseif (is_array($data)) {
                $items = $data;
            }
        }

        $normalized = [];
        foreach ($items as $item) {
            $destId = $item['dest_id'] ?? ($item['id'] ?? null);
            $name = $item['name'] ?? ($item['label'] ?? '');
            if ($destId === null && $name === '') {
                continue;
            }
            $normalized[] = [
                'dest_id' => (string) $destId,
                'dest_type' => (string) ($item['dest_type'] ?? 'city'),
                'name' => (string) $name,
                'country' => (string) ($item['country'] ?? ''),
                'label' => (string) ($item['label'] ?? ''),
            ];
        }

        return $normalized;
    }

    /**
     * /web/stays/search - tolerant search across booking.com stays.
     * Returns [] on failure; otherwise a normalized list of stays.
     * Used as a secondary/fallback source for the hotel results page.
     */
    public function search(array $params)
    {
        if ($this->isMock()) {
            return $this->mockSearch($params);
        }

        $query = array_filter([
            'dest_id' => $params['dest_id'] ?? null,
            'dest_type' => $params['dest_type'] ?? 'city',
            'checkin' => $params['checkin'] ?? null,
            'checkout' => $params['checkout'] ?? null,
            'adults' => (int) ($params['adults'] ?? 2),
            'no_rooms' => (int) ($params['rooms'] ?? 1),
            'children' => (int) ($params['children'] ?? 0),
            'page_number' => 0,
            'page_size' => 25,
            'currency' => strtoupper($params['currency'] ?? $this->currency),
            'locale' => $this->locale,
            'filter_by_currency' => strtoupper($params['currency'] ?? $this->currency),
        ], function ($v) {
            return $v !== null && $v !== '';
        });

        $data = $this->request('get', '/web/stays/search', ['query' => $query]);
        if (!is_array($data)) {
            return [];
        }

        $items = [];
        if (isset($data['data']['result']) && is_array($data['data']['result'])) {
            $items = $data['data']['result'];
        } elseif (isset($data['data']['hotels']) && is_array($data['data']['hotels'])) {
            $items = $data['data']['hotels'];
        }

        $results = [];
        foreach ($items as $stay) {
            $hotel = $this->normalizeStay($stay, $params);
            if ($hotel !== null) {
                $results[] = $hotel;
            }
        }

        return $results;
    }

    protected function normalizeStay(array $stay, array $params)
    {
        $hotelName = $stay['hotel_name'] ?? ($stay['name'] ?? '');
        $hotelId = $stay['hotel_id'] ?? ($stay['id'] ?? null);
        if ($hotelName === '' && $hotelId === null) {
            return null;
        }

        $priceInfo = $stay['price_breakdown'] ?? ($stay['price'] ?? []);
        $price = 0;
        if (is_array($priceInfo)) {
            $price = (float) ($priceInfo['gross_amount'] ?? ($priceInfo['value'] ?? 0));
        } elseif (is_numeric($priceInfo)) {
            $price = (float) $priceInfo;
        }

        $url = $stay['url'] ?? null;
        $imageUrl = $stay['photo_uri'] ?? ($stay['image_url'] ?? null);

        return [
            'propertyId' => (string) $hotelId,
            'name' => (string) $hotelName,
            'city' => (string) ($params['destination_name'] ?? ($stay['city_name'] ?? '')),
            'address' => (string) ($stay['addressstring'] ?? ''),
            'price' => $price,
            'totalPrice' => $price,
            'currency' => (string) ($stay['currencycode'] ?? ($params['currency'] ?? $this->currency)),
            'starRating' => (int) ($stay['class_is_estimated'] ?? ($stay['class'] ?? 0)),
            'reviewScore' => (float) ($stay['review_score'] ?? 0),
            'reviewCount' => (int) ($stay['review_nr'] ?? 0),
            'imageUrl' => is_string($imageUrl) ? $imageUrl : null,
            'amenities' => [],
            'latitude' => isset($stay['latitude']) ? (float) $stay['latitude'] : null,
            'longitude' => isset($stay['longitude']) ? (float) $stay['longitude'] : null,
            'url' => $url,
        ];
    }

    /**
     * /web/stays/details-by-url - enrich a property from its booking.com url.
     */
    public function detailsByUrl($url)
    {
        $url = trim((string) $url);
        if ($url === '') {
            return null;
        }

        if ($this->isMock()) {
            return ['is_mock' => true, 'data' => ['url' => $url]];
        }

        $data = $this->request('get', '/web/stays/details-by-url', ['query' => ['url' => $url]]);
        if (!is_array($data)) {
            return null;
        }

        if (isset($data['data']) && is_array($data['data'])) {
            return $data['data'];
        }

        return $data;
    }

    // ---------------------------------------------------------------------
    // Mock mode
    // ---------------------------------------------------------------------

    protected function mockDestinations($text)
    {
        // Reuse the same city pool as HotelService so results stay consistent.
        $service = app(HotelService::class);
        $regions = $service->regions($text);

        $out = [];
        foreach ($regions as $region) {
            $out[] = [
                'dest_id' => $region['dest_id'],
                'dest_type' => $region['dest_type'],
                'name' => $region['name'],
                'country' => $region['country'],
                'label' => $region['name'] . ', ' . $region['country'],
            ];
        }

        return $out;
    }

    protected function mockSearch(array $params)
    {
        $service = app(HotelService::class);
        $hotels = $service->searchHotels($params);

        $out = [];
        foreach ($hotels as $hotel) {
            $out[] = [
                'propertyId' => $hotel['propertyId'],
                'name' => $hotel['name'],
                'city' => $hotel['city'],
                'address' => $hotel['address'],
                'price' => $hotel['price'],
                'totalPrice' => $hotel['totalPrice'],
                'currency' => $hotel['currency'],
                'starRating' => $hotel['starRating'],
                'reviewScore' => $hotel['reviewScore'],
                'reviewCount' => $hotel['reviewCount'],
                'imageUrl' => $hotel['imageUrl'],
                'amenities' => $hotel['amenities'],
                'latitude' => $hotel['latitude'],
                'longitude' => $hotel['longitude'],
                'url' => null,
            ];
        }

        return $out;
    }
}