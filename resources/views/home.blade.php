@extends('layouts.main')

@section('content')

{{-- ===== HERO (ziara video slides) ===== --}}
<div class="ziara-hero-host">
    @include('partials.ziara-hero')
</div>

{{-- ===== BOOK A TRIP / SEARCH STRIP ===== --}}
<div class="ziara-strip">
    <div class="ziara-strip__inner">
        <div class="ziara-strip__tabs" id="searchTabs">
            <button type="button" class="ziara-strip__tab active" data-tab="flights" onclick="switchTab('flights')">
                <i class="fa-solid fa-plane" aria-hidden="true"></i> Flights
            </button>
            <button type="button" class="ziara-strip__tab" data-tab="hotels" onclick="switchTab('hotels')">
                <i class="fa-solid fa-bed" aria-hidden="true"></i> Hotels
            </button>
            <button type="button" class="ziara-strip__tab" data-tab="packages" onclick="switchTab('packages')">
                <i class="fa-solid fa-suitcase" aria-hidden="true"></i> Flight + Hotel
            </button>
        </div>

        {{-- FLIGHTS --}}
        <div class="ziara-strip__panel active" id="panel-flights">
            @include('flights.partials.search-widget', ['idPrefix' => 'hero'])
        </div>

        {{-- HOTELS --}}
        <div class="ziara-strip__panel" id="panel-hotels">
            <form action="{{ route('hotels.search') }}" method="POST">
                @csrf
                <div class="ziara-hform">
                    <div class="ziara-hfield">
                        <label for="hero_hotel_dest">Where to?</label>
                        <input type="text" name="destination" id="hero_hotel_dest" class="city-autocomplete" placeholder="City or region" autocomplete="off" required>
                        <div class="city-suggestions hidden"></div>
                    </div>
                    <div class="ziara-hfield">
                        <label for="hero_checkin">Check-in</label>
                        <input type="date" name="checkin" id="hero_checkin" required>
                    </div>
                    <div class="ziara-hfield">
                        <label for="hero_checkout">Check-out</label>
                        <input type="date" name="checkout" id="hero_checkout" required>
                    </div>
                    <div class="ziara-hfield">
                        <label for="hero_hotel_guests">Guests</label>
                        <select name="adults" id="hero_hotel_guests">
                            @for($i=1;$i<=9;$i++)<option value="{{$i}}" @if($i===2)selected @endif>{{$i}} adult{{$i>1?'s':''}}</option>@endfor
                        </select>
                    </div>
                    <input type="hidden" name="rooms" value="1">
                    <input type="hidden" name="children" value="0">
                    <div class="ziara-hfield">
                        <button type="submit" class="ziara-hsubmit">
                            <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i> Search Hotels
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- PACKAGES (FLIGHT + HOTEL) --}}
        <div class="ziara-strip__panel" id="panel-packages">
            @include('flights.partials.search-widget', ['idPrefix' => 'pkg', 'packageMode' => true])
        </div>
    </div>
</div>

{{-- ===== HEADING + DESTINATION BANNERS =====
     Layout mirrors ziaratravels.co.ke section 1198452 + 585f834: the heading
     occupies the left half with the first two banners beside it, and the
     remaining four banners fill a full-width row underneath. --}}
<section class="zt-section zt-section--white zt-safaris">
    <div class="container">
        <div class="zt-safaris__top">
            <div class="zt-safaris__head">
                <div class="gsc-heading align-center style-2 widget auto-responsive">
                    <div class="content-inner">
                        <div class="sub-title"><span>Safaris &amp; Packages</span></div>
                        <h2 class="title"><span>Our Safaris &amp; International Packages</span></h2>
                    </div>
                </div>
            </div>
            <div class="zt-safaris__pair">
                @include('partials.ziara-banners', ['from' => 0, 'to' => 2])
            </div>
        </div>
        <div class="zt-safaris__row">
            @include('partials.ziara-banners', ['from' => 2])
        </div>
    </div>
</section>

{{-- ===== POPULAR SAFARIS (cards) ===== --}}
<section class="zt-section zt-section--pale">
    <div class="container">
        <div class="ziara-carousel-wrap">
            @include('partials.ziara-safaris-carousel')
        </div>
    </div>
</section>

{{-- ===== WHY CHOOSE ZIARA ===== --}}
<section class="zt-section zt-section--white">
    <div class="container">
        <div class="zt-why">
            <div class="zt-why__media">
                <img src="{{ asset('images/ziara/beach.png') }}" alt="Ziara Travels beach holidays" loading="lazy">
                <span class="zt-why__orn" aria-hidden="true"></span>
            </div>
            <div class="zt-why__content">
                <div class="gsc-heading align-left style-1 widget auto-responsive">
                    <div class="content-inner">
                        <div class="sub-title"><span>Why Choose Us?</span></div>
                        <h2 class="title"><span>Why Choose Ziara?</span></h2>
                        <div class="title-desc">Based on our experience in the Tour and Travel industry for the last 15 years and our core values of integrity and excellence, we have learnt to never settle for nothing else less but perfection. We offer our client international resources, local market knowledge, creativity and commitment to ensure the success of our client's travel program.</div>
                    </div>
                </div>
                <ul class="elementor-icon-list-items">
                    <li class="elementor-icon-list-item">
                        <span class="elementor-icon-list-icon"><i class="fa-solid fa-circle-check" aria-hidden="true"></i></span>
                        <span class="elementor-icon-list-text">Exemplary competence</span>
                    </li>
                    <li class="elementor-icon-list-item">
                        <span class="elementor-icon-list-icon"><i class="fa-solid fa-circle-check" aria-hidden="true"></i></span>
                        <span class="elementor-icon-list-text">Impeccable integrity</span>
                    </li>
                    <li class="elementor-icon-list-item">
                        <span class="elementor-icon-list-icon"><i class="fa-solid fa-circle-check" aria-hidden="true"></i></span>
                        <span class="elementor-icon-list-text">To meet our client travel needs</span>
                    </li>
                    <li class="elementor-icon-list-item">
                        <span class="elementor-icon-list-icon"><i class="fa-solid fa-circle-check" aria-hidden="true"></i></span>
                        <span class="elementor-icon-list-text">To form a long-standing relationship with our client</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

