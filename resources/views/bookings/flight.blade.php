<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Flight Booking Details &mdash; Ziara Travels</title>
    <meta name="description" content="Review your Ziara Travels flight booking details.">
</head>
<body class="bg-gray-50">

<div class="container mx-auto px-4 py-10 max-w-3xl">

    <div class="mb-6">
        <a href="{{ route('flights.public.search') }}"
           class="inline-flex items-center text-blue-600 hover:text-blue-800">
            &larr; Search more flights
        </a>
    </div>

    @if (! $booking)
        {{-- The id did not resolve to a flight booking (for example a stale or
             non-booking id from the deals page). Show a usable next step rather
             than a ViewNotFoundException / 500. --}}
        <div class="bg-white rounded-lg shadow p-10 text-center">
            <div class="text-4xl mb-3">&#9992;&#65039;</div>
            <h1 class="text-xl font-semibold mb-2">Flight booking not found</h1>
            <p class="text-gray-600 mb-6">
                We could not find a flight booking matching this link. It may have been
                removed, or the link may be out of date.
            </p>
            <a href="{{ route('flights.public.search') }}"
               class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-3 rounded-lg">
                Start a new search
            </a>
        </div>
    @else
        <div class="bg-white rounded-lg shadow overflow-hidden">

            <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white p-6">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <h1 class="text-2xl font-bold mb-1">
                            {{ $booking->origin_city ?? $booking->origin_code }}
                            &rarr;
                            {{ $booking->destination_city ?? $booking->destination_code }}
                        </h1>
                        <p class="text-blue-100">
                            {{ $booking->airline_code ?? 'Flight' }}
                            @if ($booking->cabin_class)
                                &middot; {{ ucfirst($booking->cabin_class) }}
                            @endif
                        </p>
                    </div>

                    <div class="text-right">
                        <div class="text-xs uppercase tracking-wide text-blue-200">Reference</div>
                        <div class="font-mono font-semibold">{{ $booking->booking_reference ?? '—' }}</div>
                    </div>
                </div>
            </div>

            <div class="p-6 space-y-6">

                <div class="flex flex-wrap gap-2">
                    @php
                        $status = $booking->booking_status ?? 'pending';
                        $statusClass = match ($status) {
                            'confirmed'  => 'bg-green-100 text-green-800',
                            'completed'  => 'bg-green-100 text-green-800',
                            'cancelled'  => 'bg-red-100 text-red-800',
                            default      => 'bg-amber-100 text-amber-800',
                        };
                    @endphp

                    <span class="text-xs font-medium px-3 py-1 rounded {{ $statusClass }}">
                        Booking: {{ ucfirst($status) }}
                    </span>

                    @php
                        $payment = $booking->payment_status ?? 'pending';
                        $paymentClass = match ($payment) {
                            'paid'    => 'bg-green-100 text-green-800',
                            'failed'  => 'bg-red-100 text-red-800',
                            'refunded'=> 'bg-gray-100 text-gray-700',
                            default   => 'bg-amber-100 text-amber-800',
                        };
                    @endphp

                    <span class="text-xs font-medium px-3 py-1 rounded {{ $paymentClass }}">
                        Payment: {{ ucfirst($payment) }}
                    </span>

                    @if (! empty($booking->payment_reference))
                        <span class="text-xs font-mono px-3 py-1 rounded bg-gray-100 text-gray-700">
                            {{ $booking->payment_reference }}
                        </span>
                    @endif
                </div>

                {{-- Flight summary --}}
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4">
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-gray-500">Departure</dt>
                        <dd class="font-medium">
                            {{ $booking->departure_date?->format('d M Y') ?? '—' }}
                            @if ($booking->departure_time)
                                <span class="text-gray-600">&middot; {{ $booking->departure_time }}</span>
                            @endif
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs uppercase tracking-wide text-gray-500">Return</dt>
                        <dd class="font-medium">
                            @if ($booking->return_date)
                                {{ $booking->return_date->format('d M Y') }}
                                @if ($booking->return_time)
                                    <span class="text-gray-600">&middot; {{ $booking->return_time }}</span>
                                @endif
                            @else
                                One way
                            @endif
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs uppercase tracking-wide text-gray-500">Route</dt>
                        <dd class="font-medium">
                            {{ $booking->origin_code ?? '—' }} &rarr; {{ $booking->destination_code ?? '—' }}
                            @if ($booking->stops !== null)
                                <span class="text-gray-600">&middot; {{ $booking->stops }} stop(s)</span>
                            @endif
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs uppercase tracking-wide text-gray-500">Passengers</dt>
                        <dd class="font-medium">{{ $booking->passenger_count ?? 1 }}</dd>
                    </div>
                </dl>

                {{-- Price --}}
                <div class="border-t border-gray-100 pt-4 flex items-center justify-between">
                    <span class="text-sm uppercase tracking-wide text-gray-500">Total paid</span>
                    <span class="text-2xl font-bold">
                        {{ number_format((float) $booking->total_price, 2) }}
                        <span class="text-base font-semibold text-gray-600">{{ $booking->currency ?? 'KES' }}</span>
                    </span>
                </div>

                {{-- Pending payment CTA --}}
                @if (($booking->payment_status ?? '') !== 'paid' && ($booking->booking_status ?? '') === 'pending')
                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-5">
                        <h2 class="font-semibold text-amber-900 mb-1">Payment required</h2>
                        <p class="text-sm text-amber-800 mb-4">
                            This booking is not confirmed until payment is completed.
                        </p>
                        @auth
                            <a href="{{ route('flights.booking.payment', ['booking' => $booking->id]) }}"
                               class="inline-block bg-amber-600 hover:bg-amber-700 text-white font-medium px-6 py-3 rounded-lg">
                                Complete payment
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                               class="inline-block bg-amber-600 hover:bg-amber-700 text-white font-medium px-6 py-3 rounded-lg">
                                Log in to pay
                            </a>
                        @endauth
                    </div>
                @endif

            </div>
        </div>
    @endif

</div>

</body>
</html>