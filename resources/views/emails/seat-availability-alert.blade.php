<!DOCTYPE html>
<html lang="en">
<head><meta charset="utf-8"><title>Low Seat Availability</title></head>
<body style="margin:0;padding:24px;background:#f5f6f8;font-family:-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif;color:#1f2937;">
<div style="max-width:600px;margin:0 auto;background:#ffffff;border-radius:12px;overflow:hidden;border:1px solid #e5e7eb;">

    <div style="background:#c2410c;padding:24px;">
        <h1 style="margin:0;color:#ffffff;font-size:20px;font-weight:700;">Seats Running Low</h1>
        <p style="margin:6px 0 0;color:#fed7aa;font-size:14px;">Ziara Travels</p>
    </div>

    <div style="padding:24px;">
        <p style="margin:0 0 16px;font-size:15px;line-height:1.6;">
            Seats on flight <strong>{{ $flightNumber ?? '—' }}</strong> for booking
            <strong>{{ $bookingReference ?? '—' }}</strong> are almost gone. If you have not travelled yet,
            please make sure your ticket is issued.
        </p>

        <table style="width:100%;border-collapse:collapse;margin:20px 0;font-size:14px;">
            <tr>
                <td style="padding:10px;border-bottom:1px solid #f3f4f6;color:#6b7280;">Flight</td>
                <td style="padding:10px;border-bottom:1px solid #f3f4f6;text-align:right;">{{ $flightNumber ?? '—' }}</td>
            </tr>
            <tr>
                <td style="padding:10px;border-bottom:1px solid #f3f4f6;color:#6b7280;">Departure</td>
                <td style="padding:10px;border-bottom:1px solid #f3f4f6;text-align:right;">
                    {{ $booking->departure_time?->format('d M Y H:i') ?? '—' }}
                </td>
            </tr>
            <tr>
                <td style="padding:10px;border-bottom:1px solid #f3f4f6;color:#6b7280;">Route</td>
                <td style="padding:10px;border-bottom:1px solid #f3f4f6;text-align:right;">
                    {{ $booking->departure_airport ?? '—' }} &rarr; {{ $booking->arrival_airport ?? '—' }}
                </td>
            </tr>
            <tr>
                <td style="padding:10px;color:#6b7280;">Seats remaining</td>
                <td style="padding:10px;text-align:right;font-weight:700;color:#c2410c;">
                    {{ $availableSeats ?? '—' }} of {{ $totalSeats ?? '—' }}
                </td>
            </tr>
        </table>

        <a href="{{ $bookingUrl ?? url('/') }}"
           style="display:inline-block;background:#1d4ed8;color:#ffffff;text-decoration:none;font-weight:600;font-size:14px;padding:12px 22px;border-radius:8px;">
            View your booking
        </a>
    </div>

    <div style="padding:16px 24px;background:#f9fafb;border-top:1px solid #e5e7eb;color:#6b7280;font-size:12px;">
        Ziara Travels &middot; This is an automated message.
    </div>

</div>
</body>
</html>