{{-- ===== INTERNATIONAL PACKAGES ===== --}}
<section class="zt-section zt-section--pale">
    <div class="container">
        <div class="gsc-heading align-center style-1 widget auto-responsive">
            <div class="content-inner">
                <div class="sub-title"><span>Popular Tours</span></div>
                <h2 class="title"><span>International Packages</span></h2>
            </div>
        </div>
        <div class="ziara-carousel-wrap">
            @include('partials.ziara-international-carousel')
        </div>
    </div>
</section>

{{-- ===== JOIN US =====
     Mirrors ziaratravels.co.ke section 6ea8b05: two equal columns, centred
     heading + CTA on the left and the photo on the right at 75% width. --}}
<section class="zt-section zt-section--cream ziara-join">
    <div class="container">
        <div class="zt-join__grid">
            <div class="zt-join__content">
                <div class="gsc-heading align-center style-1 widget auto-responsive">
                    <div class="content-inner">
                        <div class="sub-title"><span>Join Us</span></div>
                        <h2 class="title"><span>Ready for an unforgettable tour?</span></h2>
                        <div class="title-desc">Plan your trip with us</div>
                    </div>
                </div>
                <div class="zt-join__cta">
                    <a class="elementor-button" href="{{ route('contactus') }}">
                        <span class="elementor-button-content-wrapper">
                            <span class="elementor-button-icon"><i class="fa-solid fa-circle-arrow-right" aria-hidden="true"></i></span>
                            <span class="elementor-button-text">Contact Us</span>
                        </span>
                    </a>
                </div>
            </div>
            <div class="zt-join__media">
                <img src="{{ asset('images/ziara/006.jpeg') }}" alt="A Ziara Tours holiday group ready to travel" loading="lazy">
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
function switchTab(tab){
    document.querySelectorAll('.ziara-strip__tab').forEach(function(t){ t.classList.remove('active'); });
    document.querySelectorAll('.ziara-strip__panel').forEach(function(p){ p.classList.remove('active'); });
    var btn = document.querySelector('.ziara-strip__tab[data-tab="' + tab + '"]');
    if (btn) btn.classList.add('active');
    var panel = document.getElementById('panel-' + tab);
    if (panel) panel.classList.add('active');
    if (window.ziaraResizeSearch) { window.ziaraResizeSearch(); }
}

function initCityAutocomplete(){
    var cache = {};
    document.querySelectorAll('.city-autocomplete').forEach(function(input){
        var field = input.closest('.ziara-hfield');
        var sug = field ? field.querySelector('.city-suggestions') : null;
        if (!sug) return;
        var to;
        input.addEventListener('input', function(){
            clearTimeout(to);
            var q = this.value.trim();
            if (q.length < 2) { sug.classList.add('hidden'); return; }
            to = setTimeout(function(){
                if (cache[q]) { renderCitySuggestions(cache[q], sug, input); return; }
                fetch('/api/hotels/regions?q=' + encodeURIComponent(q))
                    .then(function(r){ return r.json(); })
                    .then(function(d){ cache[q] = d; renderCitySuggestions(d, sug, input); })
                    .catch(function(){ sug.classList.add('hidden'); });
            }, 200);
        });
        document.addEventListener('click', function(e){
            if (!input.contains(e.target) && !sug.contains(e.target)) sug.classList.add('hidden');
        });
    });
}

function renderCitySuggestions(data, container, input){
    if (!data || !Array.isArray(data) || !data.length) { container.classList.add('hidden'); return; }
    container.innerHTML = '';
    var seen = {};
    data.forEach(function(item){
        var city = item.name || item.value || '';
        var country = item.country || '';
        if (!city || (country && seen[city])) return;
        seen[city] = true;
        var d = document.createElement('div');
        d.className = 'px-4 py-2.5 hover:bg-blue-50 cursor-pointer flex justify-between items-center border-b border-gray-50 text-sm';
        d.innerHTML = '<span class="font-medium text-gray-900">' + city +
            (country ? '<span class="text-gray-400 ml-1 text-xs">' + country + '</span>' : '') + '</span>';
        d.addEventListener('click', function(){ input.value = city; container.classList.add('hidden'); });
        container.appendChild(d);
    });
    container.classList.remove('hidden');
}

document.addEventListener('DOMContentLoaded', function(){
    var tmr = new Date(); tmr.setDate(tmr.getDate() + 1);
    var nw  = new Date(); nw.setDate(nw.getDate() + 8);
    function fmt(d){ return d.toISOString().split('T')[0]; }
    var ci = document.getElementById('hero_checkin'), co = document.getElementById('hero_checkout');
    if (ci && !ci.value) { ci.value = fmt(tmr); ci.min = fmt(tmr); }
    if (co && !co.value) { co.value = fmt(nw); co.min = fmt(nw); }
    initCityAutocomplete();
});
</script>
@endpush