<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

/**
 * RapidAPI Sky Scanner client.
 *
 * Talks to https://flights-sky.p.rapidapi.com - the "Flights Scraper Sky"
 * provider by ntd119 (https://rapidapi.com/ntd119/api/flights-sky). It
 * searches + compares prices sourced from Google Flights / Skyscanner /
 * booking.com style search engines. It is a METASEARCH only - it cannot
 * issue tickets.
 *
 * When no RAPIDAPI_KEY is configured (or SKYSCANNER_MOCK=true) the service
 * falls back to locally generated mock itineraries so the whole
 * search -> results -> select -> booking -> payment flow stays testable.
 */
class SkyscannerService
{
    protected $client;

    protected $apiKey;

    protected $host;

    protected $market;

    protected $locale;

    protected $currency;

    protected $mock;

    public function __construct()
    {
        $this->apiKey = (string) config('services.skyscanner.api_key', '');
        $this->host = (string) config('services.skyscanner.host', 'flights-sky.p.rapidapi.com');
        $this->market = (string) config('services.skyscanner.market', 'KE');
        $this->locale = (string) config('services.skyscanner.locale', 'en-KE');
        $this->currency = (string) config('services.skyscanner.currency', 'KES');
        $this->mock = (bool) config('services.skyscanner.mock', env('SKYSCANNER_MOCK', false));

        $this->client = new Client([
            'base_uri' => 'https://' . $this->host . '/',
            'timeout' => 30,
            'http_errors' => false,
        ]);
    }

    /**
     * True when the service will serve mock data instead of hitting RapidAPI.
     */
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

    /**
     * Low level request helper. Returns decoded array or null on failure.
     */
    protected function request($method, $endpoint, $options = [])
    {
        $options['headers'] = array_merge($this->headers(), $options['headers'] ?? []);

        try {
            $response = $this->client->request($method, $endpoint, $options);
            $body = (string) $response->getBody();
            $decoded = json_decode($body, true);

            $statusCode = $response->getStatusCode();
            if ($statusCode >= 400 || ($decoded === null && $body !== 'null')) {
                Log::warning('SkyScanner request failed', [
                    'endpoint' => $endpoint,
                    'status' => $statusCode,
                    'body' => mb_substr($body, 0, 500),
                ]);
                return null;
            }

            return $decoded;
        } catch (GuzzleException $e) {
            Log::warning('SkyScanner request exception', ['endpoint' => $endpoint, 'error' => $e->getMessage()]);
            return null;
        }
    }

    // ---------------------------------------------------------------------
    // Public endpoints
    // ---------------------------------------------------------------------

    /**
     * get-config - support markets / locales / currencies.
     */
    public function getConfig()
    {
        if ($this->isMock()) {
            return $this->mockConfig();
        }

        $data = $this->request('get', 'get-config', [
            'query' => ['market' => $this->market, 'locale' => $this->locale, 'currency' => $this->currency],
        ]);

        return is_array($data) ? $data : $this->mockConfig();
    }

    /**
     * web/flights/auto-complete - resolve a typed query into places (cities/airports).
     * Returns a normalized list:
     * [
     *   'iata_code'   display code (e.g. "NBO"),
     *   'name'        place name,
     *   'city'        city name,
     *   'country'     country name,
     *   'entity_id'   the id to send to the search endpoints,
     *   'entity_type'=> 'CITY'|'AIRPORT',
     *   'sky_id'      skyscanner sky id,
     *   ...
     * ]
     */
    public function autoComplete($query, $limit = 10)
    {
        $query = trim($query);
        if (mb_strlen($query) < 1) {
            return [];
        }

        if ($this->isMock()) {
            return $this->mockAutoComplete($query, $limit);
        }

        $data = $this->request('get', '/web/flights/auto-complete', [
            'query' => [
                'query' => $query,
                'market' => $this->market,
                'locale' => $this->locale,
                'currency' => $this->currency,
            ],
        ]);

        $data = is_array($data) ? $this->unwrapPayload($data) : [];
        $places = isset($data['places']) && is_array($data['places']) ? $data['places']
            : (isset($data['data']) && is_array($data['data']) ? $data['data'] : $data);
        $normalized = [];

        foreach ($places as $place) {
            $normalized[] = $this->normalizePlace($place);
        }

        return array_slice($normalized, 0, max(1, $limit));
    }

    /**
     * Best-effort resolution of an entity id from a typed query / airport code.
     * Falls back to the raw code when nothing useful comes back.
     */
    public function resolveEntityId($query, $iata = null)
    {
        $query = trim((string) $query);
        $fallback = $iata !== null && $iata !== '' ? strtoupper($iata) : $query;

        if (mb_strlen($query) < 1) {
            return $fallback;
        }

        if ($this->isMock()) {
            if ($fallback !== '') {
                return $fallback;
            }
            return $query;
        }

        $places = $this->autoComplete($query, 5);
        foreach ($places as $place) {
            if (!empty($place['entity_id'])) {
                return $place['entity_id'];
            }
        }

        return $fallback;
    }

