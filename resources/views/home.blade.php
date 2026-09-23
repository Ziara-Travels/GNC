@extends('layouts.main')

@section('content')

{{-- ===== HERO ===== --}}
<section class="relative bg-slate-900 overflow-hidden">
    <img src="https://images.unsplash.com/photo-1436491865332-7a61a109db05?w=1400&h=600&fit=crop" alt="" class="absolute inset-0 w-full h-full object-cover opacity-30" loading="eager">
    <div class="relative max-w-6xl mx-auto px-4 pt-24 pb-14">
        <h1 class="text-2xl md:text-3xl font-bold text-white leading-snug mb-2">Find & Book Your Next Trip</h1>
        <p class="text-sm text-gray-300 mb-8 max-w-md">Search flights, hotels and packages across East Africa at the best prices.</p>

        {{-- Search Card --}}
        <div class="bg-white rounded-xl shadow-xl overflow-hidden max-w-4xl">
            <div class="flex border-b border-gray-100" id="searchTabs">
                <button class="search-tab active flex-1 py-2.5 text-center text-xs font-semibold transition-all flex items-center justify-center gap-1.5" data-tab="flights" onclick="switchTab('flights')"><i class="fas fa-plane text-[10px]"></i> Flights</button>
                <button class="search-tab flex-1 py-2.5 text-center text-xs font-semibold transition-all flex items-center justify-center gap-1.5" data-tab="hotels" onclick="switchTab('hotels')"><i class="fas fa-hotel text-[10px]"></i> Hotels</button>
                <button class="search-tab flex-1 py-2.5 text-center text-xs font-semibold transition-all flex items-center justify-center gap-1.5" data-tab="packages" onclick="switchTab('packages')"><i class="fas fa-suitcase text-[10px]"></i> Flight + Hotel</button>
            </div>

            {{-- FLIGHTS --}}
            <div class="search-panel p-4" id="panel-flights">
                @include('flights.partials.search-widget', ['idPrefix' => 'hero'])
            </div>

            {{-- HOTELS --}}
            <div class="search-panel p-4 hidden" id="panel-hotels">
                <form action="{{ route('hotels.search') }}" method="POST" id="heroHotelForm">
                    @csrf
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-3">
                        <div class="col-span-2 relative">
                            <label class="text-[10px] font-semibold text-gray-400 uppercase mb-0.5 block">Destination</label>
                            <div class="relative">
                                <i class="fas fa-map-marker-alt absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-300 text-[10px]"></i>
                                <input type="text" name="destination" id="hero_hotel_dest" class="w-full pl-7 pr-2 py-2 border border-gray-200 rounded-lg text-xs text-gray-800 focus:ring-1 focus:ring-blue-500 city-autocomplete" placeholder="City or region" autocomplete="off" required>
                                <input type="hidden" name="city_code" id="hero_hotel_city_code">
                            </div>
                            <div class="city-suggestions absolute z-50 w-full bg-white border border-gray-200 rounded-lg shadow-xl mt-1 hidden max-h-48 overflow-y-auto"></div>
                        </div>
                        <div><label class="text-[10px] font-semibold text-gray-400 uppercase mb-0.5 block">Check-in</label><input type="date" name="check_in_date" id="hero_checkin" class="w-full py-2 px-2.5 border border-gray-200 rounded-lg text-xs text-gray-800 focus:ring-1 focus:ring-blue-500" required></div>
                        <div><label class="text-[10px] font-semibold text-gray-400 uppercase mb-0.5 block">Check-out</label><input type="date" name="check_out_date" id="hero_checkout" class="w-full py-2 px-2.5 border border-gray-200 rounded-lg text-xs text-gray-800 focus:ring-1 focus:ring-blue-500" required></div>
                    </div>
                    <div class="grid grid-cols-3 gap-3 mb-3">
                        <div><label class="text-[10px] font-semibold text-gray-400 uppercase mb-0.5 block">Rooms</label><select name="rooms" class="w-full py-2 px-2 border border-gray-200 rounded-lg text-xs text-gray-800">@for($i=1;$i<=5;$i++)<option value="{{$i}}">{{$i}}</option>@endfor</select></div>
                        <div><label class="text-[10px] font-semibold text-gray-400 uppercase mb-0.5 block">Adults</label><select name="adults" class="w-full py-2 px-2 border border-gray-200 rounded-lg text-xs text-gray-800">@for($i=1;$i<=9;$i++)<option value="{{$i}}">{{$i}}</option>@endfor</select></div>
                        <div><label class="text-[10px] font-semibold text-gray-400 uppercase mb-0.5 block">Children</label><select name="children" class="w-full py-2 px-2 border border-gray-200 rounded-lg text-xs text-gray-800">@for($i=0;$i<=5;$i++)<option value="{{$i}}">{{$i}}</option>@endfor</select></div>
                    </div>
                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold py-2.5 rounded-lg transition-colors flex items-center justify-center gap-2"><i class="fas fa-search text-[10px]"></i> Search Hotels</button>
                </form>
            </div>

            {{-- PACKAGES --}}
            <div class="search-panel p-4 hidden" id="panel-packages">
                <form action="/api/search" method="POST" id="heroPackageForm">
                    @csrf
                    <input type="hidden" name="trip_type" value="round_trip"><input type="hidden" name="search_type" value="package">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-3">
                        <div class="relative"><label class="text-[10px] font-semibold text-gray-400 uppercase mb-0.5 block">From</label><div class="relative"><i class="fas fa-plane-departure absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-300 text-[10px]"></i><input type="text" name="from" class="w-full pl-7 pr-2 py-2 border border-gray-200 rounded-lg text-xs text-gray-800 airport-autocomplete" placeholder="City or Airport" autocomplete="off" required><input type="hidden" name="from_iata"></div><div class="airport-suggestions absolute z-50 w-full bg-white border border-gray-200 rounded-lg shadow-xl mt-1 hidden max-h-48 overflow-y-auto"></div></div>
                        <div class="relative"><label class="text-[10px] font-semibold text-gray-400 uppercase mb-0.5 block">To</label><div class="relative"><i class="fas fa-plane-arrival absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-300 text-[10px]"></i><input type="text" name="to" class="w-full pl-7 pr-2 py-2 border border-gray-200 rounded-lg text-xs text-gray-800 airport-autocomplete" placeholder="City or Airport" autocomplete="off" required><input type="hidden" name="to_iata"></div><div class="airport-suggestions absolute z-50 w-full bg-white border border-gray-200 rounded-lg shadow-xl mt-1 hidden max-h-48 overflow-y-auto"></div></div>
                        <div><label class="text-[10px] font-semibold text-gray-400 uppercase mb-0.5 block">Check-in</label><input type="date" name="date" class="w-full py-2 px-2.5 border border-gray-200 rounded-lg text-xs text-gray-800" required></div>
                        <div><label class="text-[10px] font-semibold text-gray-400 uppercase mb-0.5 block">Check-out</label><input type="date" name="return_date" class="w-full py-2 px-2.5 border border-gray-200 rounded-lg text-xs text-gray-800" required></div>
                    </div>
                    <div class="grid grid-cols-3 gap-3 mb-3">
                        <div><label class="text-[10px] font-semibold text-gray-400 uppercase mb-0.5 block">Adults</label><select name="adults" class="w-full py-2 px-2 border border-gray-200 rounded-lg text-xs text-gray-800">@for($i=1;$i<=9;$i++)<option value="{{$i}}">{{$i}}</option>@endfor</select></div>
                        <div><label class="text-[10px] font-semibold text-gray-400 uppercase mb-0.5 block">Children</label><select name="children" class="w-full py-2 px-2 border border-gray-200 rounded-lg text-xs text-gray-800">@for($i=0;$i<=5;$i++)<option value="{{$i}}">{{$i}}</option>@endfor</select></div>
                        <div><label class="text-[10px] font-semibold text-gray-400 uppercase mb-0.5 block">Rooms</label><select name="rooms" class="w-full py-2 px-2 border border-gray-200 rounded-lg text-xs text-gray-800">@for($i=1;$i<=5;$i++)<option value="{{$i}}">{{$i}}</option>@endfor</select></div>
                    </div>
                    <button type="submit" class="w-full bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold py-2.5 rounded-lg transition-colors flex items-center justify-center gap-2"><i class="fas fa-search text-[10px]"></i> Search Packages</button>
                </form>
            </div>
        </div>
    </div>
