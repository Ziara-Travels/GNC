<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Airport;
use Carbon\Carbon;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class WeatherController extends Controller
{
    private $apiKey;
    private $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.openweathermap.key');
        $this->baseUrl = 'https://api.openweathermap.org/data/2.5';
    }

    /**
     * Get current weather for a specific city
     */
    public function getCurrentWeather(Request $request)
    {
        $city = $request->input('city');
        $country = $request->input('country');
        
        if (!$city) {
            return response()->json([
                'success' => false,
                'message' => 'City is required'
            ], 400);
        }

        $cacheKey = "weather_{$city}_{$country}";
        
        try {
            $data = Cache::remember($cacheKey, 1800, function () use ($city, $country) {
                $query = $country ? "{$city},{$country}" : $city;
                
                $response = Http::get("{$this->baseUrl}/weather", [
                    'q' => $query,
                    'appid' => $this->apiKey,
                    'units' => 'metric'
                ]);

                if ($response->successful()) {
                    $result = $response->json();
                    return [
                        'city' => $result['name'],
                        'country' => $result['sys']['country'],
                        'temperature' => round($result['main']['temp']),
                        'feels_like' => round($result['main']['feels_like']),
                        'humidity' => $result['main']['humidity'],
                        'pressure' => $result['main']['pressure'],
                        'visibility' => isset($result['visibility']) ? $result['visibility'] / 1000 : null,
                        'wind_speed' => $result['wind']['speed'] ?? null,
                        'wind_direction' => $result['wind']['deg'] ?? null,
                        'description' => ucfirst($result['weather'][0]['description']),
                        'main' => $result['weather'][0]['main'],
                        'icon' => $result['weather'][0]['icon'],
                        'icon_url' => "https://openweathermap.org/img/wn/{$result['weather'][0]['icon']}@2x.png",
                        'sunrise' => Carbon::createFromTimestamp($result['sys']['sunrise'])->format('H:i'),
                        'sunset' => Carbon::createFromTimestamp($result['sys']['sunset'])->format('H:i'),
                        'last_updated' => now()->format('Y-m-d H:i:s')
                    ];
                }
                return null;
            });

            if ($data) {
                return response()->json([
                    'success' => true,
                    'data' => $data
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Weather data not available'
            ], 404);

        } catch (\Exception $e) {
            Log::error("Weather API error for {$city}: " . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch weather data'
            ], 500);
        }
    }

    /**
     * Get East African cities weather from database
     */
    public function getEastAfricanCitiesWeather()
    {
        try {
            // Get unique cities from airports table for East African countries
            $eastAfricanCountries = [
                'Kenya', 'Uganda', 'Tanzania', 'Burundi', 'Rwanda', 
                'Ethiopia', 'Djibouti', 'Somalia', 'Seychelles', 
                'Madagascar', 'Mauritius', 'Comoros', 'South Africa'
            ];

            $cities = Airport::whereIn('country', $eastAfricanCountries)
                ->select('city', 'country')
                ->distinct()
                ->orderBy('country')
                ->orderBy('city')
                ->get()
                ->map(function ($airport) {
                    return [
                        'name' => $airport->city,
                        'country' => $this->getCountryCode($airport->country)
                    ];
                })
                ->unique(function ($item) {
                    return $item['name'] . $item['country'];
                })
                ->values()
                ->take(20); // Limit to 20 cities to avoid API rate limits

            $weatherData = [];

            foreach ($cities as $city) {
                $cacheKey = "east_african_weather_{$city['name']}_{$city['country']}";
                
                try {
                    $data = Cache::remember($cacheKey, 1800, function () use ($city) {
                        $response = Http::timeout(10)->get("{$this->baseUrl}/weather", [
                            'q' => "{$city['name']},{$city['country']}",
                            'appid' => $this->apiKey,
                            'units' => 'metric'
                        ]);

                        if ($response->successful()) {
                            $result = $response->json();
                            return [
                                'city' => $result['name'],
                                'country' => $result['sys']['country'],
                                'country_name' => $this->getCountryName($result['sys']['country']),
                                'temperature' => round($result['main']['temp']),
                                'feels_like' => round($result['main']['feels_like']),
                                'humidity' => $result['main']['humidity'],
                                'description' => ucfirst($result['weather'][0]['description']),
                                'main' => $result['weather'][0]['main'],
                                'icon' => $result['weather'][0]['icon'],
                                'icon_url' => "https://openweathermap.org/img/wn/{$result['weather'][0]['icon']}@2x.png",
                                'wind_speed' => $result['wind']['speed'] ?? 0,
                                'last_updated' => now()->format('H:i')
                            ];
                        }
                        return null;
                    });

                    if ($data) {
                        $weatherData[] = $data;
                    }

                } catch (\Exception $e) {
                    Log::error("East African weather error for {$city['name']}: " . $e->getMessage());
                    continue;
                }
            }

            return response()->json([
                'success' => true,
                'data' => $weatherData,
                'count' => count($weatherData),
                'last_updated' => now()->format('Y-m-d H:i:s')
            ]);

        } catch (\Exception $e) {
            Log::error("East African cities weather error: " . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch weather data for East African cities'
            ], 500);
        }
    }

    /**
     * Get popular cities weather (database-driven)
     */
    public function getPopularCitiesWeather()
    {
        try {
            // Get popular cities from database based on airport traffic/importance
            $popularCities = Airport::whereIn('iata_code', [
                'NBO', 'DAR', 'ADD', 'JNB', 'CPT', 'EBB', 'KGL', 'MBA', 
                'JRO', 'ZNZ', 'MRU', 'SEZ', 'DUR', 'TNR'
            ])
            ->select('city', 'country')
            ->get()
            ->map(function ($airport) {
                return [
                    'name' => $airport->city,
                    'country' => $this->getCountryCode($airport->country)
                ];
            });

            $weatherData = [];

            foreach ($popularCities as $city) {
                $cacheKey = "popular_weather_{$city['name']}_{$city['country']}";
                
                try {
                    $data = Cache::remember($cacheKey, 1800, function () use ($city) {
                        $response = Http::timeout(10)->get("{$this->baseUrl}/weather", [
                            'q' => "{$city['name']},{$city['country']}",
                            'appid' => $this->apiKey,
                            'units' => 'metric'
                        ]);

                        if ($response->successful()) {
                            $result = $response->json();
                            return [
                                'city' => $result['name'],
                                'country' => $result['sys']['country'],
                                'country_name' => $this->getCountryName($result['sys']['country']),
                                'temperature' => round($result['main']['temp']),
                                'description' => ucfirst($result['weather'][0]['description']),
                                'icon' => $result['weather'][0]['icon'],
                                'icon_url' => "https://openweathermap.org/img/wn/{$result['weather'][0]['icon']}@2x.png"
                            ];
                        }
                        return null;
                    });

                    if ($data) {
                        $weatherData[] = $data;
                    }

                } catch (\Exception $e) {
                    Log::error("Popular cities weather error for {$city['name']}: " . $e->getMessage());
                    continue;
                }
            }

            return response()->json([
                'success' => true,
                'data' => $weatherData
            ]);

        } catch (\Exception $e) {
            Log::error("Popular cities weather error: " . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch popular cities weather'
            ], 500);
        }
    }

    /**
     * Get weather forecast for a city
     */
    public function getForecast(Request $request)
    {
        $city = $request->input('city');
        $country = $request->input('country');
        
        if (!$city) {
            return response()->json([
                'success' => false,
                'message' => 'City is required'
            ], 400);
        }

        $cacheKey = "forecast_{$city}_{$country}";
        
        try {
            $data = Cache::remember($cacheKey, 3600, function () use ($city, $country) {
                $query = $country ? "{$city},{$country}" : $city;
                
                $response = Http::get("{$this->baseUrl}/forecast", [
                    'q' => $query,
                    'appid' => $this->apiKey,
                    'units' => 'metric'
                ]);

                if ($response->successful()) {
                    $result = $response->json();
                    $forecasts = [];

                    foreach ($result['list'] as $item) {
                        $forecasts[] = [
                            'datetime' => $item['dt_txt'],
                            'temperature' => round($item['main']['temp']),
                            'description' => ucfirst($item['weather'][0]['description']),
                            'icon' => $item['weather'][0]['icon'],
                            'icon_url' => "https://openweathermap.org/img/wn/{$item['weather'][0]['icon']}@2x.png",
                            'humidity' => $item['main']['humidity'],
                            'wind_speed' => $item['wind']['speed']
                        ];
                    }

                    return [
                        'city' => $result['city']['name'],
                        'country' => $result['city']['country'],
                        'forecasts' => $forecasts
                    ];
                }
                return null;
            });

            if ($data) {
                return response()->json([
                    'success' => true,
                    'data' => $data
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Forecast data not available'
            ], 404);

        } catch (\Exception $e) {
            Log::error("Forecast API error for {$city}: " . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch forecast data'
            ], 500);
        }
    }

    /**
     * Get country code from country name
     */
    private function getCountryCode($countryName)
    {
        $countryCodes = [
            'Kenya' => 'KE',
            'Uganda' => 'UG',
            'Tanzania' => 'TZ',
            'Burundi' => 'BI',
            'Rwanda' => 'RW',
            'Ethiopia' => 'ET',
            'Djibouti' => 'DJ',
            'Somalia' => 'SO',
            'Seychelles' => 'SC',
            'Madagascar' => 'MG',
            'Mauritius' => 'MU',
            'Comoros' => 'KM',
            'South Africa' => 'ZA'
        ];

        return $countryCodes[$countryName] ?? $countryName;
    }

    /**
     * Get country name from country code
     */
    private function getCountryName($countryCode)
    {
        $countryNames = [
            'KE' => 'Kenya',
            'UG' => 'Uganda',
            'TZ' => 'Tanzania',
            'BI' => 'Burundi',
            'RW' => 'Rwanda',
            'ET' => 'Ethiopia',
            'DJ' => 'Djibouti',
            'SO' => 'Somalia',
            'SC' => 'Seychelles',
            'MG' => 'Madagascar',
            'MU' => 'Mauritius',
            'KM' => 'Comoros',
            'ZA' => 'South Africa'
        ];

        return $countryNames[$countryCode] ?? $countryCode;
    }

    /**
     * Clear weather cache
     */
    public function clearCache()
    {
        try {
            Cache::flush();
            
            return response()->json([
                'success' => true,
                'message' => 'Weather cache cleared successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear cache'
            ], 500);
        }
    }
}
