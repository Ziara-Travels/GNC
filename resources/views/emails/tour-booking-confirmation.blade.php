
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tour Booking Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            padding: 20px 0;
            border-bottom: 1px solid #eee;
        }
        .logo {
            max-width: 150px;
        }
        .booking-details {
            margin: 20px 0;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
        .tour-info {
            margin: 20px 0;
        }
        .tour-image {
            width: 100%;
            max-height: 300px;
            object-fit: cover;
            border-radius: 5px;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
        }
        .details-table th,
        .details-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        .payment-status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 3px;
            font-weight: bold;
        }
        .status-paid {
            background-color: #dff0d8;
            color: #3c763d;
        }
        .status-pending {
            background-color: #fcf8e3;
            color: #8a6d3b;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            text-align: center;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('images/logo.png') }}" alt="Ziara Travel Logo" class="logo">
            <h1>Your Tour Booking is Confirmed!</h1>
            <p>Booking Reference: <strong>{{ $booking->booking_reference }}</strong></p>
        </div>
        
        <p>Hello {{ $booking->contact_name }},</p>
        
        <p>Thank you for booking with Ziara Travel. Your tour reservation has been received and is confirmed. Below are the details of your booking:</p>
        
        <div class="booking-details">
            <h3>Booking Summary</h3>
            <table class="details-table">
                <tr>
                    <th>Booking Reference:</th>
                    <td>{{ $booking->booking_reference }}</td>
                </tr>
                <tr>
                    <th>Tour:</th>
                    <td>{{ $booking->tour->name }}</td>
                </tr>
                <tr>
                    <th>Date:</th>
                    <td>{{ $booking->tour_date->format('F d, Y') }}</td>
                </tr>
                <tr>
                    <th>Time:</th>
                    <td>{{ $booking->tour_time ? $booking->tour_time->format('h:i A') : 'To be confirmed' }}</td>
                </tr>
                <tr>
                    <th>Participants:</th>
                    <td>{{ $booking->participants }}</td>
                </tr>
                <tr>
                    <th>Total Amount:</th>
                    <td>{{ $booking->currency }} {{ number_format($booking->total_amount, 2) }}</td>
                </tr>
                <tr>
                    <th>Payment Status:</th>
                    <td>
                        <span class="payment-status {{ $booking->payment_status == 'paid' ? 'status-paid' : 'status-pending' }}">
                            {{ ucfirst($booking->payment_status) }}
                        </span>
                    </td>
                </tr>
            </table>
        </div>
        
        <div class="tour-info">
            <h3>Tour Information</h3>
            <img src="{{ $booking->tour->main_image }}" alt="{{ $booking->tour->name }}" class="tour-image">
            <h4>{{ $booking->tour->name }}</h4>
            <p>{{ $booking->tour->short_description }}</p>
            
            <h4>Meeting Point:</h4>
            <p>{{ $booking->tour->meeting_point ?? 'Details will be provided closer to the tour date' }}</p>
            
            @if($booking->hotel_pickup)
                <p><strong>Hotel Pickup:</strong> Yes (from {{ $booking->pickup_location }})</p>
            @endif
            
            @if($booking->special_requirements)
                <h4>Special Requirements:</h4>
                <p>{{ $booking->special_requirements }}</p>
            @endif
        </div>
        
        @if($booking->payment_status == 'pending')
            <p>Please complete your payment to fully confirm your booking:</p>
            <p style="text-align: center;">
                <a href="{{ route('tour.payment', ['reference' => $booking->booking_reference]) }}" class="button">
                    Complete Payment
                </a>
            </p>
        @endif
        
        <p>If you have any questions about your tour, please don't hesitate to contact us at support@ziaratravel.com or call us at +254 712 345 678.</p>
        
        <p>We look forward to welcoming you on this exciting adventure!</p>
        
        <p>Best regards,<br>
        The Ziara Travel Team</p>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Ziara Travel. All rights reserved.</p>
            <p>This is an automated email. Please do not reply to this message.</p>
        </div>
    </div>
</body>
</html>