</section>

{{-- ===== TRUST BAR ===== --}}
<section class="bg-white border-b border-gray-100 py-4">
    <div class="max-w-6xl mx-auto px-4 flex flex-wrap justify-center gap-8 text-center">
        <div><span class="text-lg font-bold text-gray-900">500+</span><span class="text-[10px] text-gray-400 block">Airlines</span></div>
        <div><span class="text-lg font-bold text-gray-900">2M+</span><span class="text-[10px] text-gray-400 block">Hotels</span></div>
        <div><span class="text-lg font-bold text-gray-900">190+</span><span class="text-[10px] text-gray-400 block">Countries</span></div>
        <div><span class="text-lg font-bold text-gray-900">24/7</span><span class="text-[10px] text-gray-400 block">Support</span></div>
    </div>
</section>

{{-- ===== CTA: EXPLORE EAST AFRICA (image + text) ===== --}}
<section class="py-10 bg-gray-50">
    <div class="max-w-6xl mx-auto px-4">
        <div class="flex flex-col md:flex-row items-center gap-8 bg-white rounded-xl overflow-hidden shadow-sm border border-gray-100">
            <div class="md:w-1/2">
                <img src="https://images.unsplash.com/photo-1523805009345-7448845a9e53?w=700&h=450&fit=crop" alt="East Africa" class="w-full h-56 md:h-72 object-cover" loading="lazy">
            </div>
            <div class="md:w-1/2 p-6 md:p-8">
                <span class="text-[10px] font-bold text-blue-600 uppercase tracking-wider">Featured</span>
                <h2 class="text-xl font-bold text-gray-900 mt-1 mb-2">Explore East Africa</h2>
                <p class="text-xs text-gray-500 leading-relaxed mb-4">From the white beaches of Zanzibar to the mountain gorillas of Rwanda — discover the best of East Africa with flights starting at KES 7,500.</p>
                <a href="{{ route('flights.public.search') }}" class="inline-flex items-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-4 py-2 rounded-lg transition-colors">Explore Destinations <i class="fas fa-arrow-right text-[10px]"></i></a>
            </div>
        </div>
    </div>
