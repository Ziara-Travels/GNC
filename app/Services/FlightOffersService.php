<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class FlightOffersService
{
    /**
     * Get East African flight offers
     */
    public function getEastAfricanOffers()
    {
        // Cache flight deals for better performance
        return Cache::remember('east_african_offers', now()->addHours(12), function () {
            // This would typically be fetched from a database or external API
            // For demo purposes, we'll generate some mock data
            return $this->generateMockOffers();
        });
    }

    /**
     * Generate mock flight offers for demonstration
     */
    protected function generateMockOffers()
    {
        $offers = [];
        
        // East African destinations
        $destinations = [
            ['code' => 'NBO', 'name' => 'Nairobi'],
            ['code' => 'DAR', 'name' => 'Dar es Salaam'],
            ['code' => 'EBB', 'name' => 'Entebbe'],
            ['code' => 'JRO', 'name' => 'Kilimanjaro'],
            ['code' => 'ZNZ', 'name' => 'Zanzibar'],
            ['code' => 'MBA', 'name' => 'Mombasa']
        ];
        
        // Origin cities
        $origins = [
            ['code' => 'LHR', 'name' => 'London'],
            ['code' => 'JFK', 'name' => 'New York'],
            ['code' => 'DXB', 'name' => 'Dubai'],
            ['code' => 'CDG', 'name' => 'Paris'],
            ['code' => 'AMS', 'name' => 'Amsterdam']
        ];
        
        // Airlines
        $airlines = [
            'Kenya Airways',
            'Ethiopian Airlines',
            'RwandAir',
            'Emirates',
            'Qatar Airways'
        ];
        
        // Generate 8 random offers
        for ($i = 0; $i < 8; $i++) {
            $destination = $destinations[array_rand($destinations)];
            $origin = $origins[array_rand($origins)];
            $airline = $airlines[array_rand($airlines)];
            
            // Calculate random prices with discount
            $originalPrice = rand(800, 2000);
            $discount = rand(15, 35) / 100;
            $offerPrice = round($originalPrice * (1 - $discount));
            
            // Generate random flight number
            $flightNumber = strtoupper(substr($airline, 0, 2)) . rand(100, 999);
            
            // Random departure date in the next 3 months
            $departureDate = date('Y-m-d', strtotime('+' . rand(10, 90) . ' days'));
            
            // Random times
            $departureHour = str_pad(rand(0, 23), 2, '0', STR_PAD_LEFT);
            $departureMinute = str_pad(rand(0, 59), 2, '0', STR_PAD_LEFT);
            $departureTime = $departureHour . ':' . $departureMinute;
            
            // Flight duration between 6-12 hours
            $durationHours = rand(6, 12);
            $durationMinutes = rand(0, 59);
            
            // Calculate arrival time
            $arrivalTimestamp = strtotime("$departureDate $departureTime") + ($durationHours * 3600) + ($durationMinutes * 60);
            $arrivalTime = date('H:i', $arrivalTimestamp);
            $arrivalDate = date('Y-m-d', $arrivalTimestamp);
            
            // Create offer
            $offers[] = (object)[
                'id' => 'offer_' . ($i + 1),
                'origin' => $origin['code'],
                'originName' => $origin['name'],
                'destination' => $destination['code'],
                'destinationName' => $destination['name'],
                'departureDate' => date('M d, Y', strtotime($departureDate)),
                'departureTime' => $departureTime,
                'arrivalTime' => $arrivalTime,
                'arrivalDate' => date('M d, Y', $arrivalTimestamp),
                'airline' => $airline,
                'flightNumber' => $flightNumber,
                'duration' => $durationHours . 'h ' . $durationMinutes . 'm',
                'stops' => rand(0, 2),
                'originalPrice' => $originalPrice,
                'offerPrice' => $offerPrice,
                'isOfferFlight' => true
            ];
        }
        
        return $offers;
    }
    
    /**
     * Convert a regular flight to an offer flight
     */
    public function convertToOffer($flight)
    {
        $flight = clone $flight;
        
        // Apply discount (20-30%)
        $discount = rand(20, 30) / 100;
        $flight->originalPrice = $flight->price;
        $flight->offerPrice = round($flight->price * (1 - $discount));
        $flight->price = $flight->offerPrice;
        $flight->isOfferFlight = true;
        
        return $flight;
    }
    
    /**
     * Process flight booking (regular or offer)
     */
    public function processBooking($flightId, $isOffer = false)
    {
        if ($isOffer) {
            $flight = $this->getOfferById($flightId);
        } else {
            // Get flight from session
            $flight = session('processed_flights') ? collect(session('processed_flights'))->firstWhere('id', $flightId) : null;
        }
        
        if (!$flight) {
            return null;
        }
        
        // Process booking logic here
        // ...
        
        return $flight;
    }
    
    /**
     * Get an offer flight by ID
     */
    protected function getOfferById($id)
    {
        $offers = $this->getEastAfricanOffers();
        return collect($offers)->firstWhere('id', $id);
    }
    
    /**
     * Get airline information
     */
    public function getAirlineInfo($airlineName)
    {
        // Cache airline information
        return Cache::remember('airline_info_' . md5($airlineName), now()->addDays(30), function () use ($airlineName) {
            // In a real application, this would fetch airline information from a database or API
            $airlines = [
                'Kenya Airways' => [
                    'logo' => 'kenya-airways.png',
                    'code' => 'KQ',
                    'alliance' => 'SkyTeam'
                ],
                'Ethiopian Airlines' => [
                    'logo' => 'ethiopian-airlines.png',
                    'code' => 'ET',
                    'alliance' => 'Star Alliance'
                ],
                'RwandAir' => [
                    'logo' => 'rwandair.png',
                    'code' => 'WB',
                    'alliance' => null
                ],
                'Emirates' => [
                    'logo' => 'emirates.png',
                    'code' => 'EK',
                    'alliance' => null
                ],
                'Qatar Airways' => [
                    'logo' => 'qatar-airways.png',
                    'code' => 'QR',
                    'alliance' => 'Oneworld'
                ]
            ];
            
            return $airlines[$airlineName] ?? [
                'logo' => 'default.png',
                'code' => substr($airlineName, 0, 2),
                'alliance' => null
            ];
        });
    }
    
    /**
     * Get country information by code
     */
    public function getCountryInfo($countryCode)
    {
        // Cache country information
        return Cache::remember('country_info_' . $countryCode, now()->addDays(30), function () use ($countryCode) {
            // In a real application, this would fetch country information from a database or API
            $countries = [
                'KE' => [
                    'name' => 'Kenya',
                    'flag' => 'ke.png',
                    'currency' => 'KES'
                ],
                'TZ' => [
                    'name' => 'Tanzania',
                    'flag' => 'tz.png',
                    'currency' => 'TZS'
                ],
                'UG' => [
                    'name' => 'Uganda',
                    'flag' => 'ug.png',
                    'currency' => 'UGX'
                ],
                'RW' => [
                    'name' => 'Rwanda',
                    'flag' => 'rw.png',
                    'currency' => 'RWF'
                ],
                'ET' => [
                    'name' => 'Ethiopia',
                    'flag' => 'et.png',
                    'currency' => 'ETB'
                ]
            ];
            
            return $countries[$countryCode] ?? [
                'name' => 'Unknown',
                'flag' => 'default.png',
                'currency' => 'USD'
            ];
        });
    }
}
