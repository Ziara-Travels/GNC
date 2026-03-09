<?php


namespace App\Services;

use Laravel\AI\AI;
use App\Models\ChatSession;
use App\Models\User;

class AIChatbotService
{
    protected $ai;
    
    public function __construct()
    {
        $this->ai = AI::chat();
    }
    
    public function handleUserMessage($message, $sessionId = null, $userId = null)
    {
        // Create or get chat session
        $session = $this->getOrCreateSession($sessionId, $userId);
        
        // Store user message
        $this->storeMessage($session->id, 'user', $message);
        
        // Determine intent and generate response
        $intent = $this->detectIntent($message);
        $response = $this->generateResponse($message, $intent, $session);
        
        // Store AI response
        $this->storeMessage($session->id, 'assistant', $response['message']);
        
        return [
            'session_id' => $session->id,
            'response' => $response['message'],
            'intent' => $intent,
            'actions' => $response['actions'] ?? [],
            'quick_replies' => $response['quick_replies'] ?? []
        ];
    }
    
    private function detectIntent($message)
    {
        $message = strtolower($message);
        
        // Booking related
        if (preg_match('/book|booking|reserve|flight|hotel/', $message)) {
            return 'booking';
        }
        
        // Cancellation related
        if (preg_match('/cancel|refund|change|modify/', $message)) {
            return 'cancellation';
        }
        
        // Baggage related
        if (preg_match('/baggage|luggage|bag|carry.on|check.in/', $message)) {
            return 'baggage';
        }
        
        // Airport information
        if (preg_match('/airport|terminal|gate|lounge/', $message)) {
            return 'airport';
        }
        
        // Travel recommendations
        if (preg_match('/recommend|suggest|best|where|what/', $message)) {
            return 'recommendations';
        }
        
        // Policy questions
        if (preg_match('/policy|rule|allowed|restriction/', $message)) {
            return 'policy';
        }
        
        // General help
        return 'general';
    }
    
    private function generateResponse($message, $intent, $session)
    {
        $context = $this->buildContext($session);
        
        $systemPrompt = "You are a helpful travel booking assistant. Be concise, friendly, and provide actionable information. 
        Context: User is on a flight booking website. Previous conversation: " . json_encode($context) . "
        
        Based on the user's message and intent '{$intent}', provide:
        1. A helpful response
        2. Suggested quick reply options
        3. Any actionable buttons/links
        
        Keep responses under 150 words.";
        
        $userPrompt = "User message: {$message}
        Intent: {$intent}
        
        Please provide a helpful response with specific information about:
        " . $this->getIntentSpecificPrompt($intent);
        
        try {
            $aiResponse = $this->ai->prompt($systemPrompt . "\n\n" . $userPrompt)->generate();
            
            return $this->parseAIResponse($aiResponse, $intent);
        } catch (\Exception $e) {
            return $this->getFallbackResponse($intent);
        }
    }
    
    private function getIntentSpecificPrompt($intent)
    {
        switch ($intent) {
            case 'booking':
                return "- How to search and book flights
                - Required information for booking
                - Payment options and security
                - Confirmation process";
                
            case 'cancellation':
                return "- Cancellation policies
                - Refund timelines
                - Change fees
                - How to modify bookings";
                
            case 'baggage':
                return "- Carry-on size limits
                - Checked baggage allowances
                - Prohibited items
                - Excess baggage fees";
                
            case 'airport':
                return "- Check-in procedures
                - Security requirements
                - Terminal information
                - Lounge access";
                
            case 'recommendations':
                return "- Popular destinations
                - Best travel times
                - Budget-friendly options
                - Travel tips";
                
            case 'policy':
                return "- Airline policies
                - Travel restrictions
                - Health requirements
                - Documentation needed";
                
            default:
                return "- General travel assistance
                - How to use the website
                - Available services
                - Contact information";
        }
    }
    
