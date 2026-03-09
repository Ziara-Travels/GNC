<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Required - Ziara Travel</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #dc2626; color: white; padding: 20px; text-align: center; }
        .content { padding: 30px 20px; background: #f9fafb; }
        .booking-details { background: #fff; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #dc2626; }
        .amount-box { background: #fef2f2; padding: 20px; border-radius: 8px; text-align: center; margin: 20px 0; border: 2px solid #dc2626; }
        .amount { font-size: 28px; font-weight: bold; color: #dc2626; }
        .footer { background: #374151; color: white; padding: 20px; text-align: center; font-size: 14px; }
        .btn { display: inline-block; background: #dc2626; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; margin: 15px 0; }
        .urgent { background: #fecaca; padding: 15px; border-radius: 6px; margin: 15px 0; border-left: 4px solid #dc2626; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔔 Payment Required</h1>
        </div>
        
        <div class="content">
            <h2>Hello {{ $user->name }}!</h2>
            
            <p>A {{ $booking->booking_type }} booking has been made for you by <strong>{{ $bookedBy->name }}</strong>. To confirm your booking, please complete the payment as soon as possible.</p>
            
            <div class="urgent">
                <h4>⏰ Action Required</h4>
                <p>This booking is currently pending payment. Please log in to your dashboard to complete the payment and confirm your reservation.</p>
            </div>
            
            <div class="booking-details">
                <h3>Booking Details:</h3>
                <ul>
                    <li><strong>Booking Reference:</strong> {{ $booking->booking_reference }}</li>
                    <li><strong>Booking Type:</strong> {{ ucfirst($booking->booking_type) }}</li>
                    <li><strong>Booked By:</strong> {{ $bookedBy->name }} ({{ $bookedBy->email }})</li>
                    <li><strong>Created:</strong> {{ $booking->created_at->format('M d, Y H:i') }}</li>
                    @if($booking->booking_type === 'flight')
                        <li><strong>Route:</strong> {{ $booking->departure_airport }} → {{ $booking->arrival_airport }}</li>
                        @if($booking->departure_time)
                            <li><strong>Departure:</strong> {{ $booking->departure_time->format('M d, Y H:i') }}</li>
                        @endif
                        @if($booking->arrival_time)
                            <li><strong>Arrival:</strong> {{ $booking->arrival_time->format('M d, Y H:i') }}</li>
                        @endif
                    @elseif($booking->booking_type === 'hotel')
                        @if($booking->check_in_date)
                            <li><strong>Check-in:</strong> {{ $booking->check_in_date->format('M d, Y') }}</li>
                        @endif
                        @if($booking->check_out_date)
                            <li><strong>Check-out:</strong> {{ $booking->check_out_date->format('M d, Y') }}</li>
                        @endif
                    @endif
                    <li><strong>Status:</strong> <span style="color: #dc2626;">{{ ucfirst($booking->status) }}</span></li>
                </ul>
            </div>
            
            <div class="amount-box">
                <h3>Amount Due:</h3>
                <div class="amount">{{ $booking->currency }} {{ number_format($booking->total_amount, 2) }}</div>
                <p><em>Total amount for your {{ $booking->booking_type }} booking</em></p>
            </div>
            
            <h3>How to Complete Payment:</h3>
            <ol>
                <li>Log in to your Ziara Travel dashboard</li>
                <li>Go to "Pending Payments" section</li>
                <li>Find booking reference: <strong>{{ $booking->booking_reference }}</strong></li>
                <li>Click "Pay Now" and follow the payment instructions</li>
                <li>Your booking will be confirmed once payment is processed</li>
            </ol>
            
            <div class="urgent">
                <h4>⚠️ Important Notes:</h4>
                <ul>
                    <li>This booking will be cancelled if payment is not received within 24 hours</li>
                    <li>Prices may change if booking expires</li>
                    <li>Contact {{ $bookedBy->name }} if you have questions about this booking</li>
                </ul>
            </div>
            
            <a href="{{ url('/dashboard/pending-payments') }}" class="btn">Pay Now</a>
            <a href="{{ url('/dashboard') }}" class="btn" style="background: #374151;">View Dashboard</a>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Ziara Travel. All rights reserved.</p>
            <p>Questions? Contact us at support@ziaratravel.com</p>
            <p>This booking was made by {{ $bookedBy->name }} on your behalf.</p>
        </div>
    </div>
</body>
</html>
