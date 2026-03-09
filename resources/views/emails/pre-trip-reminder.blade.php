<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flight Reminder</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 0 auto; background-color: white; }
        .header { background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%); color: white; padding: 30px; text-align: center; }
        .content { padding: 30px; }
        .flight-info { background-color: #f8f9fa; border-radius: 8px; padding: 20px; margin: 20px 0; }
        .urgent { background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0; }
        .footer { background-color: #2d3748; color: white; padding: 20px; text-align: center; }
        .button { display: inline-block; background-color: #ff6b6b; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; margin: 10px 0; }
        .countdown { font-size: 36px; font-weight: bold; color: #ff6b6b; text-align: center; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚨 Flight Reminder</h1>
            <p>Your flight is approaching soon!</p>
        </div>
        
        <div class="content">
            @if($reminderType === '24_hours')
                <div class="countdown">24 HOURS</div>
                <p style="text-align: center; font-size: 18px;">Until your flight departure</p>
            @elseif($reminderType === '2_hours')
                <div class="countdown">2 HOURS</div>
                <p style="text-align: center; font-size: 18px;">Until your flight departure</p>
            @elseif($reminderType === 'check_in')
                <div class="urgent">
                    <h3 style="color: #856404; margin-top: 0;">✅ Check-in Now Available</h3>
                    <p style="color: #856404; margin: 0;">Online check-in is now open for your flight. Check in now to secure your seat!</p>
                </div>
            @endif

            <div class="flight-info">
                <h3>Flight Details</h3>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <strong>{{ $flight['from'] }}</strong>
                        <div style="font-size: 14px; color: #666;">{{ $flight['departure_time'] }}</div>
                        <div style="font-size: 12px; color: #999;">{{ $flight['departure_date'] }}</div>
                    </div>
                    <div style="text-align: center;">
                        <div>✈️</div>
                        <div style="font-size: 12px; color: #666;">{{ $flight['duration'] }}</div>
                    </div>
                    <div style="text-align: right;">
                        <strong>{{ $flight['to'] }}</strong>
                        <div style="font-size: 14px; color: #666;">{{ $flight['arrival_time'] }}</div>
                        <div style="font-size: 12px; color: #999;">{{ $flight['arrival_date'] }}</div>
                    </div>
                </div>
                
                <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #dee2e6;">
                    <div style="display: flex; justify-content: space-between;">
                        <span>Booking Reference:</span>
                        <span><strong>{{ $booking->booking_reference }}</strong></span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span>Flight Number:</span>
                        <span><strong>{{ $flight['flight_number'] }}</strong></span>
                    </div>
                </div>
            </div>

            <div style="background-color: #e8f5e8; padding: 20px; border-radius: 8px; margin: 20px 0;">
                <h3 style="color: #2e7d32; margin-top: 0;">📋 Pre-Flight Checklist</h3>
                <ul style="margin: 0; padding-left: 20px; color: #2e7d32;">
                    <li>✅ Check-in online (available 24 hours before departure)</li>
                    <li>📱 Download mobile boarding pass</li>
                    <li>🧳 Review baggage allowance</li>
                    <li>📄 Ensure travel documents are ready</li>
                    <li>🚗 Plan your route to the airport</li>
                    <li>⏰ Arrive 2 hours early for domestic flights</li>
                </ul>
            </div>

            <div style="text-align: center; margin: 30px 0;">
                @if($reminderType === 'check_in')
                    <a href="{{ $checkInUrl }}" class="button">
                        Check-in Now
                    </a>
                @endif
                <a href="{{ route('booking.details', $booking->booking_reference) }}" class="button">
                    View Booking
                </a>
            </div>

            <div style="background-color: #fff3cd; padding: 15px; border-radius: 8px; border-left: 4px solid #ffc107;">
                <h4 style="color: #856404; margin-top: 0;">🌤️ Weather Update</h4>
                <p style="color: #856404; margin: 0;">Check the weather forecast for your destination to pack accordingly.</p>
            </div>
        </div>
        
        <div class="footer">
            <p>Safe travels with {{ config('app.name') }}!</p>
            <p>Need help? Contact us at {{ config('mail.support_email', 'support@example.com') }}</p>
        </div>
    </div>
</body>
</html>