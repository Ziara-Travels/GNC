<?php


namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    private $apiUrl;
    private $accessToken;
    private $phoneNumberId;

    public function __construct()
    {
        $this->apiUrl = config('services.whatsapp.api_url');
        $this->accessToken = config('services.whatsapp.access_token');
        $this->phoneNumberId = config('services.whatsapp.phone_number_id');
    }

    public function sendBookingConfirmation($phoneNumber, $booking)
    {
        $message = "🎉 *Booking Confirmed!*\n\n";
        $message .= "📋 Reference: *{$booking->booking_reference}*\n";
        $message .= "✈️ Flight: {$booking->flight_details['from']} → {$booking->flight_details['to']}\n";
        $message .= "📅 Date: {$booking->flight_details['departure_date']}\n";
        $message .= "🕐 Time: {$booking->flight_details['departure_time']}\n\n";
        $message .= "💳 Total: \${$booking->total_price}\n\n";
        $message .= "📧 E-ticket sent to your email.\n";
        $message .= "🌐 Manage booking: " . route('booking.manage', $booking->booking_reference);

        return $this->sendMessage($phoneNumber, $message);
    }

    public function sendFlightDelay($phoneNumber, $booking, $delayInfo)
    {
        $message = "⚠️ *Flight Delay Alert*\n\n";
        $message .= "📋 Booking: *{$booking->booking_reference}*\n";
        $message .= "✈️ Flight: {$delayInfo['flight_number']}\n";
        $message .= "⏰ New departure: {$delayInfo['new_departure_time']}\n";
        $message .= "🕐 Delay: {$delayInfo['delay_duration']} minutes\n\n";
        $message .= "💼 Reason: {$delayInfo['reason']}\n\n";
        $message .= "🔄 We'll keep you updated with any changes.\n";
        $message .= "📞 Need help? Contact support.";

        return $this->sendMessage($phoneNumber, $message);
    }

    public function sendPaymentReminder($phoneNumber, $booking)
    {
        $message = "💳 *Payment Reminder*\n\n";
        $message .= "📋 Booking: *{$booking->booking_reference}*\n";
        $message .= "💰 Amount due: \${$booking->pending_amount}\n";
        $message .= "⏰ Due date: {$booking->payment_due_date}\n\n";
        $message .= "Complete payment to secure your booking:\n";
        $message .= "🔗 " . route('booking.payment', $booking->booking_reference);

        return $this->sendMessage($phoneNumber, $message);
    }

    public function sendCheckInReminder($phoneNumber, $booking)
    {
        $message = "✅ *Check-in Available*\n\n";
        $message .= "📋 Booking: *{$booking->booking_reference}*\n";
        $message .= "✈️ Flight: {$booking->flight_details['flight_number']}\n";
        $message .= "🕐 Departure: {$booking->flight_details['departure_time']}\n\n";
        $message .= "📱 Check-in now and get your boarding pass:\n";
        $message .= "🔗 " . route('booking.success', $booking->booking_reference);

        return $this->sendMessage($phoneNumber, $message);
    }

    private function sendMessage($phoneNumber, $message)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl . '/' . $this->phoneNumberId . '/messages', [
                'messaging_product' => 'whatsapp',
                'to' => $this->formatPhoneNumber($phoneNumber),
                'type' => 'text',
                'text' => [
                    'body' => $message
                ]
            ]);

            Log::info('WhatsApp message sent', [
                'phone' => $phoneNumber,
                'response' => $response->json()
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('WhatsApp message failed', [
                'phone' => $phoneNumber,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    private function formatPhoneNumber($phoneNumber)
    {
        // Remove any non-digit characters
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
        
        // Add country code if not present
        if (!str_starts_with($phoneNumber, '1') && !str_starts_with($phoneNumber, '254')) {
            $phoneNumber = '1' . $phoneNumber; // Default to US
        }
        
        return $phoneNumber;
    }
}