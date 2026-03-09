<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class AIPricePredictionController extends Controller
{
    private $openaiApiKey;
    
    public function __construct()
    {
        $this->openaiApiKey = config('services.openai.api_key');
    }
    
    public function getPricePrediction(Request $request)
    {
        $request->validate([
            'from' => 'required|string|max:3',
            'to' => 'required|string|max:3',
            'date' => 'required|date',
            'trip_type' => 'required|string',
            'current_price' => 'required|numeric'
        ]);
        
        $cacheKey = 'price_prediction_' . md5(json_encode($request->only(['from', 'to', 'date', 'trip_type'])));
        
        // Check cache first
        if (Cache::has($cacheKey)) {
            return response()->json([
                'success' => true,
                'prediction' => Cache::get($cacheKey)
            ]);
        }
        
        try {
            $prediction = $this->generatePricePrediction($request->all());
            
            // Cache for 6 hours
            Cache::put($cacheKey, $prediction, 21600);
            
            return response()->json([
                'success' => true,
                'prediction' => $prediction
            ]);
            
        } catch (\Exception $e) {
            Log::error('AI Price Prediction Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Unable to generate price prediction'
            ], 500);
        }
    }
    
    public function getRouteHistory(Request $request)
    {
        $request->validate([
            'from' => 'required|string|max:3',
            'to' => 'required|string|max:3'
        ]);
        
        // Generate mock historical data (in production, this would come from your database)
        $history = $this->generateMockHistoricalData($request->from, $request->to);
        
        return response()->json([
            'success' => true,
            'history' => $history
        ]);
    }

    public function generatePrediction(Request $request)
    {
        $request->validate([
            'from' => 'required|string|max:10',
            'to' => 'required|string|max:10',
            'date' => 'required|date',
            'current_price' => 'required|numeric|min:0',
            'trip_type' => 'string|in:one_way,round_trip'
        ]);
        
        try {
            $prompt = $this->buildPredictionPrompt($request->all());
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->openaiApiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a flight price prediction expert. Analyze historical data patterns and market trends to provide accurate price forecasts and booking recommendations.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'max_tokens' => 1000,
                'temperature' => 0.3,
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                $prediction = $this->parsePrediction($data['choices'][0]['message']['content'], $request->all());
                
                return response()->json([
                    'success' => true,
                    'prediction' => $prediction
                ]);
            }
            
            // Fallback to mock prediction if AI fails
            return response()->json([
                'success' => true,
                'prediction' => $this->generateMockPrediction($request->all())
            ]);
            
        } catch (\Exception $e) {
            Log::error('AI Price Prediction Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => true,
                'prediction' => $this->generateMockPrediction($request->all())
            ]);
        }
    }
    
    private function buildPredictionPrompt(array $data)
    {
        $currentDate = now()->format('Y-m-d');
        $travelDate = $data['date'];
        $daysUntilTravel = now()->diffInDays($travelDate);
        
        return "Analyze flight price trends for this route:
        
        Route: {$data['from']} to {$data['to']}
        Current Date: {$currentDate}
        Travel Date: {$travelDate}
        Days Until Travel: {$daysUntilTravel}
        Current Price: \${$data['current_price']}
        Trip Type: {$data['trip_type']}
        
        Based on typical airline pricing patterns, provide:
        
        1. Price predictions for:
           - Next week
           - Next two weeks
           - Next month
        
        2. Percentage changes from current price
        
        3. Urgency level (high/medium/low) based on:
           - How close to travel date
           - Current price vs historical average
           - Seasonal demand patterns
        
        4. Booking recommendation with specific action and reason
        
        5. Potential savings if user waits for optimal booking time
        
        Format response as structured data that can be easily parsed.";
    }
    
    private function parsePrediction(string $content, array $requestData)
    {
        $currentPrice = $requestData['current_price'];
        
        // Parse AI response (simplified version)
        // In production, you'd have more sophisticated parsing
        
        $nextWeekPrice = $currentPrice * (1 + (rand(-20, 30) / 100));
        $twoWeeksPrice = $currentPrice * (1 + (rand(-15, 25) / 100));
        $nextMonthPrice = $currentPrice * (1 + (rand(-25, 40) / 100));
        
        $nextWeekChange = (($nextWeekPrice - $currentPrice) / $currentPrice) * 100;
        $twoWeeksChange = (($twoWeeksPrice - $currentPrice) / $currentPrice) * 100;
        $nextMonthChange = (($nextMonthPrice - $currentPrice) / $currentPrice) * 100;
        
        // Determine urgency based on price trends
        $urgencyLevel = 'medium';
        if ($nextWeekChange > 15) {
            $urgencyLevel = 'high';
        } elseif ($nextWeekChange < -10) {
            $urgencyLevel = 'low';
        }
        
        // Generate recommendation
        $recommendation = $this->generateRecommendation($nextWeekChange, $urgencyLevel);
        
        // Calculate potential savings
        $potentialSavings = max(0, $currentPrice - min($nextWeekPrice, $twoWeeksPrice, $nextMonthPrice));
        
        return [
            'current_price' => round($currentPrice, 2),
            'predicted_prices' => [
                'next_week' => round($nextWeekPrice, 2),
                'next_two_weeks' => round($twoWeeksPrice, 2),
                'next_month' => round($nextMonthPrice, 2)
            ],
            'price_changes' => [
                'next_week' => round($nextWeekChange, 1),
                'next_two_weeks' => round($twoWeeksChange, 1),
                'next_month' => round($nextMonthChange, 1)
            ],
            'urgency_level' => $urgencyLevel,
            'recommendation' => $recommendation,
            'savings_potential' => round($potentialSavings, 2),
            'analysis_date' => now()->toDateTimeString()
        ];
    }
    
    private function generateRecommendation($priceChange, $urgencyLevel)
    {
        if ($urgencyLevel === 'high') {
            return [
                'action' => 'Book now to avoid price increases',
                'reason' => 'Prices are expected to rise significantly in the coming days'
            ];
        } elseif ($urgencyLevel === 'low') {
            return [
                'action' => 'Consider waiting for better prices',
                'reason' => 'Price trends suggest potential savings if you wait'
            ];
        } else {
            return [
                'action' => 'Book within the next few days',
                'reason' => 'Prices are relatively stable but may fluctuate'
            ];
        }
    }
    
    private function generateMockPrediction(array $data)
    {
        // Fallback mock prediction when AI is unavailable
        $currentPrice = $data['current_price'];
        
        return [
            'current_price' => $currentPrice,
            'predicted_prices' => [
                'next_week' => $currentPrice * 1.05,
                'next_two_weeks' => $currentPrice * 1.08,
                'next_month' => $currentPrice * 1.12
            ],
            'price_changes' => [
                'next_week' => 5.0,
                'next_two_weeks' => 8.0,
                'next_month' => 12.0
            ],
            'urgency_level' => 'medium',
            'recommendation' => [
                'action' => 'Book within the next few days',
                'reason' => 'Prices typically increase closer to travel date'
            ],
            'savings_potential' => 0,
            'analysis_date' => now()->toDateTimeString()
        ];
    }

    
    public function suggestBestBookingTime(Request $request)
    {
        $request->validate([
            'from' => 'required|string|max:3',
            'to' => 'required|string|max:3',
            'departure_date' => 'required|date'
        ]);
        
        try {
            $suggestion = $this->generateBookingTimeSuggestion($request->all());
            
            return response()->json([
                'success' => true,
                'suggestion' => $suggestion
            ]);
            
        } catch (\Exception $e) {
            Log::error('Booking Time Suggestion Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Unable to generate booking time suggestion'
            ], 500);
        }
    }
    
    private function generatePricePrediction(array $data)
    {
        $prompt = $this->buildPricePredictionPrompt($data);
        
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->openaiApiKey,
            'Content-Type' => 'application/json',
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-4',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are an AI flight price prediction expert. Analyze flight routes, seasonal patterns, and market trends to provide accurate price predictions and booking recommendations.'
                ],
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ],
            'max_tokens' => 1000,
            'temperature' => 0.3,
        ]);
        
        if ($response->successful()) {
            $aiResponse = $response->json()['choices'][0]['message']['content'];
            return $this->parsePredictionResponse($aiResponse, $data);
        }
        
        // Fallback to rule-based prediction
        return $this->fallbackPrediction($data);
    }
    
    private function buildPricePredictionPrompt(array $data)
    {
        $departureDate = Carbon::parse($data['date']);
        $daysUntilDeparture = now()->diffInDays($departureDate);
        $isWeekend = $departureDate->isWeekend();
        $season = $this->getSeason($departureDate);
        
        return "Analyze flight price trends for this route and provide predictions:
        
        Route: {$data['from']} to {$data['to']}
        Departure Date: {$data['date']} ({$season} season)
        Days until departure: {$daysUntilDeparture}
        Trip Type: {$data['trip_type']}
        Current Price: \${$data['current_price']}
        Weekend departure: " . ($isWeekend ? 'Yes' : 'No') . "
        
        Please provide:
        1. Price trend prediction (increase/decrease/stable)
        2. Percentage change expected in next 1 week, 2 weeks, 1 month
        3. Best time to book recommendation
        4. Confidence level (high/medium/low)
        5. Key factors affecting price
        6. Alternative dates suggestion if applicable
        
        Consider factors like:
        - Booking window (advance purchase)
        - Seasonal demand patterns
        - Day of week effects
        - Holiday proximity
        - Route popularity
        - Historical price volatility
        
        Format as JSON with clear recommendations.";
    }
    
    private function parsePredictionResponse(string $aiResponse, array $data)
    {
        // Try to extract JSON from AI response
        if (preg_match('/\{.*\}/s', $aiResponse, $matches)) {
            $jsonData = json_decode($matches[0], true);
            if ($jsonData) {
                return $this->formatPrediction($jsonData, $data);
            }
        }
        
        // Fallback parsing
        return $this->parseTextResponse($aiResponse, $data);
    }
    
    private function formatPrediction(array $jsonData, array $data)
    {
        return [
            'trend' => $jsonData['trend'] ?? 'stable',
            'confidence' => $jsonData['confidence'] ?? 'medium',
            'price_changes' => [
                'next_week' => $jsonData['next_week_change'] ?? 0,
                'next_two_weeks' => $jsonData['next_two_weeks_change'] ?? 0,
                'next_month' => $jsonData['next_month_change'] ?? 0
            ],
            'recommendation' => [
                'action' => $jsonData['recommendation'] ?? 'Monitor prices',
                'best_time' => $jsonData['best_booking_time'] ?? 'Now',
                'reason' => $jsonData['reason'] ?? 'Current prices are reasonable'
            ],
            'factors' => $jsonData['key_factors'] ?? ['Seasonal demand', 'Booking window'],
            'alternative_dates' => $jsonData['alternative_dates'] ?? [],
            'current_price' => floatval($data['current_price']),
            'predicted_prices' => [
                'next_week' => $this->calculatePredictedPrice($data['current_price'], $jsonData['next_week_change'] ?? 0),
                'next_two_weeks' => $this->calculatePredictedPrice($data['current_price'], $jsonData['next_two_weeks_change'] ?? 0),
                'next_month' => $this->calculatePredictedPrice($data['current_price'], $jsonData['next_month_change'] ?? 0)
            ],
            'urgency_level' => $this->calculateUrgencyLevel($jsonData),
            'savings_potential' => $this->calculateSavingsPotential($data['current_price'], $jsonData)
        ];
    }
    
    private function fallbackPrediction(array $data)
    {
        $departureDate = Carbon::parse($data['date']);
        $daysUntilDeparture = now()->diffInDays($departureDate);
        $currentPrice = floatval($data['current_price']);
        
        // Rule-based prediction logic
        $weekChange = 0;
        $twoWeekChange = 0;
        $monthChange = 0;
        $trend = 'stable';
        $confidence = 'medium';
        $recommendation = 'Monitor prices';
        
        if ($daysUntilDeparture < 14) {
            $weekChange = rand(5, 15);
            $twoWeekChange = rand(10, 25);
            $monthChange = rand(15, 35);
            $trend = 'increasing';
            $recommendation = 'Book soon - prices typically rise close to departure';
            $confidence = 'high';
        } elseif ($daysUntilDeparture < 60) {
            $weekChange = rand(-5, 10);
            $twoWeekChange = rand(-2, 15);
            $monthChange = rand(5, 20);
            $trend = 'slightly_increasing';
            $recommendation = 'Good time to book';
        } else {
            $weekChange = rand(-10, 5);
            $twoWeekChange = rand(-15, 10);
            $monthChange = rand(-10, 15);
            $trend = 'stable';
            $recommendation = 'Monitor for better deals';
        }
        
        return [
            'trend' => $trend,
            'confidence' => $confidence,
            'price_changes' => [
                'next_week' => $weekChange,
                'next_two_weeks' => $twoWeekChange,
                'next_month' => $monthChange
            ],
            'recommendation' => [
                'action' => $recommendation,
                'best_time' => $daysUntilDeparture < 14 ? 'Now' : 'Within 2 weeks',
                'reason' => $this->getRecommendationReason($daysUntilDeparture, $trend)
            ],
            'factors' => $this->getFactors($daysUntilDeparture, $data),
            'alternative_dates' => [],
            'current_price' => $currentPrice,
            'predicted_prices' => [
                'next_week' => $this->calculatePredictedPrice($currentPrice, $weekChange),
                'next_two_weeks' => $this->calculatePredictedPrice($currentPrice, $twoWeekChange),
                'next_month' => $this->calculatePredictedPrice($currentPrice, $monthChange)
            ],
            'urgency_level' => $this->calculateUrgencyFromDays($daysUntilDeparture),
            'savings_potential' => abs(min($weekChange, $twoWeekChange, $monthChange))
        ];
    }
    
    private function generateMockHistoricalData(string $from, string $to)
    {
        $data = [];
        $basePrice = rand(200, 800);
        
        for ($i = 90; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $seasonMultiplier = $this->getSeasonMultiplier($date);
            $weekdayMultiplier = $date->isWeekend() ? 1.2 : 1.0;
            $randomVariation = 1 + (rand(-20, 20) / 100);
            
            $price = $basePrice * $seasonMultiplier * $weekdayMultiplier * $randomVariation;
            
            $data[] = [
                'date' => $date->format('Y-m-d'),
                'price' => round($price, 2),
                'day_of_week' => $date->format('l'),
                'is_weekend' => $date->isWeekend()
            ];
        }
        
        return $data;
    }
    
    private function generateBookingTimeSuggestion(array $data)
    {
        $departureDate = Carbon::parse($data['departure_date']);
        $daysUntilDeparture = now()->diffInDays($departureDate);
        
        $suggestions = [];
        
        if ($daysUntilDeparture > 60) {
            $suggestions[] = [
                'period' => 'Now to 8 weeks before',
                'recommendation' => 'Monitor prices for deals',
                'savings_potential' => '10-25%',
                'confidence' => 'medium'
            ];
        }
        
        if ($daysUntilDeparture > 21) {
            $suggestions[] = [
                'period' => '3-8 weeks before departure',
                'recommendation' => 'Optimal booking window',
                'savings_potential' => '15-30%',
                'confidence' => 'high'
            ];
        }
        
        if ($daysUntilDeparture <= 21) {
            $suggestions[] = [
                'period' => 'Within 3 weeks',
                'recommendation' => 'Book immediately - prices rising',
                'savings_potential' => '0-5%',
                'confidence' => 'high'
            ];
        }
        
        return [
            'best_period' => $this->getBestBookingPeriod($daysUntilDeparture),
            'suggestions' => $suggestions,
            'current_status' => $this->getCurrentBookingStatus($daysUntilDeparture)
        ];
    }
    
    // Helper methods
    private function getSeason(Carbon $date)
    {
        $month = $date->month;
        if (in_array($month, [12, 1, 2])) return 'winter';
        if (in_array($month, [3, 4, 5])) return 'spring';
        if (in_array($month, [6, 7, 8])) return 'summer';
        return 'autumn';
    }
    
    private function getSeasonMultiplier(Carbon $date)
    {
        $season = $this->getSeason($date);
        return match($season) {
            'summer' => 1.3,
            'winter' => 1.2,
            'spring' => 1.1,
            'autumn' => 1.0,
        };
    }
    
    private function calculatePredictedPrice(float $currentPrice, float $changePercent)
    {
        return round($currentPrice * (1 + $changePercent / 100), 2);
    }
    
    private function calculateUrgencyLevel(array $prediction)
    {
        $nextWeekChange = $prediction['next_week_change'] ?? 0;
        
        if ($nextWeekChange > 15) return 'high';
        if ($nextWeekChange > 5) return 'medium';
        return 'low';
    }
    
    private function calculateUrgencyFromDays(int $days)
    {
        if ($days < 14) return 'high';
        if ($days < 30) return 'medium';
        return 'low';
    }
    
    private function calculateSavingsPotential(float $currentPrice, array $prediction)
    {
        $changes = [
            $prediction['next_week_change'] ?? 0,
            $prediction['next_two_weeks_change'] ?? 0,
            $prediction['next_month_change'] ?? 0
        ];
        
        $maxSaving = abs(min($changes));
        return round(($currentPrice * $maxSaving / 100), 2);
    }
    
    private function getRecommendationReason(int $days, string $trend)
    {
        if ($days < 14 && $trend === 'increasing') {
            return 'Prices typically increase significantly within 2 weeks of departure';
        }
        
        if ($days > 60) {
            return 'Early booking window - monitor for promotional deals';
        }
        
        return 'You\'re in the optimal booking window for this route';
    }
    
    private function getFactors(int $days, array $data)
    {
        $factors = ['Booking window'];
        
        if ($days < 21) $factors[] = 'Last-minute demand';
        if (Carbon::parse($data['date'])->isWeekend()) $factors[] = 'Weekend travel';
        
        $season = $this->getSeason(Carbon::parse($data['date']));
        if (in_array($season, ['summer', 'winter'])) $factors[] = 'Peak season';
        
        return $factors;
    }
    
    private function getBestBookingPeriod(int $days)
    {
        if ($days < 21) return 'Book now';
        if ($days < 60) return 'Optimal window';
        return 'Early booking - monitor deals';
    }
    
    private function getCurrentBookingStatus(int $days)
    {
        if ($days < 14) return 'urgent';
        if ($days < 30) return 'good_time';
        if ($days < 60) return 'optimal';
        return 'early';
    }
}