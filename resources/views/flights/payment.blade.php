@extends('layouts.app')

@section('title', 'Complete Payment')

@section('content')
<div class="container mx-auto px-4 py-10 max-w-3xl">

    <div class="mb-6">
        <h1 class="text-2xl font-bold mb-1">Complete your payment</h1>
        <p class="text-gray-600">
            Your booking is reserved but not yet confirmed. Complete payment to issue your ticket.
        </p>
    </div>

    @if (session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-800 rounded-lg px-4 py-3">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">

        {{-- Summary --}}
        <div class="border-b border-gray-100 bg-gray-50 px-6 py-5">
            <dl class="grid grid-cols-2 gap-x-6 gap-y-3 sm:grid-cols-4">
                <div>
                    <dt class="text-xs uppercase tracking-wide text-gray-500">Reference</dt>
                    <dd class="font-mono font-semibold">{{ $booking->booking_reference }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-gray-500">Route</dt>
                    <dd class="font-medium">
                        {{ $booking->departure_airport ?? '—' }} &rarr; {{ $booking->arrival_airport ?? '—' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-gray-500">Passengers</dt>
                    <dd class="font-medium">{{ $booking->getTotalPassengersAttribute() }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-gray-500">Amount due</dt>
                    <dd class="font-semibold">
                        {{ number_format((float) $booking->getRemainingBalanceAttribute(), 2) }}
                        {{ $booking->currency }}
                    </dd>
                </div>
            </dl>
        </div>

        {{-- Payment form --}}
        <form method="POST" action="{{ route('flights.payment.process', $booking) }}" class="p-6 space-y-6">
            @csrf

            <div>
                <label for="payment_method" class="block text-sm font-medium mb-2">
                    Payment method
                </label>
                <select name="payment_method" id="payment_method" required
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    <option value="mpesa">M-Pesa</option>
                    <option value="card">Card</option>
                    <option value="bank_transfer">Bank transfer</option>
                </select>
            </div>

            {{-- M-Pesa fields --}}
            <div data-method="mpesa" class="method-fields space-y-4">
                <div>
                    <label for="phone" class="block text-sm font-medium mb-1">M-Pesa phone number</label>
                    <input type="tel" name="payment_details[phone]" id="phone"
                           value="{{ old('payment_details.phone', $booking->contact_phone) }}"
                           placeholder="07XXXXXXXX"
                           class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>

            {{-- Card fields --}}
            <div data-method="card" class="method-fields space-y-4 hidden">
                <div>
                    <label for="card_number" class="block text-sm font-medium mb-1">Card number</label>
                    <input type="text" name="payment_details[card_number]" id="card_number"
                           inputmode="numeric" autocomplete="cc-number"
                           class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="expiry" class="block text-sm font-medium mb-1">Expiry</label>
                        <input type="text" name="payment_details[expiry]" id="expiry"
                               placeholder="MM/YY" autocomplete="cc-exp"
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="cvv" class="block text-sm font-medium mb-1">CVV</label>
                        <input type="text" name="payment_details[cvv]" id="cvv"
                               inputmode="numeric" autocomplete="cc-csc"
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            {{-- Bank transfer fields --}}
            <div data-method="bank_transfer" class="method-fields space-y-4 hidden">
                <div>
                    <label for="account_name" class="block text-sm font-medium mb-1">Account name</label>
                    <input type="text" name="payment_details[account_name]" id="account_name"
                           value="{{ auth()->user()->name }}"
                           class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                </div>
                <p class="text-sm text-gray-600">
                    Transfer the amount due using your booking reference
                    <span class="font-mono font-semibold">{{ $booking->booking_reference }}</span>,
                    then submit to record your payment.
                </p>
            </div>

            {{-- Required by the controller; populated from the selected method. --}}
            <input type="hidden" name="payment_details[transaction_id]" value="">

            <div class="flex flex-wrap items-center gap-3 pt-2 border-t border-gray-100">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-3 rounded-lg">
                    Pay {{ number_format((float) $booking->getRemainingBalanceAttribute(), 2) }}
                    {{ $booking->currency }}
                </button>

                <a href="{{ route('bookings.show', $booking) }}"
                   class="text-sm text-gray-600 hover:text-gray-900">
                    Pay later
                </a>
            </div>
        </form>
    </div>

</div>

@push('scripts')
<script>
    (function () {
        var select = document.getElementById('payment_method');
        var groups = document.querySelectorAll('.method-fields');

        function toggle() {
            var chosen = select.value;
            groups.forEach(function (group) {
                group.classList.toggle('hidden', group.dataset.method !== chosen);
            });
        }

        select.addEventListener('change', toggle);
        toggle();
    })();
</script>
@endpush

@endsection