    /**
     * Run a search across trip types and poll the incomplete endpoint until
     * results are ready. Returns the merged sky payload:
     * ['context' => ['status','sessionId','totalResults'], 'itineraries' => [...]]
     */
    public function searchFlights(array $params)
    {
        $tripType = $params['trip_type'] ?? 'one_way';
        $market = $params['market'] ?? $this->market;
        $locale = $params['locale'] ?? $this->locale;
        $currency = $params['currency'] ?? $this->currency;

        if ($this->isMock()) {
            return $this->mockSearch($params);
        }

        switch ($tripType) {
            case 'round_trip':
                $data = $this->searchRoundtrip($params);
                break;
            case 'multi_city':
                $data = $this->searchMultiCity($params);
                break;
            default:
                $data = $this->searchOneWay($params);
                break;
        }

        if (!is_array($data)) {
            return ['context' => ['status' => 'failed'], 'itineraries' => []];
        }

        $data = $this->unwrapPayload($data);

        $searchMetadata = $data['searchMetadata'] ?? [];
        $context = $data['context'] ?? [];
        $sessionId = $searchMetadata['sessionId'] ?? $context['sessionId'] ?? ($data['sessionId'] ?? null);
        $status = $searchMetadata['status'] ?? $context['status'] ?? 'complete';
        $totalResults = $searchMetadata['totalResults'] ?? $context['totalResults'] ?? null;

        // Poll until the search completes (or we run out of attempts).
        $attempts = 0;
        while ($status === 'incomplete' && $sessionId && $attempts < 8) {
            $attempts++;
            usleep(1200000);

            $poll = $this->searchIncomplete($sessionId, $data['itineraries'] ?? [], $market, $locale, $currency);
            if (!is_array($poll)) {
                break;
            }

            $poll = $this->unwrapPayload($poll);

            if (!empty($poll['itineraries'])) {
                $data['itineraries'] = $poll['itineraries'];
            }

            $pollMetadata = $poll['searchMetadata'] ?? [];
            $pollContext = $poll['context'] ?? [];
            $status = $pollMetadata['status'] ?? $pollContext['status'] ?? 'complete';
            $sessionId = $pollMetadata['sessionId'] ?? $pollContext['sessionId'] ?? ($poll['sessionId'] ?? $sessionId);
            $totalResults = $pollMetadata['totalResults'] ?? $pollContext['totalResults'] ?? $totalResults;
        }

        $data['context'] = [
            'status' => $status,
            'sessionId' => $sessionId,
            'totalResults' => $totalResults ?? count($data['itineraries'] ?? []),
        ];
        $data['itineraries'] = $data['itineraries'] ?? [];

        return $data;
    }

    protected function searchOneWay(array $params)
    {
        $query = array_filter([
            'fromEntityId' => $params['from_entity'] ?? null,
            'toEntityId' => $params['to_entity'] ?? null,
            'departDate' => $params['date'] ?? null,
            'market' => $params['market'] ?? $this->market,
            'locale' => $params['locale'] ?? $this->locale,
            'currency' => $params['currency'] ?? $this->currency,
            'adults' => (int) ($params['adults'] ?? 1),
            'children' => (int) ($params['children'] ?? 0),
            'infants' => (int) ($params['infants'] ?? 0),
            'cabinClass' => $this->cabinForApi($params['cabin'] ?? null),
            'limit' => (int) ($params['limit'] ?? 30),
        ], function ($v) {
            return $v !== null && $v !== '';
        });

        $data = $this->request('get', '/web/flights/search-one-way', ['query' => $query]);

        return is_array($data) ? $data : null;
    }

    protected function searchRoundtrip(array $params)
    {
        $query = array_filter([
            'fromEntityId' => $params['from_entity'] ?? null,
            'toEntityId' => $params['to_entity'] ?? null,
            'departDate' => $params['date'] ?? null,
            'returnDate' => $params['return_date'] ?? null,
            'market' => $params['market'] ?? $this->market,
            'locale' => $params['locale'] ?? $this->locale,
            'currency' => $params['currency'] ?? $this->currency,
            'adults' => (int) ($params['adults'] ?? 1),
            'children' => (int) ($params['children'] ?? 0),
            'infants' => (int) ($params['infants'] ?? 0),
            'cabinClass' => $this->cabinForApi($params['cabin'] ?? null),
            'limit' => (int) ($params['limit'] ?? 30),
        ], function ($v) {
            return $v !== null && $v !== '';
        });

        $data = $this->request('get', '/web/flights/search-roundtrip', ['query' => $query]);

        return is_array($data) ? $data : null;
    }

