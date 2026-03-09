<?php


namespace App\Services;

use App\Models\TravelInsurance;
use App\Models\Booking;
use Carbon\Carbon;

class TravelInsuranceService
{
    private $insurancePlans = [
        'basic' => [
            'name' => 'Basic Coverage',
            'price_percentage' => 0.05, // 5% of trip cost
            'min_premium' => 25,
            'max_premium' => 200,
            'coverage' => [
                'trip_cancellation' => 5000,
                'medical_emergency' => 25000,
                'baggage_loss' => 1000,
                'trip_delay' => 500
            ],
            'benefits' => [
                'Trip cancellation coverage',
                'Emergency medical coverage',
                'Baggage loss protection',
                'Trip delay compensation'
            ]
        ],
        'standard' => [
            'name' => 'Standard Coverage',
            'price_percentage' => 0.08, // 8% of trip cost
            'min_premium' => 50,
            'max_premium' => 400,
            'coverage' => [
                'trip_cancellation' => 10000,
                'medical_emergency' => 50000,
                'baggage_loss' => 2000,
                'trip_delay' => 1000,
                'missed_connection' => 800
            ],
            'benefits' => [
                'Trip cancellation coverage',
                'Emergency medical coverage',
                'Baggage loss protection',
                'Trip delay compensation',
                'Missed connection coverage'
            ]
        ],
        'premium' => [
            'name' => 'Premium Coverage',
            'price_percentage' => 0.12, // 12% of trip cost
            'min_premium' => 100,
            'max_premium' => 800,
            'coverage' => [
                'trip_cancellation' => 25000,
                'medical_emergency' => 100000,
                'baggage_loss' => 5000,
                'trip_delay' => 2000,
                'missed_connection' => 1500,
                'rental_car' => 1000,
                'adventure_sports' => 10000
            ],
            'benefits' => [
                'Trip cancellation coverage',
                'Emergency medical coverage',
                'Baggage loss protection',
                'Trip delay compensation',
                'Missed connection coverage',
                'Rental car coverage',
                'Adventure sports coverage'
            ]
        ]
    ];

    public function getAvailablePlans(Booking $booking)
    {
        $tripCost = $booking->amount;
        $plans = [];

        foreach ($this->insurancePlans as $key => $plan) {
            $premium = max(
                $plan['min_premium'],
                min($plan['max_premium'], $tripCost * $plan['price_percentage'])
            );

            $plans[$key] = [
                'id' => $key,
                'name' => $plan['name'],
                'premium' => $premium,
                'coverage' => $plan['coverage'],
                'benefits' => $plan['benefits'],
                'description' => $this->getPlanDescription($key)
            ];
        }

        return $plans;
    }

    public function calculatePremium(Booking $booking, string $planType)
    {
        if (!isset($this->insurancePlans[$planType])) {
            throw new \InvalidArgumentException("Invalid insurance plan type: {$planType}");
        }

        $plan = $this->insurancePlans[$planType];
        $tripCost = $booking->amount;

        return max(
            $plan['min_premium'],
            min($plan['max_premium'], $tripCost * $plan['price_percentage'])
        );
    }

    public function addInsuranceToBooking(Booking $booking, string $planType)
    {
        $plan = $this->insurancePlans[$planType];
        $premium = $this->calculatePremium($booking, $planType);

        $startDate = $booking->booking_type === 'flight' 
            ? $booking->departure_time 
            : $booking->check_in_date;

        $endDate = $booking->booking_type === 'flight' 
            ? $booking->arrival_time 
            : $booking->check_out_date;

        return TravelInsurance::create([
            'booking_id' => $booking->id,
            'plan_type' => $planType,
            'coverage_amount' => $plan['coverage']['trip_cancellation'],
            'premium_amount' => $premium,
            'provider' => 'TravelGuard Insurance',
            'policy_number' => 'TG-' . $booking->booking_reference . '-' . strtoupper($planType),
            'coverage_details' => $plan['coverage'],
            'status' => 'active',
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);
    }

    private function getPlanDescription(string $planType)
    {
        return match($planType) {
            'basic' => 'Essential coverage for peace of mind during your travels.',
            'standard' => 'Comprehensive protection with additional benefits.',
            'premium' => 'Complete coverage including adventure sports and rental car protection.',
            default => 'Travel insurance coverage'
        };
    }

    public function mockInsuranceApiCall(array $bookingData, string $planType)
    {
        // Mock API call to insurance provider
        sleep(1); // Simulate API delay

        return [
            'success' => true,
            'policy_number' => 'TG-' . uniqid() . '-' . strtoupper($planType),
            'quote_id' => 'QT-' . time(),
            'premium' => $this->calculatePremium((object)$bookingData, $planType),
            'coverage_details' => $this->insurancePlans[$planType]['coverage'],
            'terms_url' => 'https://example.com/insurance-terms',
            'claims_phone' => '+1-800-CLAIMS',
            'expires_at' => now()->addMinutes(30)->toISOString()
        ];
    }
}