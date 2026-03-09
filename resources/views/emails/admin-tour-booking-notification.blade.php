<!-- filepath: c:\xampp\htdocs\GNC\resources\views\emails\admin-tour-booking-notification.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Tour Booking Notification</title>
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
            padding: 20px 0;
            border-bottom: 1px solid #eee;
        }
        .booking-details {
            margin: 20px 0;
            padding: 20px;
            background-color: #f9f9f9;
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
            background-color: #337ab7;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>New Tour Booking Notification</h2>
            <p>A new tour booking has been created in the system.</p>
        </div>
        
        <div class="booking-details">
            <h3>Booking Details</h3>
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
                    <th>Participants:</th>
                    <td>{{ $booking->participants }}</td>
                </tr>
                <tr>
                    <th>Total Amount:</th>
                    <td>{{ $booking->currency }} {{ number_format($booking->total_amount, 2) }}</td>
                </tr>
                <tr>
                    <th>Payment Status: </th>
                    <td>
                        <span class="payment-status {{ $booking->is_paid ? 'status-paid' : 'status-pending' }}">
                            {{ $booking->is_paid ? 'Paid' : 'Pending' }}
                        </span> 
                        @if(!$booking->is_paid)
                            <a href="{{ route('admin.tour.booking.pay', $booking->id) }}" class="button">Pay Now</a>    
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Customer Name:</th>
                    <td>{{ $booking->customer_name }}</td>
                </tr>
                <tr>
                    <th>Customer Email:</th>
                    <td>{{ $booking->customer_email }}</td>
                </tr>
                <tr>
                    <th>Customer Phone:</th>
                    <td>{{ $booking->customer_phone }}</td>
                </tr>   
                <tr>
                    <th>Booking Date:</th>
                    <td>{{ $booking->created_at->format('F d, Y H:i') }}</td>
                </tr>
                <tr>
                    <th>Booking Status:</th>
                    <td>{{ $booking->status }}</td>
                </tr>
                <tr>
                    <th>Notes:</th>
                    <td>{{ $booking->notes ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>
        <p>For more details, you can view the booking in the admin panel.</p>
        <p>Thank you!</p>
        <p>Best regards,<br>
        The Ziara Travels Team</p>
        <p><a href="{{ route('admin.tour.bookings.index') }}" class="button">View All Bookings</a></p>
    </div>
</body>
</html>