    protected function searchMultiCity(array $params)
    {
        $legs = [];
        $froms = $params['from_entity'] ?? [];
        $tos = $params['to_entity'] ?? [];
        $dates = $params['date'] ?? [];

        if (!is_array($froms)) {
            $froms = [$froms];
        }
        if (!is_array($tos)) {
            $tos = [$tos];
        }
        if (!is_array($dates)) {
            $dates = [$dates];
        }

        $count = min(count($froms), count($tos), count($dates));
        for ($i = 0; $i < $count; $i++) {
            if (empty($froms[$i]) || empty($tos[$i]) || empty($dates[$i])) {
                continue;
            }
            $legs[] = [
                'origin' => $froms[$i],
                'destination' => $tos[$i],
                'date' => $dates[$i],
            ];
        }

        if (empty($legs)) {
            return null;
        }

        $query = array_filter([
            'market' => $params['market'] ?? $this->market,
            'locale' => $params['locale'] ?? $this->locale,
            'currency' => $params['currency'] ?? $this->currency,
            'adults' => (int) ($params['adults'] ?? 1),
            'children' => (int) ($params['children'] ?? 0),
            'infants' => (int) ($params['infants'] ?? 0),
            'cabinClass' => $this->cabinForApi($params['cabin'] ?? null),
            'limit' => (int) ($params['limit'] ?? 30),
        ], function ($v) {
            return $v !== null && $v !== '';
        });

        $query['legs'] = json_encode($legs);

        $data = $this->request('get', '/web/flights/search-multi-city', ['query' => $query]);

        return is_array($data) ? $data : null;
    }

    /**
     * Poll an incomplete session.
     */
    protected function searchIncomplete($sessionId, $itineraries, $market, $locale, $currency)
    {
        $takeOffs = $this->extractTakeOffs($itineraries);

        $query = array_filter([
            'sessionId' => $sessionId,
            'market' => $market,
            'locale' => $locale,
            'currency' => $currency,
        ], function ($v) {
            return $v !== null && $v !== '';
        });

        if (!empty($takeOffs)) {
            $query['takeOffs'] = $takeOffs;
        }

        $data = $this->request('get', '/web/flights/search-incomplete', ['query' => $query]);

        return is_array($data) ? $data : null;
    }

    /**
     * web/flights/details - full itinerary detail for a token + itinerary id.
     * Useful for the booking/confirmation step to revalidate price & details.
     */
    public function getFlightDetails($token, $itineraryId)
    {
        if ($this->isMock()) {
            return ['status' => true, 'is_mock' => true, 'data' => ['id' => $itineraryId]];
        }

        $query = array_filter([
            'token' => $token,
            'itineraryId' => $itineraryId,
            'market' => $this->market,
            'locale' => $this->locale,
            'currency' => $this->currency,
        ], function ($v) {
            return $v !== null && $v !== '';
        });

        if (empty($query['token']) || empty($query['itineraryId'])) {
            return null;
        }

        $data = $this->request('get', '/web/flights/details', ['query' => $query]);

        return is_array($data) ? $this->unwrapPayload($data) : null;
    }

    /**
     * Flights-sky wraps the real payload inside a top-level "data" key.
     * Unwrap it when present so the rest of the service can read the
     * canonical ['context' => ..., 'itineraries' => ...] shape.
     */
    protected function unwrapPayload($data)
    {
        if (is_array($data) && isset($data['data']) && is_array($data['data']) && array_key_exists('itineraries', $data['data'])) {
            return $data['data'];
        }

        return is_array($data) ? $data : [];
    }

    protected function extractTakeOffs(array $itineraries)
    {
        $codes = [];
        foreach ($itineraries as $it) {
            foreach (($it['legs'] ?? []) as $leg) {
                foreach (($leg['segments'] ?? []) as $segment) {
                    $number = $segment['flightNumber'] ?? '';
                    if ($number === '') {
                        continue;
                    }
                    $codes[] = $this->formatFlightNumber($number);
                }
            }
        }

        return implode(',', array_slice(array_values(array_unique($codes)), 0, 20));
    }

    // ---------------------------------------------------------------------
    // Normalization (sky payload -> Amadeus-compatible + extended shape)
    // ---------------------------------------------------------------------

    /**
     * Convert a raw rapidapi sky-scanner payload plus search params into the
     * structure stored in session('flight_data').
     *
     * Kept Amadeus-compatible so the existing passenger/checkout/payment views
     * keep working: itineraries[].segments[] with departure.iataCode/at etc,
     * price.total/currency, travelerPricings[].travelerType.
     */
    public function normalizePayload(array $payload, array $params)
    {
        $context = $payload['context'] ?? [];
        $skyItineraries = $payload['itineraries'] ?? [];

        $offers = [];
        $airlines = [];

        foreach ($skyItineraries as $sky) {
            $offer = $this->normalizeOffer($sky, $params);
            if ($offer !== null) {
                $offers[] = $offer;
                foreach (($offer['airlines'] ?? []) as $code => $info) {
                    if (!isset($airlines[$code])) {
                        $airlines[$code] = $info;
                    }
                }
            }
        }

        return [
            'source' => 'skyscanner',
            'is_mock' => $this->isMock(),
            'context' => [
                'status' => $context['status'] ?? 'complete',
                'sessionId' => $context['sessionId'] ?? null,
                'totalResults' => $context['totalResults'] ?? count($offers),
            ],
            'searchParams' => $this->normalizeSearchParams($params),
            'data' => $offers,
            'airlines' => $airlines,
        ];
    }