</section>

{{-- ===== TRENDING DESTINATIONS ===== --}}
<section class="py-10 bg-white">
    <div class="max-w-6xl mx-auto px-4">
        <div class="flex items-end justify-between mb-6">
            <div>
                <span class="text-[10px] font-bold text-blue-600 uppercase tracking-wider">Trending</span>
                <h2 class="text-lg font-bold text-gray-900 mt-0.5">Popular Destinations</h2>
            </div>
            <a href="{{ route('flights.public.search') }}" class="text-xs text-blue-600 hover:text-blue-800 font-medium">View all &rarr;</a>
        </div>
        @php
        $destinations = [
            ['city'=>'Zanzibar','country'=>'Tanzania','code'=>'ZNZ','img'=>'https://images.unsplash.com/photo-1586861635167-e5223aadc9fe?w=400&h=280&fit=crop','price'=>'12,000','tag'=>'Beach'],
            ['city'=>'Mombasa','country'=>'Kenya','code'=>'MBA','img'=>'https://images.unsplash.com/photo-1596005554384-d293674c91d7?w=400&h=280&fit=crop','price'=>'7,500','tag'=>'Coastal'],
            ['city'=>'Kigali','country'=>'Rwanda','code'=>'KGL','img'=>'https://images.unsplash.com/photo-1580060839134-75a5edca2e99?w=400&h=280&fit=crop','price'=>'16,500','tag'=>'City'],
            ['city'=>'Dar es Salaam','country'=>'Tanzania','code'=>'DAR','img'=>'https://images.unsplash.com/photo-1611348524140-53c9a25263d6?w=400&h=280&fit=crop','price'=>'11,000','tag'=>'Business'],
            ['city'=>'Kampala','country'=>'Uganda','code'=>'EBB','img'=>'https://images.unsplash.com/photo-1619122753925-a01d8bb38c20?w=400&h=280&fit=crop','price'=>'14,500','tag'=>'Culture'],
            ['city'=>'Addis Ababa','country'=>'Ethiopia','code'=>'ADD','img'=>'https://images.unsplash.com/photo-1524492412937-b28074a5d7da?w=400&h=280&fit=crop','price'=>'24,000','tag'=>'Historic'],
        ];
        @endphp
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
            @foreach($destinations as $d)
            <a href="{{ route('flights.public.search') }}?to={{ $d['code'] }}" class="group block rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow bg-white border border-gray-100">
                <div class="relative h-28 overflow-hidden">
                    <img src="{{ $d['img'] }}" alt="{{ $d['city'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                    <span class="absolute top-2 left-2 text-[9px] font-bold text-white bg-black/30 backdrop-blur-sm px-1.5 py-0.5 rounded">{{ $d['tag'] }}</span>
                    <div class="absolute bottom-2 left-2"><h3 class="text-xs font-bold text-white leading-tight">{{ $d['city'] }}</h3><p class="text-[9px] text-white/70">{{ $d['country'] }}</p></div>
                </div>
                <div class="p-2 text-center">
                    <span class="text-[10px] text-gray-500">From</span>
                    <span class="text-xs font-bold text-blue-600 ml-0.5">KES {{ $d['price'] }}</span>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== FLIGHT DEALS ===== --}}
