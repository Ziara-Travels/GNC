<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Booking Confirmation</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #2563eb; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f9f9f9; }
        .booking-details { background: white; padding: 20px; margin: 20px 0; border-radius: 8px; }
        .footer { padding: 20px; text-align: center; color: #666; }
        .btn { display: inline-block; padding: 12px 24px; background: #2563eb; color: white; text-decoration: none; border-radius: 6px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Booking Confirmation</h1>
            <p>Your {{ $booking->booking_type }} booking has been confirmed!</p>
        </div>
        
        <div class="content">
            <div class="booking-details">
                <h2>Booking Details</h2>
                <p><strong>Booking Reference:</strong> {{ $booking->booking_reference }}</p>
                <p><strong>Amount:</strong> {{ $booking->currency }} {{ number_format($booking->amount, 2) }}</p>
                <p><strong>Payment Method:</strong> {{ ucfirst($booking->payment_method) }}</p>
                <p><strong>Transaction ID:</strong> {{ $booking->transaction_id }}</p>
                
                @if($booking->booking_type === 'flight')
                    <p><strong>Flight:</strong> {{ $booking->flight_number }}</p>
                    <p><strong>From:</strong> {{ $booking->departure_airport }}</p>
                    <p><strong>To:</strong> {{ $booking->arrival_airport }}</p>
                    <p><strong>Departure:</strong> {{ \Carbon\Carbon::parse($booking->departure_time)->format('d M Y, H:i') }}</p>
                @else
                    <p><strong>Hotel ID:</strong> {{ $booking->hotel_id }}</p>
                    <p><strong>Check-in:</strong> {{ \Carbon\Carbon::parse($booking->check_in_date)->format('d M Y') }}</p>
                    <p><strong>Check-out:</strong> {{ \Carbon\Carbon::parse($booking->check_out_date)->format('d M Y') }}</p>
                @endif
            </div>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ route('dashboard') }}" class="btn">View in Dashboard</a>
            </div>
        </div>
        
        <div class="footer">
            <p>Thank you for choosing our platform!</p>
            <p>For any queries, contact us at support@yourplatform.com</p>
        </div>
    </div>
</body>
</html>