    protected function normalizeSearchParams(array $params)
    {
        $from = $params['from'] ?? ($params['from_iata'] ?? '');
        $to = $params['to'] ?? ($params['to_iata'] ?? '');
        if (is_array($from)) {
            $from = array_map('strval', $from);
        }
        if (is_array($to)) {
            $to = array_map('strval', $to);
        }

        $fromLabel = is_array($from) ? implode(' → ', $from) : (string) $from;
        $toLabel = is_array($to) ? implode(' → ', $to) : (string) $to;
        $fromIataDisplay = is_array($params['from_iata'] ?? null)
            ? implode(',', array_map('strtoupper', $params['from_iata']))
            : strtoupper((string) ($params['from_iata'] ?? $fromLabel));
        $toIataDisplay = is_array($params['to_iata'] ?? null)
            ? implode(',', array_map('strtoupper', $params['to_iata']))
            : strtoupper((string) ($params['to_iata'] ?? $toLabel));

        return [
            'trip_type' => $params['trip_type'] ?? 'one_way',
            'from' => is_array($from) ? $from : $fromLabel,
            'to' => is_array($to) ? $to : $toLabel,
            'fromLabel' => $fromLabel,
            'toLabel' => $toLabel,
            'from_iata' => $fromIataDisplay,
            'to_iata' => $toIataDisplay,
            'from_entity' => $params['from_entity'] ?? null,
            'to_entity' => $params['to_entity'] ?? null,
            'date' => $params['date'] ?? null,
            'return_date' => $params['return_date'] ?? null,
            'adults' => (int) ($params['adults'] ?? 1),
            'children' => (int) ($params['children'] ?? 0),
            'infants' => (int) ($params['infants'] ?? 0),
            'travel_class' => $params['travel_class'] ?? 'ECONOMY',
            'cabin' => $this->cabinFromTravelClass($params['travel_class'] ?? 'ECONOMY'),
            'currency' => $params['currency'] ?? $this->currency,
        ];
    }

    protected function normalizeOffer(array $sky, array $params)
    {
        $legs = isset($sky['legs']) && is_array($sky['legs']) ? $sky['legs'] : [];
        $price = $sky['price'] ?? [];
        $priceRaw = isset($price['raw']) ? (float) $price['raw'] : 0.0;
        $currency = $price['currency'] ?? ($params['currency'] ?? $this->currency);
        $cabin = $this->cabinFromTravelClass($params['travel_class'] ?? 'ECONOMY');

        $itineraries = [];
        $airlines = [];

        foreach ($legs as $leg) {
            $segments = $leg['segments'] ?? [];

            $formattedSegments = [];
            foreach ($segments as $segment) {
                $formatted = $this->normalizeSegment($segment, $sky);
                if ($formatted !== null) {
                    $formattedSegments[] = $formatted;

                    $code = $formatted['carrierCode'] ?? '';
                    if ($code !== '' && !isset($airlines[$code])) {
                        $airlines[$code] = [
                            'code' => $code,
                            'name' => $formatted['carrierName'] ?? $code,
                            'logoUrl' => $formatted['carrierLogo'] ?? null,
                        ];
                    }
                }
            }

            if (empty($formattedSegments)) {
                continue;
            }

            $origin = $this->legEndpoint($leg, 'origin');
            $destination = $this->legEndpoint($leg, 'destination');

            $itineraries[] = [
                'duration' => $this->minutesToIso((int) ($leg['durationInMinutes'] ?? 0)),
                'durationInMinutes' => $leg['durationInMinutes'] ?? null,
                'stopCount' => $leg['stopCount'] ?? max(0, count($formattedSegments) - 1),
                'isReturn' => (bool) ($leg['isReturn'] ?? false),
                'segments' => $formattedSegments,
                'firstDeparture' => $leg['departure'] ?? null,
                'lastArrival' => $leg['arrival'] ?? null,
                'origin' => $origin,
                'destination' => $destination,
                'carriers' => $leg['carriers'] ?? [],
            ];
        }

        if (empty($itineraries)) {
            return null;
        }

        return [
            'id' => (string) ($sky['id'] ?? ''),
            'token' => (string) ($sky['token'] ?? ''),
            'agent' => (string) ($sky['agent'] ?? ''),
            'source' => 'skyscanner',
            'trip_type' => $params['trip_type'] ?? 'one_way',
            'price' => [
                'total' => $priceRaw,
                'base' => null,
                'taxes' => null,
                'currency' => $currency,
                'raw' => $priceRaw,
                'formatted' => (string) ($price['formatted'] ?? $this->formatPrice($priceRaw, $currency)),
            ],
            'travelers' => [
                'adults' => (int) ($params['adults'] ?? 1),
                'children' => (int) ($params['children'] ?? 0),
                'infants' => (int) ($params['infants'] ?? 0),
            ],
            'travelerPricings' => $this->buildTravelerPricings($params),
            'itineraries' => $itineraries,
            'legs' => array_values($legs),
            'airlines' => $airlines,
            'isReturn' => count($itineraries) > 1,
        ];
    }