<section class="py-10 bg-gray-50">
    <div class="max-w-6xl mx-auto px-4">
        <div class="flex items-end justify-between mb-6">
            <div>
                <span class="text-[10px] font-bold text-amber-600 uppercase tracking-wider">Deals</span>
                <h2 class="text-lg font-bold text-gray-900 mt-0.5">Best Flight Deals</h2>
            </div>
            <a href="{{ route('flights.public.search') }}" class="text-xs text-blue-600 hover:text-blue-800 font-medium">All deals &rarr;</a>
        </div>
        <div id="flightDealsContainer" class="grid grid-cols-2 md:grid-cols-4 gap-3">
            @for($i=0;$i<4;$i++)
            <div class="animate-pulse bg-white rounded-lg p-3 border border-gray-100"><div class="h-3 bg-gray-100 rounded w-3/4 mb-2"></div><div class="h-5 bg-gray-100 rounded w-1/2 mb-2"></div><div class="h-2 bg-gray-100 rounded w-2/3"></div></div>
            @endfor
        </div>
    </div>
</section>

{{-- ===== FEATURED HOTELS ===== --}}
<section class="py-10 bg-white">
    <div class="max-w-6xl mx-auto px-4">
        <div class="flex items-end justify-between mb-6">
            <div>
                <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider">Stays</span>
                <h2 class="text-lg font-bold text-gray-900 mt-0.5">Featured Hotels</h2>
            </div>
            <a href="{{ route('hotels.search.form') }}" class="text-xs text-emerald-600 hover:text-emerald-800 font-medium">Browse &rarr;</a>
        </div>
        @php
        $hotels = [
            ['name'=>'Zanzibar Serena','city'=>'Zanzibar','stars'=>5,'price'=>185,'img'=>'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=400&h=260&fit=crop','rating'=>'9.2'],
            ['name'=>'Sarova Whitesands','city'=>'Mombasa','stars'=>4,'price'=>120,'img'=>'https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=400&h=260&fit=crop','rating'=>'8.7'],
            ['name'=>'Kigali Marriott','city'=>'Kigali','stars'=>5,'price'=>160,'img'=>'https://images.unsplash.com/photo-1564501049412-61c2a3083791?w=400&h=260&fit=crop','rating'=>'9.0'],
            ['name'=>'Hyatt Regency','city'=>'Addis Ababa','stars'=>5,'price'=>140,'img'=>'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=400&h=260&fit=crop','rating'=>'8.9'],
        ];
        @endphp
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            @foreach($hotels as $h)
            <div class="group bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow overflow-hidden">
                <div class="relative h-32 overflow-hidden">
                    <img src="{{ $h['img'] }}" alt="{{ $h['name'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
                    <span class="absolute top-2 right-2 bg-white/90 text-[10px] font-bold text-gray-800 px-1.5 py-0.5 rounded"><i class="fas fa-star text-amber-400 text-[8px]"></i> {{ $h['rating'] }}</span>
                </div>
                <div class="p-2.5">
                    <div class="flex gap-0.5 mb-0.5">@for($s=0;$s<$h['stars'];$s++)<i class="fas fa-star text-amber-400 text-[7px]"></i>@endfor</div>
                    <h3 class="text-xs font-semibold text-gray-900 truncate">{{ $h['name'] }}</h3>
                    <p class="text-[10px] text-gray-400 mb-2"><i class="fas fa-map-marker-alt mr-0.5"></i>{{ $h['city'] }}</p>
                    <div class="flex items-end justify-between">
                        <div><span class="text-[10px] text-gray-400">From</span><div class="text-sm font-bold text-gray-900">${{ $h['price'] }}<span class="text-[10px] font-normal text-gray-400">/night</span></div></div>
                        <a href="{{ route('hotels.search.form') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white text-[10px] font-semibold px-2.5 py-1 rounded transition-colors">Book</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== CTA: BOOK WITH CONFIDENCE ===== --}}
