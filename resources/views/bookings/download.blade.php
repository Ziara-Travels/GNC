<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation - {{ $booking->reference_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 30px;
            border: 1px solid #ddd;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 20px;
        }
        
        .header h1 {
            color: #2563eb;
            margin: 0;
            font-size: 28px;
        }
        
        .header .reference {
            font-size: 16px;
            color: #666;
            margin-top: 5px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 10px;
        }
        
        .status-confirmed {
            background-color: #10b981;
            color: white;
        }
        
        .status-pending {
            background-color: #f59e0b;
            color: white;
        }
        
        .details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }
        
        .details-section {
            background: #f9fafb;
            padding: 20px;
            border-radius: 8px;
        }
        
        .details-section h3 {
            margin: 0 0 15px 0;
            color: #374151;
            font-size: 18px;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 10px;
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            padding: 5px 0;
        }
        
        .detail-label {
            font-weight: 600;
            color: #6b7280;
        }
        
        .detail-value {
            font-weight: 500;
            color: #111827;
            text-align: right;
        }
        
        .payment-section {
            background: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
        }
        
        .payment-pending {
            text-align: center;
            color: #92400e;
        }
        
        .payment-confirmed {
            background: #d1fae5;
            border-color: #10b981;
            color: #065f46;
        }
        
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 14px;
        }
        
        .contact-info {
            margin-top: 20px;
        }
        
        .contact-info strong {
            color: #2563eb;
        }
        
        @media print {
            body {
                padding: 0;
            }
            
            .container {
                border: none;
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>{{ ucfirst($booking->booking_type) }} Booking Confirmation</h1>
            <div class="reference">Reference Number: {{ $booking->reference_number }}</div>
            <div class="status-badge status-{{ $booking->status }}">
                {{ ucfirst($booking->status) }}
            </div>
        </div>

        <!-- Booking Details Grid -->
        <div class="details-grid">
            <!-- Trip Information -->
            <div class="details-section">
                <h3>Trip Information</h3>
                
                @if($booking->booking_type === 'flight')
                    <div class="detail-row">
                        <span class="detail-label">From:</span>
                        <span class="detail-value">{{ $booking->origin }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">To:</span>
                        <span class="detail-value">{{ $booking->destination }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Departure:</span>
                        <span class="detail-value">
                            {{ $booking->departure_date ? Carbon\Carbon::parse($booking->departure_date)->format('M d, Y H:i') : 'N/A' }}
                        </span>
                    </div>
                    @if($booking->return_date)
                    <div class="detail-row">
                        <span class="detail-label">Return:</span>
                        <span class="detail-value">
                            {{ Carbon\Carbon::parse($booking->return_date)->format('M d, Y H:i') }}
                        </span>
                    </div>
                    @endif
                    <div class="detail-row">
                        <span class="detail-label">Passengers:</span>
                        <span class="detail-value">{{ $booking->adults + $booking->children + $booking->infants }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Class:</span>
                        <span class="detail-value">{{ ucfirst($booking->travel_class ?? 'Economy') }}</span>
                    </div>
                @elseif($booking->booking_type === 'hotel')
                    <div class="detail-row">
                        <span class="detail-label">Hotel:</span>
                        <span class="detail-value">{{ $booking->destination }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Check-in:</span>
                        <span class="detail-value">
                            {{ $booking->departure_date ? Carbon\Carbon::parse($booking->departure_date)->format('M d, Y') : 'N/A' }}
                        </span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Check-out:</span>
                        <span class="detail-value">
                            {{ $booking->return_date ? Carbon\Carbon::parse($booking->return_date)->format('M d, Y') : 'N/A' }}
                        </span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Guests:</span>
                        <span class="detail-value">{{ $booking->adults }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Rooms:</span>
                        <span class="detail-value">{{ $booking->rooms ?? 1 }}</span>
                    </div>
                @endif
            </div>

            <!-- Customer Information -->
            <div class="details-section">
                <h3>Customer Information</h3>
                <div class="detail-row">
                    <span class="detail-label">Name:</span>
                    <span class="detail-value">{{ $booking->passenger_name ?? $booking->user->name }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Email:</span>
                    <span class="detail-value">{{ $booking->passenger_email ?? $booking->user->email }}</span>
                </div>
                @if($booking->passenger_phone)
                <div class="detail-row">
                    <span class="detail-label">Phone:</span>
                    <span class="detail-value">{{ $booking->passenger_phone }}</span>
                </div>
                @endif
                @if($booking->passport_number)
                <div class="detail-row">
                    <span class="detail-label">Passport:</span>
                    <span class="detail-value">{{ $booking->passport_number }}</span>
                </div>
                @endif
                <div class="detail-row">
                    <span class="detail-label">Booking Date:</span>
                    <span class="detail-value">{{ $booking->created_at->format('M d, Y H:i') }}</span>
                </div>
            </div>
        </div>

        <!-- Payment Information -->
        <div class="payment-section {{ $booking->payment_status === 'paid' ? 'payment-confirmed' : '' }}">
            <h3 style="margin: 0 0 15px 0;">Payment Information</h3>
            <div class="detail-row">
                <span class="detail-label">Total Amount:</span>
                <span class="detail-value">{{ $booking->currency }} {{ number_format($booking->total_price, 2) }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Payment Status:</span>
                <span class="detail-value">{{ ucfirst($booking->payment_status) }}</span>
            </div>
            @if($booking->payment_method)
            <div class="detail-row">
                <span class="detail-label">Payment Method:</span>
                <span class="detail-value">{{ ucfirst($booking->payment_method) }}</span>
            </div>
            @endif
            
            @if($booking->payment_status === 'pending')
            <div class="payment-pending">
                <strong>Payment Required</strong><br>
                Please complete your payment to confirm this booking.
            </div>
            @endif
        </div>

        @if($booking->special_requests)
        <!-- Special Requests -->
        <div class="details-section" style="margin-bottom: 30px;">
            <h3>Special Requests</h3>
            <p style="margin: 0; color: #374151;">{{ $booking->special_requests }}</p>
        </div>
        @endif

        @if($booking->bookedBy && $booking->bookedBy->id !== $booking->user_id)
        <!-- Booked By Information -->
        <div class="details-section" style="margin-bottom: 30px;">
            <h3>Booked By</h3>
            <div class="detail-row">
                <span class="detail-label">Name:</span>
                <span class="detail-value">{{ $booking->bookedBy->name }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Email:</span>
                <span class="detail-value">{{ $booking->bookedBy->email }}</span>
            </div>
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p><strong>Thank you for choosing GNC Travels!</strong></p>
            <div class="contact-info">
                <p>For assistance, please contact us:</p>
                <p><strong>Email:</strong> support@gnctravels.com</p>
                <p><strong>Phone:</strong> +1 (234) 567-890</p>
                <p><strong>Website:</strong> www.gnctravels.com</p>
            </div>
            <p style="margin-top: 20px; font-size: 12px;">
                This is a computer-generated document. No signature is required.
            </p>
        </div>
    </div>

    <script>
        // Auto-print when page loads (optional)
        // window.print();
    </script>
</body>
</html>
                <span class="detail-value">{{ $booking->destination ?? 'N/A' }}</span>
            </div>
        </div>
        @endif

        @if($booking->adults || $booking->children)
        <div class="section">
            <h3>Traveler Information</h3>
            @if($booking->adults)
            <div class="detail-row">
                <span class="detail-label">Adults:</span>
                <span class="detail-value">{{ $booking->adults }}</span>
            </div>
            @endif
            @if($booking->children)
            <div class="detail-row">
                <span class="detail-label">Children:</span>
                <span class="detail-value">{{ $booking->children }}</span>
            </div>
            @endif
        </div>
        @endif

        @if($booking->contact_email || $booking->contact_phone)
        <div class="section">
            <h3>Contact Information</h3>
            @if($booking->contact_email)
            <div class="detail-row">
                <span class="detail-label">Email:</span>
                <span class="detail-value">{{ $booking->contact_email }}</span>
            </div>
            @endif
            @if($booking->contact_phone)
            <div class="detail-row">
                <span class="detail-label">Phone:</span>
                <span class="detail-value">{{ $booking->contact_phone }}</span>
            </div>
            @endif
        </div>
        @endif

        <div class="footer">
            <p>Thank you for choosing our travel services!</p>
            <p>For any inquiries, please contact our support team.</p>
            <p>Generated on {{ now()->format('d M Y, H:i') }}</p>
        </div>
    </div>

    <script>
        // Auto-print when page loads
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 1000);
        }
    </script>
</body>
</html>
