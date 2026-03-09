<?php


namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingConfirmationMail;
use App\Models\Booking;
use App\Services\WhatsAppService;

class SendBookingConfirmationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function handle()
    {
        // Send email confirmation
        Mail::to($this->booking->email)->send(new BookingConfirmationMail($this->booking));
        
        // Send email to specific admin
        Mail::to('admin@omniviatech.co.ke')->send(new \App\Mail\AdminBookingNotification($this->booking));
        
        // Send WhatsApp confirmation
        $whatsappService = new WhatsAppService();
        $whatsappService->sendBookingConfirmation($this->booking->phone, $this->booking);
        
        // Create user notification
        if ($this->booking->user_id) {
            \App\Models\Notification::create([
                'user_id' => $this->booking->user_id,
                'title' => 'Booking Confirmed',
                'message' => 'Your booking #' . $this->booking->booking_reference . ' has been confirmed.',
                'type' => 'booking_success',
                'data' => [
                    'booking_id' => $this->booking->id,
                    'booking_reference' => $this->booking->booking_reference,
                ],
                'action_url' => route('bookings.view', $this->booking->booking_reference),
                'priority' => 'high'
            ]);
        }
        
        // Create admin notification
        $adminUser = \App\Models\User::where('email', 'admin@omniviatech.co.ke')->first();
        if ($adminUser) {
            \App\Models\Notification::create([
                'user_id' => $adminUser->id,
                'title' => 'New Booking Created',
                'message' => 'A new booking #' . $this->booking->booking_reference . ' has been created.',
                'type' => 'new_booking',
                'data' => [
                    'booking_id' => $this->booking->id,
                    'booking_reference' => $this->booking->booking_reference,
                    'user_id' => $this->booking->user_id,
                ],
                'action_url' => route('admin.bookings.view', $this->booking->id),
                'priority' => 'medium'
            ]);
        }
        
        // Update dashboard stats (trigger cache clearing for stats)
        \Cache::forget('booking_stats_all');
        \Cache::forget('booking_stats_user_' . $this->booking->user_id);
        \Cache::forget('payment_stats_all');
        \Cache::forget('payment_stats_user_' . $this->booking->user_id);
        
        // Update booking status
        $this->booking->update(['confirmation_sent' => true]);
    }
}