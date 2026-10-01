<?php

namespace App\Mail;

use App\Models\Booking;
use App\Models\SeatAvailability;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Sent by UpdateSeatAvailabilityJob (scheduled every fifteen minutes) when seat
 * availability on a booked flight drops to a critical level.
 */
class SeatAvailabilityAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public Booking $booking;

    public SeatAvailability $seatRecord;

    public function __construct(Booking $booking, SeatAvailability $seatRecord)
    {
        $this->booking = $booking;
        $this->seatRecord = $seatRecord;
    }

    public function build()
    {
        return $this->subject('Low seat availability on your flight '.$this->seatRecord->flight_number)
            ->view('emails.seat-availability-alert')
            ->with([
                'booking'          => $this->booking,
                'bookingReference' => $this->booking->booking_reference,
                'seatRecord'       => $this->seatRecord,
                'flightNumber'     => $this->seatRecord->flight_number,
                'availableSeats'   => $this->seatRecord->available_seats,
                'totalSeats'       => $this->seatRecord->total_seats,
                'bookingUrl'       => route('bookings.show', $this->booking->id),
            ]);
    }
}