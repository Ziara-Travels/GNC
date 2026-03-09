<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>E-Ticket - {{ $booking->booking_reference }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; font-size: 12px; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 20px; margin-bottom: 20px; }
        .booking-ref { font-size: 24px; font-weight: bold; color: #333; }
        .flight-section { margin-bottom: 20px; border: 1px solid #ddd; padding: 15px; }
        .passenger-section { background-color: #f9f9f9; padding: 15px; margin-bottom: 20px; }
        .barcode { text-align: center; margin: 20px 0; }
        .important { background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 10px; margin: 10px 0; }
        .row { display: flex; justify-content: space-between; margin-bottom: 10px; }
        .label { font-weight: bold; }
        .qr-code { width: 100px; height: 100px; margin: 0 auto; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ config('app.name') }}</h1>
        <h2>Electronic Ticket</h2>
        <div class="booking-ref">{{ $booking->booking_reference }}</div>
    </div>

    @foreach($passengers as $passenger)
        <div class="passenger-section">
            <h3>Passenger: {{ $passenger->title }} {{ $passenger->first_name }} {{ $passenger->last_name }}</h3>
            <div class="row">
                <span class="label">Email:</span>
                <span>{{ $passenger->email }}</span>
            </div>
            <div class="row">
                <span class="label">Phone:</span>
                <span>{{ $passenger->phone }}</span>
            </div>
            <div class="row">
                <span class="label">Passport:</span>
                <span>{{ $passenger->passport_number }}</span>
            </div>
        </div>
    @endforeach

    <div class="flight-section">
        <h3>Flight Details</h3>
        <div class="row">
            <span class="label">From:</span>
            <span>{{ $flight['from'] }} ({{ $flight['departure_time'] }})</span>
        </div>
        <div class="row">
            <span class="label">To:</span>
            <span>{{ $flight['to'] }} ({{ $flight['arrival_time'] }})</span>
        </div>
        <div class="row">
            <span class="label">Date:</span>
            <span>{{ $flight['departure_date'] }}</span>
        </div>
        <div class="row">
            <span class="label">Flight Number:</span>
            <span>{{ $flight['flight_number'] }}</span>
        </div>
        <div class="row">
            <span class="label">Class:</span>
            <span>{{ $flight['class'] }}</span>
        </div>
        <div class="row">
            <span class="label">Duration:</span>
            <span>{{ $flight['duration'] }}</span>
        </div>
    </div>

    <div class="important">
        <h4>Important Information</h4>
        <ul>
            <li>This is your official e-ticket. Please keep it with you during travel.</li>
            <li>Check-in opens 24 hours before departure</li>
            <li>Arrive at the airport at least 2 hours before domestic flights</li>
            <li>Ensure your passport is valid for at least 6 months</li>
            <li>Review baggage allowance and restrictions</li>
        </ul>
    </div>

    <div class="barcode">
        <div class="qr-code">
            <!-- QR Code would be generated here -->
            <div style="border: 1px solid #333; width: 100px; height: 100px; display: flex; align-items: center; justify-content: center;">
                QR CODE
            </div>
        </div>
        <div style="margin-top: 10px;">{{ $booking->booking_reference }}</div>
    </div>

    <div style="text-align: center; margin-top: 30px; font-size: 10px; color: #666;">
        <p>Generated on {{ now()->format('Y-m-d H:i:s') }}</p>
        <p>{{ config('app.name') }} - Your Travel Partner</p>
    </div>
</body>
</html>