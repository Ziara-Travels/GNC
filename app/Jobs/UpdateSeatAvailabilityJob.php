<?php


namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\SeatAvailability;
use App\Models\Booking;
use App\Services\AmadeusService;
use App\Mail\SeatAvailabilityAlertMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class UpdateSeatAvailabilityJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $amadeusService;

    public function __construct()
    {
        $this->amadeusService = new AmadeusService();
    }

    public function handle()
    {
        // AmadeusService has no getSeatAvailability(); without this guard every
        // run of this 15-minutely job died on a BadMethodCallException.
        if (! method_exists($this->amadeusService, 'getSeatAvailability')) {
            Log::warning('Skipping seat availability refresh: AmadeusService::getSeatAvailability() is not implemented.');

            return;
        }

        // Get active bookings that need monitoring
        $activeFlights = Booking::where('status', 'pending')
            ->where('departure_time', '>', now())
            ->where('departure_time', '<=', now()->addDays(7))
            ->select('flight_number', 'departure_time', 'departure_airport', 'arrival_airport', 'travel_class')
            ->distinct()
            ->get();

        foreach ($activeFlights as $flight) {
            try {
                // Poll Amadeus for seat availability
                $availability = $this->amadeusService->getSeatAvailability([
                    'flight_number' => $flight->flight_number,
                    'departure_date' => $flight->departure_time->format('Y-m-d'),
                    'departure_airport' => $flight->departure_airport,
                    'arrival_airport' => $flight->arrival_airport,
                    'travel_class' => $flight->travel_class
                ]);

                // Update or create seat availability record
                $seatRecord = SeatAvailability::updateOrCreate(
                    [
                        'flight_number' => $flight->flight_number,
                        'departure_date' => $flight->departure_time->format('Y-m-d'),
                        'departure_airport' => $flight->departure_airport,
                        'arrival_airport' => $flight->arrival_airport,
                        'travel_class' => $flight->travel_class
                    ],
                    [
                        'available_seats' => $availability['available_seats'],
                        'total_seats' => $availability['total_seats'],
                        'last_updated' => now(),
                        'is_critical' => $availability['available_seats'] <= 5
                    ]
                );

                // Send alerts if seats are running low
                // SeatAvailability exposes isCriticallyLow(); there is no
                // isCritical() method, so the previous call always threw.
                if ($seatRecord->isCriticallyLow()) {
                    $this->sendLowSeatAlerts($seatRecord);
                }

            } catch (\Exception $e) {
                Log::error('Failed to update seat availability', [
                    'flight_number' => $flight->flight_number,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    private function sendLowSeatAlerts($seatRecord)
    {
        // Find users with pending bookings for this flight
        $affectedBookings = Booking::where('flight_number', $seatRecord->flight_number)
            ->where('departure_time', '>=', $seatRecord->departure_date . ' 00:00:00')
            ->where('departure_time', '<=', $seatRecord->departure_date . ' 23:59:59')
            ->where('status', 'pending')
            ->with('user')
            ->get();

        foreach ($affectedBookings as $booking) {
            if ($booking->user) {
                Mail::to($booking->user->email)->send(new SeatAvailabilityAlertMail($booking, $seatRecord));
            }
        }
    }
}