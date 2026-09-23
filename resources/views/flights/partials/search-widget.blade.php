{{--
    Shared flight search widget, used by both resources/views/home.blade.php (hero)
    and resources/views/flights/search.blade.php (dedicated search page). Replaces
    the two near-duplicate forms that used to live inline in each page.

    Usage: @include('flights.partials.search-widget', ['idPrefix' => 'hero'])
    $idPrefix must be unique per widget instance on a page (only one instance is
    ever included per page today, so this is just a safety convention).
--}}
@php
    $idPrefix = $idPrefix ?? 'sf';
@endphp

<div class="bg-white rounded-xl shadow-xl overflow-hidden max-w-4xl mx-auto ff-search-widget" data-prefix="{{ $idPrefix }}">
    {{-- Trip type tabs --}}
    <div class="flex border-b border-gray-100">
        <button type="button" class="ff-trip-tab active flex-1 py-2.5 text-center text-xs font-semibold transition-all" data-type="round_trip">
            <i class="fas fa-exchange-alt text-[10px] mr-1"></i> Round Trip
        </button>
        <button type="button" class="ff-trip-tab flex-1 py-2.5 text-center text-xs font-semibold transition-all" data-type="one_way">
            <i class="fas fa-long-arrow-alt-right text-[10px] mr-1"></i> One Way
        </button>
        <button type="button" class="ff-trip-tab flex-1 py-2.5 text-center text-xs font-semibold transition-all" data-type="multi_city">
            <i class="fas fa-route text-[10px] mr-1"></i> Multi-city
        </button>
    </div>

    <form id="{{ $idPrefix }}FlightForm" class="p-4 ff-search-form" data-prefix="{{ $idPrefix }}">
        @csrf
        <input type="hidden" name="trip_type" value="round_trip" id="{{ $idPrefix }}_trip_type">

        {{-- Round-trip / one-way row --}}
        <div id="{{ $idPrefix }}_standardRow">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-3">
                <div class="relative">
                    <label class="text-[10px] font-semibold text-gray-400 uppercase mb-0.5 block">From</label>
                    <div class="relative">
                        <i class="fas fa-plane-departure absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-300 text-[10px]"></i>
                        <input type="text" name="from" id="{{ $idPrefix }}_from" class="w-full pl-7 pr-2 py-2 border border-gray-200 rounded-lg text-xs text-gray-800 focus:ring-1 focus:ring-blue-500 airport-autocomplete" placeholder="City or Airport" autocomplete="off" required>
                        <input type="hidden" name="from_iata" id="{{ $idPrefix }}_from_iata">
                    </div>
                    <div class="airport-suggestions absolute z-50 w-full bg-white border border-gray-200 rounded-lg shadow-xl mt-1 hidden max-h-48 overflow-y-auto"></div>
                </div>
                <div class="relative">
                    <label class="text-[10px] font-semibold text-gray-400 uppercase mb-0.5 block">To</label>
                    <div class="relative">
                        <i class="fas fa-plane-arrival absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-300 text-[10px]"></i>
                        <input type="text" name="to" id="{{ $idPrefix }}_to" class="w-full pl-7 pr-2 py-2 border border-gray-200 rounded-lg text-xs text-gray-800 focus:ring-1 focus:ring-blue-500 airport-autocomplete" placeholder="City or Airport" autocomplete="off" required>
                        <input type="hidden" name="to_iata" id="{{ $idPrefix }}_to_iata">
                    </div>
                    <div class="airport-suggestions absolute z-50 w-full bg-white border border-gray-200 rounded-lg shadow-xl mt-1 hidden max-h-48 overflow-y-auto"></div>
                </div>
                <div class="relative">
                    <label class="text-[10px] font-semibold text-gray-400 uppercase mb-0.5 block">Departure</label>
                    @include('flights.partials.date-field', ['idPrefix' => $idPrefix, 'field' => 'depart', 'name' => 'date'])
                </div>
                <div class="relative" id="{{ $idPrefix }}_returnWrap">
                    <label class="text-[10px] font-semibold text-gray-400 uppercase mb-0.5 block">Return</label>
                    @include('flights.partials.date-field', ['idPrefix' => $idPrefix, 'field' => 'return', 'name' => 'return_date'])
                </div>
            </div>
        </div>

        {{-- Multi-city rows (JS-managed, starts hidden) --}}
        <div id="{{ $idPrefix }}_multiCityRows" class="hidden space-y-3 mb-3"></div>
        <button type="button" id="{{ $idPrefix }}_addLeg" class="hidden w-full mb-3 py-2 text-xs font-semibold text-blue-600 border border-dashed border-blue-300 rounded-lg hover:bg-blue-50 transition-colors">
            <i class="fas fa-plus text-[10px] mr-1"></i> Add another flight
        </button>

        {{-- Passengers / cabin (shared across all trip types) --}}
        <div class="grid grid-cols-4 gap-3 mb-3">
            <div>
                <label class="text-[10px] font-semibold text-gray-400 uppercase mb-0.5 block">Adults</label>
                <select name="adults" class="w-full py-2 px-2 border border-gray-200 rounded-lg text-xs text-gray-800">@for($i=1;$i<=9;$i++)<option value="{{$i}}">{{$i}}</option>@endfor</select>
            </div>
            <div>
                <label class="text-[10px] font-semibold text-gray-400 uppercase mb-0.5 block">Children</label>
                <select name="children" class="w-full py-2 px-2 border border-gray-200 rounded-lg text-xs text-gray-800">@for($i=0;$i<=8;$i++)<option value="{{$i}}">{{$i}}</option>@endfor</select>
            </div>
            <div>
                <label class="text-[10px] font-semibold text-gray-400 uppercase mb-0.5 block">Infants</label>
                <select name="infants" class="w-full py-2 px-2 border border-gray-200 rounded-lg text-xs text-gray-800">@for($i=0;$i<=4;$i++)<option value="{{$i}}">{{$i}}</option>@endfor</select>
            </div>
            <div>
                <label class="text-[10px] font-semibold text-gray-400 uppercase mb-0.5 block">Cabin</label>
                <select name="travel_class" class="w-full py-2 px-2 border border-gray-200 rounded-lg text-xs text-gray-800">
                    <option value="ECONOMY">Economy</option><option value="PREMIUM_ECONOMY">Premium</option><option value="BUSINESS">Business</option><option value="FIRST">First</option>
                </select>
            </div>
        </div>

        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold py-2.5 rounded-lg transition-colors flex items-center justify-center gap-2">
            <i class="fas fa-search text-[10px]"></i> Search Flights
        </button>
    </form>