<section class="py-10 bg-gray-50">
    <div class="max-w-6xl mx-auto px-4">
        <div class="flex flex-col md:flex-row-reverse items-center gap-8 bg-white rounded-xl overflow-hidden shadow-sm border border-gray-100">
            <div class="md:w-1/2">
                <img src="https://images.unsplash.com/photo-1488085061387-422e29b40080?w=700&h=450&fit=crop" alt="Travel confidently" class="w-full h-56 md:h-72 object-cover" loading="lazy">
            </div>
            <div class="md:w-1/2 p-6 md:p-8">
                <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider">Peace of mind</span>
                <h2 class="text-xl font-bold text-gray-900 mt-1 mb-2">Book With Confidence</h2>
                <p class="text-xs text-gray-500 leading-relaxed mb-3">Secure payments via M-Pesa and Equity Bank, instant e-tickets, free cancellation on select bookings, and 24/7 customer support.</p>
                <ul class="space-y-1.5 mb-4">
                    <li class="flex items-center gap-2 text-xs text-gray-600"><i class="fas fa-check-circle text-emerald-500 text-[10px]"></i> M-Pesa &amp; card payments</li>
                    <li class="flex items-center gap-2 text-xs text-gray-600"><i class="fas fa-check-circle text-emerald-500 text-[10px]"></i> Instant e-ticket delivery</li>
                    <li class="flex items-center gap-2 text-xs text-gray-600"><i class="fas fa-check-circle text-emerald-500 text-[10px]"></i> Free cancellation options</li>
                </ul>
                <a href="{{ route('hotels.search.form') }}" class="inline-flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-4 py-2 rounded-lg transition-colors">Start Booking <i class="fas fa-arrow-right text-[10px]"></i></a>
            </div>
        </div>
    </div>
</section>

{{-- ===== HOW IT WORKS ===== --}}
<section class="py-10 bg-slate-900">
    <div class="max-w-6xl mx-auto px-4">
        <h2 class="text-lg font-bold text-white text-center mb-8">How It Works</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @php $steps = [['icon'=>'fa-search','t'=>'Search','d'=>'Enter your destination and travel dates.'],['icon'=>'fa-list-check','t'=>'Compare','d'=>'Compare prices from hundreds of providers.'],['icon'=>'fa-credit-card','t'=>'Book','d'=>'Pay securely via M-Pesa or card.'],['icon'=>'fa-ticket-alt','t'=>'Travel','d'=>'Get your e-ticket instantly.']]; @endphp
            @foreach($steps as $i => $s)
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-white/10 mb-3 relative"><i class="fas {{ $s['icon'] }} text-white text-sm"></i><span class="absolute -top-1 -right-1 bg-amber-500 text-white text-[8px] font-bold w-4 h-4 rounded-full flex items-center justify-center">{{ $i+1 }}</span></div>
                <h3 class="text-xs font-semibold text-white mb-1">{{ $s['t'] }}</h3>
                <p class="text-[10px] text-gray-400">{{ $s['d'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== POPULAR ROUTES ===== --}}
<section class="py-10 bg-white">
    <div class="max-w-6xl mx-auto px-4">
        <div class="flex items-end justify-between mb-6">
            <div>
                <span class="text-[10px] font-bold text-indigo-600 uppercase tracking-wider">Routes</span>
                <h2 class="text-lg font-bold text-gray-900 mt-0.5">Popular Flight Routes</h2>
            </div>
        </div>
        @php
        $routes = [
            ['to'=>'Mombasa','code'=>'MBA','dur'=>'55m','price'=>'7,500','airline'=>'Kenya Airways'],
            ['to'=>'Zanzibar','code'=>'ZNZ','dur'=>'1h 30m','price'=>'12,000','airline'=>'Precision Air'],
            ['to'=>'Kampala','code'=>'EBB','dur'=>'1h 20m','price'=>'14,500','airline'=>'Kenya Airways'],
            ['to'=>'Kigali','code'=>'KGL','dur'=>'1h 35m','price'=>'16,500','airline'=>'RwandAir'],
            ['to'=>'Dar es Salaam','code'=>'DAR','dur'=>'1h 15m','price'=>'11,000','airline'=>'Fastjet'],
            ['to'=>'Addis Ababa','code'=>'ADD','dur'=>'2h 10m','price'=>'24,000','airline'=>'Ethiopian'],
        ];
        @endphp
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
            @foreach($routes as $r)
            <a href="{{ route('flights.public.search') }}?to={{ $r['code'] }}" class="group flex items-center justify-between bg-gray-50 hover:bg-blue-50 border border-gray-100 hover:border-blue-200 rounded-lg p-3 transition-colors">
                <div class="flex items-center gap-3">
                    <div class="text-center"><div class="text-sm font-bold text-gray-900">NBO</div><div class="text-[9px] text-gray-400">Nairobi</div></div>
                    <div class="flex flex-col items-center"><div class="text-[9px] text-gray-400">{{ $r['dur'] }}</div><div class="w-10 h-px bg-gray-300 relative"><i class="fas fa-plane text-blue-500 text-[7px] absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-gray-50 group-hover:bg-blue-50 px-0.5"></i></div></div>
                    <div class="text-center"><div class="text-sm font-bold text-gray-900">{{ $r['code'] }}</div><div class="text-[9px] text-gray-400">{{ $r['to'] }}</div></div>
                </div>
                <div class="text-right"><div class="text-xs font-bold text-blue-600">KES {{ $r['price'] }}</div><div class="text-[9px] text-gray-400">{{ $r['airline'] }}</div></div>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== TRAVEL INSPIRATION (3 image cards) ===== --}}
