<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 0 auto; background-color: white; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; }
        .content { padding: 30px; }
        .flight-info { background-color: #f8f9fa; border-radius: 8px; padding: 20px; margin: 20px 0; }
        .passenger-info { border-left: 4px solid #667eea; padding-left: 15px; margin: 15px 0; }
        .footer { background-color: #2d3748; color: white; padding: 20px; text-align: center; }
        .button { display: inline-block; background-color: #667eea; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; margin: 10px 0; }
        .booking-ref { font-size: 24px; font-weight: bold; color: #667eea; }
        .status-badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; }
        .confirmed { background-color: #d4edda; color: #155724; }
    </style>
</head>
<body>
    @php
        $isFlight = $booking->booking_type === 'flight';
        $isHotel = $booking->booking_type === 'hotel';
        $firstPassenger = collect($booking->passenger_details ?? [])->first();
        $contactName = $firstPassenger
            ? trim(($firstPassenger['first_name'] ?? '') . ' ' . ($firstPassenger['last_name'] ?? ''))
            : ($booking->user->name ?? trim(($booking->user->first_name ?? '') . ' ' . ($booking->user->last_name ?? '')));

        $detailsRoute = null;
        if ($isFlight) {
            $detailsRoute = route('booking.success', $booking->booking_reference);
        } elseif (\Illuminate\Support\Facades\Route::has('tours.confirmation') && in_array($booking->booking_type, ['tour', 'activity'])) {
            $detailsRoute = route('tours.confirmation', ['reference' => $booking->booking_reference]);
        }
    @endphp
    <div class="container">
        <div class="header">
            <h1>&#9992;&#65039; Booking Confirmed!</h1>
            <p>Your {{ $booking->booking_type }} has been successfully booked</p>
        </div>

        <div class="content">
            <div style="text-align: center; margin-bottom: 30px;">
                <h2>Booking Reference</h2>
                <div class="booking-ref">{{ $booking->booking_reference }}</div>
                <div class="status-badge confirmed">CONFIRMED</div>
            </div>

            @if($isFlight)
            <div class="flight-info">
                <h3>Flight Details</h3>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <strong>{{ $booking->departure_airport ?? 'N/A' }}</strong>
                        <div style="font-size: 14px; color: #666;">{{ $booking->departure_time ? $booking->departure_time->format('H:i') : '' }}</div>
                        <div style="font-size: 12px; color: #999;">{{ $booking->departure_time ? $booking->departure_time->format('D, d M Y') : '' }}</div>
                    </div>
                    <div style="text-align: center;">
                        <div>&#9992;&#65039;</div>
                    </div>
                    <div style="text-align: right;">
                        <strong>{{ $booking->arrival_airport ?? 'N/A' }}</strong>
                        <div style="font-size: 14px; color: #666;">{{ $booking->arrival_time ? $booking->arrival_time->format('H:i') : '' }}</div>
                        <div style="font-size: 12px; color: #999;">{{ $booking->arrival_time ? $booking->arrival_time->format('D, d M Y') : '' }}</div>
                    </div>
                </div>

                <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #dee2e6;">
                    <div style="display: flex; justify-content: space-between;">
                        <span>Flight Number:</span>
                        <span><strong>{{ $booking->flight_number ?? 'N/A' }}</strong></span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span>Class:</span>
                        <span><strong>{{ ucfirst(strtolower($booking->travel_class ?? 'Economy')) }}</strong></span>
                    </div>
                </div>
            </div>
            @elseif($isHotel)
            <div class="flight-info">
                <h3>Hotel Details</h3>
                <div style="display: flex; justify-content: space-between;">
                    <span>Check-in:</span>
                    <span><strong>{{ $booking->check_in_date ? $booking->check_in_date->format('D, d M Y') : 'N/A' }}</strong></span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span>Check-out:</span>
                    <span><strong>{{ $booking->check_out_date ? $booking->check_out_date->format('D, d M Y') : 'N/A' }}</strong></span>
                </div>
            </div>
            @else
            <div class="flight-info">
                <h3>{{ ucfirst($booking->booking_type) }} Details</h3>
                <div style="display: flex; justify-content: space-between;">
                    <span>{{ $booking->activity_name ?? $booking->title }}</span>
                    <span><strong>{{ $booking->destination ?? '' }}</strong></span>
                </div>
                @if($booking->activity_date)
                <div style="display: flex; justify-content: space-between;">
                    <span>Date:</span>
                    <span><strong>{{ $booking->activity_date->format('D, d M Y') }}</strong></span>
                </div>
                @endif
            </div>
            @endif

            <div class="passenger-info">
                <h3>Contact Information</h3>
                <div>
                    <strong>{{ $contactName ?: 'Guest' }}</strong>
                </div>
                <div style="color: #666; font-size: 14px;">
                    Email: {{ $booking->contact_email }}<br>
                    Phone: {{ $booking->contact_phone ?? 'N/A' }}
                </div>
            </div>

            <div style="background-color: #e3f2fd; padding: 20px; border-radius: 8px; margin: 20px 0;">
                <h3 style="color: #1976d2; margin-top: 0;">&#128161; Important Reminders</h3>
                <ul style="margin: 0; padding-left: 20px;">
                    @if($isFlight)
                    <li>Check-in opens 24 hours before departure</li>
                    <li>Arrive at the airport 2 hours before domestic flights, 3 hours for international</li>
                    <li>Ensure your passport is valid for at least 6 months</li>
                    @endif
                    <li>Keep this confirmation and your booking reference handy</li>
                </ul>
            </div>

            @if($detailsRoute)
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $detailsRoute }}" class="button">
                    View Booking Details
                </a>
            </div>
            @endif

            @if($isFlight)
            <div style="background-color: #fff3cd; padding: 15px; border-radius: 8px; border-left: 4px solid #ffc107;">
                <h4 style="color: #856404; margin-top: 0;">&#128206; E-Ticket Attached</h4>
                <p style="color: #856404; margin: 0;">Your e-ticket has been attached to this email. Please keep it handy for your travel.</p>
            </div>
            @endif
        </div>

        <div class="footer">
            <p>Thank you for choosing {{ config('app.name') }}!</p>
            <p>Need help? Contact us at {{ config('mail.support_email', 'support@example.com') }}</p>
            <p style="font-size: 12px; color: #a0aec0;">
                This is an automated email. Please do not reply to this message.
            </p>
        </div>
    </div>
</body>
</html>