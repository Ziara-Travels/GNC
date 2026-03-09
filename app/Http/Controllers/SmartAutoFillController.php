<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\PassengerProfile;
use App\Models\BookingHistory;

class SmartAutoFillController extends Controller
{
    public function getPassengerSuggestions(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ], 401);
        }
        
        try {
            $suggestions = [];
            
            // Get user's saved passenger profiles
            $savedProfiles = PassengerProfile::where('user_id', $user->id)
                ->orderBy('updated_at', 'desc')
                ->limit(5)
                ->get();
            
            foreach ($savedProfiles as $profile) {
                $suggestions[] = [
                    'type' => 'saved_profile',
                    'id' => $profile->id,
                    'title' => $profile->title,
                    'first_name' => $profile->first_name,
                    'last_name' => $profile->last_name,
                    'email' => $profile->email,
                    'phone' => $profile->phone,
                    'date_of_birth' => $profile->date_of_birth,
                    'nationality' => $profile->nationality,
                    'passport_number' => $profile->passport_number,
                    'passport_expiry' => $profile->passport_expiry,
                    'gender' => $profile->gender,
                    'confidence' => 100,
                    'last_used' => $profile->updated_at->format('Y-m-d')
                ];
            }
            
            // Get suggestions from booking history
            $historySuggestions = $this->getHistoryBasedSuggestions($user);
            $suggestions = array_merge($suggestions, $historySuggestions);
            
            // Get user profile as primary suggestion
            if ($user->first_name && $user->last_name) {
                array_unshift($suggestions, [
                    'type' => 'user_profile',
                    'id' => 'user_' . $user->id,
                    'title' => $user->title ?? 'Mr',
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'date_of_birth' => $user->date_of_birth,
                    'nationality' => $user->nationality,
                    'passport_number' => $user->passport_number,
                    'passport_expiry' => $user->passport_expiry,
                    'gender' => $user->gender,
                    'confidence' => 100,
                    'last_used' => 'Current user'
                ]);
            }
            
            return response()->json([
                'success' => true,
                'suggestions' => $suggestions
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching passenger suggestions'
            ], 500);
        }
    }
    
    public function processDocumentUpload(Request $request)
    {
        $request->validate([
            'document' => 'required|image|max:10240', // 10MB max
            'document_type' => 'required|in:passport,id_card,license'
        ]);
        
        try {
            $file = $request->file('document');
            $path = $file->store('documents', 'private');
            
            // In a real application, you would use OCR service like Google Vision API
            // or AWS Textract to extract information from the document
            $extractedData = $this->extractDocumentData($path, $request->document_type);
            
            return response()->json([
                'success' => true,
                'extracted_data' => $extractedData,
                'message' => 'Document processed successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error processing document'
            ], 500);
        }
    }
    
    public function savePassengerProfile(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:10',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'required|date',
            'nationality' => 'required|string|max:100',
            'passport_number' => 'nullable|string|max:50',
            'passport_expiry' => 'nullable|date',
            'gender' => 'required|in:male,female,other'
        ]);
        
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ], 401);
        }
        
        try {
            $profile = PassengerProfile::updateOrCreate([
                'user_id' => $user->id,
                'email' => $request->email
            ], $request->all());
            
            return response()->json([
                'success' => true,
                'profile' => $profile,
                'message' => 'Passenger profile saved successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error saving passenger profile'
            ], 500);
        }
    }
    
    public function getSmartRecommendations(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ], 401);
        }
        
        try {
            $recommendations = [];
            
            // Analyze booking patterns
            $bookingHistory = BookingHistory::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
            
            $patterns = $this->analyzeBookingPatterns($bookingHistory);
            
            // Generate smart recommendations based on patterns
            if ($patterns['preferred_class']) {
                $recommendations[] = [
                    'type' => 'travel_class',
                    'suggestion' => $patterns['preferred_class'],
                    'reason' => 'Based on your booking history',
                    'confidence' => $patterns['class_confidence']
                ];
            }
            
            if ($patterns['preferred_airlines']) {
                $recommendations[] = [
                    'type' => 'airline_preference',
                    'suggestion' => $patterns['preferred_airlines'],
                    'reason' => 'Your frequently chosen airlines',
                    'confidence' => $patterns['airline_confidence']
                ];
            }
            
            if ($patterns['seat_preferences']) {
                $recommendations[] = [
                    'type' => 'seat_preference',
                    'suggestion' => $patterns['seat_preferences'],
                    'reason' => 'Based on previous seat selections',
                    'confidence' => $patterns['seat_confidence']
                ];
            }
            
            if ($patterns['meal_preferences']) {
                $recommendations[] = [
                    'type' => 'meal_preference',
                    'suggestion' => $patterns['meal_preferences'],
                    'reason' => 'Your usual meal choices',
                    'confidence' => $patterns['meal_confidence']
                ];
            }
            
            return response()->json([
                'success' => true,
                'recommendations' => $recommendations,
                'patterns' => $patterns
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error generating recommendations'
            ], 500);
        }
    }
    
    private function getHistoryBasedSuggestions($user)
    {
        $suggestions = [];
        
        // Get unique passenger combinations from booking history
        $bookingHistory = BookingHistory::where('user_id', $user->id)
            ->whereNotNull('passenger_details')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();
        
        $uniquePassengers = [];
        
        foreach ($bookingHistory as $booking) {
            $passengers = json_decode($booking->passenger_details, true);
            
            if (is_array($passengers)) {
                foreach ($passengers as $passenger) {
                    $key = strtolower($passenger['first_name'] . '_' . $passenger['last_name']);
                    
                    if (!isset($uniquePassengers[$key])) {
                        $uniquePassengers[$key] = [
                            'type' => 'booking_history',
                            'id' => 'history_' . md5($key),
                            'title' => $passenger['title'] ?? 'Mr',
                            'first_name' => $passenger['first_name'],
                            'last_name' => $passenger['last_name'],
                            'email' => $passenger['email'] ?? '',
                            'phone' => $passenger['phone'] ?? '',
                            'date_of_birth' => $passenger['date_of_birth'] ?? null,
                            'nationality' => $passenger['nationality'] ?? '',
                            'passport_number' => $passenger['passport_number'] ?? '',
                            'passport_expiry' => $passenger['passport_expiry'] ?? null,
                            'gender' => $passenger['gender'] ?? 'male',
                            'confidence' => 80,
                            'last_used' => $booking->created_at->format('Y-m-d'),
                            'usage_count' => 1
                        ];
                    } else {
                        $uniquePassengers[$key]['usage_count']++;
                        $uniquePassengers[$key]['confidence'] = min(95, 80 + ($uniquePassengers[$key]['usage_count'] * 5));
                    }
                }
            }
        }
        
        // Sort by usage count and confidence
        usort($uniquePassengers, function($a, $b) {
            return ($b['usage_count'] * $b['confidence']) - ($a['usage_count'] * $a['confidence']);
        });
        
        return array_slice($uniquePassengers, 0, 5);
    }
    
    private function extractDocumentData($path, $documentType)
    {
        // Mock OCR extraction - in production, use Google Vision API or AWS Textract
        // This is a simplified example
        
        $mockData = [
            'passport' => [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'nationality' => 'United States',
                'passport_number' => 'P123456789',
                'date_of_birth' => '1985-06-15',
                'passport_expiry' => '2030-06-15',
                'gender' => 'male',
                'confidence' => 85
            ],
            'id_card' => [
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'nationality' => 'Kenya',
                'id_number' => '12345678',
                'date_of_birth' => '1990-03-22',
                'gender' => 'female',
                'confidence' => 90
            ]
        ];
        
        return $mockData[$documentType] ?? [
            'confidence' => 0,
            'error' => 'Unable to extract data from document'
        ];
    }
    
    private function analyzeBookingPatterns($bookingHistory)
    {
        $patterns = [
            'preferred_class' => null,
            'class_confidence' => 0,
            'preferred_airlines' => [],
            'airline_confidence' => 0,
            'seat_preferences' => [],
            'seat_confidence' => 0,
            'meal_preferences' => [],
            'meal_confidence' => 0,
            'booking_lead_time' => 0,
            'destination_patterns' => []
        ];
        
        if ($bookingHistory->isEmpty()) {
            return $patterns;
        }
        
        $classes = [];
        $airlines = [];
        $leadTimes = [];
        $destinations = [];
        
        foreach ($bookingHistory as $booking) {
            $details = json_decode($booking->booking_details, true);
            
            if (isset($details['travel_class'])) {
                $classes[] = $details['travel_class'];
            }
            
            if (isset($details['airline'])) {
                $airlines[] = $details['airline'];
            }
            
            if (isset($details['destination'])) {
                $destinations[] = $details['destination'];
            }
            
            // Calculate lead time
            $bookingDate = $booking->created_at;
            $travelDate = \Carbon\Carbon::parse($details['departure_date'] ?? $booking->created_at);
            $leadTimes[] = $bookingDate->diffInDays($travelDate);
        }
        
        // Analyze travel class preferences
        if (!empty($classes)) {
            $classCount = array_count_values($classes);
            $patterns['preferred_class'] = array_keys($classCount, max($classCount))[0];
            $patterns['class_confidence'] = (max($classCount) / count($classes)) * 100;
        }
        
        // Analyze airline preferences
        if (!empty($airlines)) {
            $airlineCount = array_count_values($airlines);
            arsort($airlineCount);
            $patterns['preferred_airlines'] = array_slice(array_keys($airlineCount), 0, 3);
            $patterns['airline_confidence'] = (max($airlineCount) / count($airlines)) * 100;
        }
        
        // Average booking lead time
        if (!empty($leadTimes)) {
            $patterns['booking_lead_time'] = round(array_sum($leadTimes) / count($leadTimes));
        }
        
        // Destination patterns
        if (!empty($destinations)) {
            $destCount = array_count_values($destinations);
            arsort($destCount);
            $patterns['destination_patterns'] = array_slice($destCount, 0, 5, true);
        }
        
        return $patterns;
    }

    public function scanPassport(Request $request)
{
    $request->validate([
        'passport_image' => 'required|image|mimes:jpeg,png,jpg|max:10240'
    ]);
    
    try {
        // Store the uploaded image temporarily
        $image = $request->file('passport_image');
        $imagePath = Storage::disk('public')->put('temp/passports', $image);
        $fullPath = Storage::disk('public')->path($imagePath);
        
        // Extract passport data using OCR (mock implementation)
        $extractedData = $this->extractPassportData($fullPath);
        
        // Clean up temporary file
        Storage::disk('public')->delete($imagePath);
        
        if ($extractedData['confidence'] > 70) {
            return response()->json([
                'success' => true,
                'data' => $extractedData,
                'message' => 'Passport scanned successfully'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Could not extract clear data from passport image. Please try again with better lighting.',
                'confidence' => $extractedData['confidence']
            ], 422);
        }
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error processing passport image: ' . $e->getMessage()
        ], 500);
    }
}

