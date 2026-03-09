<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Booking;

class BookingStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $booking;
    public $dashboardData;

    /**
     * Create a new event instance.
     *
     * @param  \App\Models\Booking  $booking
     * @return void
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
        $this->dashboardData = [
            'booking_id' => $booking->id,
            'booking_reference' => $booking->booking_reference,
            'status' => $booking->status,
            'user_id' => $booking->user_id,
            'amount' => $booking->total_amount,
            'currency' => $booking->currency,
            'updated_at' => $booking->updated_at->format('Y-m-d H:i:s'),
            'type' => $booking->booking_type,
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return [
            new PrivateChannel('dashboard.admin'),
            new PrivateChannel('dashboard.user.' . $this->booking->user_id),
        ];
    }
    
    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'booking.updated';
    }
    
    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return $this->dashboardData;
    }
}
