@extends('layouts.main')

@section('title', 'Find Hotels & Stays - Ziara Travel')

@section('content')
@php
    $dests = [
        ['city'=>'Mombasa','hotels'=>'320+','img'=>'https://images.unsplash.com/photo-1596005554384-d293674c91d7?w=400&h=260&fit=crop'],
        ['city'=>'Zanzibar','hotels'=>'250+','img'=>'https://images.unsplash.com/photo-1586861635167-e5223aadc9fe?w=400&h=260&fit=crop'],
        ['city'=>'Nairobi','hotels'=>'580+','img'=>'https://images.unsplash.com/photo-1611348524140-53c9a25263d6?w=400&h=260&fit=crop'],
        ['city'=>'Kampala','hotels'=>'180+','img'=>'https://images.unsplash.com/photo-1619122753925-a01d8bb38c20?w=400&h=260&fit=crop'],
        ['city'=>'Kigali','hotels'=>'140+','img'=>'https://images.unsplash.com/photo-1580060839134-75a5edca2e99?w=400&h=260&fit=crop'],
        ['city'=>'Dar es Salaam','hotels'=>'290+','img'=>'https://images.unsplash.com/photo-1524492412937-b28074a5d7da?w=400&h=260&fit=crop'],
    ];
    $features = [
        ['icon'=>'fa-tags','title'=>'Best price guarantee','text'=>'Powered by Booking.com. We compare rates so you always land the best deal.'],
        ['icon'=>'fa-lock','title'=>'Secure booking','text'=>'Reservations complete securely on Booking.com with instant confirmation.'],
        ['icon'=>'fa-headset','title'=>'24/7 support','text'=>'Our team is here around the clock before, during and after your stay.'],
    ];
@endphp

