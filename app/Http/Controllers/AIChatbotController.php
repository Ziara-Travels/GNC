<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AIChatbotService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\ChatHistory;


class AIChatbotController extends Controller
{
    protected $chatbot;
    
    public function __construct(AIChatbotService $chatbot)
    {
        $this->chatbot = $chatbot;
    }
    
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'session_id' => 'nullable|string',
            'user_id' => 'nullable|integer'
        ]);
        
        $response = $this->chatbot->handleUserMessage(
            $request->message,
            $request->session_id,
            $request->user_id ?: auth()->id()
        );
        
        return response()->json([
            'success' => true,
            'data' => $response
        ]);
    }
    
    public function getChatHistory(Request $request)
    {
        try {
            $sessionId = $request->session()->getId();
            
            $history = ChatHistory::where('session_id', $sessionId)
                ->where('user_id', auth()->id())
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
                ->reverse()
                ->values();
            
            return response()->json([
                'success' => true,
                'history' => $history
            ]);
            
        } catch (\Exception $e) {
            Log::error('Chat History Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'history' => []
            ]);
        }
    }
    
    private function getSystemPrompt()
    {
        return "You are a helpful AI travel assistant for a flight booking website. Your role is to help users with:

1. Flight booking questions and procedures
2. Travel requirements and documentation
3. Baggage allowances and restrictions
4. Cancellation and refund policies
5. Airport information and facilities
6. Travel tips and recommendations
7. Destination information
8. Weather and best time to travel
9. Visa and immigration requirements
10. Travel insurance advice

Always provide accurate, helpful, and concise responses. If you don't know something specific about the company's policies, direct users to contact customer service. Be friendly and professional in your responses.

Current website features:
- Flight search and booking
- Hotel reservations
- Travel packages
- 24/7 customer support
- Mobile app available
- Multiple payment options including M-Pesa
- Trip planning tools

Keep responses under 150 words unless more detail is specifically requested.";
    }
    
    private function getChatContext(Request $request)
    {
        $sessionId = $request->session()->getId();
        
        return ChatHistory::where('session_id', $sessionId)
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->reverse()
            ->map(function ($chat) {
                return [
                    'message' => $chat->message,
                    'response' => $chat->response
                ];
            })
            ->toArray();
    }
    
    private function saveChatHistory(Request $request, string $message, string $response)
    {
        try {
            ChatHistory::create([
                'user_id' => auth()->id(),
                'session_id' => $request->session()->getId(),
                'message' => $message,
                'response' => $response,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to save chat history: ' . $e->getMessage());
        }
    }
}