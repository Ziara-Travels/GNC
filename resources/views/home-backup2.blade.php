@extends('layouts.main')

@section('content')

{{-- ========== HERO + SEARCH SECTION ========== --}}
<section class="relative min-h-[600px] bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-900 overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-20 left-10 w-72 h-72 bg-blue-500 rounded-full blur-3xl"></div>
        <div class="absolute bottom-10 right-20 w-96 h-96 bg-indigo-500 rounded-full blur-3xl"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 pt-28 pb-16">
        <div class="text-center mb-10">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-4 leading-tight">
                Your Next Adventure <br>
                <span class="bg-gradient-to-r from-amber-400 to-orange-500 bg-clip-text text-transparent">Starts Here</span>
            </h1>
            <p class="text-lg text-blue-200 max-w-2xl mx-auto">Compare flights, hotels and packages from hundreds of providers. Book smart, travel better.</p>
        </div>

        {{-- Search Widget --}}
        <div class="max-w-5xl mx-auto">
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
                {{-- Tabs --}}
                <div class="flex border-b border-gray-200" id="searchTabs">
                    <button class="search-tab active flex-1 py-4 px-6 text-center font-semibold text-sm transition-all duration-200 flex items-center justify-center gap-2" data-tab="flights" onclick="switchTab('flights')">
                        <i class="fas fa-plane-departure"></i> Flights
                    </button>
                    <button class="search-tab flex-1 py-4 px-6 text-center font-semibold text-sm transition-all duration-200 flex items-center justify-center gap-2" data-tab="hotels" onclick="switchTab('hotels')">
                        <i class="fas fa-hotel"></i> Hotels
                    </button>
                    <button class="search-tab flex-1 py-4 px-6 text-center font-semibold text-sm transition-all duration-200 flex items-center justify-center gap-2" data-tab="packages" onclick="switchTab('packages')">
                        <i class="fas fa-suitcase-rolling"></i> Flight + Hotel
                    </button>
                </div>

                {{-- FLIGHTS TAB --}}
                <div class="search-panel p-6" id="panel-flights">
                    <div class="flex gap-4 mb-5">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="trip_type_home" value="round_trip" checked class="text-blue-600 focus:ring-blue-500" onchange="toggleReturnDate()">
                            <span class="text-sm font-medium text-gray-700">Round Trip</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="trip_type_home" value="one_way" class="text-blue-600 focus:ring-blue-500" onchange="toggleReturnDate()">
                            <span class="text-sm font-medium text-gray-700">One Way</span>
                        </label>
                    </div>

                    <form action="/api/search" method="POST" id="heroFlightForm">
                        @csrf
                        <input type="hidden" name="trip_type" value="round_trip" id="hero_trip_type">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                            <div class="relative">
                                <label class="block text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">From</label>
                                <div class="relative">
                                    <i class="fas fa-plane-departure absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                    <input type="text" name="from" id="hero_from" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-800 airport-autocomplete" placeholder="City or Airport" autocomplete="off" required>
                                    <input type="hidden" name="from_iata" id="hero_from_iata">
                                </div>
                                <div class="airport-suggestions absolute z-50 w-full bg-white border border-gray-200 rounded-lg shadow-xl mt-1 hidden max-h-64 overflow-y-auto"></div>
                            </div>

                            <div class="relative">
                                <label class="block text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">To</label>
                                <div class="relative">
                                    <i class="fas fa-plane-arrival absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                    <input type="text" name="to" id="hero_to" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-800 airport-autocomplete" placeholder="City or Airport" autocomplete="off" required>
                                    <input type="hidden" name="to_iata" id="hero_to_iata">
                                </div>
                                <div class="airport-suggestions absolute z-50 w-full bg-white border border-gray-200 rounded-lg shadow-xl mt-1 hidden max-h-64 overflow-y-auto"></div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">Departure</label>
                                <div class="relative">
                                    <i class="fas fa-calendar-alt absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                    <input type="date" name="date" id="hero_depart" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-800" required>
                                </div>
                            </div>

                            <div id="returnDateWrap">
                                <label class="block text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">Return</label>
                                <div class="relative">
                                    <i class="fas fa-calendar-alt absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                    <input type="date" name="return_date" id="hero_return" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-800">
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-5">
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">Adults</label>
                                <select name="adults" class="w-full py-3 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-gray-800">
                                    @for($i=1; $i<=9; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">Children</label>
                                <select name="children" class="w-full py-3 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-gray-800">
                                    @for($i=0; $i<=8; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">Infants</label>
                                <select name="infants" class="w-full py-3 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-gray-800">
                                    @for($i=0; $i<=4; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">Cabin</label>
                                <select name="travel_class" class="w-full py-3 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-gray-800">
                                    <option value="ECONOMY">Economy</option>
                                    <option value="PREMIUM_ECONOMY">Premium Economy</option>
                                    <option value="BUSINESS">Business</option>
                                    <option value="FIRST">First Class</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold py-4 px-8 rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl flex items-center justify-center gap-2 text-lg">
                            <i class="fas fa-search"></i> Search Flights
                        </button>
                    </form>
                </div>

                {{-- HOTELS TAB --}}
                <div class="search-panel p-6 hidden" id="panel-hotels">
                    <form action="{{ route('hotels.search') }}" method="POST" id="heroHotelForm">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                            <div class="lg:col-span-2 relative">
                                <label class="block text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">Destination</label>
                                <div class="relative">
                                    <i class="fas fa-map-marker-alt absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                    <input type="text" name="destination" id="hero_hotel_dest" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-800 city-autocomplete" placeholder="City or region" autocomplete="off" required>
                                    <input type="hidden" name="city_code" id="hero_hotel_city_code">
                                </div>
                                <div class="city-suggestions absolute z-50 w-full bg-white border border-gray-200 rounded-lg shadow-xl mt-1 hidden max-h-64 overflow-y-auto"></div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">Check-in</label>
                                <div class="relative">
                                    <i class="fas fa-calendar-alt absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                    <input type="date" name="check_in_date" id="hero_checkin" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-800" required>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">Check-out</label>
                                <div class="relative">
                                    <i class="fas fa-calendar-alt absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                    <input type="date" name="check_out_date" id="hero_checkout" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-800" required>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-4 mb-5">
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">Rooms</label>
                                <select name="rooms" class="w-full py-3 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-gray-800">
                                    @for($i=1; $i<=5; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">Adults</label>
                                <select name="adults" class="w-full py-3 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-gray-800">
                                    @for($i=1; $i<=9; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">Children</label>
                                <select name="children" class="w-full py-3 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-gray-800">
                                    @for($i=0; $i<=5; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-bold py-4 px-8 rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl flex items-center justify-center gap-2 text-lg">
                            <i class="fas fa-search"></i> Search Hotels
                        </button>
                    </form>
                </div>

                {{-- PACKAGES TAB --}}
                <div class="search-panel p-6 hidden" id="panel-packages">
                    <form action="/api/search" method="POST" id="heroPackageForm">
                        @csrf
                        <input type="hidden" name="trip_type" value="round_trip">
                        <input type="hidden" name="search_type" value="package">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                            <div class="relative">
                                <label class="block text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">From</label>
                                <div class="relative">
                                    <i class="fas fa-plane-departure absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                    <input type="text" name="from" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-gray-800 airport-autocomplete" placeholder="City or Airport" autocomplete="off" required>
                                    <input type="hidden" name="from_iata">
                                </div>
                                <div class="airport-suggestions absolute z-50 w-full bg-white border border-gray-200 rounded-lg shadow-xl mt-1 hidden max-h-64 overflow-y-auto"></div>
                            </div>
                            <div class="relative">
                                <label class="block text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">To</label>
                                <div class="relative">
                                    <i class="fas fa-plane-arrival absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                    <input type="text" name="to" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-gray-800 airport-autocomplete" placeholder="City or Airport" autocomplete="off" required>
                                    <input type="hidden" name="to_iata">
                                </div>
                                <div class="airport-suggestions absolute z-50 w-full bg-white border border-gray-200 rounded-lg shadow-xl mt-1 hidden max-h-64 overflow-y-auto"></div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">Check-in</label>
                                <input type="date" name="date" class="w-full py-3 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-gray-800" required>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">Check-out</label>
                                <input type="date" name="return_date" class="w-full py-3 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-gray-800" required>
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-4 mb-5">
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">Adults</label>
                                <select name="adults" class="w-full py-3 px-3 border border-gray-300 rounded-lg text-gray-800">
                                    @for($i=1;$i<=9;$i++)<option value="{{ $i }}">{{ $i }}</option>@endfor
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">Children</label>
                                <select name="children" class="w-full py-3 px-3 border border-gray-300 rounded-lg text-gray-800">
                                    @for($i=0;$i<=5;$i++)<option value="{{ $i }}">{{ $i }}</option>@endfor
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">Rooms</label>
                                <select name="rooms" class="w-full py-3 px-3 border border-gray-300 rounded-lg text-gray-800">
                                    @for($i=1;$i<=5;$i++)<option value="{{ $i }}">{{ $i }}</option>@endfor
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="w-full bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white font-bold py-4 px-8 rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl flex items-center justify-center gap-2 text-lg">
                            <i class="fas fa-search"></i> Search Packages
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Stats bar --}}
        <div class="max-w-4xl mx-auto mt-10 grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            <div>
                <div class="text-3xl font-bold text-white">500+</div>
                <div class="text-blue-300 text-sm">Airlines</div>
            </div>
            <div>
                <div class="text-3xl font-bold text-white">2M+</div>
                <div class="text-blue-300 text-sm">Hotels</div>
            </div>
            <div>
                <div class="text-3xl font-bold text-white">190+</div>
                <div class="text-blue-300 text-sm">Countries</div>
            </div>
            <div>
                <div class="text-3xl font-bold text-white">24/7</div>
                <div class="text-blue-300 text-sm">Support</div>
            </div>
        </div>
    </div>
</section>

{{-- ========== TRENDING DESTINATIONS ========== --}}
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12">
            <span class="bg-blue-100 text-blue-700 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">Trending</span>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mt-3 mb-3">Popular Destinations</h2>
            <p class="text-gray-600 max-w-xl mx-auto">Discover the most searched destinations from Nairobi and find the best deals.</p>
        </div>

        @php
        $trendingDestinations = [
            ['city' => 'Zanzibar', 'country' => 'Tanzania', 'code' => 'ZNZ', 'img' => 'https://images.unsplash.com/photo-1586861635167-e5223aadc9fe?w=600&h=400&fit=crop', 'flight_price' => 'KES 12,000', 'hotel_price' => '$85/night', 'tag' => 'Beach Paradise', 'color' => '#06b6d4'],
            ['city' => 'Mombasa', 'country' => 'Kenya', 'code' => 'MBA', 'img' => 'https://images.unsplash.com/photo-1596005554384-d293674c91d7?w=600&h=400&fit=crop', 'flight_price' => 'KES 7,500', 'hotel_price' => '$45/night', 'tag' => 'Coastal Gem', 'color' => '#f59e0b'],
            ['city' => 'Kigali', 'country' => 'Rwanda', 'code' => 'KGL', 'img' => 'https://images.unsplash.com/photo-1580060839134-75a5edca2e99?w=600&h=400&fit=crop', 'flight_price' => 'KES 16,500', 'hotel_price' => '$70/night', 'tag' => 'City of Peace', 'color' => '#10b981'],
            ['city' => 'Dar es Salaam', 'country' => 'Tanzania', 'code' => 'DAR', 'img' => 'https://images.unsplash.com/photo-1611348524140-53c9a25263d6?w=600&h=400&fit=crop', 'flight_price' => 'KES 11,000', 'hotel_price' => '$55/night', 'tag' => 'Business Hub', 'color' => '#8b5cf6'],
            ['city' => 'Kampala', 'country' => 'Uganda', 'code' => 'EBB', 'img' => 'https://images.unsplash.com/photo-1619122753925-a01d8bb38c20?w=600&h=400&fit=crop', 'flight_price' => 'KES 14,500', 'hotel_price' => '$60/night', 'tag' => 'Pearl of Africa', 'color' => '#ef4444'],
            ['city' => 'Addis Ababa', 'country' => 'Ethiopia', 'code' => 'ADD', 'img' => 'https://images.unsplash.com/photo-1524492412937-b28074a5d7da?w=600&h=400&fit=crop', 'flight_price' => 'KES 24,000', 'hotel_price' => '$50/night', 'tag' => 'Historic Capital', 'color' => '#f97316'],
        ];
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($trendingDestinations as $dest)
            <div class="group bg-white rounded-2xl shadow-md hover:shadow-2xl transition-all duration-300 overflow-hidden hover:-translate-y-1">
                <div class="relative h-56 overflow-hidden">
                    <img src="{{ $dest['img'] }}" alt="{{ $dest['city'] }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" loading="lazy">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                    <span class="absolute top-4 left-4 text-xs font-bold text-white px-3 py-1 rounded-full" style="background: {{ $dest['color'] }}">{{ $dest['tag'] }}</span>
                    <div class="absolute bottom-4 left-4 right-4">
                        <h3 class="text-xl font-bold text-white">{{ $dest['city'] }}</h3>
                        <p class="text-white/80 text-sm">{{ $dest['country'] }}</p>
                    </div>
                </div>
                <div class="p-5">
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-plane text-blue-500 text-sm"></i>
                            <span class="text-sm text-gray-600">From <strong class="text-gray-900">{{ $dest['flight_price'] }}</strong></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-hotel text-emerald-500 text-sm"></i>
                            <span class="text-sm text-gray-600"><strong class="text-gray-900">{{ $dest['hotel_price'] }}</strong></span>
                        </div>
                    </div>
                    <a href="{{ route('flights.public.search') }}?to={{ $dest['code'] }}" class="block w-full text-center bg-gray-100 hover:bg-blue-600 hover:text-white text-gray-700 font-semibold py-2.5 rounded-lg transition-all duration-200 text-sm">
                        Explore {{ $dest['city'] }} &rarr;
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ========== FLIGHT DEALS ========== --}}
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-10">
            <div>
                <span class="bg-amber-100 text-amber-700 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">Deals</span>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mt-3 mb-2">Best Flight Deals</h2>
                <p class="text-gray-600">Cheapest flights departing from Nairobi this month</p>
            </div>
            <a href="{{ route('flights.public.search') }}" class="mt-4 md:mt-0 text-blue-600 hover:text-blue-800 font-semibold text-sm flex items-center gap-1">View All Deals <i class="fas fa-arrow-right text-xs"></i></a>
        </div>

        <div id="flightDealsContainer" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @for($i=0; $i<4; $i++)
            <div class="flight-deal-skeleton animate-pulse bg-gray-100 rounded-xl p-5">
                <div class="h-4 bg-gray-200 rounded w-3/4 mb-3"></div>
                <div class="h-3 bg-gray-200 rounded w-1/2 mb-4"></div>
                <div class="h-8 bg-gray-200 rounded w-full mb-3"></div>
                <div class="h-3 bg-gray-200 rounded w-2/3 mb-2"></div>
                <div class="h-3 bg-gray-200 rounded w-1/2"></div>
            </div>
            @endfor
        </div>
    </div>
</section>

{{-- ========== FEATURED HOTELS ========== --}}
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-10">
            <div>
                <span class="bg-emerald-100 text-emerald-700 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">Stays</span>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mt-3 mb-2">Featured Hotels</h2>
                <p class="text-gray-600">Top-rated hotels in popular East African cities</p>
            </div>
            <a href="{{ route('hotels.search') }}" class="mt-4 md:mt-0 text-emerald-600 hover:text-emerald-800 font-semibold text-sm flex items-center gap-1">Browse Hotels <i class="fas fa-arrow-right text-xs"></i></a>
        </div>

        @php
        $featuredHotels = [
            ['name' => 'Zanzibar Serena Hotel', 'city' => 'Zanzibar', 'stars' => 5, 'price' => 185, 'img' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=600&h=400&fit=crop', 'rating' => '9.2', 'amenities' => ['Free WiFi', 'Pool', 'Spa'], 'cancellation' => true],
            ['name' => 'Sarova Whitesands', 'city' => 'Mombasa', 'stars' => 4, 'price' => 120, 'img' => 'https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=600&h=400&fit=crop', 'rating' => '8.7', 'amenities' => ['Beach', 'Pool', 'Restaurant'], 'cancellation' => true],
            ['name' => 'Kigali Marriott', 'city' => 'Kigali', 'stars' => 5, 'price' => 160, 'img' => 'https://images.unsplash.com/photo-1564501049412-61c2a3083791?w=600&h=400&fit=crop', 'rating' => '9.0', 'amenities' => ['Free WiFi', 'Gym', 'Bar'], 'cancellation' => false],
            ['name' => 'Hyatt Regency', 'city' => 'Addis Ababa', 'stars' => 5, 'price' => 140, 'img' => 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=600&h=400&fit=crop', 'rating' => '8.9', 'amenities' => ['Pool', 'Spa', 'Parking'], 'cancellation' => true],
        ];
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($featuredHotels as $hotel)
            <div class="group bg-white rounded-2xl shadow-md hover:shadow-2xl transition-all duration-300 overflow-hidden hover:-translate-y-1">
                <div class="relative h-48 overflow-hidden">
                    <img src="{{ $hotel['img'] }}" alt="{{ $hotel['name'] }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" loading="lazy">
                    @if($hotel['cancellation'])
                    <span class="absolute top-3 left-3 bg-green-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">Free Cancellation</span>
                    @endif
                    <div class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm text-blue-700 px-2 py-1 rounded-lg text-xs font-bold">
                        <i class="fas fa-star text-amber-400"></i> {{ $hotel['rating'] }}
                    </div>
                </div>
                <div class="p-4">
                    <div class="flex items-center gap-0.5 mb-1">
                        @for($s=0; $s < $hotel['stars']; $s++)
                            <i class="fas fa-star text-amber-400 text-[10px]"></i>
                        @endfor
                    </div>
                    <h3 class="font-bold text-gray-900 mb-1 text-sm leading-tight">{{ $hotel['name'] }}</h3>
                    <p class="text-gray-500 text-xs mb-3"><i class="fas fa-map-marker-alt mr-1"></i>{{ $hotel['city'] }}</p>
                    <div class="flex flex-wrap gap-1 mb-3">
                        @foreach($hotel['amenities'] as $amenity)
                        <span class="text-[10px] bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">{{ $amenity }}</span>
                        @endforeach
                    </div>
                    <div class="flex items-end justify-between">
                        <div>
                            <span class="text-xs text-gray-500">From</span>
                            <div class="text-lg font-bold text-gray-900">${{ $hotel['price'] }}<span class="text-xs text-gray-500 font-normal">/night</span></div>
                        </div>
                        <a href="{{ route('hotels.search') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-4 py-2 rounded-lg transition-colors">Book</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ========== TRAVEL INSPIRATION ========== --}}
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12">
            <span class="bg-purple-100 text-purple-700 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">Inspiration</span>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mt-3 mb-3">Travel Inspiration</h2>
            <p class="text-gray-600 max-w-xl mx-auto">Not sure where to go? Get inspired by these curated travel experiences.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="relative h-80 rounded-2xl overflow-hidden group cursor-pointer" onclick="window.location='{{ route('flights.public.search') }}'">
                <img src="https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?w=600&h=400&fit=crop" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" loading="lazy">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                <div class="absolute bottom-6 left-6 right-6">
                    <span class="bg-amber-500 text-white text-[10px] font-bold px-2 py-1 rounded-full uppercase">Weekend</span>
                    <h3 class="text-2xl font-bold text-white mt-2">Weekend Getaways</h3>
                    <p class="text-white/80 text-sm mt-1">Quick escapes from Nairobi &mdash; 2 to 3 day trips to recharge.</p>
                </div>
            </div>

            <div class="relative h-80 rounded-2xl overflow-hidden group cursor-pointer" onclick="window.location='{{ route('flights.public.search') }}'">
                <img src="https://images.unsplash.com/photo-1516426122078-c23e76319801?w=600&h=400&fit=crop" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" loading="lazy">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                <div class="absolute bottom-6 left-6 right-6">
                    <span class="bg-rose-500 text-white text-[10px] font-bold px-2 py-1 rounded-full uppercase">Trending</span>
                    <h3 class="text-2xl font-bold text-white mt-2">Trending This Month</h3>
                    <p class="text-white/80 text-sm mt-1">Most popular destinations right now &mdash; safari, beaches &amp; culture.</p>
                </div>
            </div>

            <div class="relative h-80 rounded-2xl overflow-hidden group cursor-pointer" onclick="window.location='{{ route('flights.public.search') }}'">
                <img src="https://images.unsplash.com/photo-1501785888041-af3ef285b470?w=600&h=400&fit=crop" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" loading="lazy">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                <div class="absolute bottom-6 left-6 right-6">
                    <span class="bg-emerald-500 text-white text-[10px] font-bold px-2 py-1 rounded-full uppercase">Budget</span>
                    <h3 class="text-2xl font-bold text-white mt-2">Budget-Friendly</h3>
                    <p class="text-white/80 text-sm mt-1">Amazing destinations starting under KES 15,000 &mdash; travel more for less.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ========== HOW IT WORKS ========== --}}