<section class="py-10 bg-gray-50">
    <div class="max-w-6xl mx-auto px-4">
        <h2 class="text-lg font-bold text-gray-900 mb-6">Travel Inspiration</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('flights.public.search') }}" class="relative h-48 rounded-xl overflow-hidden group block">
                <img src="https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?w=500&h=300&fit=crop" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                <div class="absolute bottom-3 left-3"><span class="text-[9px] font-bold text-amber-400 uppercase">Weekend</span><h3 class="text-sm font-bold text-white">Weekend Getaways</h3><p class="text-[10px] text-white/70 mt-0.5">2-3 day escapes from Nairobi</p></div>
            </a>
            <a href="{{ route('flights.public.search') }}" class="relative h-48 rounded-xl overflow-hidden group block">
                <img src="https://images.unsplash.com/photo-1516426122078-c23e76319801?w=500&h=300&fit=crop" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                <div class="absolute bottom-3 left-3"><span class="text-[9px] font-bold text-rose-400 uppercase">Trending</span><h3 class="text-sm font-bold text-white">Trending This Month</h3><p class="text-[10px] text-white/70 mt-0.5">Safari, beaches &amp; culture</p></div>
            </a>
            <a href="{{ route('flights.public.search') }}" class="relative h-48 rounded-xl overflow-hidden group block">
                <img src="https://images.unsplash.com/photo-1501785888041-af3ef285b470?w=500&h=300&fit=crop" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                <div class="absolute bottom-3 left-3"><span class="text-[9px] font-bold text-emerald-400 uppercase">Budget</span><h3 class="text-sm font-bold text-white">Budget-Friendly</h3><p class="text-[10px] text-white/70 mt-0.5">Under KES 15,000</p></div>
            </a>
        </div>
    </div>
</section>

