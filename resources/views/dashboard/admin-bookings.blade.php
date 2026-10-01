@extends('layouts.dashboard')

@section('title', 'Flight Bookings Made')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">

        <div class="mb-6">
            <h1 class="text-2xl font-bold mb-1">Flight Bookings</h1>
            <p class="text-gray-600">Bookings you created on behalf of other users.</p>
        </div>

        @if ($bookings->isEmpty())
            <div class="bg-white rounded-lg shadow p-10 text-center">
                <div class="text-4xl mb-3">&#9992;&#65039;</div>
                <h2 class="text-lg font-semibold mb-1">No bookings made yet</h2>
                <p class="text-gray-600">
                    Flight bookings you create for customers will appear here.
                </p>
            </div>
        @else
            <div class="bg-white rounded-lg shadow overflow-hidden">

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-left text-xs uppercase tracking-wide text-gray-500">
                            <tr>
                                <th class="px-4 py-3 font-medium">Reference</th>
                                <th class="px-4 py-3 font-medium">Booked for</th>
                                <th class="px-4 py-3 font-medium">Route</th>
                                <th class="px-4 py-3 font-medium">Departure</th>
                                <th class="px-4 py-3 font-medium">Amount</th>
                                <th class="px-4 py-3 font-medium">Booking</th>
                                <th class="px-4 py-3 font-medium">Payment</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($bookings as $booking)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 font-mono text-xs">{{ $booking->booking_reference }}</td>

                                    <td class="px-4 py-3">
                                        <div class="font-medium">{{ $booking->user?->name ?? 'Unknown' }}</div>
                                        <div class="text-xs text-gray-500">{{ $booking->contact_email ?? $booking->user?->email }}</div>
                                    </td>

                                    <td class="px-4 py-3">
                                        {{ $booking->departure_airport ?? '—' }} &rarr; {{ $booking->arrival_airport ?? '—' }}
                                    </td>

                                    <td class="px-4 py-3">
                                        {{ $booking->departure_time?->format('d M Y H:i') ?? '—' }}
                                    </td>

                                    <td class="px-4 py-3 font-medium">
                                        {{ number_format((float) $booking->total_amount, 2) }} {{ $booking->currency }}
                                    </td>

                                    <td class="px-4 py-3">
                                        @php
                                            $bs = $booking->booking_status ?? 'pending';
                                            $bsClass = match ($bs) {
                                                'confirmed' => 'bg-green-100 text-green-800',
                                                'cancelled' => 'bg-red-100 text-red-800',
                                                default     => 'bg-amber-100 text-amber-800',
                                            };
                                        @endphp
                                        <span class="text-xs px-2 py-0.5 rounded {{ $bsClass }}">{{ ucfirst($bs) }}</span>
                                    </td>

                                    <td class="px-4 py-3">
                                        @php
                                            $ps = $booking->payment_status ?? 'pending';
                                            $psClass = match ($ps) {
                                                'completed', 'paid' => 'bg-green-100 text-green-800',
                                                'failed'            => 'bg-red-100 text-red-800',
                                                default             => 'bg-amber-100 text-amber-800',
                                            };
                                        @endphp
                                        <span class="text-xs px-2 py-0.5 rounded {{ $psClass }}">{{ ucfirst($ps) }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        @endif

    </div>
</div>
@endsection