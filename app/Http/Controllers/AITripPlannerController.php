<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AITripPlannerService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AITripPlannerController extends Controller
{
    protected $tripPlanner;
    
    public function __construct(AITripPlannerService $tripPlanner)
    {
        $this->tripPlanner = $tripPlanner;
    }
    
    public function generateTripPlan(Request $request)
    {
        $request->validate([
            'destination' => 'required|string|max:100',
            'duration' => 'required|string|max:50',
            'budget' => 'required|string|max:50',
            'departure_date' => 'nullable|string|max:50',
            'interests' => 'nullable|string|max:200'
        ]);
        
        $tripPlan = $this->tripPlanner->generateTripSuggestions($request->all());
        
        return response()->json([
            'success' => true,
            'data' => $tripPlan
        ]);
    }
    
    public function generateLayoverGuide(Request $request)
    {
        $request->validate([
            'airport' => 'required|string|max:10',
            'duration' => 'required|string|max:20',
            'time_of_day' => 'required|string|max:20'
        ]);
        
        $layoverGuide = $this->tripPlanner->generateLayoverSuggestions($request->all());
        
        return response()->json([
            'success' => true,
            'data' => $layoverGuide
        ]);
    }
    
     public function generatePackingList(Request $request)
    {
        $request->validate([
            'destination' => 'required|string|max:255',
            'duration' => 'required|string',
            'season' => 'required|string',
            'trip_type' => 'required|string',
            'special_requirements' => 'nullable|string',
        ]);
        
        try {
            $prompt = $this->buildPackingPrompt($request->all());
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->openaiApiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a professional travel packing expert. Create comprehensive, categorized packing lists based on destination, weather, and trip type.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'max_tokens' => 1800,
                'temperature' => 0.5,
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                $packingList = $this->parsePackingList($data['choices'][0]['message']['content'], $request->destination);
                
                return response()->json([
                    'success' => true,
                    'list' => $packingList
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate packing list'
            ], 500);
            
        } catch (\Exception $e) {
            Log::error('AI Packing List Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while generating your packing list'
            ], 500);
        }
    }
    
    private function buildTripPlanPrompt(array $data)
    {
        return "Create a detailed trip plan for {$data['destination']} with the following requirements:
        
        Duration: {$data['duration']}
        Budget: {$data['budget']}
        Departure: {$data['departure_date']}
        Interests: {$data['interests']}
        
        Please provide:
        1. Best time to visit and weather expectations
        2. Recommended flight booking strategy
        3. Hotel recommendations by budget tier
        4. Day-by-day itinerary with specific activities
        5. Local transportation tips
        6. Must-try food and dining recommendations
        7. Cultural tips and etiquette
        8. Budget breakdown estimate
        9. Essential items to pack
        10. Emergency contacts and useful phrases
        
        Format the response in a clear, organized manner with specific recommendations.";
    }
    
    private function buildLayoverPrompt(array $data)
    {
        return "Provide a comprehensive layover guide for {$data['airport']} airport with these details:
        
        Layover Duration: {$data['duration']}
        Time of Day: {$data['time_of_day']}
        
        Please include:
        1. Terminal facilities and amenities
        2. Rest areas and sleeping options
        3. Dining and shopping recommendations
        4. Free WiFi and charging stations
        5. Shower facilities
        6. If time permits, nearby attractions within the airport
        7. Transportation options if leaving the airport is feasible
        8. Time management tips for the layover
        9. Immigration and customs considerations
        10. Emergency services and contact information
        
        Focus on practical, time-specific advice based on the layover duration.";
    }
    
    private function buildPackingPrompt(array $data)
    {
        return "Create a comprehensive packing list for a trip to {$data['destination']} with these specifications:
        
        Duration: {$data['duration']}
        Season/Weather: {$data['season']}
        Trip Type: {$data['trip_type']}
        Special Requirements: {$data['special_requirements']}
        
        Organize the packing list into these categories:
        1. Clothing (weather-appropriate)
        2. Toiletries and Personal Care
        3. Electronics and Gadgets
        4. Documents and Travel Essentials
        5. Health and Medical Items
        6. Entertainment and Comfort
        7. Destination-Specific Items
        8. Emergency and Safety Items
        
        Consider baggage restrictions, local customs, and climate. Provide specific quantities where appropriate.";
    }
    
    private function parseTripPlan(string $content)
    {
        // Parse the AI response into structured data
        $sections = [
            'overview' => $this->extractSection($content, ['overview', 'summary', 'introduction']),
            'itinerary' => $this->extractSection($content, ['itinerary', 'day-by-day', 'schedule']),
            'accommodations' => $this->extractSection($content, ['hotel', 'accommodation', 'stay']),
            'activities' => $this->extractSection($content, ['activities', 'attractions', 'things to do']),
            'dining' => $this->extractSection($content, ['food', 'dining', 'restaurant']),
            'transportation' => $this->extractSection($content, ['transport', 'getting around', 'travel']),
            'budget' => $this->extractSection($content, ['budget', 'cost', 'expense']),
            'tips' => $this->extractSection($content, ['tips', 'advice', 'recommendation'])
        ];
        
        return [
            'content' => $content,
            'sections' => $sections,
            'generated_at' => now()->toDateTimeString()
        ];
    }
    
    private function parseLayoverGuide(string $content, string $airport)
    {
        // Parse layover guide into structured suggestions
        $suggestions = [
            [
                'title' => 'Terminal Facilities',
                'description' => $this->extractSection($content, ['terminal', 'facilities', 'amenities']),
                'time' => 'Anytime',
                'location' => 'Inside terminal'
            ],
            [
                'title' => 'Rest & Relaxation',
                'description' => $this->extractSection($content, ['rest', 'sleep', 'lounge']),
                'time' => 'Extended layovers',
                'location' => 'Various terminals'
            ],
            [
                'title' => 'Dining Options',
                'description' => $this->extractSection($content, ['dining', 'food', 'restaurant']),
                'time' => 'Meal times',
                'location' => 'Food courts'
            ],
            [
                'title' => 'Shopping & Activities',
                'description' => $this->extractSection($content, ['shopping', 'activities', 'entertainment']),
                'time' => '2+ hours',
                'location' => 'Retail areas'
            ]
        ];
        
        return [
            'airport' => strtoupper($airport),
            'suggestions' => $suggestions,
            'full_content' => $content
        ];
    }
    
    private function parsePackingList(string $content, string $destination)
    {
        // Parse packing list into categories
        $categories = [
            [
                'name' => 'Clothing',
                'icon' => 'shirt',
                'items' => $this->extractListItems($content, ['clothing', 'clothes', 'apparel'])
            ],
            [
                'name' => 'Toiletries',
                'icon' => 'droplets',
                'items' => $this->extractListItems($content, ['toiletries', 'personal care', 'hygiene'])
            ],
            [
                'name' => 'Electronics',
                'icon' => 'smartphone',
                'items' => $this->extractListItems($content, ['electronics', 'gadgets', 'devices'])
            ],
            [
                'name' => 'Documents',
                'icon' => 'file-text',
                'items' => $this->extractListItems($content, ['documents', 'papers', 'essentials'])
            ],
            [
                'name' => 'Health & Medical',
                'icon' => 'heart',
                'items' => $this->extractListItems($content, ['health', 'medical', 'medicine'])
            ],
            [
                'name' => 'Travel Accessories',
                'icon' => 'luggage',
                'items' => $this->extractListItems($content, ['accessories', 'travel', 'comfort'])
            ]
        ];
        
        return [
            'destination' => $destination,
            'categories' => $categories,
            'full_content' => $content
        ];
    }
    
    private function extractSection(string $content, array $keywords)
    {
        // Extract relevant section based on keywords
        $lines = explode("\n", $content);
        $section = '';
        $capturing = false;
        
        foreach ($lines as $line) {
            $lowercaseLine = strtolower($line);
            
            if (!$capturing) {
                foreach ($keywords as $keyword) {
                    if (strpos($lowercaseLine, $keyword) !== false) {
                        $capturing = true;
                        break;
                    }
                }
            }
            
            if ($capturing) {
                $section .= $line . "\n";
                
                // Stop capturing if we hit a new major section
                if (preg_match('/^\d+\./', $line) && strlen($section) > 100) {
                    break;
                }
            }
        }
        
        return trim($section) ?: 'Information not available';
    }
    
    private function extractListItems(string $content, array $keywords)
    {
        // Extract list items for packing categories
        $items = [];
        $lines = explode("\n", $content);
        
        foreach ($lines as $line) {
            $line = trim($line);
            
            // Look for bullet points, numbers, or dashes
            if (preg_match('/^[-•*]\s*(.+)$/', $line, $matches) ||
                preg_match('/^\d+\.\s*(.+)$/', $line, $matches)) {
                
                $item = trim($matches[1]);
                
                // Check if this item relates to our keywords
                foreach ($keywords as $keyword) {
                    if (strpos(strtolower($item), $keyword) !== false ||
                        strpos(strtolower($line), $keyword) !== false) {
                        $items[] = $item;
                        break;
                    }
                }
            }
        }
        
        // If no specific items found, provide default items
        if (empty($items)) {
            $items = $this->getDefaultItems($keywords[0]);
        }
        
        return array_slice($items, 0, 8); // Limit to 8 items per category
    }
    
    private function getDefaultItems(string $category)
    {
        $defaults = [
            'clothing' => ['T-shirts', 'Pants/Jeans', 'Underwear', 'Socks', 'Jacket', 'Comfortable shoes'],
            'toiletries' => ['Toothbrush', 'Toothpaste', 'Shampoo', 'Soap', 'Deodorant', 'Razor'],
            'electronics' => ['Phone charger', 'Camera', 'Power bank', 'Headphones', 'Adapter'],
            'documents' => ['Passport', 'Flight tickets', 'Hotel reservations', 'Travel insurance', 'ID'],
            'health' => ['Prescription medicines', 'First aid kit', 'Sunscreen', 'Hand sanitizer'],
            'travel' => ['Travel pillow', 'Eye mask', 'Luggage tags', 'Travel locks', 'Reusable water bottle']
        ];
        
        return $defaults[$category] ?? ['Essential items for ' . $category];
    }
}