    protected function normalizeSegment(array $segment, array $sky)
    {
        $origin = $segment['origin'] ?? [];
        $destination = $segment['destination'] ?? [];
        $carriers = $segment['carriers'] ?? [];
        $marketing = $carriers['marketing'][0] ?? null;

        $code = $marketing['code'] ?? $marketing['name'] ?? null;
        if ($code === null) {
            $code = $this->extractCarrierCode((string) ($segment['flightNumber'] ?? ''));
            if ($code === '') {
                $code = '--';
            }
        }

        $flightNumber = (string) ($segment['flightNumber'] ?? '');
        $number = $this->extractFlightNumber($flightNumber);
        $durationInMinutes = $segment['durationInMinutes'] ?? null;

        $seg = [
            'id' => (string) ($segment['id'] ?? ''),
            'departure' => [
                'iataCode' => $this->endpointCode($origin, $sky, $segment['departure'] ?? null),
                'at' => $segment['departure'] ?? null,
            ],
            'arrival' => [
                'iataCode' => $this->endpointCode($destination, $sky, $segment['arrival'] ?? null),
                'at' => $segment['arrival'] ?? null,
            ],
            'number' => $number,
            'carrierCode' => $code,
            'carrierName' => $marketing['name'] ?? $code,
            'carrierLogo' => $marketing['logoUrl'] ?? null,
            'operating' => $marketing['name'] ?? null,
            'duration' => $durationInMinutes !== null ? $this->minutesToIso((int) $durationInMinutes) : null,
            'durationInMinutes' => $durationInMinutes,
            'flightNumber' => $flightNumber !== '' ? $this->formatFlightNumber($flightNumber) : null,
            'aircraft' => isset($segment['aircraft']['name']) ? $segment['aircraft']['name'] : null,
            'origin' => [
                'displayCode' => $this->endpointCode($origin, $sky, null),
                'name' => $origin['name'] ?? ($origin['displayCode'] ?? ''),
                'city' => $origin['city'] ?? '',
                'country' => $origin['country'] ?? '',
            ],
            'destination' => [
                'displayCode' => $this->endpointCode($destination, $sky, null),
                'name' => $destination['name'] ?? ($destination['displayCode'] ?? ''),
                'city' => $destination['city'] ?? '',
                'country' => $destination['country'] ?? '',
            ],
        ];

        if (isset($segment['departureToken'])) {
            $seg['departure']['terminal'] = $segment['departureToken'];
        }
        if (isset($segment['arrivalToken'])) {
            $seg['arrival']['terminal'] = $segment['arrivalToken'];
        }

        return $seg;
    }

    protected function legEndpoint(array $leg, $which)
    {
        $endpoint = isset($leg[$which]) && is_array($leg[$which]) ? $leg[$which]
            : (isset($leg[$which === 'origin' ? 'end' : 'origin']) && $which === 'destination' ? $leg['end'] : []);

        if ($which === 'destination' && empty($endpoint) && isset($leg['end'])) {
            $endpoint = $leg['end'];
        }

        return [
            'displayCode' => $this->endpointCode($endpoint, [], null),
            'name' => $endpoint['name'] ?? ($endpoint['displayCode'] ?? ''),
            'city' => $endpoint['city'] ?? '',
            'country' => $endpoint['country'] ?? '',
        ];
    }

    protected function endpointCode($endpoint, $sky, $iso)
    {
        if (is_array($endpoint) && !empty($endpoint['displayCode'])) {
            return strtoupper($endpoint['displayCode']);
        }
        if (is_array($endpoint) && !empty($endpoint['code'])) {
            return strtoupper($endpoint['code']);
        }
        return null;
    }

    protected function buildTravelerPricings(array $params)
    {
        $pricings = [];
        $cabin = $this->cabinFromTravelClass($params['travel_class'] ?? 'ECONOMY');

        $add = function ($type, $count) use (&$pricings, $cabin) {
            for ($i = 0; $i < $count; $i++) {
                $pricings[] = [
                    'travelerType' => $type,
                    'fareDetailsBySegment' => [
                        ['cabin' => $cabin],
                    ],
                ];
            }
        };

        $add('ADULT', (int) ($params['adults'] ?? 1));
        $add('CHILD', (int) ($params['children'] ?? 0));
        $add('HELD_INFANT', (int) ($params['infants'] ?? 0));

        return $pricings;
    }

    protected function cabinFromTravelClass($travelClass)
    {
        $travelClass = strtoupper((string) $travelClass);
        if (in_array($travelClass, ['PREMIUM_ECONOMY', 'BUSINESS', 'FIRST'])) {
            return $travelClass;
        }
        return 'ECONOMY';
    }

    protected function cabinForApi($travelClass)
    {
        $cabin = $this->cabinFromTravelClass($travelClass);
        return $cabin === 'PREMIUM_ECONOMY' ? 'premium_economy' : strtolower($cabin);
    }