    private function parseAIResponse($response, $intent)
    {
        // Extract quick replies and actions from AI response
        $quickReplies = $this->generateQuickReplies($intent);
        $actions = $this->generateActions($intent);
        
        return [
            'message' => $response,
            'quick_replies' => $quickReplies,
            'actions' => $actions
        ];
    }
    
    private function generateQuickReplies($intent)
    {
        $replies = [
            'booking' => ['Search Flights', 'Hotel Booking', 'Travel Insurance', 'Group Booking'],
            'cancellation' => ['Cancel Booking', 'Change Dates', 'Refund Status', 'Contact Support'],
            'baggage' => ['Size Limits', 'Prohibited Items', 'Extra Baggage', 'Travel Tips'],
            'airport' => ['Check-in Online', 'Flight Status', 'Terminal Map', 'Parking Info'],
            'recommendations' => ['Popular Destinations', 'Best Deals', 'Travel Guides', 'Weather Info'],
            'policy' => ['Cancellation Policy', 'Travel Requirements', 'Health Guidelines', 'Visa Info'],
            'general' => ['Search Flights', 'My Bookings', 'Help Center', 'Contact Us']
        ];
        
        return $replies[$intent] ?? $replies['general'];
    }
    
    private function generateActions($intent)
    {
        $actions = [
            'booking' => [
                ['type' => 'url', 'text' => 'Search Flights', 'url' => '/'],
                ['type' => 'url', 'text' => 'View Deals', 'url' => '/deals']
            ],
            'cancellation' => [
                ['type' => 'url', 'text' => 'Manage Booking', 'url' => '/bookings'],
                ['type' => 'url', 'text' => 'Contact Support', 'url' => '/support']
            ]
        ];
        
        return $actions[$intent] ?? [];
    }
    
    private function getFallbackResponse($intent)
    {
        $fallbackResponses = [
            'booking' => "I can help you with booking flights! You can search for flights using our search form on the homepage. Simply enter your departure and destination cities, select your dates, and browse available options.",
            'cancellation' => "For cancellations and changes, please check your booking confirmation email for your reservation number. You can then manage your booking online or contact our support team for assistance.",
            'baggage' => "Baggage policies vary by airline. Generally, carry-on bags should be under 22 inches and weigh less than 7kg. Check with your specific airline for detailed baggage allowances and restrictions.",
            'airport' => "Airport information varies by location. I recommend checking the specific airport's website for terminal maps, check-in counters, and facility information. Most airports open check-in 2-3 hours before international flights.",
            'recommendations' => "I'd be happy to help with recommendations! Popular destinations include Paris, Dubai, Tokyo, and New York. The best time to book is usually 6-8 weeks in advance for domestic flights and 2-3 months for international travel.",
            'policy' => "Travel policies vary by airline and destination. Please check the specific airline's terms and conditions. For international travel, ensure you have valid passports and any required visas.",
            'general' => "I'm here to help with your travel needs! You can ask me about booking flights, cancellation policies, baggage information, airport details, or travel recommendations. How can I assist you today?"
        ];
        
        return [
            'message' => $fallbackResponses[$intent] ?? $fallbackResponses['general'],
            'quick_replies' => $this->generateQuickReplies($intent),
            'actions' => $this->generateActions($intent)
        ];
    }
    
    private function getOrCreateSession($sessionId, $userId)
    {
        if ($sessionId) {
            $session = ChatSession::find($sessionId);
            if ($session) {
                return $session;
            }
        }
        
        return ChatSession::create([
            'user_id' => $userId,
            'session_token' => uniqid('chat_', true),
            'started_at' => now()
        ]);
    }
    
    private function storeMessage($sessionId, $role, $message)
    {
        \App\Models\ChatMessage::create([
            'session_id' => $sessionId,
            'role' => $role,
            'message' => $message,
            'timestamp' => now()
        ]);
    }
    
    private function buildContext($session)
    {
        return $session->messages()
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->reverse()
            ->map(function ($message) {
                return ['role' => $message->role, 'content' => $message->message];
            })
            ->toArray();
    }
}