<section class="py-16 bg-gradient-to-br from-slate-900 to-indigo-900">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-3">How It Works</h2>
            <p class="text-blue-200 max-w-lg mx-auto">Book your trip in 4 simple steps &mdash; from search to e-ticket.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            @php
            $steps = [
                ['icon' => 'fa-search', 'title' => 'Search', 'desc' => 'Enter your destination, dates and passengers to search.', 'num' => '01'],
                ['icon' => 'fa-list-check', 'title' => 'Compare', 'desc' => 'Compare flights and hotels from hundreds of providers.', 'num' => '02'],
                ['icon' => 'fa-credit-card', 'title' => 'Book', 'desc' => 'Select the best offer and complete your booking securely.', 'num' => '03'],
                ['icon' => 'fa-ticket-alt', 'title' => 'Travel', 'desc' => 'Receive your e-ticket instantly and enjoy your trip!', 'num' => '04'],
            ];
            @endphp
            @foreach($steps as $step)
            <div class="text-center group">
                <div class="relative inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-white/10 border border-white/20 mb-5 group-hover:bg-blue-600 transition-all duration-300">
                    <i class="fas {{ $step['icon'] }} text-2xl text-white"></i>
                    <span class="absolute -top-2 -right-2 bg-amber-500 text-white text-xs font-bold w-7 h-7 rounded-full flex items-center justify-center">{{ $step['num'] }}</span>
                </div>
                <h3 class="text-lg font-bold text-white mb-2">{{ $step['title'] }}</h3>
                <p class="text-blue-200 text-sm">{{ $step['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ========== POPULAR FLIGHT ROUTES ========== --}}
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-10">
            <span class="bg-indigo-100 text-indigo-700 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">Routes</span>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mt-3 mb-3">Popular Flight Routes</h2>
            <p class="text-gray-600">Most searched routes from Nairobi &mdash; updated daily.</p>
        </div>

        @php
        $routes = [
            ['to' => 'Mombasa', 'code' => 'MBA', 'dur' => '55m', 'price' => '7,500', 'airline' => 'Kenya Airways', 'freq' => '8x daily', 'type' => 'Domestic'],
            ['to' => 'Zanzibar', 'code' => 'ZNZ', 'dur' => '1h 30m', 'price' => '12,000', 'airline' => 'Precision Air', 'freq' => '4x daily', 'type' => 'Regional'],
            ['to' => 'Kampala', 'code' => 'EBB', 'dur' => '1h 20m', 'price' => '14,500', 'airline' => 'Kenya Airways', 'freq' => '5x daily', 'type' => 'Regional'],
            ['to' => 'Kigali', 'code' => 'KGL', 'dur' => '1h 35m', 'price' => '16,500', 'airline' => 'RwandAir', 'freq' => '3x daily', 'type' => 'Regional'],
            ['to' => 'Dar es Salaam', 'code' => 'DAR', 'dur' => '1h 15m', 'price' => '11,000', 'airline' => 'Fastjet', 'freq' => '4x daily', 'type' => 'Regional'],
            ['to' => 'Addis Ababa', 'code' => 'ADD', 'dur' => '2h 10m', 'price' => '24,000', 'airline' => 'Ethiopian', 'freq' => '6x daily', 'type' => 'International'],
        ];
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($routes as $r)
            <div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-lg hover:border-blue-200 transition-all duration-200 group">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-gray-900">NBO</div>
                            <div class="text-xs text-gray-500">Nairobi</div>
                        </div>
                        <div class="flex flex-col items-center px-4">
                            <div class="text-xs text-gray-400 mb-1">{{ $r['dur'] }}</div>
                            <div class="w-20 h-px bg-gray-300 relative">
                                <i class="fas fa-plane text-blue-500 text-xs absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white px-1"></i>
                            </div>
                            <div class="text-[10px] text-gray-400 mt-1">{{ $r['type'] }}</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-gray-900">{{ $r['code'] }}</div>
                            <div class="text-xs text-gray-500">{{ $r['to'] }}</div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                    <div>
                        <div class="text-xs text-gray-500">{{ $r['airline'] }} &middot; {{ $r['freq'] }}</div>
                        <div class="text-lg font-bold text-blue-600 mt-0.5">KES {{ $r['price'] }}</div>
                    </div>
                    <a href="{{ route('flights.public.search') }}?to={{ $r['code'] }}" class="bg-blue-50 hover:bg-blue-600 hover:text-white text-blue-600 text-sm font-semibold px-4 py-2 rounded-lg transition-all duration-200 group-hover:bg-blue-600 group-hover:text-white">
                        Search
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ========== AIRLINE PARTNERS ========== --}}
<section class="py-12 bg-white border-y border-gray-100">
    <div class="max-w-7xl mx-auto px-4">
        <p class="text-center text-gray-400 text-sm font-semibold uppercase tracking-wider mb-8">Trusted by leading airlines</p>
        <div class="flex flex-wrap justify-center items-center gap-8 md:gap-12 opacity-60 grayscale hover:grayscale-0 hover:opacity-100 transition-all duration-500">
            @php
            $airlines = ['Kenya Airways', 'Ethiopian Airlines', 'RwandAir', 'Uganda Airlines', 'Precision Air', 'Fastjet', 'Fly540'];
            @endphp
            @foreach($airlines as $airline)
            <div class="text-gray-400 font-bold text-lg hover:text-gray-700 transition-colors">{{ $airline }}</div>
            @endforeach
        </div>
    </div>
</section>

{{-- ========== WHY CHOOSE US ========== --}}
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3">Why Book With Ziara Travels</h2>
            <p class="text-gray-600 max-w-lg mx-auto">Everything you need for a seamless travel experience.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @php
            $features = [
                ['icon' => 'fa-shield-halved', 'title' => 'Secure Booking', 'desc' => 'Your payment and personal data are protected with enterprise-grade encryption.', 'color' => 'blue'],
                ['icon' => 'fa-tag', 'title' => 'Best Price Guarantee', 'desc' => 'Found it cheaper somewhere else? We will match the price — guaranteed.', 'color' => 'amber'],
                ['icon' => 'fa-headset', 'title' => '24/7 Support', 'desc' => 'Our travel experts are available around the clock via phone, email and chat.', 'color' => 'emerald'],
                ['icon' => 'fa-bolt', 'title' => 'Instant Confirmation', 'desc' => 'Receive your e-ticket or hotel voucher instantly after booking.', 'color' => 'purple'],
            ];
            @endphp
            @foreach($features as $f)
            <div class="text-center bg-white rounded-2xl shadow-sm hover:shadow-lg transition-shadow duration-300 p-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-{{ $f['color'] }}-50 mb-5">
                    <i class="fas {{ $f['icon'] }} text-2xl text-{{ $f['color'] }}-600"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $f['title'] }}</h3>
                <p class="text-gray-600 text-sm">{{ $f['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ========== FAQ ========== --}}
<section class="py-16 bg-white">
    <div class="max-w-3xl mx-auto px-4">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-bold text-gray-900 mb-3">Frequently Asked Questions</h2>
            <p class="text-gray-600">Quick answers to common questions about booking with us.</p>
        </div>

        @php
        $faqs = [
            ['q' => 'How does Ziara Travels work?', 'a' => 'We compare flights and hotels from hundreds of airlines and travel providers to find you the best deals. Simply search, compare and book!'],
            ['q' => 'How can I find the cheapest flight?', 'a' => 'Use our flexible date search, set up price alerts, and check our deals section for the lowest fares. Booking early and being flexible with dates helps too.'],
            ['q' => 'What payment methods do you accept?', 'a' => 'We accept M-Pesa, credit/debit cards (Visa, Mastercard), bank transfers (via Equity/Jenga API), and PayPal for international payments.'],
            ['q' => 'Can I cancel or change my booking?', 'a' => 'Yes, cancellation and change policies depend on the fare type. Many of our hotel bookings offer free cancellation. Check the booking terms before confirming.'],
            ['q' => 'How do I get my e-ticket?', 'a' => 'After successful payment, your e-ticket is sent instantly to your email and is also available in your dashboard. You can print it or show it on your phone.'],
            ['q' => 'Is my payment secure?', 'a' => 'Absolutely. We use industry-standard encryption and process payments through trusted providers like Jenga API (Equity Bank) and M-Pesa.'],
        ];
        @endphp

        <div class="space-y-3">
            @foreach($faqs as $i => $faq)
            <div class="border border-gray-200 rounded-xl overflow-hidden">
                <button class="faq-toggle w-full flex items-center justify-between p-5 text-left hover:bg-gray-50 transition-colors" onclick="toggleFaq(this)">
                    <span class="font-semibold text-gray-900 pr-4">{{ $faq['q'] }}</span>
                    <i class="fas fa-chevron-down text-gray-400 text-sm transition-transform duration-200 flex-shrink-0"></i>
                </button>
                <div class="faq-content hidden px-5 pb-5">
                    <p class="text-gray-600 text-sm leading-relaxed">{{ $faq['a'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ========== NEWSLETTER ========== --}}
<section class="py-16 bg-gradient-to-r from-blue-600 to-indigo-700">
    <div class="max-w-3xl mx-auto px-4 text-center">
        <i class="fas fa-paper-plane text-4xl text-white/30 mb-4"></i>
        <h2 class="text-3xl font-bold text-white mb-3">Get the Best Travel Deals</h2>
        <p class="text-blue-100 mb-8">Subscribe to our newsletter and be the first to know about exclusive flight and hotel deals.</p>
        <form class="flex flex-col sm:flex-row gap-3 max-w-lg mx-auto" onsubmit="event.preventDefault(); subscribeNewsletter(this);">
            <input type="email" name="email" placeholder="Enter your email" class="flex-1 px-5 py-3.5 rounded-xl text-gray-800 focus:ring-2 focus:ring-amber-400 focus:outline-none" required>
            <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white font-bold px-8 py-3.5 rounded-xl transition-colors whitespace-nowrap">Subscribe</button>
        </form>
        <p class="text-blue-200 text-xs mt-3">No spam, unsubscribe anytime.</p>
    </div>
</section>

@endsection

@push('styles')
<style>
    .search-tab { color: #6b7280; border-bottom: 3px solid transparent; }
    .search-tab.active { color: #2563eb; border-bottom-color: #2563eb; background: #eff6ff; }
    .search-tab:hover:not(.active) { background: #f9fafb; }
    .flight-deal-card { transition: all 0.2s ease; }
    .flight-deal-card:hover { transform: translateY(-4px); box-shadow: 0 10px 25px rgba(0,0,0,0.12); }
</style>
@endpush

@push('scripts')
<script>
// Tab switching
function switchTab(tab) {
    document.querySelectorAll('.search-tab').forEach(function(t) { t.classList.remove('active'); });
    document.querySelectorAll('.search-panel').forEach(function(p) { p.classList.add('hidden'); });
    document.querySelector('[data-tab="' + tab + '"]').classList.add('active');
    document.getElementById('panel-' + tab).classList.remove('hidden');
}

// Trip type toggle
function toggleReturnDate() {
    var tripType = document.querySelector('input[name="trip_type_home"]:checked').value;
    var wrap = document.getElementById('returnDateWrap');
    var input = document.getElementById('hero_return');
    document.getElementById('hero_trip_type').value = tripType;
    if (tripType === 'one_way') {
        wrap.style.opacity = '0.4';
        wrap.style.pointerEvents = 'none';
        input.removeAttribute('required');
    } else {
        wrap.style.opacity = '1';
        wrap.style.pointerEvents = 'auto';
    }
}

// FAQ toggle
function toggleFaq(btn) {
    var content = btn.nextElementSibling;
    var icon = btn.querySelector('i');
    var isOpen = !content.classList.contains('hidden');
    document.querySelectorAll('.faq-content').forEach(function(c) { c.classList.add('hidden'); });
    document.querySelectorAll('.faq-toggle i').forEach(function(i) { i.style.transform = 'rotate(0)'; });
    if (!isOpen) {
        content.classList.remove('hidden');
        icon.style.transform = 'rotate(180deg)';
    }
}

// Newsletter
function subscribeNewsletter(form) {
    var email = form.querySelector('input[name="email"]').value;
    alert('Thanks for subscribing! We will send deals to ' + email);
    form.reset();
}

// Set default dates
document.addEventListener('DOMContentLoaded', function() {
    var tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    var nextWeek = new Date();
    nextWeek.setDate(nextWeek.getDate() + 8);
    function fmt(d) { return d.toISOString().split('T')[0]; }

    var dep = document.getElementById('hero_depart');
    var ret = document.getElementById('hero_return');
    if (dep && !dep.value) { dep.value = fmt(tomorrow); dep.min = fmt(tomorrow); }
    if (ret && !ret.value) { ret.value = fmt(nextWeek); ret.min = fmt(nextWeek); }

    var ci = document.getElementById('hero_checkin');
    var co = document.getElementById('hero_checkout');
    if (ci && !ci.value) { ci.value = fmt(tomorrow); ci.min = fmt(tomorrow); }
    if (co && !co.value) { co.value = fmt(nextWeek); co.min = fmt(nextWeek); }

    // Load flight deals
    loadFlightDeals();

    // Init airport autocomplete for hero
    initHeroAirportAutocomplete();

    // Init city autocomplete for hotels
    initCityAutocomplete();
});

// Load flight deals from API
function loadFlightDeals() {
    var container = document.getElementById('flightDealsContainer');
    fetch('/east-african-offers')
        .then(function(r) { return r.json(); })
        .then(function(data) {
            var offers = data.offers || data.data || data;
            if (!Array.isArray(offers) || offers.length === 0) {
                renderStaticDeals(container);
                return;
            }
            container.innerHTML = '';
            offers.slice(0, 8).forEach(function(offer) {
                var dep = offer.departureDate || offer.departure_date || '';
                var ret = offer.returnDate || offer.return_date || '';
                var price = offer.price ? (offer.price.total || offer.price) : '—';
                var dest = offer.destination || offer.to || 'Destination';
                var origin = offer.origin || offer.from || 'NBO';
                container.innerHTML += '<div class="flight-deal-card bg-white rounded-xl border border-gray-200 p-5 hover:shadow-lg hover:border-blue-200 cursor-pointer">' +
                    '<div class="flex items-center justify-between mb-3">' +
                    '<span class="text-xs font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded-full">' + origin + ' &rarr; ' + dest + '</span>' +
                    '<span class="text-xs text-gray-400">' + dep + '</span></div>' +
                    '<div class="text-2xl font-bold text-gray-900 mb-1">' + price + ' <span class="text-sm font-normal text-gray-500">USD</span></div>' +
                    '<div class="text-xs text-gray-500">' + dep + ' &mdash; ' + (ret || 'One Way') + '</div></div>';
            });
        })
        .catch(function() { renderStaticDeals(container); });
}

function renderStaticDeals(container) {
    var deals = [
        {from:'NBO', to:'MBA', price:'KES 7,500', info:'Daily flights'},
        {from:'NBO', to:'ZNZ', price:'KES 12,000', info:'4x daily'},
        {from:'NBO', to:'DAR', price:'KES 11,000', info:'4x daily'},
        {from:'NBO', to:'EBB', price:'KES 14,500', info:'5x daily'}
    ];
    container.innerHTML = '';
    deals.forEach(function(d) {
        container.innerHTML += '<div class="flight-deal-card bg-white rounded-xl border border-gray-200 p-5 hover:shadow-lg hover:border-blue-200 cursor-pointer">' +
            '<div class="flex items-center justify-between mb-3">' +
            '<span class="text-xs font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded-full">' + d.from + ' &rarr; ' + d.to + '</span>' +
            '<span class="text-xs text-emerald-600 font-semibold">Best Price</span></div>' +
            '<div class="text-2xl font-bold text-gray-900 mb-1">' + d.price + '</div>' +
            '<div class="text-xs text-gray-500">' + d.info + '</div></div>';
    });
}

// Airport autocomplete for hero search forms
function initHeroAirportAutocomplete() {
    var cache = {};
    document.querySelectorAll('.airport-autocomplete').forEach(function(input) {
        var parent = input.closest('.relative').parentElement;
        var suggestionsDiv = parent.querySelector('.airport-suggestions');
        var hiddenInput = parent.querySelector('input[type="hidden"]');
        var timeout;

        input.addEventListener('input', function() {
            clearTimeout(timeout);
            var q = this.value.trim();
            if (q.length < 2) { suggestionsDiv.classList.add('hidden'); return; }
            timeout = setTimeout(function() {
                if (cache[q]) { renderAirportSuggestions(cache[q], suggestionsDiv, input, hiddenInput); return; }
                fetch('/api/airports/search?q=' + encodeURIComponent(q))
                    .then(function(r) { return r.json(); })
                    .then(function(data) {
                        cache[q] = data;
                        renderAirportSuggestions(data, suggestionsDiv, input, hiddenInput);
                    })
                    .catch(function() { suggestionsDiv.classList.add('hidden'); });
            }, 200);
        });

        document.addEventListener('click', function(e) {
            if (!input.contains(e.target) && !suggestionsDiv.contains(e.target)) {
                suggestionsDiv.classList.add('hidden');
            }
        });
    });
}

function renderAirportSuggestions(data, container, input, hiddenInput) {
    if (!data || !data.length) { container.classList.add('hidden'); return; }
    container.innerHTML = '';
    data.slice(0, 8).forEach(function(item) {
        var div = document.createElement('div');
        div.className = 'px-4 py-3 hover:bg-blue-50 cursor-pointer flex justify-between items-center border-b border-gray-50';
        var name = item.name || item.city || '';
        var city = item.city || '';
        var country = item.country || '';
        var code = item.iata_code || item.iataCode || item.code || '';
        div.innerHTML = '<div><div class="font-semibold text-gray-900 text-sm">' + name + '</div>' +
            '<div class="text-xs text-gray-500">' + city + (country ? ', ' + country : '') + '</div></div>' +
            '<span class="text-xs font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded">' + code + '</span>';
        div.addEventListener('click', function() {
            input.value = name + ' (' + code + ')';
            hiddenInput.value = code;
            container.classList.add('hidden');
        });
        container.appendChild(div);
    });
    container.classList.remove('hidden');
}

// City autocomplete for hotel search
function initCityAutocomplete() {
    var cache = {};
    document.querySelectorAll('.city-autocomplete').forEach(function(input) {
        var parent = input.closest('.relative').parentElement;
        var suggestionsDiv = parent.querySelector('.city-suggestions');
        var hiddenInput = parent.querySelector('input[type="hidden"]');
        var timeout;

        input.addEventListener('input', function() {
            clearTimeout(timeout);
            var q = this.value.trim();
            if (q.length < 2) { suggestionsDiv.classList.add('hidden'); return; }
            timeout = setTimeout(function() {
                if (cache[q]) { renderCitySuggestions(cache[q], suggestionsDiv, input, hiddenInput); return; }
                fetch('/api/airports/search?q=' + encodeURIComponent(q))
                    .then(function(r) { return r.json(); })
                    .then(function(data) {
                        cache[q] = data;
                        renderCitySuggestions(data, suggestionsDiv, input, hiddenInput);
                    })
                    .catch(function() { suggestionsDiv.classList.add('hidden'); });
            }, 200);
        });

        document.addEventListener('click', function(e) {
            if (!input.contains(e.target) && !suggestionsDiv.contains(e.target)) {
                suggestionsDiv.classList.add('hidden');
            }
        });
    });
}

function renderCitySuggestions(data, container, input, hiddenInput) {
    if (!data || !data.length) { container.classList.add('hidden'); return; }
    container.innerHTML = '';
    var seen = {};
    data.forEach(function(item) {
        var city = item.city || item.name || '';
        var code = item.iata_code || item.iataCode || item.code || '';
        var country = item.country || '';
        if (!city || seen[code]) return;
        seen[code] = true;
        var div = document.createElement('div');
        div.className = 'px-4 py-3 hover:bg-blue-50 cursor-pointer flex justify-between items-center border-b border-gray-50';
        div.innerHTML = '<div><div class="font-semibold text-gray-900 text-sm">' + city + '</div>' +
            '<div class="text-xs text-gray-500">' + country + '</div></div>' +
            '<span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded">' + code + '</span>';
        div.addEventListener('click', function() {
            input.value = city;
            hiddenInput.value = code;
            container.classList.add('hidden');
        });
        container.appendChild(div);
    });
    container.classList.remove('hidden');
}
</script>
@endpush