<style>
.ziara-hotel-search .ziara-archive__hero { --zt-maroon:#7b2a1b;--zt-maroon-deep:#6b1d15;--zt-gold:#d9a561;--zt-gold-bright:#f3d24a; }
.ziara-hotel-search__form .ziara-search-tab.active { color:#fff;background:#7b2a1b;border-color:#7b2a1b; }
.ziara-hotel-search__form .ziara-search-tab:hover { color:#7b2a1b;border-color:#d9a561;background:rgba(217,165,97,0.10); }
.ziara-hotel-search__form .guest-btn { background:#f6efe6;color:#7b2a1b;border:1px solid #e5d3b5; }
.ziara-hotel-search__form .guest-btn:hover { background:#f3d24a;border-color:#d9a561;color:#6b1d15; }
.ziara-hotel-search__form input:focus,
.ziara-hotel-search__form select:focus { border-color:#d9a561 !important;box-shadow:0 0 0 3px rgba(217,165,97,0.22) !important;outline:none; }
</style>

@include('partials.ziara-page-hero', [
    'kicker' => 'Hotels & Stays',
    'title'  => 'Find Your <em>Perfect Stay</em>',
    'intro'  => 'Search 2M+ hotels and compare the best rates on Booking.com.',
    'crumbs' => [
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Hotels', 'url' => route('hotels.search.form')],
    ],
])

<section class="zt-section zt-section--pale ziara-hotel-search" style="--maroon:#7b2a1b;--maroon-deep:#6b1d15;--gold:#d9a561;--gold-bright:#f3d24a;">
    <div class="container">
        <div class="ziara-panel ziara-hotel-search__form" style="max-width:900px;margin:0 auto;padding:32px;">
            <div class="flex gap-1 mb-4 border-b border-gray-100 text-xs flex-wrap">
                <a href="{{ route('flights.search') }}" class="ziara-search-tab py-2.5 px-4 rounded-t-lg inline-flex items-center gap-2"><i class="fas fa-plane"></i> Flights</a>
                <a href="{{ route('hotels.search.form') }}" class="ziara-search-tab active py-2.5 px-4 rounded-t-lg inline-flex items-center gap-2"><i class="fas fa-bed"></i> Hotels</a>
                <a href="{{ route('hotels.search.form') }}" class="ziara-search-tab py-2.5 px-4 rounded-t-lg inline-flex items-center gap-2"><i class="fas fa-plane-departure"></i> Flight + Hotel</a>
            </div>

            <form action="{{ route('hotels.search') }}" method="POST" id="hotelSearchForm" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div class="relative md:col-span-2">
                        <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider block mb-1.5">Where to?</label>
                        <div class="relative">
                            <i class="fas fa-map-marker-alt absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-300 text-sm"></i>
                            <input type="text" name="destination" id="hs_dest" value="{{ old('destination') }}"
                                class="w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl text-sm text-gray-800 focus:ring-2 focus:ring-blue-200 focus:border-blue-400 city-autocomplete"
                                placeholder="City or region" autocomplete="off" required>
                        </div>
                        <div class="city-suggestions absolute z-50 w-full bg-white border border-gray-200 rounded-xl shadow-xl mt-1 hidden max-h-56 overflow-y-auto"></div>
                    </div>

                    <div>
                        <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider block mb-1.5">Check-in</label>
                        <input type="date" name="checkin" id="hs_checkin"
                            class="w-full px-3 py-3 border border-gray-200 rounded-xl text-sm text-gray-800 focus:ring-2 focus:ring-blue-200 focus:border-blue-400" required>
                    </div>
                    <div>
                        <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider block mb-1.5">Check-out</label>
                        <input type="date" name="checkout" id="hs_checkout"
                            class="w-full px-3 py-3 border border-gray-200 rounded-xl text-sm text-gray-800 focus:ring-2 focus:ring-blue-200 focus:border-blue-400" required>
                    </div>

                    <div>
                        <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider block mb-1.5">Guests</label>
                        <div class="flex items-center gap-2 border border-gray-200 rounded-xl px-3 py-2.5">
                            <button type="button" class="guest-btn w-8 h-8 rounded-full bg-gray-100 hover:bg-blue-100 text-gray-700 font-bold" data-target="adults" data-delta="-1">−</button>
                            <span class="flex-1 text-center text-sm text-gray-800"><span id="adults-val">2</span> <span class="text-gray-400 text-xs">adults</span></span>
                            <button type="button" class="guest-btn w-8 h-8 rounded-full bg-gray-100 hover:bg-blue-100 text-gray-700 font-bold" data-target="adults" data-delta="1">+</button>
                            <input type="hidden" name="adults" id="adults" value="2">
                        </div>
                    </div>
                    <div>
                        <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider block mb-1.5">Rooms</label>
                        <div class="flex items-center gap-2 border border-gray-200 rounded-xl px-3 py-2.5">
                            <button type="button" class="guest-btn w-8 h-8 rounded-full bg-gray-100 hover:bg-blue-100 text-gray-700 font-bold" data-target="rooms" data-delta="-1">−</button>
                            <span class="flex-1 text-center text-sm text-gray-800"><span id="rooms-val">1</span> <span class="text-gray-400 text-xs">room</span></span>
                            <button type="button" class="guest-btn w-8 h-8 rounded-full bg-gray-100 hover:bg-blue-100 text-gray-700 font-bold" data-target="rooms" data-delta="1">+</button>
                            <input type="hidden" name="rooms" id="rooms" value="1">
                        </div>
                    </div>
                </div>

                <input type="hidden" name="children" value="0">

                <button type="submit" class="ziara-btn ziara-btn--gold w-full">
                    <i class="fas fa-search"></i> Search Hotels
                </button>
            </form>
        </div>
    </div>
</section>

<section class="zt-section zt-section--white ziara-hotel-search" style="--maroon:#7b2a1b;--maroon-deep:#6b1d15;--gold:#d9a561;--gold-bright:#f3d24a;">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'Popular',
            'title' => 'Where Travellers <em>Stay</em>',
            'align' => 'center',
        ])
        <div class="ziara-deals">
            @foreach($dests as $d)
                <a href="{{ route('hotels.search.form') }}{{ $d['city'] ? '?destination=' . urlencode($d['city']) : '' }}" class="ziara-deal">
                    <div class="ziara-deal__media">
                        <img src="{{ $d['img'] }}" alt="{{ $d['city'] }}" loading="lazy">
                    </div>
                    <div class="ziara-deal__body">
                        <h3 class="ziara-deal__title" style="font-size:22px;">{{ $d['city'] }}</h3>
                        <p class="ziara-deal__desc" style="margin-bottom:12px;">{{ $d['hotels'] }} hotels</p>
                        <div class="ziara-deal__price">
                            <span class="ziara-deal__btn" style="margin:0;">Explore stays <i class="fa-solid fa-arrow-right-long" aria-hidden="true"></i></span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

<section class="zt-section zt-section--pale ziara-hotel-search" style="--maroon:#7b2a1b;--maroon-deep:#6b1d15;--gold:#d9a561;--gold-bright:#f3d24a;">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'Why Ziara',
            'title' => 'Book with <em>Confidence</em>',
            'align' => 'center',
        ])
        <div class="ziara-features">
            @foreach($features as $f)
                <article class="ziara-feature">
                    <span class="ziara-feature__icon"><i class="fas {{ $f['icon'] }}" aria-hidden="true"></i></span>
                    <h3 class="ziara-feature__title">{{ $f['title'] }}</h3>
                    <p class="ziara-feature__desc">{{ $f['text'] }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var tmr = new Date(); tmr.setDate(tmr.getDate() + 1);
    var nw = new Date(); nw.setDate(nw.getDate() + 3);
    function fmt(d) { return d.toISOString().split('T')[0]; }
    var ci = document.getElementById('hs_checkin'), co = document.getElementById('hs_checkout');
    if (ci && !ci.value) { ci.value = fmt(tmr); ci.min = fmt(tmr); }
    if (co && !co.value) { co.value = fmt(nw); co.min = fmt(nw); }

    document.querySelectorAll('.guest-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var target = this.dataset.target, delta = parseInt(this.dataset.delta, 10);
            var el = document.getElementById(target), label = document.getElementById(target + '-val');
            var v = Math.min(9, Math.max(1, parseInt(el.value, 10) || 1) + delta);
            el.value = v; label.textContent = v;
            var suffix = document.getElementById(target + '-val').nextElementSibling;
            suffix.textContent = (target === 'adults') ? (v === 1 ? 'adult' : 'adults') : (v === 1 ? 'room' : 'rooms');
        });
    });

    initCityAutocomplete();
});