    protected function extractCarrierCode($flightNumber)
    {
        $flightNumber = trim((string) $flightNumber);
        $parts = preg_split('/\s+/', $flightNumber);
        if (count($parts) > 1 && $parts[0] !== '') {
            return strtoupper($parts[0]);
        }
        if (preg_match('/^([A-Za-z]+)\d+$/', $flightNumber, $m)) {
            return strtoupper($m[1]);
        }
        return '';
    }

    protected function extractFlightNumber($flightNumber)
    {
        if (preg_match('/(\d+)$/', trim((string) $flightNumber), $m)) {
            return $m[1];
        }
        return '';
    }

    protected function formatFlightNumber($flightNumber)
    {
        $code = $this->extractCarrierCode($flightNumber);
        $number = $this->extractFlightNumber($flightNumber);

        if ($code !== '' && $number !== '') {
            return $code . ':' . $number;
        }

        return $flightNumber;
    }

    protected function minutesToIso($minutes)
    {
        $minutes = max(0, (int) $minutes);
        $hours = floor($minutes / 60);
        $remaining = $minutes % 60;

        return sprintf('PT%dH%dM', $hours, $remaining);
    }

    public function formatPrice($amount, $currency = 'KES')
    {
        $currency = strtoupper((string) $currency);
        $symbol = $currency === 'KES' ? 'KSh ' : ($currency === 'USD' ? '$' : $currency . ' ');

        return $symbol . number_format((float) $amount, 2);
    }

    // ---------------------------------------------------------------------
    // Place normalization (autocomplete)
    // ---------------------------------------------------------------------

    protected function normalizePlace(array $place)
    {
        $presentation = $place['presentation'] ?? [];
        $navigation = $place['navigation'] ?? [];
        $entityId = $navigation['entityId'] ?? ($place['entityId'] ?? null);
        $skyId = $navigation['skyId'] ?? ($place['skyId'] ?? $place['id'] ?? null);
        $entityType = $navigation['entityType'] ?? ($place['entityType'] ?? 'CITY');

        $title = $presentation['suggestionTitle'] ?? ($presentation['title'] ?? ($place['name'] ?? ''));
        $subtitle = $presentation['subtitle'] ?? ($place['name'] ?? '');

        // Extract a usable code. For airports the skyId often ends with "-sky".
        $code = $presentation['suggestionSubtitle'] ?? null;
        if ($code === null) {
            $code = $this->extractPlaceCode($title, $skyId);
        }

        $city = $this->extractCity($title, $subtitle);

        return [
            'iata_code' => strtoupper($code),
            'code' => strtoupper($code),
            'name' => $title,
            'city' => $city,
            'country' => $this->extractCountry($title, $subtitle),
            'entity_id' => (string) $entityId,
            'sky_id' => (string) $skyId,
            'entity_type' => $entityType,
            'display_name' => $title . ($subtitle !== '' && $subtitle !== $title ? ', ' . $subtitle : ''),
            'location_description' => $subtitle,
            'full_display' => ($code !== '' ? strtoupper($code) . ' - ' : '') . $title . ($subtitle !== '' ? ', ' . $subtitle : ''),
        ];
    }

    protected function extractPlaceCode($title, $skyId)
    {
        // "Nairobi (Any)" -> the skyId for airports is like "NBO-sky".
        if (preg_match('/\(([A-Za-z]{3})\)/', (string) $title, $m)) {
            return $m[1];
        }

        $skyId = (string) $skyId;
        if ($skyId !== '') {
            return preg_replace('/-sky$/i', '', $skyId);
        }

        return '';
    }

    protected function extractCity($title)
    {
        $title = (string) $title;
        $city = preg_replace('/\s*\(.*\)\s*$/', '', $title);

        $parts = preg_split('/[\s,-]+/', $city, 2);
        return trim($parts[0] ?? $city);
    }

    protected function extractCountry($title, $subtitle)
    {
        $subtitle = (string) $subtitle;
        $parts = explode(',', $subtitle);
        $country = trim(end($parts));
        return $country;
    }

    // ---------------------------------------------------------------------
    // Mock mode
    // ---------------------------------------------------------------------

    protected function mockConfig()
    {
        return [
            'data' => [
                'markets' => ['KE'],
                'locales' => ['en-KE'],
                'currencies' => ['KES'],
            ],
            'status' => true,
            'message' => null,
            'is_mock' => true,
        ];
    }

