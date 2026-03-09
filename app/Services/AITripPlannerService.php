<?php


namespace App\Services;

use Laravel\AI\AI;
use Illuminate\Support\Facades\Http;

class AITripPlannerService
{
    protected $ai;
    
    public function __construct()
    {
        $this->ai = AI::chat();
    }
    
    public function generateTripSuggestions($request)
    {
        $destination = $request->destination ?? 'Dubai';
        $duration = $request->duration ?? '3 days';
        $budget = $request->budget ?? '$500';
        $departure_date = $request->departure_date ?? 'next weekend';
        $interests = $request->interests ?? 'sightseeing, culture';
        
        $prompt = "I need a complete travel plan for {$destination} for {$duration}, leaving {$departure_date} with a budget of {$budget}. I'm interested in {$interests}. 
        
        Please provide:
        1. Flight recommendations (best airlines, times, price ranges)
        2. Hotel suggestions (3-4 star options with locations)
        3. Daily itinerary with activities
        4. Budget breakdown
        5. Local transportation options
        6. Must-try restaurants
        7. Cultural tips and local customs
        8. Packing recommendations
        
        Format the response as JSON with structured data.";
        
        try {
            $response = $this->ai->prompt($prompt)->generate();
            
            // Parse and structure the AI response
            return $this->parseAIResponse($response);
        } catch (\Exception $e) {
            return $this->getFallbackResponse($destination, $duration, $budget);
        }
    }
    
    public function generateLayoverSuggestions($layoverInfo)
    {
        $airport = $layoverInfo['airport'];
        $duration = $layoverInfo['duration'];
        $timeOfDay = $layoverInfo['time_of_day'];
        
        $prompt = "I have a {$duration} layover at {$airport} airport during {$timeOfDay}. 
        
        Please suggest:
        1. Airport amenities and services
        2. If time permits, quick city tours or attractions
        3. Food and shopping options
        4. Rest areas and lounges
        5. Transportation options to/from city
        6. Time recommendations for each activity
        
        Consider immigration/customs time and return travel time to airport.";
        
        try {
            $response = $this->ai->prompt($prompt)->generate();
            return $this->parseLayoverResponse($response);
        } catch (\Exception $e) {
            return $this->getFallbackLayoverResponse($airport, $duration);
        }
    }
    
    public function generatePackingList($tripDetails)
    {
        $destination = $tripDetails['destination'];
        $duration = $tripDetails['duration'];
        $season = $tripDetails['season'] ?? 'current season';
        $activities = $tripDetails['activities'] ?? 'general tourism';
        
        $prompt = "Create a comprehensive packing list for {$duration} trip to {$destination} during {$season}. 
        Activities planned: {$activities}.
        
        Categorize items by:
        1. Essential documents
        2. Clothing (weather-appropriate)
        3. Electronics and accessories
        4. Toiletries and health items
        5. Activity-specific gear
        6. Optional/comfort items
        
        Include quantity suggestions and local shopping alternatives.";
        
        try {
            $response = $this->ai->prompt($prompt)->generate();
            return $this->parsePackingResponse($response);
        } catch (\Exception $e) {
            return $this->getFallbackPackingList($destination);
        }
    }
    
    private function parseAIResponse($response)
    {
        // Try to parse JSON response, fallback to structured text parsing
        $decoded = json_decode($response, true);
        
        if ($decoded) {
            return $decoded;
        }
        
        // Fallback parsing for non-JSON responses
        return [
            'status' => 'success',
            'trip_plan' => [
                'flights' => $this->extractSection($response, 'flight'),
                'hotels' => $this->extractSection($response, 'hotel'),
                'itinerary' => $this->extractSection($response, 'itinerary'),
                'budget' => $this->extractSection($response, 'budget'),
                'transportation' => $this->extractSection($response, 'transport'),
                'restaurants' => $this->extractSection($response, 'restaurant'),
                'tips' => $this->extractSection($response, 'tip'),
                'packing' => $this->extractSection($response, 'pack')
            ],
            'raw_response' => $response
        ];
    }
    
