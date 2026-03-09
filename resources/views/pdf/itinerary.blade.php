<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Travel Itinerary - {{ $booking->booking_reference }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #28a745; padding-bottom: 20px; }
        .title { font-size: 28px; font-weight: bold; color: #28a745; margin-bottom: 10px; }
        .subtitle { font-size: 14px; color: #666; }
        .section { margin-bottom: 30px; }
        .section-title { font-size: 16px; font-weight: bold; color: #28a745; margin-bottom: 15px; border-bottom: 1px solid #28a745; padding-bottom: 5px; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
        .info-item { background-color: #f8f9fa; padding: 15px; border-radius: 5px; }
        .info-label { font-weight: bold; color: #333; margin-bottom: 5px; }
        .timeline { position: relative; }
        .timeline-item { position: relative; padding: 20px 0; border-left: 3px solid #28a745; margin-left: 20px; padding-left: 30px; }
        .timeline-item:before { content: ''; position: absolute; left: -8px; top: 25px; width: 13px; height: 13px; border-radius: 50%; background-color: #28a745; }
        .timeline-time { font-weight: bold; color: #28a745; margin-bottom: 5px; }
        .timeline-content { color: #333; }
        .passenger-list { background-color: #f8f9fa; padding: 15px; border-radius: 5px; }
        .passenger-item { margin-bottom: 10px; padding: 10px; background-color: white; border-radius: 3px; }
        .important-notes { background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin-top: 20px; }
        .footer { margin-top: 50px; text-align: center; color: #666; font-size: 11px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">TRAVEL ITINERARY</div>
        <div class="subtitle">Booking Reference: {{ $booking->booking_reference }}</div>
        <div class="subtitle">Generated: {{ $generated_at }}</div>
    </div>

    <div class="section">
        <div class="section-title">Traveler Information</div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Primary Traveler</div>
                <div>{{ $user->name }}</div>
                <div>{{ $user->email }}</div>
                <div>{{ $user->phone ?? 'N/A' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Booking Details</div>
                <div>Reference: {{ $booking->booking_reference }}</div>
                <div>Type: {{ ucfirst($booking->booking_type) }}</div>
                <div>Status: {{ ucfirst($booking->status) }}</div>
                <div>Total: ${{ number_format($booking->amount, 2) }} {{ $booking->currency }}</div>
            </div>
        </div>
    </div>

    @if($booking->passengers && $booking->passengers->count() > 0)
        <div class="section">
            <div class="section-title">Passenger Details</div>
            <div class="passenger-list">
                @foreach($booking->passengers as $index => $passenger)
                    <div class="passenger-item">
                        <strong>Passenger {{ $index + 1 }}:</strong>
                        {{ $passenger->title ?? 'Mr/Ms' }} {{ $passenger->first_name }} {{ $passenger->last_name }}
                        @if(isset($passenger->passport_number))
                            <br>Passport: {{ $passenger->passport_number }}
                        @endif
                        @if(isset($passenger->date_of_birth))
                            <br>DOB: {{ $passenger->date_of_birth }}
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if($booking->booking_type === 'flight')
        <div class="section">
            <div class="section-title">Flight Details</div>
            <div class="timeline">
                <div class="timeline-item">
                    <div class="timeline-time">{{ $booking->departure_time ? $booking->departure_time->format('M j, Y - g:i A') : 'TBD' }}</div>
                    <div class="timeline-content">
                        <strong>Departure</strong><br>
                        {{ $booking->departure_airport }} Airport<br>
                        Flight: {{ $booking->flight_number }}<br>
                        Class: {{ $booking->travel_class ?? 'Economy' }}
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-time">{{ $booking->arrival_time ? $booking->arrival_time->format('M j, Y - g:i A') : 'TBD' }}</div>
                    <div class="timeline-content">
                        <strong>Arrival</strong><br>
                        {{ $booking->arrival_airport }} Airport
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($booking->booking_type === 'hotel')
        <div class="section">
            <div class="section-title">Hotel Details</div>
            <div class="timeline">
                <div class="timeline-item">
                    <div class="timeline-time">{{ $booking->check_in_date ? $booking->check_in_date->format('M j, Y') : 'TBD' }}</div>
                    <div class="timeline-content">
                        <strong>Check-in</strong><br>
                        Hotel ID: {{ $booking->hotel_id }}<br>
                        Guests: {{ $booking->adults }} Adults, {{ $booking->children }} Children
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-time">{{ $booking->check_out_date ? $booking->check_out_date->format('M j, Y') : 'TBD' }}</div>
                    <div class="timeline-content">
                        <strong>Check-out</strong><br>
                        End of stay
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="important-notes">
        <h4 style="margin-top: 0; color: #856404;">Important Travel Notes</h4>
        <ul style="margin: 0; padding-left: 20px; color: #856404;">
            <li>Arrive at the airport at least 2 hours before domestic flights and 3 hours before international flights</li>
            <li>Ensure your passport is valid for at least 6 months from your travel date</li>
            <li>Check visa requirements for your destination</li>
            <li>Review baggage allowances and restrictions</li>
            <li>Keep this itinerary handy during your travel</li>
        </ul>
    </div>

    <div class="footer">
        <p>Safe travels from {{ config('app.name') }}!</p>
        <p>For assistance, contact us at {{ config('mail.support_email', 'support@example.com') }}</p>
    </div>
</body>
</html>