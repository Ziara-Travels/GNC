<!DOCTYPE html>
<html lang="en">
<head><meta charset="utf-8"><title>Booking Cancelled</title></head>
<body style="margin:0;padding:24px;background:#f5f6f8;font-family:-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif;color:#1f2937;">
<div style="max-width:600px;margin:0 auto;background:#ffffff;border-radius:12px;overflow:hidden;border:1px solid #e5e7eb;">

    <div style="background:#b91c1c;padding:24px;">
        <h1 style="margin:0;color:#ffffff;font-size:20px;font-weight:700;">Booking Cancelled</h1>
        <p style="margin:6px 0 0;color:#fecaca;font-size:14px;">Ziara Travels</p>
    </div>

    <div style="padding:24px;">
        <p style="margin:0 0 16px;font-size:15px;line-height:1.6;">
            Your booking <strong>{{ $bookingReference ?? '—' }}</strong> was cancelled because payment was
            not completed within the allowed window.
        </p>

        <table style="width:100%;border-collapse:collapse;margin:20px 0;font-size:14px;">
            <tr>
                <td style="padding:10px;border-bottom:1px solid #f3f4f6;color:#6b7280;">Booking reference</td>
                <td style="padding:10px;border-bottom:1px solid #f3f4f6;text-align:right;font-family:monospace;">
                    {{ $bookingReference ?? '—' }}
                </td>
            </tr>
            <tr>
                <td style="padding:10px;border-bottom:1px solid #f3f4f6;color:#6b7280;">Route</td>
                <td style="padding:10px;border-bottom:1px solid #f3f4f6;text-align:right;">
                    {{ $booking->departure_airport ?? '—' }} &rarr; {{ $booking->arrival_airport ?? '—' }}
                </td>
            </tr>
            <tr>
                <td style="padding:10px;border-bottom:1px solid #f3f4f6;color:#6b7280;">Departure</td>
                <td style="padding:10px;border-bottom:1px solid #f3f4f6;text-align:right;">
                    {{ $booking->departure_time?->format('d M Y H:i') ?? '—' }}
                </td>
            </tr>
            <tr>
                <td style="padding:10px;color:#6b7280;">Amount</td>
                <td style="padding:10px;text-align:right;font-weight:700;">
                    {{ $amount ?? '0.00' }} {{ $currency ?? '' }}
                </td>
            </tr>
        </table>

        <p style="margin:0 0 20px;font-size:15px;line-height:1.6;">
            Nothing has been charged. You are welcome to start a new search and book again.
        </p>

        <a href="{{ $supportUrl ?? url('/') }}"
           style="display:inline-block;background:#1d4ed8;color:#ffffff;text-decoration:none;font-weight:600;font-size:14px;padding:12px 22px;border-radius:8px;">
            Contact support
        </a>
    </div>

    <div style="padding:16px 24px;background:#f9fafb;border-top:1px solid #e5e7eb;color:#6b7280;font-size:12px;">
        Ziara Travels &middot; This is an automated message.
    </div>

</div>
</body>
</html>