{{-- ===== WHY CHOOSE US ===== --}}
<section class="py-10 bg-white">
    <div class="max-w-6xl mx-auto px-4">
        <h2 class="text-lg font-bold text-gray-900 text-center mb-6">Why Book With Ziara Travels</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @php $features = [['i'=>'fa-shield-halved','t'=>'Secure Booking','d'=>'Enterprise-grade encryption for payments.','c'=>'blue'],['i'=>'fa-tag','t'=>'Best Prices','d'=>'We match and beat competitor pricing.','c'=>'amber'],['i'=>'fa-headset','t'=>'24/7 Support','d'=>'Our team is available around the clock.','c'=>'emerald'],['i'=>'fa-bolt','t'=>'Instant E-Tickets','d'=>'Receive your ticket instantly after booking.','c'=>'purple']]; @endphp
            @foreach($features as $f)
            <div class="text-center p-4 rounded-xl bg-gray-50">
                <div class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-{{ $f['c'] }}-100 mb-2"><i class="fas {{ $f['i'] }} text-sm text-{{ $f['c'] }}-600"></i></div>
                <h3 class="text-xs font-semibold text-gray-900 mb-1">{{ $f['t'] }}</h3>
                <p class="text-[10px] text-gray-500">{{ $f['d'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== FAQ ===== --}}
<section class="py-10 bg-gray-50">
    <div class="max-w-2xl mx-auto px-4">
        <h2 class="text-lg font-bold text-gray-900 text-center mb-6">Frequently Asked Questions</h2>
        @php
        $faqs = [
            ['q'=>'How does Ziara Travels work?','a'=>'We compare flights and hotels from hundreds of providers to find the best deals. Search, compare and book!'],
            ['q'=>'What payment methods do you accept?','a'=>'M-Pesa, M-Pesa STK Push, Equity Eazzy Banking, credit/debit cards and bank transfers via Jenga API.'],
            ['q'=>'How do I get my e-ticket?','a'=>'After payment, your e-ticket is emailed instantly and can be downloaded as a PDF from your dashboard.'],
            ['q'=>'Can I cancel my booking?','a'=>'Cancellation policies depend on the fare type. Many hotel bookings offer free cancellation.'],
            ['q'=>'Is my payment secure?','a'=>'Yes. Payments are processed through Jenga API (Equity Bank) and M-Pesa with industry-standard encryption.'],
        ];
        @endphp
        <div class="space-y-2">
            @foreach($faqs as $faq)
            <div class="border border-gray-200 rounded-lg bg-white overflow-hidden">
                <button class="faq-toggle w-full flex items-center justify-between p-3 text-left hover:bg-gray-50 transition-colors" onclick="toggleFaq(this)">
                    <span class="text-xs font-semibold text-gray-900 pr-3">{{ $faq['q'] }}</span>
                    <i class="fas fa-chevron-down text-gray-300 text-[10px] transition-transform duration-200 shrink-0"></i>
                </button>
                <div class="faq-content hidden px-3 pb-3"><p class="text-[11px] text-gray-500 leading-relaxed">{{ $faq['a'] }}</p></div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== NEWSLETTER ===== --}}
<section class="py-10 bg-blue-600">
    <div class="max-w-xl mx-auto px-4 text-center">
        <h2 class="text-base font-bold text-white mb-1">Get the Best Travel Deals</h2>
        <p class="text-[11px] text-blue-100 mb-4">Subscribe for exclusive flight and hotel deals.</p>
        <form class="flex gap-2" onsubmit="event.preventDefault(); alert('Thanks for subscribing!'); this.reset();">
            <input type="email" placeholder="Your email" class="flex-1 px-3 py-2 rounded-lg text-xs text-gray-800 focus:ring-2 focus:ring-amber-400 focus:outline-none" required>
            <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold px-5 py-2 rounded-lg transition-colors whitespace-nowrap">Subscribe</button>
        </form>
    </div>
</section>

@endsection

