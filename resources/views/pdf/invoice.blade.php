<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice - {{ $invoice_number }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; font-size: 12px; color: #333; }
        .header { display: flex; justify-content: space-between; align-items: start; margin-bottom: 30px; border-bottom: 2px solid #007bff; padding-bottom: 20px; }
        .company-info { flex: 1; }
        .company-name { font-size: 24px; font-weight: bold; color: #007bff; margin-bottom: 10px; }
        .invoice-info { text-align: right; }
        .invoice-title { font-size: 28px; font-weight: bold; color: #333; margin-bottom: 10px; }
        .invoice-number { font-size: 16px; color: #666; }
        .billing-section { display: flex; justify-content: space-between; margin-bottom: 30px; }
        .billing-info { flex: 1; }
        .billing-title { font-size: 14px; font-weight: bold; margin-bottom: 10px; color: #007bff; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table th, .table td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        .table th { background-color: #f8f9fa; font-weight: bold; color: #333; }
        .total-section { text-align: right; margin-top: 20px; }
        .total-row { margin-bottom: 5px; }
        .total-label { display: inline-block; width: 150px; text-align: right; }
        .total-amount { font-weight: bold; margin-left: 20px; }
        .grand-total { font-size: 18px; color: #007bff; border-top: 2px solid #007bff; padding-top: 10px; margin-top: 10px; }
        .footer { margin-top: 50px; text-align: center; color: #666; font-size: 11px; }
        .payment-status { display: inline-block; padding: 5px 15px; border-radius: 20px; font-size: 11px; font-weight: bold; }
        .status-completed { background-color: #d4edda; color: #155724; }
        .status-pending { background-color: #fff3cd; color: #856404; }
        .status-failed { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-info">
            <div class="company-name">{{ $company_details['name'] }}</div>
            <div>{{ $company_details['address'] }}</div>
            <div>Phone: {{ $company_details['phone'] }}</div>
            <div>Email: {{ $company_details['email'] }}</div>
            <div>Tax ID: {{ $company_details['tax_id'] }}</div>
        </div>
        <div class="invoice-info">
            <div class="invoice-title">INVOICE</div>
            <div class="invoice-number">{{ $invoice_number }}</div>
            <div>Date: {{ $invoice_date }}</div>
            <div>Due Date: {{ $due_date }}</div>
        </div>
    </div>

    <div class="billing-section">
        <div class="billing-info">
            <div class="billing-title">BILL TO:</div>
            <div><strong>{{ $user->name }}</strong></div>
            <div>{{ $user->email }}</div>
            <div>{{ $user->phone ?? 'N/A' }}</div>
        </div>
        <div class="billing-info">
            <div class="billing-title">BOOKING DETAILS:</div>
            <div>Reference: <strong>{{ $booking->booking_reference }}</strong></div>
            <div>Type: {{ ucfirst($booking->booking_type) }}</div>
            <div>Status: <span class="payment-status status-{{ $booking->payment_status }}">{{ strtoupper($booking->payment_status) }}</span></div>
            <div>Payment Method: {{ strtoupper($booking->payment_method) }}</div>
        </div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Description</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @if($booking->booking_type === 'flight')
                <tr>
                    <td>
                        <strong>Flight Booking</strong><br>
                        {{ $booking->departure_airport }} → {{ $booking->arrival_airport }}<br>
                        Flight: {{ $booking->flight_number }}<br>
                        Date: {{ $booking->departure_time ? $booking->departure_time->format('M j, Y') : 'N/A' }}<br>
                        Class: {{ $booking->travel_class ?? 'Economy' }}
                    </td>
                    <td>{{ $booking->adults + $booking->children }}</td>
                    <td>${{ number_format($booking->amount / ($booking->adults + $booking->children), 2) }}</td>
                    <td>${{ number_format($booking->amount, 2) }}</td>
                </tr>
            @else
                <tr>
                    <td>
                        <strong>Hotel Booking</strong><br>
                        Hotel ID: {{ $booking->hotel_id }}<br>
                        Check-in: {{ $booking->check_in_date ? $booking->check_in_date->format('M j, Y') : 'N/A' }}<br>
                        Check-out: {{ $booking->check_out_date ? $booking->check_out_date->format('M j, Y') : 'N/A' }}<br>
                        Guests: {{ $booking->adults + $booking->children }}
                    </td>
                    <td>1</td>
                    <td>${{ number_format($booking->amount, 2) }}</td>
                    <td>${{ number_format($booking->amount, 2) }}</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="total-section">
        <div class="total-row">
            <span class="total-label">Subtotal:</span>
            <span class="total-amount">${{ number_format($booking->amount, 2) }}</span>
        </div>
        <div class="total-row">
            <span class="total-label">Tax (0%):</span>
            <span class="total-amount">$0.00</span>
        </div>
        <div class="total-row">
            <span class="total-label">Service Fee:</span>
            <span class="total-amount">$0.00</span>
        </div>
        <div class="total-row grand-total">
            <span class="total-label">Total Amount:</span>
            <span class="total-amount">${{ number_format($booking->amount, 2) }} {{ $booking->currency }}</span>
        </div>
    </div>

    <div class="footer">
        <p>Thank you for choosing {{ $company_details['name'] }}!</p>
        <p>This invoice was generated on {{ now()->format('Y-m-d H:i:s') }}</p>
        <p>For any questions regarding this invoice, please contact us at {{ $company_details['email'] }}</p>
    </div>
</body>
</html>