    protected function mockAutoComplete($query, $limit = 10)
    {
        $query = strtolower(trim($query));

        $places = [
            ['NBO', 'Nairobi', 'Kenya', 'NBO-sky', 'CITY'],
            ['MBA', 'Mombasa', 'Kenya', 'MBA-sky', 'CITY'],
            ['EBB', 'Kampala/Entebbe', 'Uganda', 'EBB-sky', 'CITY'],
            ['DAR', 'Dar es Salaam', 'Tanzania', 'DAR-sky', 'CITY'],
            ['ZNZ', 'Zanzibar', 'Tanzania', 'ZNZ-sky', 'CITY'],
            ['JRO', 'Kilimanjaro', 'Tanzania', 'JRO-sky', 'CITY'],
            ['KGL', 'Kigali', 'Rwanda', 'KGL-sky', 'CITY'],
            ['ADD', 'Addis Ababa', 'Ethiopia', 'ADD-sky', 'CITY'],
            ['JUB', 'Juba', 'South Sudan', 'JUB-sky', 'CITY'],
            ['KIS', 'Kisumu', 'Kenya', 'KIS-sky', 'CITY'],
            ['EDL', 'Eldoret', 'Kenya', 'EDL-sky', 'CITY'],
            ['MYD', 'Malindi', 'Kenya', 'MYD-sky', 'CITY'],
            ['LHR', 'London', 'United Kingdom', 'LHR-sky', 'CITY'],
            ['DXB', 'Dubai', 'United Arab Emirates', 'DXB-sky', 'CITY'],
            ['JFK', 'New York', 'United States', 'JFK-sky', 'CITY'],
            ['CDG', 'Paris', 'France', 'CDG-sky', 'CITY'],
            ['AMS', 'Amsterdam', 'Netherlands', 'AMS-sky', 'CITY'],
        ];

        $results = [];
        foreach ($places as $place) {
            $city = $place[1];
            $iata = $place[0];
            $haystack = strtolower(implode(' ', $place));
            if ($query === '' || strpos($haystack, $query) !== false || strpos($city, $query) !== false || $query === strtolower($iata)) {
                $results[] = [
                    'iata_code' => $iata,
                    'code' => $iata,
                    'name' => $city . ' (Any)',
                    'city' => $city,
                    'country' => $place[2],
                    'entity_id' => $iata,
                    'sky_id' => $place[3],
                    'entity_type' => $place[4],
                    'display_name' => $city . ' (' . $iata . ')',
                    'location_description' => $city . ', ' . $place[2],
                    'full_display' => $iata . ' - ' . $city . ', ' . $place[2],
                ];
            }
        }

        // If nothing matched, surface a few popular ones so the dropdown never feels dead.
        if (empty($results)) {
            $results = array_slice($places, 0, 6);
            foreach ($results as &$place) {
                $place = [
                    'iata_code' => $place[0],
                    'code' => $place[0],
                    'name' => $place[1] . ' (Any)',
                    'city' => $place[1],
                    'country' => $place[2],
                    'entity_id' => $place[0],
                    'sky_id' => $place[3],
                    'entity_type' => $place[4],
                    'display_name' => $place[1] . ' (' . $place[0] . ')',
                    'location_description' => $place[1] . ', ' . $place[2],
                    'full_display' => $place[0] . ' - ' . $place[1] . ', ' . $place[2],
                ];
            }
            unset($place);
        }

        return array_slice($results, 0, max(1, $limit));
    }

    protected function mockSearch(array $params)
    {
        $from = strtoupper((string) ($params['from_iata'] ?? 'NBO'));
        $to = strtoupper((string) ($params['to_iata'] ?? 'EBB'));
        $tripType = $params['trip_type'] ?? 'one_way';

        $itineraries = $this->mockItineraries($from, $to, $params);

        if ($tripType === 'round_trip' && !empty($params['return_date'])) {
            $returnLegs = $this->mockItineraries($to, $from, array_merge($params, [
                'date' => $params['return_date'],
                'trips' => array_reverse($params['trips'] ?? []),
                'trip_index' => null,
            ]));

            // Combine outbound + return into single offers.
            $combined = [];
            foreach ($itineraries as $outbound) {
                foreach ($returnLegs as $inbound) {
                    $total = $outbound['price']['raw'] + $inbound['price']['raw'];
                    $itId = $outbound['id'] . '-' . $inbound['id'];
                    $combined[] = [
                        'id' => $itId,
                        'token' => 'mock-' . $itId,
                        'agent' => 'Mock',
                        'price' => [
                            'raw' => $total,
                            'formatted' => $this->formatPrice($total, $this->currency),
                            'currency' => $this->currency,
                        ],
                        'legs' => array_merge($outbound['legs'], $inbound['legs']),
                    ];
                }
            }
            $itineraries = $combined;
        }

        // Add the isReturn flag on return legs.
        foreach ($itineraries as $i => $it) {
            $legCount = count($it['legs'] ?? []);
            foreach (($it['legs'] ?? []) as $l => $leg) {
                $it['legs'][$l]['isReturn'] = $legCount > 1 && $l === 1;
            }
            $itineraries[$i] = $it;
        }

        return [
            'context' => [
                'status' => 'complete',
                'sessionId' => 'mock-' . md5($from . '-' . $to . '-' . json_encode($params)),
                'totalResults' => count($itineraries),
            ],
            'itineraries' => $itineraries,
            'is_mock' => true,
        ];
    }