function initCityAutocomplete() {
    var cache = {};
    document.querySelectorAll('.city-autocomplete').forEach(function (input) {
        var parent = input.closest('.relative').parentElement;
        var sug = parent.querySelector('.city-suggestions');
        var hid = parent.querySelector('input[type="hidden"]');
        var to;
        input.addEventListener('input', function () {
            clearTimeout(to); var q = this.value.trim();
            if (q.length < 2) { sug.classList.add('hidden'); return; }
            to = setTimeout(function () {
                if (cache[q]) { renderSuggestions(cache[q], sug, input, hid); return; }
                fetch('/api/hotels/regions?q=' + encodeURIComponent(q))
                    .then(function (r) { return r.json(); })
                    .then(function (d) { cache[q] = d; renderSuggestions(d, sug, input, hid); })
                    .catch(function () { sug.classList.add('hidden'); });
            }, 200);
        });
        document.addEventListener('click', function (e) {
            if (!input.contains(e.target) && !sug.contains(e.target)) sug.classList.add('hidden');
        });
    });
}

function renderSuggestions(data, container, input, hidden) {
    if (!data || !data.length) { container.classList.add('hidden'); return; }
    container.innerHTML = ''; var seen = {};
    data.forEach(function (item) {
        var city = item.name || item.value || '', country = item.country || '';
        if (!city || seen[city]) return; seen[city] = true;
        var d = document.createElement('div');
        d.className = 'px-4 py-2.5 hover:bg-blue-50 cursor-pointer flex justify-between items-center border-b border-gray-50 text-sm';
        d.innerHTML = '<span class="font-medium text-gray-900">' + city + (country ? '<span class="text-gray-400 ml-1 text-xs">' + country + '</span>' : '') + '</span>' +
            '<span class="font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded text-[10px] uppercase">' + (item.dest_type || 'city') + '</span>';
        d.addEventListener('click', function () {
            input.value = city;
            if (hidden) hidden.value = item.dest_id || '';
            container.classList.add('hidden');
        });
        container.appendChild(d);
    });
    container.classList.remove('hidden');
}
</script>
@endpush
