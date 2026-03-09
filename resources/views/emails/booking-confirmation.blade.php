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
    <div class="container">
        <div class="header">
            <h1>✈️ Booking Confirmed!</h1>
            <p>Your flight has been successfully booked</p>
        </div>
        
        <div class="content">
            <div style="text-align: center; margin-bottom: 30px;">
                <h2>Booking Reference</h2>
                <div class="booking-ref">{{ $booking->booking_reference }}</div>
                <div class="status-badge confirmed">CONFIRMED</div>
            </div>

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
                        <span>Airline:</span>
                        <span><strong>{{ $flight['airline'] }}</strong></span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span>Flight Number:</span>
                        <span><strong>{{ $flight['flight_number'] }}</strong></span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span>Class:</span>
                        <span><strong>{{ $flight['class'] }}</strong></span>
                    </div>
                </div>
            </div>

            <div class="passenger-info">
                <h3>Passenger Information</h3>
                <div>
                    <strong>{{ $passenger->title }} {{ $passenger->first_name }} {{ $passenger->last_name }}</strong>
                </div>
                <div style="color: #666; font-size: 14px;">
                    Email: {{ $passenger->email }}<br>
                    Phone: {{ $passenger->phone }}
                </div>
            </div>

            <div style="background-color: #e3f2fd; padding: 20px; border-radius: 8px; margin: 20px 0;">
                <h3 style="color: #1976d2; margin-top: 0;">💡 Important Reminders</h3>
                <ul style="margin: 0; padding-left: 20px;">
                    <li>Check-in opens 24 hours before departure</li>
                    <li>Arrive at the airport 2 hours before domestic flights</li>
                    <li>Ensure your passport is valid for at least 6 months</li>
                    <li>Review baggage allowance and restrictions</li>
                </ul>
            </div>

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ route('booking.details', $booking->booking_reference) }}" class="button">
                    View Booking Details
                </a>
                <a href="{{ route('booking.manage', $booking->booking_reference) }}" class="button">
                    Manage Booking
                </a>
            </div>

            <div style="background-color: #fff3cd; padding: 15px; border-radius: 8px; border-left: 4px solid #ffc107;">
                <h4 style="color: #856404; margin-top: 0;">📎 E-Ticket Attached</h4>
                <p style="color: #856404; margin: 0;">Your e-ticket has been attached to this email. Please keep it handy for your travel.</p>
            </div>
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