</div>

{{-- Full-screen search overlay, shown while the AJAX search request is in flight --}}
<div id="{{ $idPrefix }}SearchOverlay" class="fixed inset-0 z-[9999] bg-slate-900/80 backdrop-blur-sm hidden items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-sm w-full mx-4 text-center">
        <div class="relative w-16 h-16 mx-auto mb-5">
            <div class="absolute inset-0 rounded-full border-4 border-gray-200"></div>
            <div class="absolute inset-0 rounded-full border-4 border-t-blue-600 animate-spin"></div>
            <i class="fas fa-plane absolute inset-0 flex items-center justify-center text-blue-600 text-lg"></i>
        </div>
        <h3 class="text-base font-semibold text-gray-900 mb-1">Searching flights&hellip;</h3>
        <p class="text-xs text-gray-500 mb-5" id="{{ $idPrefix }}SearchStatusText">Comparing prices across 500+ airlines</p>
        <button type="button" onclick="window.ffCancelSearch && window.ffCancelSearch('{{ $idPrefix }}')" class="text-xs text-red-500 hover:text-red-700 font-medium underline underline-offset-2">Cancel search</button>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="{{ asset('css/flight-flow.css') }}">
<style>
.ff-trip-tab{color:#9ca3af;border-bottom:2px solid transparent;}
.ff-trip-tab.active{color:#2563eb;border-bottom-color:#2563eb;background:#eff6ff;}
.ff-trip-tab:hover:not(.active){background:#f9fafb;}
.ff-date-trigger{width:100%;text-align:left;background:#fff;}
.ff-date-trigger .ff-date-display{display:block;}
.ff-calendar-popover{position:absolute;z-index:60;background:#fff;border:1px solid #e2e8f0;border-radius:12px;box-shadow:0 10px 30px rgba(15,23,42,.15);padding:12px;width:280px;}
.ff-cal-day{width:32px;height:32px;display:flex;align-items:center;justify-content:center;font-size:11px;border-radius:8px;cursor:pointer;}
.ff-cal-day:hover:not(.ff-cal-disabled){background:#eff6ff;}
.ff-cal-day.ff-cal-selected{background:#2563eb;color:#fff;font-weight:700;}
.ff-cal-day.ff-cal-disabled{color:#d1d5db;cursor:not-allowed;}
.ff-cal-day.ff-cal-today:not(.ff-cal-selected){border:1px solid #2563eb;}
</style>
@endpush

@push('scripts')
<script src="{{ asset('js/flight-search-widget.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (window.initFlightSearchWidget) {
            window.initFlightSearchWidget('{{ $idPrefix }}');
        }
    });
</script>
@endpush
