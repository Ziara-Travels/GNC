{{-- Flight Filters - works with data-* attributes on .flight-card --}}
@php
    // Extract airlines from Amadeus data
    $airlineList = collect($flights)->map(function($f) {
        return $f['itineraries'][0]['segments'][0]['carrierCode'] ?? '';
    })->filter()->unique()->sort()->values();

    // Extract prices
    $priceList = collect($flights)->map(function($f) {
        return floatval($f['price']['grandTotal'] ?? $f['price']['total'] ?? 0);
    })->filter(fn($p) => $p > 0);
    $minPrice = $priceList->count() > 0 ? floor($priceList->min()) : 0;
    $maxPrice = $priceList->count() > 0 ? ceil($priceList->max()) : 1000;
@endphp

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden sticky top-24">
    <div class="bg-gray-900 text-white px-5 py-4 flex items-center justify-between">
        <h3 class="font-bold text-sm"><i class="fas fa-sliders-h mr-2"></i>Filters</h3>
        <button type="button" id="reset-filters" class="text-xs text-blue-300 hover:text-white transition-colors">Reset</button>
    </div>

    <div class="p-5 space-y-6">
        {{-- Airlines --}}
        <div>
            <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Airlines</h4>
            @foreach($airlineList as $code)
            <label class="flex items-center gap-2 mb-2 cursor-pointer group">
                <input type="checkbox" class="airline-filter rounded text-blue-600 focus:ring-blue-500" value="{{ $code }}" checked>
                <span class="text-sm text-gray-700 group-hover:text-gray-900">{{ $code }}</span>
            </label>
            @endforeach
        </div>

        {{-- Price Range --}}
        <div>
            <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Max Price</h4>
            <input type="range" id="price-range" class="w-full accent-blue-600" min="{{ $minPrice }}" max="{{ $maxPrice }}" value="{{ $maxPrice }}" step="10">
            <div class="flex justify-between text-xs text-gray-500 mt-1">
                <span>${{ number_format($minPrice, 0) }}</span>
                <span class="font-semibold text-blue-600">Up to $<span id="price-value">{{ number_format($maxPrice, 0) }}</span></span>
                <span>${{ number_format($maxPrice, 0) }}</span>
            </div>
        </div>

        {{-- Stops --}}
        <div>
            <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Stops</h4>
            <label class="flex items-center gap-2 mb-2 cursor-pointer">
                <input type="checkbox" class="stops-filter rounded text-blue-600 focus:ring-blue-500" value="0" checked>
                <span class="text-sm text-gray-700">Direct</span>
            </label>
            <label class="flex items-center gap-2 mb-2 cursor-pointer">
                <input type="checkbox" class="stops-filter rounded text-blue-600 focus:ring-blue-500" value="1" checked>
                <span class="text-sm text-gray-700">1 Stop</span>
            </label>
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" class="stops-filter rounded text-blue-600 focus:ring-blue-500" value="2+" checked>
                <span class="text-sm text-gray-700">2+ Stops</span>
            </label>
        </div>

        {{-- Departure Time --}}
        <div>
            <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Departure Time</h4>
            @php
            $timeSlots = [
                ['id' => 'morning', 'label' => 'Morning', 'range' => '5AM – 12PM', 'icon' => 'fa-sun'],
                ['id' => 'afternoon', 'label' => 'Afternoon', 'range' => '12PM – 5PM', 'icon' => 'fa-cloud-sun'],
                ['id' => 'evening', 'label' => 'Evening', 'range' => '5PM – 9PM', 'icon' => 'fa-moon'],
                ['id' => 'night', 'label' => 'Night', 'range' => '9PM – 5AM', 'icon' => 'fa-star'],
            ];
            @endphp
            @foreach($timeSlots as $slot)
            <label class="flex items-center gap-2 mb-2 cursor-pointer">
                <input type="checkbox" class="time-filter rounded text-blue-600 focus:ring-blue-500" value="{{ $slot['id'] }}" checked>
                <i class="fas {{ $slot['icon'] }} text-gray-400 text-xs w-4 text-center"></i>
                <span class="text-sm text-gray-700">{{ $slot['label'] }} <span class="text-gray-400 text-xs">{{ $slot['range'] }}</span></span>
            </label>
            @endforeach
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var priceRange = document.getElementById('price-range');
    var priceValue = document.getElementById('price-value');

    if (priceRange) {
        priceRange.addEventListener('input', function() {
            priceValue.textContent = Number(this.value).toLocaleString();
            applyFilters();
        });
    }

    document.querySelectorAll('.airline-filter, .stops-filter, .time-filter').forEach(function(cb) {
        cb.addEventListener('change', applyFilters);
    });

    document.getElementById('reset-filters').addEventListener('click', function() {
        document.querySelectorAll('.airline-filter, .stops-filter, .time-filter').forEach(function(cb) { cb.checked = true; });
        if (priceRange) { priceRange.value = priceRange.max; priceValue.textContent = Number(priceRange.max).toLocaleString(); }
        applyFilters();
    });

    function applyFilters() {
        var airlines = Array.from(document.querySelectorAll('.airline-filter:checked')).map(function(i) { return i.value; });
        var stops = Array.from(document.querySelectorAll('.stops-filter:checked')).map(function(i) { return i.value; });
        var times = Array.from(document.querySelectorAll('.time-filter:checked')).map(function(i) { return i.value; });
        var maxP = priceRange ? parseInt(priceRange.value) : Infinity;
        var count = 0;

        document.querySelectorAll('.flight-card').forEach(function(card) {
            var a = card.getAttribute('data-airline') || '';
            var p = parseFloat(card.getAttribute('data-price') || 0);
            var s = parseInt(card.getAttribute('data-stops') || 0);
            var t = card.getAttribute('data-departure-time') || '';
            var sVal = s >= 2 ? '2+' : String(s);

            var ok = (airlines.length === 0 || airlines.indexOf(a) !== -1)
                  && p <= maxP
                  && (stops.length === 0 || stops.indexOf(sVal) !== -1)
                  && (times.length === 0 || times.indexOf(t) !== -1);

            card.style.display = ok ? '' : 'none';
            if (ok) count++;
        });

        var counter = document.getElementById('results-count');
        if (counter) counter.textContent = count;
    }
});
</script>