@push('styles')
<style>
.search-tab{color:#9ca3af;border-bottom:2px solid transparent;}.search-tab.active{color:#2563eb;border-bottom-color:#2563eb;background:#eff6ff;}
.search-tab:hover:not(.active){background:#f9fafb;}
</style>
@endpush

@push('scripts')
<script>
function switchTab(tab){
    document.querySelectorAll('.search-tab').forEach(function(t){t.classList.remove('active');});
    document.querySelectorAll('.search-panel').forEach(function(p){p.classList.add('hidden');});
    document.querySelector('[data-tab="'+tab+'"]').classList.add('active');
    document.getElementById('panel-'+tab).classList.remove('hidden');
}

function toggleFaq(btn){
    var c=btn.nextElementSibling,ic=btn.querySelector('i'),open=!c.classList.contains('hidden');
    document.querySelectorAll('.faq-content').forEach(function(x){x.classList.add('hidden');});
    document.querySelectorAll('.faq-toggle i').forEach(function(x){x.style.transform='rotate(0)';});
    if(!open){c.classList.remove('hidden');ic.style.transform='rotate(180deg)';}
}

// Flight search itself (autocomplete, date picker, trip type, AJAX submit) is
// now handled by public/js/flight-search-widget.js via the shared search
// widget partial — only the hotel-panel defaults/autocomplete remain here.
document.addEventListener('DOMContentLoaded',function(){
    var tmr=new Date();tmr.setDate(tmr.getDate()+1);
    var nw=new Date();nw.setDate(nw.getDate()+8);
    function fmt(d){return d.toISOString().split('T')[0];}
    var ci=document.getElementById('hero_checkin'),co=document.getElementById('hero_checkout');
    if(ci&&!ci.value){ci.value=fmt(tmr);ci.min=fmt(tmr);}
    if(co&&!co.value){co.value=fmt(nw);co.min=fmt(nw);}
    loadFlightDeals();
    initCityAutocomplete();
});

function loadFlightDeals(){
    var c=document.getElementById('flightDealsContainer');
    fetch('/east-african-offers').then(function(r){return r.json();}).then(function(data){
        var offers=data.offers||data.data||data;
        if(!Array.isArray(offers)||!offers.length){renderStaticDeals(c);return;}
        c.innerHTML='';
        offers.slice(0,4).forEach(function(o){
            var price=o.price?(o.price.total||o.price):'—';
            var dest=o.destination||o.to||'Dest';
            var origin=o.origin||o.from||'NBO';
            c.innerHTML+='<div class="bg-white rounded-lg border border-gray-100 p-3 hover:shadow-sm transition-shadow cursor-pointer"><div class="flex items-center justify-between mb-1.5"><span class="text-[10px] font-bold text-blue-600">'+origin+' &rarr; '+dest+'</span></div><div class="text-sm font-bold text-gray-900 mb-0.5">'+price+' <span class="text-[10px] font-normal text-gray-400">USD</span></div><div class="text-[10px] text-gray-400">'+(o.departureDate||'')+'</div></div>';
        });
    }).catch(function(){renderStaticDeals(c);});
}
function renderStaticDeals(c){
    var d=[{from:'NBO',to:'MBA',price:'KES 7,500',info:'Daily'},{from:'NBO',to:'ZNZ',price:'KES 12,000',info:'4x daily'},{from:'NBO',to:'DAR',price:'KES 11,000',info:'4x daily'},{from:'NBO',to:'EBB',price:'KES 14,500',info:'5x daily'}];
    c.innerHTML='';
    d.forEach(function(x){
        c.innerHTML+='<div class="bg-white rounded-lg border border-gray-100 p-3 hover:shadow-sm transition-shadow cursor-pointer"><div class="flex items-center justify-between mb-1.5"><span class="text-[10px] font-bold text-blue-600">'+x.from+' &rarr; '+x.to+'</span><span class="text-[9px] text-emerald-600 font-semibold">Best Price</span></div><div class="text-sm font-bold text-gray-900 mb-0.5">'+x.price+'</div><div class="text-[10px] text-gray-400">'+x.info+'</div></div>';
    });
}


function initCityAutocomplete(){
    var cache={};
    document.querySelectorAll('.city-autocomplete').forEach(function(input){
        var parent=input.closest('.relative').parentElement;
        var sug=parent.querySelector('.city-suggestions');
        var hid=parent.querySelector('input[type="hidden"]');
        var to;
        input.addEventListener('input',function(){
            clearTimeout(to);var q=this.value.trim();
            if(q.length<2){sug.classList.add('hidden');return;}
            to=setTimeout(function(){
                if(cache[q]){renderCitySuggestions(cache[q],sug,input,hid);return;}
                fetch('/api/airports/search?q='+encodeURIComponent(q)).then(function(r){return r.json();}).then(function(d){cache[q]=d;renderCitySuggestions(d,sug,input,hid);}).catch(function(){sug.classList.add('hidden');});
            },200);
        });
        document.addEventListener('click',function(e){if(!input.contains(e.target)&&!sug.contains(e.target))sug.classList.add('hidden');});
    });
}
function renderCitySuggestions(data,container,input,hidden){
    if(!data||!data.length){container.classList.add('hidden');return;}
    container.innerHTML='';var seen={};
    data.forEach(function(item){
        var city=item.city||item.name||'',code=item.iata_code||item.iataCode||item.code||'',country=item.country||'';
        if(!city||seen[code])return;seen[code]=true;
        var d=document.createElement('div');
        d.className='px-3 py-2 hover:bg-blue-50 cursor-pointer flex justify-between items-center border-b border-gray-50 text-xs';
        d.innerHTML='<span class="font-medium text-gray-900">'+city+'<span class="text-gray-400 ml-1">'+country+'</span></span><span class="font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded text-[10px]">'+code+'</span>';
        d.addEventListener('click',function(){input.value=city;hidden.value=code;container.classList.add('hidden');});
        container.appendChild(d);
    });
    container.classList.remove('hidden');
}
</script>
@endpush