private function extractPassportData($imagePath)
{
    // In production, this would use Google Vision API, AWS Textract, or similar OCR service
    // For demonstration, we'll simulate OCR extraction with mock data
    
    try {
        // Simulate OCR processing delay
        usleep(500000); // 0.5 second delay
        
        // Mock OCR results based on image analysis
        $mockResults = [
            [
                'first_name' => 'JOHN',
                'last_name' => 'DOE',
                'nationality' => 'USA',
                'passport_number' => 'P123456789',
                'date_of_birth' => '1985-06-15',
                'passport_expiry' => '2030-06-15',
                'gender' => 'M',
                'issuing_country' => 'United States of America',
                'confidence' => 95
            ],
            [
                'first_name' => 'JANE',
                'last_name' => 'SMITH',
                'nationality' => 'KEN',
                'passport_number' => 'K987654321',
                'date_of_birth' => '1990-03-22',
                'passport_expiry' => '2028-03-22',
                'gender' => 'F',
                'issuing_country' => 'Republic of Kenya',
                'confidence' => 88
            ],
            [
                'first_name' => 'MICHAEL',
                'last_name' => 'JOHNSON',
                'nationality' => 'GBR',
                'passport_number' => 'G456789123',
                'date_of_birth' => '1988-11-08',
                'passport_expiry' => '2029-11-08',
                'gender' => 'M',
                'issuing_country' => 'United Kingdom',
                'confidence' => 92
            ]
        ];
        
        // Randomly select one for demonstration
        $selectedResult = $mockResults[array_rand($mockResults)];
        
        // Format the data for frontend consumption
        return [
            'title' => $selectedResult['gender'] === 'M' ? 'Mr' : 'Ms',
            'first_name' => ucfirst(strtolower($selectedResult['first_name'])),
            'last_name' => ucfirst(strtolower($selectedResult['last_name'])),
            'nationality' => $this->formatNationality($selectedResult['nationality']),
            'passport_number' => $selectedResult['passport_number'],
            'date_of_birth' => $selectedResult['date_of_birth'],
            'passport_expiry' => $selectedResult['passport_expiry'],
            'gender' => $selectedResult['gender'] === 'M' ? 'male' : 'female',
            'issuing_country' => $selectedResult['issuing_country'],
            'confidence' => $selectedResult['confidence'],
            'extraction_method' => 'OCR',
            'processed_at' => now()->toISOString()
        ];
        
    } catch (\Exception $e) {
        return [
            'confidence' => 0,
            'error' => 'Failed to process passport image',
            'message' => $e->getMessage()
        ];
    }
}