    protected function mockItineraries($from, $to, array $params)
    {
        $date = $params['date'] ?? date('Y-m-d', strtotime('+7 days'));
        $fromName = $from;
        $toName = $to;

        $airlineSets = [
            ['KQ', 'Kenya Airways', 3, 0],
            ['QF', 'Qatar Airways', 5, 1],
            ['ET', 'Ethiopian Airlines', 6, 0],
            ['EK', 'Emirates', 8, 1],
            ['TK', 'Turkish Airlines', 7, 1],
        ];

        $results = [];
        for ($i = 0; $i < 5; $i++) {
            $carrier = $airlineSets[$i];
            $departureHour = 6 + ($i * 3) % 12;
            $dep = date('Y-m-d') . 'T' . sprintf('%02d:%02d:00', $departureHour, ($i * 11) % 60);
            $arr = date('Y-m-d', strtotime($date)) . 'T' . sprintf('%02d:%02d:00', min(23, $departureHour + 2 + $carrier[2]), 20);
            $base = 12000 + ($i * 2750) + (($i % 3) * 1400);

            $segments = [];

            if ($carrier[3] === 1) {
                $hub = ['NBO' => 'ADD', 'EBB' => 'ADD', 'DAR' => 'NBO', 'ZNZ' => 'NBO', 'JRO' => 'NBO'][$from] ?? 'ADD';
                $segments[] = $this->mockSegment($from, $hub, $date, sprintf('%02d:00:00', 5 + $i), 'KQ ' . (101 + $i), $carrier[0], $carrier[1]);
                $segments[] = $this->mockSegment($hub, $to, $date, sprintf('%02d:30:00', 8 + $i), 'ET ' . (401 + $i), $carrier[0], $carrier[1]);
            } else {
                $segments[] = $this->mockSegment($from, $to, $date, sprintf('%02d:%02d:00', $departureHour, $i * 7), $carrier[0] . ' ' . (201 + $i), $carrier[0], $carrier[1]);
            }

            $itId = 'mock-' . $from . $to . '-' . $i . '-' . substr(md5($carrier[0] . $i), 0, 6);

            $results[] = [
                'id' => $itId,
                'token' => 'mock-' . $itId,
                'agent' => $carrier[1],
                'price' => [
                    'raw' => $base,
                    'formatted' => $this->formatPrice($base, $this->currency),
                    'currency' => $this->currency,
                ],
                'legs' => [
                    [
                        'departure' => $segments[0]['departure'],
                        'arrival' => $segments[count($segments) - 1]['arrival'],
                        'durationInMinutes' => 120 + ($i * 40),
                        'stopCount' => count($segments) - 1,
                        'origin' => ['code' => $from, 'displayCode' => $from, 'name' => $fromName, 'city' => $fromName, 'country' => ''],
                        'destination' => ['code' => $to, 'displayCode' => $to, 'name' => $toName, 'city' => $toName, 'country' => ''],
                        'carriers' => [
                            'marketing' => [
                                ['code' => $carrier[0], 'name' => $carrier[1], 'logoUrl' => ''],
                            ],
                        ],
                        'segments' => $segments,
                    ],
                ],
            ];
        }

        // Add a cheap option so "Cheapest" sort is meaningful.
        $results[] = [
            'id' => 'mock-' . $from . $to . '-budget-' . substr(md5($from . $to), 0, 6),
            'token' => 'mock-budget-' . substr(md5($from . $to), 0, 6),
            'agent' => 'Jambojet',
            'price' => [
                'raw' => 7800,
                'formatted' => $this->formatPrice(7800, $this->currency),
                'currency' => $this->currency,
            ],
            'legs' => [
                [
                    'departure' => date('Y-m-d', strtotime($date)) . 'T06:00:00',
                    'arrival' => date('Y-m-d', strtotime($date)) . 'T07:15:00',
                    'durationInMinutes' => 75,
                    'stopCount' => 0,
                    'origin' => ['code' => $from, 'displayCode' => $from, 'name' => $fromName, 'city' => $fromName, 'country' => ''],
                    'destination' => ['code' => $to, 'displayCode' => $to, 'name' => $toName, 'city' => $toName, 'country' => ''],
                    'carriers' => [
                        'marketing' => [
                            ['code' => 'JM', 'name' => 'Jambojet', 'logoUrl' => ''],
                        ],
                    ],
                    'segments' => [
                        $this->mockSegment($from, $to, $date, '06:00:00', 'JM 400', 'JM', 'Jambojet'),
                    ],
                ],
            ],
        ];

        return $results;
    }

    protected function mockSegment($from, $to, $date, $time, $flightNumber, $code, $name)
    {
        return [
            'id' => 'seg-' . substr(md5($from . $to . $flightNumber), 0, 8),
            'origin' => ['code' => $from, 'displayCode' => $from, 'name' => $from, 'city' => $from, 'country' => ''],
            'destination' => ['code' => $to, 'displayCode' => $to, 'name' => $to, 'city' => $to, 'country' => ''],
            'departure' => date('Y-m-d', strtotime($date)) . 'T' . $time,
            'arrival' => date('Y-m-d', strtotime($date)) . 'T' . (date('H', strtotime($time) + 3600 * 2)) . ':15:00',
            'durationInMinutes' => 135,
            'carriers' => [
                'marketing' => [
                    ['code' => $code, 'name' => $name, 'logoUrl' => ''],
                ],
            ],
            'flightNumber' => $flightNumber,
        ];
    }
}