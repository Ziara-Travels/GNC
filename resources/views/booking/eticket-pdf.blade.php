<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>E-Ticket - {{ $booking->booking_reference }}</title>
<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1a1a1a; margin: 0; padding: 0; }
    .page { padding: 30px; }
    .header { display: table; width: 100%; border-bottom: 2px solid #2563eb; padding-bottom: 12px; margin-bottom: 20px; }
    .header-left { display: table-cell; vertical-align: middle; }
    .header-right { display: table-cell; text-align: right; vertical-align: middle; }
    .brand { font-size: 22px; font-weight: bold; color: #2563eb; }
    .brand-sub { font-size: 9px; color: #6b7280; }
    .ref-box { background: #eff6ff; border: 1px solid #bfdbfe; padding: 8px 14px; display: inline-block; border-radius: 4px; }
    .ref-label { font-size: 9px; color: #6b7280; text-transform: uppercase; }
    .ref-value { font-size: 16px; font-weight: bold; color: #1e40af; letter-spacing: 1px; }
    .eticket-label { font-size: 14px; font-weight: bold; color: #1e40af; margin-bottom: 4px; }
    .status-confirmed { display: inline-block; background: #dcfce7; color: #166534; padding: 3px 10px; border-radius: 3px; font-size: 10px; font-weight: bold; text-transform: uppercase; }
    .section { margin-bottom: 18px; }
    .section-title { font-size: 12px; font-weight: bold; color: #374151; border-bottom: 1px solid #e5e7eb; padding-bottom: 4px; margin-bottom: 8px; }
    .flight-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 14px; margin-bottom: 10px; }
    .route-row { display: table; width: 100%; }
    .route-city { display: table-cell; width: 30%; }
    .route-middle { display: table-cell; width: 40%; text-align: center; vertical-align: middle; }
    .city-code { font-size: 24px; font-weight: bold; color: #1e293b; }
    .city-name { font-size: 9px; color: #6b7280; }
    .time { font-size: 14px; font-weight: bold; color: #1e293b; }
    .date { font-size: 9px; color: #6b7280; }
    .flight-line { border-top: 1px dashed #94a3b8; margin: 4px 20px; position: relative; }
    .flight-info { font-size: 9px; color: #64748b; }
    .info-grid { display: table; width: 100%; margin-top: 8px; }
    .info-cell { display: table-cell; width: 25%; }
    .info-label { font-size: 8px; color: #9ca3af; text-transform: uppercase; }
    .info-value { font-size: 10px; font-weight: bold; color: #374151; }
    .pax-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
    .pax-table th { background: #f1f5f9; text-align: left; padding: 5px 8px; font-size: 9px; color: #64748b; text-transform: uppercase; border-bottom: 1px solid #e2e8f0; }
    .pax-table td { padding: 5px 8px; font-size: 10px; border-bottom: 1px solid #f1f5f9; }
    .price-table { width: 100%; border-collapse: collapse; }
    .price-table td { padding: 4px 8px; font-size: 10px; }
    .price-table .total td { font-weight: bold; font-size: 13px; border-top: 2px solid #1e40af; color: #1e40af; }
    .footer { border-top: 1px solid #e5e7eb; padding-top: 12px; margin-top: 20px; text-align: center; font-size: 8px; color: #9ca3af; }
    .notice { background: #fef3c7; border: 1px solid #fde68a; border-radius: 4px; padding: 8px 12px; font-size: 9px; color: #92400e; margin-top: 12px; }
    .barcode { text-align: center; margin: 12px 0; font-family: monospace; font-size: 20px; letter-spacing: 4px; color: #1e293b; }
</style>
</head>
<body>
<div class="page">
    {{-- HEADER --}}
    <div class="header">
        <div class="header-left">
            <div class="brand">Ziara Travels</div>
            <div class="brand-sub">Your Gateway to East Africa &bull; www.ziaratravels.com</div>
        </div>
        <div class="header-right">
            <div class="ref-box">
                <div class="ref-label">Booking Reference</div>
                <div class="ref-value">{{ $booking->booking_reference }}</div>
            </div>
        </div>
    </div>

    <div style="margin-bottom: 14px;">
        <span class="eticket-label">Electronic Ticket / Itinerary Receipt</span><br>
        <span class="status-confirmed">&#10003; Confirmed</span>
        <span style="margin-left: 10px; font-size: 9px; color: #6b7280;">Issued: {{ $booking->created_at->format('d M Y, H:i') }}</span>
    </div>

    {{-- PASSENGER --}}
    <div class="section">
        <div class="section-title">Passenger Information</div>
        <table class="pax-table">
            <tr>
                <th>Passenger</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Type</th>
            </tr>
            <tr>
                <td style="font-weight:bold;">{{ $booking->user->name ?? ($booking->user->first_name . ' ' . $booking->user->last_name) }}</td>
                <td>{{ $booking->contact_email }}</td>
                <td>{{ $booking->contact_phone ?? 'N/A' }}</td>
                <td>Adult</td>
            </tr>
        </table>
    </div>

    {{-- FLIGHT SEGMENTS --}}
    @php
        $flightDetails = is_string($booking->flight_details) ? json_decode($booking->flight_details, true) : $booking->flight_details;
        $itineraries = $flightDetails['itineraries'] ?? [];
        $airlineCode = $flightDetails['itineraries'][0]['segments'][0]['carrierCode'] ?? 'XX';
        $travelClass = $flightDetails['travelerPricings'][0]['fareDetailsBySegment'][0]['cabin'] ?? 'ECONOMY';
    @endphp

    <div class="section">
        <div class="section-title">Flight Itinerary</div>
        @foreach($itineraries as $idx => $itinerary)
            @foreach($itinerary['segments'] as $segment)
            <div class="flight-box">
                <div style="font-size: 9px; color: #6b7280; margin-bottom: 6px; font-weight: bold;">
                    {{ $idx === 0 ? 'OUTBOUND' : 'RETURN' }} FLIGHT
                </div>
                <div class="route-row">
                    <div class="route-city">
                        <div class="city-code">{{ $segment['departure']['iataCode'] }}</div>
                        <div class="time">{{ \Carbon\Carbon::parse($segment['departure']['at'])->format('H:i') }}</div>
                        <div class="date">{{ \Carbon\Carbon::parse($segment['departure']['at'])->format('D, d M Y') }}</div>
                    </div>
                    <div class="route-middle">
                        <div class="flight-info">{{ $segment['carrierCode'] }}{{ $segment['number'] }}</div>
                        <div class="flight-line"></div>
                        @php
                            $dur = $segment['duration'] ?? '';
                            if (preg_match('/PT(?:(\d+)H)?(?:(\d+)M)?/', $dur, $m)) {
                                $durStr = (($m[1] ?? 0) > 0 ? ($m[1] ?? 0) . 'h ' : '') . (($m[2] ?? 0) > 0 ? ($m[2] ?? 0) . 'm' : '');
                            } else { $durStr = '—'; }
                        @endphp
                        <div class="flight-info">{{ $durStr }} &bull; {{ ucfirst(strtolower($travelClass)) }}</div>
                    </div>
                    <div class="route-city" style="text-align: right;">
                        <div class="city-code">{{ $segment['arrival']['iataCode'] }}</div>
                        <div class="time">{{ \Carbon\Carbon::parse($segment['arrival']['at'])->format('H:i') }}</div>
                        <div class="date">{{ \Carbon\Carbon::parse($segment['arrival']['at'])->format('D, d M Y') }}</div>
                    </div>
                </div>
                <div class="info-grid" style="margin-top: 10px;">
                    <div class="info-cell"><div class="info-label">Flight</div><div class="info-value">{{ $segment['carrierCode'] }}{{ $segment['number'] }}</div></div>
                    <div class="info-cell"><div class="info-label">Aircraft</div><div class="info-value">{{ $segment['aircraft']['code'] ?? 'N/A' }}</div></div>
                    <div class="info-cell"><div class="info-label">Cabin</div><div class="info-value">{{ ucfirst(strtolower($travelClass)) }}</div></div>
                    <div class="info-cell"><div class="info-label">Status</div><div class="info-value" style="color:#166534;">Confirmed</div></div>
                </div>
            </div>
            @endforeach
        @endforeach
    </div>

    {{-- E-TICKET NUMBERS --}}
    @if($booking->e_ticket_data)
    @php $tickets = is_string($booking->e_ticket_data) ? json_decode($booking->e_ticket_data, true) : $booking->e_ticket_data; @endphp
    @if(!empty($tickets))
    <div class="section">
        <div class="section-title">E-Ticket Numbers</div>
        <table class="pax-table">
            <tr><th>Segment</th><th>Ticket Number</th><th>Flight</th><th>Status</th></tr>
            @foreach($tickets as $t)
            <tr>
                <td>{{ $t['departure_airport'] ?? '' }} → {{ $t['arrival_airport'] ?? '' }}</td>
                <td style="font-weight:bold; font-family: monospace;">{{ $t['ticket_number'] ?? 'N/A' }}</td>
                <td>{{ $t['flight_number'] ?? '' }}</td>
                <td style="color:#166534; font-weight:bold;">Confirmed</td>
            </tr>
            @endforeach
        </table>
    </div>
    @endif
    @endif

    {{-- BARCODE --}}
    <div class="barcode">{{ $booking->booking_reference }}</div>

    {{-- PRICING --}}
    <div class="section">
        <div class="section-title">Payment Summary</div>
        <table class="price-table">
            @php
                $price = $flightDetails['price'] ?? [];
                $base = $price['base'] ?? $booking->amount;
                $total = $price['total'] ?? $booking->amount;
                $currency = $price['currency'] ?? $booking->currency ?? 'USD';
                $currSym = $currency === 'USD' ? '$' : ($currency === 'KES' ? 'KSh ' : $currency . ' ');
            @endphp
            <tr><td>Base Fare</td><td style="text-align:right;">{{ $currSym }}{{ number_format(floatval($base), 2) }}</td></tr>
            <tr><td>Taxes &amp; Fees</td><td style="text-align:right;">{{ $currSym }}{{ number_format(floatval($total) - floatval($base), 2) }}</td></tr>
            <tr class="total"><td>Total Paid</td><td style="text-align:right;">{{ $currSym }}{{ number_format(floatval($total), 2) }}</td></tr>
        </table>
        <div style="font-size: 9px; color: #6b7280; margin-top: 4px;">Payment method: {{ ucfirst(str_replace('_', ' ', $booking->payment_method ?? 'N/A')) }} &bull; Transaction: {{ $booking->transaction_id ?? 'N/A' }}</div>
    </div>

    {{-- IMPORTANT NOTES --}}
    <div class="notice">
        <strong>Important Information:</strong><br>
        &bull; Please arrive at the airport at least 2 hours before departure for domestic flights, 3 hours for international.<br>
        &bull; Present this e-ticket along with a valid photo ID at check-in.<br>
        &bull; Baggage allowances are subject to airline policy.<br>
        &bull; For changes or cancellations, contact us at support@ziaratravels.com or call +254 700 000 000.
    </div>

    {{-- FOOTER --}}
    <div class="footer">
        Ziara Travels Ltd &bull; Nairobi, Kenya &bull; support@ziaratravels.com &bull; +254 700 000 000<br>
        This is an electronic ticket. No physical ticket is needed. &bull; Generated: {{ now()->format('d M Y H:i') }}
    </div>
</div>
</body>
</html>