private function formatNationality($countryCode)
{
    $nationalities = [
        'USA' => 'United States',
        'KEN' => 'Kenya',
        'GBR' => 'United Kingdom',
        'FRA' => 'France',
        'DEU' => 'Germany',
        'JPN' => 'Japan',
        'CHN' => 'China',
        'IND' => 'India',
        'BRA' => 'Brazil',
        'CAN' => 'Canada',
        'AUS' => 'Australia',
        'ZAF' => 'South Africa',
        'NGR' => 'Nigeria',
        'EGY' => 'Egypt',
        'ARE' => 'United Arab Emirates'
    ];
    
    return $nationalities[$countryCode] ?? $countryCode;
}

public function validatePassportData(Request $request)
{
    $request->validate([
        'passport_number' => 'required|string|max:20',
        'nationality' => 'required|string|max:100',
        'date_of_birth' => 'required|date',
        'passport_expiry' => 'required|date|after:today'
    ]);
    
    try {
        $validationResults = [];
        
        // Validate passport number format
        $passportValidation = $this->validatePassportNumber(
            $request->passport_number,
            $request->nationality
        );
        $validationResults['passport_number'] = $passportValidation;
        
        // Validate expiry date
        $expiryDate = \Carbon\Carbon::parse($request->passport_expiry);
        $monthsUntilExpiry = now()->diffInMonths($expiryDate);
        
        $validationResults['passport_expiry'] = [
            'valid' => $monthsUntilExpiry >= 6,
            'months_until_expiry' => $monthsUntilExpiry,
            'warning' => $monthsUntilExpiry < 6 ? 'Passport expires in less than 6 months' : null
        ];
        
        // Validate age
        $birthDate = \Carbon\Carbon::parse($request->date_of_birth);
        $age = $birthDate->diffInYears(now());
        
        $validationResults['age'] = [
            'valid' => $age >= 18,
            'age' => $age,
            'warning' => $age < 18 ? 'Minor passenger - additional documentation may be required' : null
        ];
        
        return response()->json([
            'success' => true,
            'validation_results' => $validationResults,
            'overall_valid' => $passportValidation['valid'] && 
                              $validationResults['passport_expiry']['valid'] && 
                              $validationResults['age']['valid']
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error validating passport data'
        ], 500);
    }
}

private function validatePassportNumber($passportNumber, $nationality)
{
    // Basic passport number validation patterns by country
    $patterns = [
        'United States' => '/^[A-Z]{1}[0-9]{8}$/',
        'Kenya' => '/^[A-Z]{1}[0-9]{7}$/',
        'United Kingdom' => '/^[0-9]{9}$/',
        'France' => '/^[0-9]{2}[A-Z]{2}[0-9]{5}$/',
        'Germany' => '/^[A-Z]{1}[0-9]{8}$/',
        'Japan' => '/^[A-Z]{2}[0-9]{7}$/',
        'default' => '/^[A-Z0-9]{6,20}$/'
    ];
    
    $pattern = $patterns[$nationality] ?? $patterns['default'];
    
    return [
        'valid' => preg_match($pattern, $passportNumber) === 1,
        'pattern' => $pattern,
        'message' => preg_match($pattern, $passportNumber) === 1 
            ? 'Valid passport number format' 
            : 'Invalid passport number format for ' . $nationality
    ];
}
}