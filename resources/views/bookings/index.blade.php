@extends('layouts.dashboard')

@section('title', 'My Bookings')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-5xl mx-auto">

        <div class="mb-6">
            <h1 class="text-2xl font-bold mb-1">My Bookings</h1>
            <p class="text-gray-600">All of your flight, hotel and tour reservations.</p>
        </div>

        {{-- Type filter --}}
        <div class="mb-6 flex flex-wrap gap-2">
            @php
                $filters = [
                    'all'    => 'All',
                    'flight' => 'Flights',
                    'hotel'  => 'Hotels',
                    'tour'   => 'Tours',
                ];
            @endphp

            @foreach ($filters as $key => $label)
                <a href="{{ route('bookings.index', ['type' => $key]) }}"
                   class="px-4 py-2 rounded-lg text-sm font-medium border transition
                          {{ $type === $key
                                ? 'bg-blue-600 text-white border-blue-600'
                                : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        @if ($bookings->isEmpty())
            <div class="bg-white rounded-lg shadow p-10 text-center">
                <div class="text-4xl mb-3">&#9992;&#65039;</div>
                <h2 class="text-lg font-semibold mb-1">No bookings yet</h2>
                <p class="text-gray-600 mb-6">
                    When you book a flight, hotel or tour it will show up here.
                </p>
                <a href="{{ route('flights.public.search') }}"
                   class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-3 rounded-lg">
                    Search Flights
                </a>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($bookings as $booking)
                    <div class="bg-white rounded-lg shadow p-5 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0
                                        {{ $booking->type === 'hotel' ? 'bg-green-100 text-green-600' : ($booking->type === 'tour' ? 'bg-purple-100 text-purple-600' : 'bg-blue-100 text-blue-600') }}">
                                @if ($booking->type === 'hotel')
                                    <i class="material-symbols-outlined">hotel</i>
                                @elseif ($booking->type === 'tour')
                                    <i class="material-symbols-outlined">landscape</i>
                                @else
                                    <i class="material-symbols-outlined">flight</i>
                                @endif
                            </div>

                            <div>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="font-semibold capitalize">{{ $booking->type ?? 'booking' }}</span>
                                    @if (! empty($booking->booking_reference))
                                        <span class="text-xs font-mono text-gray-500 bg-gray-100 px-2 py-0.5 rounded">
                                            {{ $booking->booking_reference }}
                                        </span>
                                    @endif
                                    <span class="text-xs px-2 py-0.5 rounded
                                                 {{ ($booking->booking_status ?? '') === 'confirmed'
                                                        ? 'bg-green-100 text-green-800'
                                                        : (($booking->booking_status ?? '') === 'cancelled'
                                                            ? 'bg-red-100 text-red-800'
                                                            : 'bg-amber-100 text-amber-800') }}">
                                        {{ ucfirst($booking->booking_status ?? 'pending') }}
                                    </span>
                                </div>

                                <p class="text-sm text-gray-600 mt-1">
                                    Booked on {{ $booking->created_at?->format('d M Y') }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            @if (isset($booking->total_amount))
                                <div class="text-right">
                                    <div class="text-xs text-gray-500 uppercase tracking-wide">Total</div>
                                    <div class="font-semibold">
                                        {{ number_format((float) $booking->total_amount, 2) }}
                                        {{ $booking->currency ?? '' }}
                                    </div>
                                </div>
                            @endif

                            <a href="{{ route('bookings.show', $booking->id) }}"
                               class="inline-flex items-center px-4 py-2 rounded-lg border border-gray-300 text-sm font-medium hover:bg-gray-50">
                                View
                            </a>
                        </div>

                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $bookings->links() }}
            </div>
        @endif

    </div>
</div>
@endsection