    private function parseLayoverResponse($response)
    {
        return [
            'status' => 'success',
            'layover_guide' => [
                'airport_amenities' => $this->extractSection($response, 'amenities'),
                'city_tours' => $this->extractSection($response, 'tour'),
                'dining' => $this->extractSection($response, 'food'),
                'transportation' => $this->extractSection($response, 'transport'),
                'time_recommendations' => $this->extractSection($response, 'time')
            ],
            'raw_response' => $response
        ];
    }
    
    private function parsePackingResponse($response)
    {
        return [
            'status' => 'success',
            'packing_list' => [
                'documents' => $this->extractSection($response, 'document'),
                'clothing' => $this->extractSection($response, 'clothing'),
                'electronics' => $this->extractSection($response, 'electronic'),
                'toiletries' => $this->extractSection($response, 'toiletries'),
                'activity_gear' => $this->extractSection($response, 'gear'),
                'optional' => $this->extractSection($response, 'optional')
            ],
            'raw_response' => $response
        ];
    }
    
    private function extractSection($text, $keyword)
    {
        // Simple text extraction logic
        $lines = explode("\n", $text);
        $section = [];
        $capturing = false;
        
        foreach ($lines as $line) {
            if (stripos($line, $keyword) !== false) {
                $capturing = true;
                continue;
            }
            
            if ($capturing && (empty(trim($line)) || preg_match('/^\d+\./', trim($line)))) {
                if (!empty(trim($line))) {
                    $section[] = trim($line);
                }
            } elseif ($capturing && stripos($line, ':') !== false) {
                break;
            }
        }
        
        return $section ?: ['AI-generated suggestions will appear here'];
    }
    
    private function getFallbackResponse($destination, $duration, $budget)
    {
        return [
            'status' => 'fallback',
            'trip_plan' => [
                'flights' => ["Search for flights to {$destination} within {$budget} budget"],
                'hotels' => ["Look for 3-4 star hotels in {$destination} city center"],
                'itinerary' => ["Day 1: Arrival and city orientation", "Day 2: Main attractions", "Day 3: Local experiences"],
                'budget' => ["Flights: 60% of budget", "Hotels: 25% of budget", "Activities: 15% of budget"],
                'transportation' => ["Airport transfers", "Local public transport", "Taxi services"],
                'restaurants' => ["Try local cuisine", "Visit popular restaurants", "Street food experiences"],
                'tips' => ["Check visa requirements", "Learn basic local phrases", "Respect local customs"],
                'packing' => ["Weather-appropriate clothing", "Travel documents", "Camera and chargers"]
            ]
        ];
    }
    
    private function getFallbackLayoverResponse($airport, $duration)
    {
        return [
            'status' => 'fallback',
            'layover_guide' => [
                'airport_amenities' => ["Check airport website for current facilities"],
                'city_tours' => ["Available if layover is 6+ hours"],
                'dining' => ["Multiple restaurants and cafes available"],
                'transportation' => ["Airport shuttle, taxi, public transport"],
                'time_recommendations' => ["Allow 2 hours for international connections"]
            ]
        ];
    }
    
    private function getFallbackPackingList($destination)
    {
        return [
            'status' => 'fallback',
            'packing_list' => [
                'documents' => ['Passport', 'Visa', 'Travel insurance', 'Flight tickets'],
                'clothing' => ['Weather-appropriate outfits', 'Comfortable shoes', 'Extra underwear'],
                'electronics' => ['Phone charger', 'Camera', 'Power adapter', 'Portable battery'],
                'toiletries' => ['Toothbrush', 'Toothpaste', 'Shampoo', 'Medications'],
                'activity_gear' => ['Sunglasses', 'Hat', 'Backpack'],
                'optional' => ['Books', 'Games', 'Snacks', 'Travel pillow']
            ]
        ];
    }
}