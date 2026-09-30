@extends('layouts.user-dashboard')

@section('content')
       <!-- Main Content -->
        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">
            
            <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] !pb-0 rounded-md">
                <div class="trezo-card-content">

                    <!-- Stats Stats -->
                    <div class="trezo-card rounded-md mb-[25px]">
                        <div class="trezo-card-header mb-[20px] md:mb-[25px] sm:flex items-center justify-between">
                            <div class="trezo-card-title">
                                <h5 class="mb-0 md:text-xl flex items-center font-normal">
                                    Welcome Back, <span class="text-primary-500 font-medium">{{ $user->name ?? 'User' }}!</span><img src="assets/images/icons/dog.svg" class="inline-block ltr:ml-[5px] rtl:mr-[5px]" alt="dog">
                                </h5>
                            </div>
                            <div class="trezo-card-subtitle sm:flex items-center mt-[10px] sm:mt-0">
                                <button type="button" class="ltr:mr-[10px] rtl:ml-[10px] rounded-md inline-block transition-all py-[3.5px] px-[17px] border border-gray-100 dark:border-[#172036] hover:border-primary-500 hover:bg-primary-500 hover:text-white">
                                    <span class="inline-block relative ltr:pl-[26px] rtl:pr-[26px]">
                                        <i class="ri-calendar-line absolute text-lg top-1/2 -translate-y-1/2 ltr:left-0 rtl:right-0"></i>
                                        {{ \Carbon\Carbon::now()->startOfMonth()->format('M d, Y') }} - {{ \Carbon\Carbon::now()->endOfMonth()->format('M d, Y') }}
                                    </span>
                                </button>
                                <button type="button" class="inline-block rounded-md transition-all bg-primary-500 text-white py-[4.5px] px-[17px] hover:bg-primary-400 mt-[10px] sm:mt-0">
                                    <span class="inline-block relative ltr:pl-[26px] rtl:pr-[26px]">
                                        <i class="ri-book-line absolute text-lg top-1/2 -translate-y-1/2 ltr:left-0 rtl:right-0"></i>
                                        View All Bookings
                                    </span>
                                </button>
                            </div>
                        </div>
                        <div class="trezo-card-content">
                            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-[25px]">

                                <!-- Total Sales -->
                                <div class="relative rounded-md p-[20px] md:p-[25px] !pb-[65px] border border-gray-100 dark:border-[#172036]">
                                    <div class="flex items-center">
                                        <div class="bg-primary-100 dark:bg-[#15203c] text-white text-center relative z-[1] ltr:mr-[15px] rtl:ml-[15px] rounded-[5px] text-[25px] w-[55px] h-[55px]">
                                            <span class="inset-0 -z-[1] m-[8px] absolute rounded-[5px] bg-primary-500"></span>
                                            <i class="ri-shopping-cart-line absolute left-0 right-0 top-1/2 -translate-y-1/2"></i>
                                        </div>
                                        <div>
                                            <h6 class="text-lg md:text-xl mb-[2px] font-medium">
                                                ${{ number_format($stats['total_spent'], 2) }}
                                            </h6>
                                            <span class="block">
                                                Total Spent
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mt-[25px] md:mt-[45px] flex items-center">
                                        <span class="font-medium inline-block relative text-xs rounded-full border border-success-300 text-success-700 bg-success-100 dark:bg-[#15203c] dark:border-[#15203c] py-[2px] ltr:pl-[20px] rtl:pr-[20px] ltr:pr-[10px] rtl:pl-[10px]">
                                            <i class="ri-arrow-up-fill absolute top-1/2 -translate-y-1/2 text-base -mt-px ltr:left-[6px] rtl:right-[6px]"></i>
                                            {{ number_format($travelStats['average_trip_cost'], 0) }}%
                                        </span>
                                        <span class="block ltr:ml-[10px] rtl:mr-[10px] text-xs">
                                            Average Trip Cost
                                        </span>
                                    </div>
                                    <div class="absolute left-0 right-0 -bottom-[30px] ltr:-ml-[12px] rtl:-mr-[12px] ltr:-mr-[10px] rtl:-ml-[10px]">
                                        <div id="salesTotalSalesChart"></div>
                                    </div>
                                </div>

                                <!-- Total Bookings -->
                                <div class="relative rounded-md p-[20px] md:p-[25px] !pb-[65px] border border-gray-100 dark:border-[#172036]">
                                    <div class="flex items-center">
                                        <div class="bg-purple-100 dark:bg-[#15203c] text-white text-center relative z-[1] ltr:mr-[15px] rtl:ml-[15px] rounded-[5px] text-[25px] w-[55px] h-[55px]">
                                            <span class="inset-0 -z-[1] m-[8px] absolute rounded-[5px] bg-purple-500"></span>
                                            <i class="ri-shopping-bag-3-line absolute left-0 right-0 top-1/2 -translate-y-1/2"></i>
                                        </div>
                                        <div>
                                            <h6 class="text-lg md:text-xl mb-[2px] font-medium">
                                                {{ number_format($stats['total_bookings']) }}
                                            </h6>
                                            <span class="block">
                                                Total Bookings
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mt-[25px] md:mt-[45px] flex items-center">
                                        <span class="font-medium inline-block relative text-xs rounded-full border border-success-300 text-success-700 bg-success-100 dark:bg-[#15203c] dark:border-[#15203c] py-[2px] ltr:pl-[20px] rtl:pr-[20px] ltr:pr-[10px] rtl:pl-[10px]">
                                            <i class="ri-arrow-up-fill absolute top-1/2 -translate-y-1/2 text-base -mt-px ltr:left-[6px] rtl:right-[6px]"></i>
                                            {{ $stats['completed_bookings'] }}
                                        </span>
                                        <span class="block ltr:ml-[10px] rtl:mr-[10px] text-xs">
                                            completed bookings
                                        </span>
                                    </div>
                                    <div class="absolute -bottom-[31px] ltr:left-0 rtl:right-0 ltr:right-[10px] rtl:left-[10px]">
                                        <div id="salesTotalOrdersChart"></div>
                                    </div>
                                </div>

                                <!-- Countries Visited -->
                                <div class="relative rounded-md p-[20px] md:p-[25px] !pb-[65px] border border-gray-100 dark:border-[#172036]">
                                    <div class="flex items-center">
                                        <div class="bg-secondary-100 dark:bg-[#15203c] text-white text-center relative z-[1] ltr:mr-[15px] rtl:ml-[15px] rounded-[5px] text-[25px] w-[55px] h-[55px]">
                                            <span class="inset-0 -z-[1] m-[8px] absolute rounded-[5px] bg-secondary-500"></span>
                                            <i class="ri-earth-line absolute left-0 right-0 top-1/2 -translate-y-1/2"></i>
                                        </div>
                                        <div>
                                            <h6 class="text-lg md:text-xl mb-[2px] font-medium">
                                                {{ $travelStats['countries_visited'] }}
                                            </h6>
                                            <span class="block">
                                                Countries Visited
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mt-[25px] md:mt-[45px] flex items-center">
                                        <span class="font-medium inline-block relative text-xs rounded-full border border-primary-300 text-primary-700 bg-primary-100 dark:bg-[#15203c] dark:border-[#15203c] py-[2px] ltr:pl-[20px] rtl:pr-[20px] ltr:pr-[10px] rtl:pl-[10px]">
                                            <i class="ri-map-pin-line absolute top-1/2 -translate-y-1/2 text-base -mt-px ltr:left-[6px] rtl:right-[6px]"></i>
                                            {{ $travelStats['cities_visited'] }}
                                        </span>
                                        <span class="block ltr:ml-[10px] rtl:mr-[10px] text-xs">
                                            cities visited
                                        </span>
                                    </div>
                                    <div class="absolute left-0 right-0 -bottom-[30px] ltr:-ml-[12px] rtl:-mr-[12px] ltr:-mr-[10px] rtl:-ml-[10px]">
                                        <div id="salesTotalProfitChart"></div>
                                    </div>
                                </div>

                                <!-- Loyalty Points -->
                                <div class="relative rounded-md p-[20px] md:p-[25px] !pb-[65px] border border-gray-100 dark:border-[#172036]">
                                    <div class="flex items-center">
                                        <div class="bg-orange-100 dark:bg-[#15203c] text-white text-center relative z-[1] ltr:mr-[15px] rtl:ml-[15px] rounded-[5px] text-[25px] w-[55px] h-[55px]">
                                            <span class="inset-0 -z-[1] m-[8px] absolute rounded-[5px] bg-orange-500"></span>
                                            <i class="ri-star-line absolute left-0 right-0 top-1/2 -translate-y-1/2"></i>
                                        </div>
                                        <div>
                                            <h6 class="text-lg md:text-xl mb-[2px] font-medium">
                                                {{ number_format($travelStats['loyalty_points']) }}
                                            </h6>
                                            <span class="block">
                                                Loyalty Points
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mt-[25px] md:mt-[45px] flex items-center">
                                        <span class="font-medium inline-block relative text-xs rounded-full border border-warning-300 text-warning-700 bg-warning-100 dark:bg-[#15203c] dark:border-[#15203c] py-[2px] ltr:pl-[20px] rtl:pr-[20px] ltr:pr-[10px] rtl:pl-[10px]">
                                            <i class="ri-flight-takeoff-line absolute top-1/2 -translate-y-1/2 text-base -mt-px ltr:left-[6px] rtl:right-[6px]"></i>
                                            {{ $stats['flight_bookings'] }}
                                        </span>
                                        <span class="block ltr:ml-[10px] rtl:mr-[10px] text-xs">
                                            flight bookings
                                        </span>
                                    </div>
                                    <div class="absolute left-[15px] right-[15px] -bottom-[30px]">
                                        <div id="salesTotalRevenueChart"></div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-[25px] mb-[25px]">
                        <div class="lg:col-span-2">
        
                            <!-- User Activity & Logs -->
                            <div class="trezo-card border border-gray-100 dark:border-[#172036] p-[20px] md:p-[25px] rounded-md">
                                <div class="trezo-card-header mb-[20px] md:mb-[25px] flex items-center justify-between">
                                    <div class="trezo-card-title">
                                        <h5 class="mb-0 flex items-center">
                                            <i class="ri-history-line text-blue-500 mr-2"></i>
                                            Activity & Logs
                                        </h5>
                                    </div>
                                    <div class="trezo-card-subtitle">
                                        <div class="trezo-card-dropdown relative">
                                            <button type="button" class="trezo-card-dropdown-btn inline-block transition-all hover:text-primary-500" id="activityDropdownBtn">
                                                <span class="inline-block relative ltr:pr-[17px] ltr:md:pr-[20px] rtl:pl-[17px] rtl:ml:pr-[20px]">
                                                    Last 7 Days
                                                    <i class="ri-arrow-down-s-line text-lg absolute ltr:-right-[3px] rtl:-left-[3px] top-1/2 -translate-y-1/2"></i>
                                                </span>
                                            </button>
                                            <ul class="trezo-card-dropdown-menu transition-all bg-white shadow-3xl rounded-md top-full py-[15px] absolute ltr:right-0 rtl:left-0 w-[195px] z-[5] dark:bg-dark dark:shadow-none">
                                                <li>
                                                    <button type="button" class="block w-full transition-all text-black ltr:text-left rtl:text-right relative py-[8px] px-[20px] hover:bg-gray-50 dark:text-white dark:hover:bg-black">
                                                        Today
                                                    </button>
                                                </li>
                                                <li>
                                                    <button type="button" class="block w-full transition-all text-black ltr:text-left rtl:text-right relative py-[8px] px-[20px] hover:bg-gray-50 dark:text-white dark:hover:bg-black">
                                                        Last 3 Days
                                                    </button>
                                                </li>
                                                <li>
                                                    <button type="button" class="block w-full transition-all text-black ltr:text-left rtl:text-right relative py-[8px] px-[20px] hover:bg-gray-50 dark:text-white dark:hover:bg-black">
                                                        Last 7 Days
                                                    </button>
                                                </li>
                                                <li>
                                                    <button type="button" class="block w-full transition-all text-black ltr:text-left rtl:text-right relative py-[8px] px-[20px] hover:bg-gray-50 dark:text-white dark:hover:bg-black">
                                                        Last 30 Days
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="trezo-card-content">
                                    <div class="space-y-4 max-h-80 overflow-y-auto">
                                        @forelse($recentActivities ?? [] as $activity)
                                        <div class="flex items-start space-x-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                            @php
                                                $activityConfig = match($activity->action ?? 'general') {
                                                    'login' => ['icon' => 'ri-login-circle-line', 'color' => 'text-green-600', 'bg' => 'bg-green-100 dark:bg-green-900'],
                                                    'logout' => ['icon' => 'ri-logout-circle-line', 'color' => 'text-gray-600', 'bg' => 'bg-gray-100 dark:bg-gray-900'],
                                                    'booking_created' => ['icon' => 'ri-calendar-check-line', 'color' => 'text-blue-600', 'bg' => 'bg-blue-100 dark:bg-blue-900'],
                                                    'flight_search' => ['icon' => 'ri-search-line', 'color' => 'text-purple-600', 'bg' => 'bg-purple-100 dark:bg-purple-900'],
                                                    'payment_completed' => ['icon' => 'ri-money-dollar-circle-line', 'color' => 'text-green-600', 'bg' => 'bg-green-100 dark:bg-green-900'],
                                                    'profile_updated' => ['icon' => 'ri-user-settings-line', 'color' => 'text-indigo-600', 'bg' => 'bg-indigo-100 dark:bg-indigo-900'],
                                                    'wishlist_added' => ['icon' => 'ri-heart-add-line', 'color' => 'text-pink-600', 'bg' => 'bg-pink-100 dark:bg-pink-900'],
                                                    default => ['icon' => 'ri-information-line', 'color' => 'text-gray-600', 'bg' => 'bg-gray-100 dark:bg-gray-900']
                                                };
                                            @endphp
                                            <div class="flex-shrink-0 {{ $activityConfig['bg'] }} p-2 rounded-full">
                                                <i class="{{ $activityConfig['icon'] }} {{ $activityConfig['color'] }}"></i>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-800 dark:text-white">
                                                    {{ $activity->description ?? 'Activity performed' }}
                                                </p>
                                                <div class="flex items-center justify-between mt-1">
                                                    <p class="text-xs text-gray-500">
                                                        {{ \Carbon\Carbon::parse($activity->created_at ?? now())->diffForHumans() }}
                                                    </p>
                                                    @if($activity->ip_address ?? null)
                                                    <span class="text-xs text-gray-400">{{ $activity->ip_address }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        @empty
                                        @for($i = 1; $i <= 5; $i++)
                                        @php
                                            $sampleActivities = [
                                                ['action' => 'login', 'description' => 'Logged into account', 'time' => now()->subMinutes($i * 30)],
                                                ['action' => 'flight_search', 'description' => 'Searched for flights to Dubai', 'time' => now()->subHours($i)],
                                                ['action' => 'booking_created', 'description' => 'Created new flight booking', 'time' => now()->subHours($i * 2)],
                                                ['action' => 'payment_completed', 'description' => 'Payment completed successfully', 'time' => now()->subDays($i)],
                                                ['action' => 'profile_updated', 'description' => 'Updated profile information', 'time' => now()->subDays($i + 1)]
                                            ];
                                            $activity = (object) $sampleActivities[($i - 1) % 5];
                                        @endphp
                                        <div class="flex items-start space-x-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                            @php
                                                $activityConfig = match($activity->action) {
                                                    'login' => ['icon' => 'ri-login-circle-line', 'color' => 'text-green-600', 'bg' => 'bg-green-100 dark:bg-green-900'],
                                                    'flight_search' => ['icon' => 'ri-search-line', 'color' => 'text-purple-600', 'bg' => 'bg-purple-100 dark:bg-purple-900'],
                                                    'booking_created' => ['icon' => 'ri-calendar-check-line', 'color' => 'text-blue-600', 'bg' => 'bg-blue-100 dark:bg-blue-900'],
                                                    'payment_completed' => ['icon' => 'ri-money-dollar-circle-line', 'color' => 'text-green-600', 'bg' => 'bg-green-100 dark:bg-green-900'],
                                                    'profile_updated' => ['icon' => 'ri-user-settings-line', 'color' => 'text-indigo-600', 'bg' => 'bg-indigo-100 dark:bg-indigo-900'],
                                                    default => ['icon' => 'ri-information-line', 'color' => 'text-gray-600', 'bg' => 'bg-gray-100 dark:bg-gray-900']
                                                };
                                            @endphp
                                            <div class="flex-shrink-0 {{ $activityConfig['bg'] }} p-2 rounded-full">
                                                <i class="{{ $activityConfig['icon'] }} {{ $activityConfig['color'] }}"></i>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-800 dark:text-white">
                                                    {{ $activity->description }}
                                                </p>
                                                <p class="text-xs text-gray-500 mt-1">
                                                    {{ \Carbon\Carbon::parse($activity->time)->diffForHumans() }}
                                                </p>
                                            </div>
                                        </div>
                                        @endfor
                                        @endforelse
                                    </div>
                                    
                                    <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-600 mt-4">
                                        <div class="text-sm text-gray-500">
                                            <i class="ri-shield-check-line mr-1"></i>
                                            All activities are logged securely
                                        </div>
                                        <button class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                                            View All Activities
                                        </button>
                                    </div>
                                </div>
                            </div>
        
                        </div>
                        <div class="lg:col-span-1">

                            <!-- Interactive Destination Map -->
                            <div class="trezo-card border border-gray-100 dark:border-[#172036] p-[20px] md:p-[25px] rounded-md">
                                <div class="trezo-card-header mb-[20px] md:mb-[25px] flex items-center justify-between">
                                    <div class="trezo-card-title">
                                        <h5 class="mb-0 flex items-center">
                                            <i class="ri-map-2-line text-green-500 mr-2"></i>
                                            Travel Destinations Map
                                        </h5>
                                    </div>
                                    <div class="trezo-card-subtitle">
                                        <div class="trezo-card-dropdown relative">
                                            <button type="button" class="trezo-card-dropdown-btn inline-block transition-all text-sm bg-gray-100 dark:bg-gray-700 px-3 py-1 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600" id="mapViewToggle">
                                                <span class="inline-block relative">
                                                    <i class="ri-global-line mr-1"></i>
                                                    <span id="currentMapView">East Africa</span>
                                                    <i class="ri-arrow-down-s-line ml-1"></i>
                                                </span>
                                            </button>
                                            <ul class="trezo-card-dropdown-menu transition-all bg-white shadow-3xl rounded-md top-full py-[15px] absolute ltr:right-0 rtl:left-0 w-[150px] z-[5] dark:bg-dark dark:shadow-none">
                                                <li>
                                                    <button type="button" class="map-view-btn block w-full transition-all text-black ltr:text-left rtl:text-right relative py-[8px] px-[20px] hover:bg-gray-50 dark:text-white dark:hover:bg-black" data-view="east-africa">
                                                        East Africa
                                                    </button>
                                                </li>
                                                <li>
                                                    <button type="button" class="map-view-btn block w-full transition-all text-black ltr:text-left rtl:text-right relative py-[8px] px-[20px] hover:bg-gray-50 dark:text-white dark:hover:bg-black" data-view="africa">
                                                        Africa
                                                    </button>
                                                </li>
                                                <li>
                                                    <button type="button" class="map-view-btn block w-full transition-all text-black ltr:text-left rtl:text-right relative py-[8px] px-[20px] hover:bg-gray-50 dark:text-white dark:hover:bg-black" data-view="world">
                                                        World
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="trezo-card-content">
                                    <!-- Map Container -->
                                    <div class="relative bg-gradient-to-br from-blue-50 to-green-50 dark:from-blue-900 dark:to-green-900 rounded-lg mb-4" style="height: 220px;">
                                        <div id="destinationMap" class="w-full h-full rounded-lg">
                                            <!-- Interactive Map Placeholder -->
                                            <div class="flex items-center justify-center h-full">
                                                <div class="text-center">
                                                    <div class="relative">
                                                        <div id="eastAfricaMap" class="map-view active">
                                                            <svg viewBox="0 0 400 300" class="w-full h-full">
                                                                <!-- East Africa simplified map -->
                                                                <g id="countries">
                                                                    <!-- Kenya -->
                                                                    <path d="M180 120 L220 110 L230 140 L210 160 L180 150 Z" fill="#3B82F6" fill-opacity="0.7" stroke="#1E40AF" stroke-width="1" class="country-path" data-country="kenya">
                                                                        <title>Kenya - {{ ($topDestinations->where('country', 'Kenya')->first()->booking_count ?? 0) }} bookings</title>
                                                                    </path>
                                                                    <!-- Tanzania -->
                                                                    <path d="M180 150 L210 160 L220 190 L190 200 L170 180 Z" fill="#10B981" fill-opacity="0.7" stroke="#047857" stroke-width="1" class="country-path" data-country="tanzania">
                                                                        <title>Tanzania - {{ ($topDestinations->where('country', 'Tanzania')->first()->booking_count ?? 0) }} bookings</title>
                                                                    </path>
                                                                    <!-- Uganda -->
                                                                    <path d="M150 120 L180 115 L180 140 L160 145 Z" fill="#8B5CF6" fill-opacity="0.7" stroke="#7C3AED" stroke-width="1" class="country-path" data-country="uganda">
                                                                        <title>Uganda - {{ ($topDestinations->where('country', 'Uganda')->first()->booking_count ?? 0) }} bookings</title>
                                                                    </path>
                                                                    <!-- Rwanda -->
                                                                    <path d="M160 145 L175 140 L170 155 L160 155 Z" fill="#F59E0B" fill-opacity="0.7" stroke="#D97706" stroke-width="1" class="country-path" data-country="rwanda">
                                                                        <title>Rwanda - {{ ($topDestinations->where('country', 'Rwanda')->first()->booking_count ?? 0) }} bookings</title>
                                                                    </path>
                                                                </g>
                                                                
                                                                <!-- Destination pins -->
                                                                <g id="destinations">
                                                                    <circle cx="200" cy="130" r="4" fill="#EF4444" class="destination-pin" data-city="nairobi">
                                                                        <title>Nairobi</title>
                                                                    </circle>
                                                                    <circle cx="205" cy="175" r="3" fill="#EF4444" class="destination-pin" data-city="dar-es-salaam">
                                                                        <title>Dar es Salaam</title>
                                                                    </circle>
                                                                    <circle cx="165" cy="130" r="3" fill="#EF4444" class="destination-pin" data-city="kampala">
                                                                        <title>Kampala</title>
                                                                    </circle>
                                                                </g>
                                                            </svg>
                                                        </div>
                                                        
                                                        <div id="africaMap" class="map-view hidden">
                                                            <div class="flex items-center justify-center h-full text-blue-600">
                                                                <div class="text-center">
                                                                    <i class="ri-map-line text-6xl mb-2"></i>
                                                                    <p class="font-medium">Africa Map View</p>
                                                                    <p class="text-sm text-gray-500">Interactive map coming soon</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div id="worldMap" class="map-view hidden">
                                                            <div class="flex items-center justify-center h-full text-green-600">
                                                                <div class="text-center">
                                                                    <i class="ri-earth-line text-6xl mb-2"></i>
                                                                    <p class="font-medium">World Map View</p>
                                                                    <p class="text-sm text-gray-500">Global destinations map</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Map Legend -->
                                        <div class="absolute bottom-3 left-3 bg-white dark:bg-gray-800 rounded-lg p-2 shadow-md">
                                            <div class="flex items-center space-x-3 text-xs">
                                                <div class="flex items-center">
                                                    <div class="w-3 h-3 bg-blue-500 rounded mr-1"></div>
                                                    <span>Visited</span>
                                                </div>
                                                <div class="flex items-center">
                                                    <div class="w-3 h-3 bg-red-500 rounded mr-1"></div>
                                                    <span>Cities</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Destination Stats Table -->
                                    <div class="table-responsive overflow-x-auto without-border">
                                        <table class="w-full">
                                            <thead>
                                                <tr>
                                                    <th class="font-medium text-xs ltr:text-left rtl:text-right px-[14px] pb-[8px] whitespace-nowrap ltr:first:pl-0 rtl:first:pr-0 ltr:last:pr-0 rtl:last:pl-0">
                                                        Destination
                                                    </th>
                                                    <th class="font-medium text-xs ltr:text-right rtl:text-left px-[14px] pb-[8px] whitespace-nowrap ltr:first:pl-0 rtl:first:pr-0 ltr:last:pr-0 rtl:last:pl-0">
                                                        Bookings
                                                    </th>
                                                    <th class="font-medium text-xs ltr:text-right rtl:text-left px-[14px] pb-[8px] whitespace-nowrap ltr:first:pl-0 rtl:first:pr-0 ltr:last:pr-0 rtl:last:pl-0">
                                                        Total Spent
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-black dark:text-white">
                                                @forelse($topDestinations ?? [] as $destination)
                                                    <tr>
                                                        <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[14px] ltr:first:pl-0 rtl:first:pr-0 ltr:last:pr-0 rtl:last:pl-0 py-[11px] border-b border-gray-100 dark:border-[#172036]">
                                                            <div class="flex items-center">
                                                                <div class="w-[22px]">
                                                                    <i class="ri-map-pin-line text-primary-500"></i>
                                                                </div>
                                                                <span class="block font-medium ltr:ml-[10px] rtl:mr-[10px]">
                                                                    {{ $destination->destination_city ?? $destination->destination_name ?? 'Unknown' }}
                                                                </span>
                                                            </div>
                                                        </td>
                                                        <td class="font-medium ltr:text-right rtl:text-left whitespace-nowrap px-[14px] ltr:first:pl-0 rtl:first:pr-0 ltr:last:pr-0 rtl:last:pl-0 py-[11px] border-b border-gray-100 dark:border-[#172036]">
                                                            {{ $destination->booking_count }}
                                                        </td>
                                                        <td class="font-medium ltr:text-right rtl:text-left whitespace-nowrap px-[14px] ltr:first:pl-0 rtl:first:pr-0 ltr:last:pr-0 rtl:last:pl-0 py-[11px] border-b border-gray-100 dark:border-[#172036]">
                                                            ${{ number_format($destination->total_amount, 2) }}
                                                        </td>
                                                    </tr>
                                                @empty
                                                @php
                                                    $sampleDestinations = [
                                                        ['city' => 'Nairobi', 'bookings' => 12, 'amount' => 4560],
                                                        ['city' => 'Dubai', 'bookings' => 8, 'amount' => 6780],
                                                        ['city' => 'Dar es Salaam', 'bookings' => 5, 'amount' => 2340],
                                                        ['city' => 'Kampala', 'bookings' => 3, 'amount' => 1890]
                                                    ];
                                                @endphp
                                                @foreach($sampleDestinations as $destination)
                                                <tr>
                                                    <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[14px] ltr:first:pl-0 rtl:first:pr-0 ltr:last:pr-0 rtl:last:pl-0 py-[11px] border-b border-gray-100 dark:border-[#172036]">
                                                        <div class="flex items-center">
                                                            <div class="w-[22px]">
                                                                <i class="ri-map-pin-line text-primary-500"></i>
                                                            </div>
                                                            <span class="block font-medium ltr:ml-[10px] rtl:mr-[10px]">
                                                                {{ $destination['city'] }}
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td class="font-medium ltr:text-right rtl:text-left whitespace-nowrap px-[14px] ltr:first:pl-0 rtl:first:pr-0 ltr:last:pr-0 rtl:last:pl-0 py-[11px] border-b border-gray-100 dark:border-[#172036]">
                                                        {{ $destination['bookings'] }}
                                                    </td>
                                                    <td class="font-medium ltr:text-right rtl:text-left whitespace-nowrap px-[14px] ltr:first:pl-0 rtl:first:pr-0 ltr:last:pr-0 rtl:last:pl-0 py-[11px] border-b border-gray-100 dark:border-[#172036]">
                                                        ${{ number_format($destination['amount'], 2) }}
                                                    </td>
                                                </tr>
                                                @endforeach
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    
                </div>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-[25px] mb-[25px]">
                <div class="lg:col-span-1">

                    <!-- Transactions History -->
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                        <div class="trezo-card-header mb-[20px] md:mb-[25px] flex items-center justify-between">
                            <div class="trezo-card-title">
                                <h5 class="mb-0">
                                    Transactions History
                                </h5>
                            </div>
                            <div class="trezo-card-subtitle">
                                <div class="trezo-card-dropdown relative">
                                    <button type="button" class="trezo-card-dropdown-btn inline-block transition-all text-[26px] text-gray-500 dark:text-gray-400 leading-none hover:text-primary-500" id="dropdownToggleBtn">
                                        <i class="ri-more-fill"></i>
                                    </button>
                                    <ul class="trezo-card-dropdown-menu transition-all bg-white shadow-3xl rounded-md top-full py-[15px] absolute ltr:right-0 rtl:left-0 w-[195px] z-[5] dark:bg-dark dark:shadow-none">
                                        <li>
                                            <button type="button" class="block w-full transition-all text-black ltr:text-left rtl:text-right relative py-[8px] px-[20px] hover:bg-gray-50 dark:text-white dark:hover:bg-black">
                                                This Day
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="block w-full transition-all text-black ltr:text-left rtl:text-right relative py-[8px] px-[20px] hover:bg-gray-50 dark:text-white dark:hover:bg-black">
                                                This Week
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="block w-full transition-all text-black ltr:text-left rtl:text-right relative py-[8px] px-[20px] hover:bg-gray-50 dark:text-white dark:hover:bg-black">
                                                This Month
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="block w-full transition-all text-black ltr:text-left rtl:text-right relative py-[8px] px-[20px] hover:bg-gray-50 dark:text-white dark:hover:bg-black">
                                                This Year
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="trezo-card-content">
                            <ul>
                                @forelse($recentPayments as $payment)
                                    <li class="flex items-center justify-between mb-[15px] md:mb-[18px] lg:mb-[26px] last:mb-0">
                                        <div class="flex items-center">
                                            <div class="w-[41px] h-[41px] ltr:mr-[12px] rtl:ml-[12px] 
                                                @if($payment->payment_method == 'credit_card') bg-primary-100 dark:bg-[#15203c] text-primary-500
                                                @elseif($payment->payment_method == 'paypal') bg-purple-100 dark:bg-[#15203c] text-purple-500
                                                @elseif($payment->payment_method == 'bank_transfer') bg-success-100 dark:bg-[#15203c] text-success-600
                                                @else bg-secondary-100 dark:bg-[#15203c] text-secondary-500
                                                @endif
                                                rounded-full flex items-center justify-center">
                                                @if($payment->payment_method == 'credit_card')
                                                    <i class="material-symbols-outlined">credit_card</i>
                                                @elseif($payment->payment_method == 'paypal')
                                                    <i class="material-symbols-outlined">account_balance_wallet</i>
                                                @elseif($payment->payment_method == 'bank_transfer')
                                                    <i class="material-symbols-outlined">account_balance</i>
                                                @else
                                                    <i class="material-symbols-outlined">payment</i>
                                                @endif
                                            </div>
                                            <div>
                                                <span class="block font-medium text-black dark:text-white text-base md:text-[16px] mb-[3px] md:mb-px">
                                                    {{ ucfirst(str_replace('_', ' ', $payment->payment_method ?? 'Payment')) }}
                                                </span>
                                                <span class="block text-sm">
                                                    {{ $payment->created_at->format('d M Y - g:i a') }}
                                                </span>
                                            </div>
                                        </div>
                                        <span class="block 
                                            @if($payment->status == 'completed') text-success-600
                                            @elseif($payment->status == 'pending') text-warning-500
                                            @elseif($payment->status == 'failed') text-danger-500
                                            @else text-gray-500
                                            @endif">
                                            ${{ number_format($payment->amount, 2) }}
                                        </span>
                                    </li>
                                @empty
                                    <li class="text-center py-4 text-gray-500">
                                        No recent transactions found
                                    </li>
                                @endforelse
                            </ul>
                        </div>
                    </div>



                </div>
                <div class="lg:col-span-2">

                    <!-- Recent Bookings -->
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                        <div class="trezo-card-header mb-[20px] md:mb-[25px] sm:flex sm:items-center sm:justify-between">
                            <div class="trezo-card-title">
                                <h5 class="mb-0">
                                    Recent Bookings
                                </h5>
                            </div>
                            <div class="trezo-card-subtitle sm:flex sm:items-center">
                                <form class="relative sm:w-[240px] ltr:sm:mr-[20px] rtl:sm:ml-[20px] my-[13px] sm:my-0">
                                    <label class="leading-none absolute ltr:left-[13px] rtl:right-[13px] text-black dark:text-white mt-px top-1/2 -translate-y-1/2">
                                        <i class="material-symbols-outlined !text-[20px]">
                                            search
                                        </i>
                                    </label>
                                    <input type="text" placeholder="Search bookings..." class="bg-gray-50 border border-gray-50 h-[36px] text-xs rounded-md w-full block text-black pt-[11px] pb-[12px] ltr:pl-[38px] rtl:pr-[38px] ltr:pr-[13px] ltr:md:pr-[16px] rtl:pl-[13px] rtl:md:pl-[16px] placeholder:text-gray-500 outline-0 dark:bg-[#15203c] dark:text-white dark:border-[#15203c] dark:placeholder:text-gray-400">
                                </form>
                                <div class="trezo-card-dropdown relative">
                                    <button type="button" class="trezo-card-dropdown-btn inline-block rounded-md border border-gray-100 py-[5px] md:py-[6.5px] px-[12px] md:px-[19px] transition-all hover:bg-gray-50 dark:border-[#172036] dark:hover:bg-[#0a0e19]" id="dropdownToggleBtn">
                                        <span class="inline-block relative ltr:pr-[17px] ltr:md:pr-[20px] rtl:pl-[17px] rtl:ml:pr-[20px]">
                                            All Types
                                            <i class="ri-arrow-down-s-line text-lg absolute ltr:-right-[3px] rtl:-left-[3px] top-1/2 -translate-y-1/2"></i>
                                        </span>
                                    </button>
                                    <ul class="trezo-card-dropdown-menu transition-all bg-white shadow-3xl rounded-md top-full py-[15px] absolute ltr:left-0 sm:ltr:right-0 rtl:right-0 sm:rtl:left-0 w-[195px] z-[5] dark:bg-dark dark:shadow-none">
                                        <li>
                                            <button type="button" class="block w-full transition-all text-black ltr:text-left rtl:text-right relative py-[8px] px-[20px] hover:bg-gray-50 dark:text-white dark:hover:bg-black">
                                                All Types
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="block w-full transition-all text-black ltr:text-left rtl:text-right relative py-[8px] px-[20px] hover:bg-gray-50 dark:text-white dark:hover:bg-black">
                                                Flights
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="block w-full transition-all text-black ltr:text-left rtl:text-right relative py-[8px] px-[20px] hover:bg-gray-50 dark:text-white dark:hover:bg-black">
                                                Hotels
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="block w-full transition-all text-black ltr:text-left rtl:text-right relative py-[8px] px-[20px] hover:bg-gray-50 dark:text-white dark:hover:bg-black">
                                                Tours
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="trezo-card-content">
                            <div class="table-responsive overflow-x-auto">
                                <table class="w-full">
                                    <thead class="text-black dark:text-white">
                                        <tr>
                                            <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap first:rounded-tl-md">
                                                Booking Ref
                                            </th>
                                            <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap">
                                                Type
                                            </th>
                                            <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap">
                                                Details
                                            </th>
                                            <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap">
                                                Date
                                            </th>
                                            <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap">
                                                Price
                                            </th>
                                            <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap">
                                                Status
                                            </th>
                                            <th class="font-medium text-center px-[20px] py-[11px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap first:rounded-tr-md">
                                                Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-black dark:text-white">
                                        @forelse($recentBookings ?? [] as $booking)
                                        <tr>
                                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                                <span class="font-medium">{{ $booking->booking_reference }}</span>
                                            </td>
                                            <td class="ltr:text-left rtl:text-right px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036]">
                                                <span class="px-[8px] py-[3px] inline-block 
                                                    @if($booking->type == 'flight') bg-blue-100 dark:bg-[#15203c] text-blue-600
                                                    @elseif($booking->type == 'hotel') bg-green-100 dark:bg-[#15203c] text-green-600
                                                    @elseif($booking->type == 'tour') bg-purple-100 dark:bg-[#15203c] text-purple-600
                                                    @else bg-gray-100 dark:bg-[#15203c] text-gray-600
                                                    @endif
                                                    rounded-sm font-medium text-xs">
                                                    {{ ucfirst($booking->type ?? 'N/A') }}
                                                </span>
                                            </td>
                                            <td class="ltr:text-left rtl:text-right px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036]">
                                                <div>
                                                    @if($booking->type == 'flight')
                                                        <span class="block font-medium">
                                                            {{ $booking->departure_city ?? 'N/A' }} → {{ $booking->arrival_city ?? 'N/A' }}
                                                        </span>
                                                        <span class="text-sm text-gray-500">
                                                            {{ $booking->airline ?? 'N/A' }}
                                                        </span>
                                                    @elseif($booking->type == 'hotel')
                                                        <span class="block font-medium">
                                                            {{ $booking->hotel_name ?? 'N/A' }}
                                                        </span>
                                                        <span class="text-sm text-gray-500">
                                                            {{ $booking->destination ?? 'N/A' }}
                                                        </span>
                                                    @elseif($booking->type == 'tour')
                                                        <span class="block font-medium">
                                                            {{ $booking->tour_name ?? 'N/A' }}
                                                        </span>
                                                        <span class="text-sm text-gray-500">
                                                            {{ $booking->destination ?? 'N/A' }}
                                                        </span>
                                                    @else
                                                        <span class="block font-medium">{{ $booking->title ?? 'N/A' }}</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036]">
                                                <span>{{ $booking->created_at->format('d M Y') }}</span>
                                            </td>
                                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036]">
                                                <span class="font-medium">${{ number_format($booking->total_amount ?? 0, 2) }}</span>
                                            </td>
                                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036]">
                                                <span class="px-[8px] py-[3px] inline-block 
                                                    @if($booking->status == 'confirmed') bg-success-100 dark:bg-[#15203c] text-success-600
                                                    @elseif($booking->status == 'processing') bg-warning-50 dark:bg-[#15203c] text-warning-500
                                                    @elseif($booking->status == 'cancelled') bg-red-50 dark:bg-[#15203c] text-red-500
                                                    @else bg-primary-50 dark:bg-[#15203c] text-primary-500
                                                    @endif
                                                    rounded-sm font-medium text-xs">
                                                    {{ ucfirst($booking->status ?? 'Unknown') }}
                                                </span>
                                            </td>
                                            <td class="text-center whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036]">
                                                <div class="flex justify-center space-x-2">
                                                    <a href="{{ route('bookings.show', $booking->id) }}" class="bg-primary-50 dark:bg-[#15203c] text-primary-500 p-1.5 rounded-md hover:bg-primary-500 hover:text-white transition-all">
                                                        <i class="ri-eye-line"></i>
                                                    </a>
                                                    <a href="{{ route('bookings.download', $booking->id) }}" class="bg-secondary-50 dark:bg-[#15203c] text-secondary-500 p-1.5 rounded-md hover:bg-secondary-500 hover:text-white transition-all">
                                                        <i class="ri-download-line"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-[20px] text-gray-500">
                                                No bookings found. Start exploring our deals!
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                             <div class="px-[20px] py-[12px] md:py-[14px] rounded-b-md border-l border-r border-b border-gray-100 dark:border-[#172036] sm:flex sm:items-center justify-between">
                                <div class="flex items-center">
                                    <p class="mb-0 text-sm">
                                        @if(isset($recentBookings) && $recentBookings->count() > 0)
                                            Showing {{ $recentBookings->count() }} recent bookings
                                        @else
                                            No bookings to display
                                        @endif
                                    </p>
                                    <a href="{{ route('user.bookings.all') }}" class="ml-4 text-primary-500 hover:underline text-sm">View all bookings</a>
                                </div>
                                <div class="mt-[10px] sm:mt-0">
                                    <a href="{{ route('user.bookings.all') }}" class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-all">
                                        <i class="ri-arrow-right-line ml-1"></i>
                                        View All
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-[25px] mb-[25px]">

                <!-- Recent Activity -->
                <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                    <div class="trezo-card-header mb-[20px] md:mb-[25px] flex items-center justify-between">
                        <div class="trezo-card-title">
                            <h5 class="mb-0">
                                Recent Activity
                            </h5>
                        </div>
                        <div class="trezo-card-subtitle">
                            <div class="trezo-card-dropdown relative">
                                <button type="button" class="trezo-card-dropdown-btn inline-block transition-all hover:text-primary-500" id="dropdownToggleBtn">
                                    <span class="inline-block relative ltr:pr-[17px] ltr:md:pr-[20px] rtl:pl-[17px] rtl:ml:pr-[20px]">
                                        This Week
                                        <i class="ri-arrow-down-s-line text-lg absolute ltr:-right-[3px] rtl:-left-[3px] top-1/2 -translate-y-1/2"></i>
                                    </span>
                                </button>
                                <ul class="trezo-card-dropdown-menu transition-all bg-white shadow-3xl rounded-md top-full py-[15px] absolute ltr:right-0 rtl:left-0 w-[195px] z-[5] dark:bg-dark dark:shadow-none">
                                    <li>
                                        <button type="button" class="block w-full transition-all text-black ltr:text-left rtl:text-right relative py-[8px] px-[20px] hover:bg-gray-50 dark:text-white dark:hover:bg-black">
                                            This Day
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="block w-full transition-all text-black ltr:text-left rtl:text-right relative py-[8px] px-[20px] hover:bg-gray-50 dark:text-white dark:hover:bg-black">
                                            This Week
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="block w-full transition-all text-black ltr:text-left rtl:text-right relative py-[8px] px-[20px] hover:bg-gray-50 dark:text-white dark:hover:bg-black">
                                            This Month
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="block w-full transition-all text-black ltr:text-left rtl:text-right relative py-[8px] px-[20px] hover:bg-gray-50 dark:text-white dark:hover:bg-black">
                                            This Year
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                        <div class="trezo-card-content">
                        <div class="space-y-4">
                            @forelse($recentActivities ?? [] as $activity)
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-[#15203c] rounded-md">
                                    <div class="flex items-center">
                                        <div class="w-[35px] h-[35px] ltr:mr-[12px] rtl:ml-[12px] 
                                            @if($activity->action == 'booking_created') bg-blue-100 dark:bg-[#15203c] text-blue-600
                                            @elseif($activity->action == 'payment_made') bg-green-100 dark:bg-[#15203c] text-green-600
                                            @elseif($activity->action == 'search_performed') bg-purple-100 dark:bg-[#15203c] text-purple-600
                                            @else bg-gray-100 dark:bg-[#15203c] text-gray-600
                                            @endif
                                            rounded-full flex items-center justify-center">
                                            @if($activity->action == 'booking_created')
                                                <i class="ri-calendar-check-line"></i>
                                            @elseif($activity->action == 'payment_made')
                                                <i class="ri-money-dollar-circle-line"></i>
                                            @elseif($activity->action == 'search_performed')
                                                <i class="ri-search-line"></i>
                                            @else
                                                <i class="ri-user-line"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <span class="block font-medium text-sm">
                                                {{ $activity->description ?? ucfirst(str_replace('_', ' ', $activity->action)) }}
                                            </span>
                                            <span class="text-xs text-gray-500">
                                                {{ $activity->created_at ? $activity->created_at->diffForHumans() : 'Recently' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-6 text-gray-500">
                                    <i class="ri-history-line text-2xl mb-2"></i>
                                    <p>No recent activity found</p>
                                </div>
                            @endforelse
                        </div>
                        <div class="flex justify-between pt-[28px] border-t border-gray-100 dark:border-[#172036]">
                            <div>
                                <span class="block text-xs">
                                    Total Searches
                                </span>
                                <h6 class="mb-0 font-medium text-lg mt-[5px]">
                                    {{ $stats['total_searches'] ?? 0 }}
                                    <span class="text-success-600 text-xs ltr:pl-[16px] rtl:pr-[16px] relative">
                                        <i class="ri-arrow-up-fill ltr:left-0 rtl:right-0 absolute text-md top-1/2 -translate-y-1/2"></i>
                                        Last 7 days
                                    </span>
                                </h6>
                            </div>
                            <div>
                                <span class="block text-xs">
                                    Profile Completion
                                </span>
                                <h6 class="mb-0 font-medium text-lg mt-[5px]">
                                    {{ $stats['profile_completion'] ?? 0 }}%
                                    <span class="text-primary-500 text-xs ltr:pl-[16px] rtl:pr-[16px] relative">
                                        <i class="ri-user-settings-line ltr:left-0 rtl:right-0 absolute text-md top-1/2 -translate-y-1/2"></i>
                                    </span>
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Travel Stats Overview -->
                <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                    <div class="trezo-card-header mb-[20px] md:mb-[25px] flex items-center justify-between">
                        <div class="trezo-card-title">
                            <h5 class="mb-0">
                                Travel Stats Overview
                            </h5>
                        </div>
                        <div class="trezo-card-subtitle">
                            <div class="trezo-card-dropdown relative">
                                <button type="button" class="trezo-card-dropdown-btn inline-block transition-all hover:text-primary-500" id="dropdownToggleBtn">
                                    <span class="inline-block relative ltr:pr-[17px] ltr:md:pr-[20px] rtl:pl-[17px] rtl:ml:pr-[20px]">
                                        All Time
                                        <i class="ri-arrow-down-s-line text-lg absolute ltr:-right-[3px] rtl:-left-[3px] top-1/2 -translate-y-1/2"></i>
                                    </span>
                                </button>
                                <ul class="trezo-card-dropdown-menu transition-all bg-white shadow-3xl rounded-md top-full py-[15px] absolute ltr:right-0 rtl:left-0 w-[195px] z-[5] dark:bg-dark dark:shadow-none">
                                    <li>
                                        <button type="button" class="block w-full transition-all text-black ltr:text-left rtl:text-right relative py-[8px] px-[20px] hover:bg-gray-50 dark:text-white dark:hover:bg-black">
                                            This Day
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="block w-full transition-all text-black ltr:text-left rtl:text-right relative py-[8px] px-[20px] hover:bg-gray-50 dark:text-white dark:hover:bg-black">
                                            This Week
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="block w-full transition-all text-black ltr:text-left rtl:text-right relative py-[8px] px-[20px] hover:bg-gray-50 dark:text-white dark:hover:bg-black">
                                            This Month
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="block w-full transition-all text-black ltr:text-left rtl:text-right relative py-[8px] px-[20px] hover:bg-gray-50 dark:text-white dark:hover:bg-black">
                                            This Year
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="trezo-card-content">
                        <div class="-mt-[13px] -mb-[40px]">
                            <div id="salesOverviewChart"></div>
                        </div>
                    </div>
                </div>

                <!-- Wishlist & Favorites -->
                <div class="trezo-card border border-purple-200 dark:border-purple-800 p-[20px] md:p-[25px] rounded-xl bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900 dark:to-purple-800">
                    <div class="trezo-card-header mb-[20px] flex items-center justify-between">
                        <div class="trezo-card-title">
                            <h5 class="mb-0 text-lg font-semibold text-gray-800 dark:text-white flex items-center">
                                <i class="ri-heart-line text-purple-500 mr-2"></i>
                                Wishlist & Favorites
                            </h5>
                        </div>
                        <button class="bg-purple-500 text-white text-xs px-3 py-1 rounded-full hover:bg-purple-600 transition-colors">
                            <i class="ri-add-line mr-1"></i>Add New
                        </button>
                    </div>
                    <div class="trezo-card-content">
                        <div class="space-y-4">
                            @php
                                $wishlistItems = [
                                    ['type' => 'flight', 'route' => 'Nairobi → Dubai', 'price' => '$450', 'status' => 'tracking'],
                                    ['type' => 'hotel', 'name' => 'Burj Al Arab', 'location' => 'Dubai', 'price' => '$800/night', 'status' => 'available'],
                                    ['type' => 'destination', 'name' => 'Maldives', 'type_label' => 'Dream Destination', 'status' => 'planning']
                                ];
                            @endphp
                            
                            @forelse($wishlistItems as $item)
                            <div class="flex items-center justify-between p-3 bg-white dark:bg-gray-700 rounded-lg border border-purple-200 dark:border-purple-700">
                                <div class="flex items-center space-x-3">
                                    @if($item['type'] === 'flight')
                                        <div class="bg-blue-100 dark:bg-blue-900 p-2 rounded-full">
                                            <i class="ri-plane-line text-blue-600"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800 dark:text-white">{{ $item['route'] }}</p>
                                            <p class="text-sm text-gray-500">Flight • {{ $item['price'] }}</p>
                                        </div>
                                    @elseif($item['type'] === 'hotel')
                                        <div class="bg-green-100 dark:bg-green-900 p-2 rounded-full">
                                            <i class="ri-hotel-line text-green-600"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800 dark:text-white">{{ $item['name'] }}</p>
                                            <p class="text-sm text-gray-500">{{ $item['location'] }} • {{ $item['price'] }}</p>
                                        </div>
                                    @else
                                        <div class="bg-orange-100 dark:bg-orange-900 p-2 rounded-full">
                                            <i class="ri-map-pin-line text-orange-600"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800 dark:text-white">{{ $item['name'] }}</p>
                                            <p class="text-sm text-gray-500">{{ $item['type_label'] }}</p>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex items-center space-x-2">
                                    @if($item['status'] === 'tracking')
                                        <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">Tracking</span>
                                    @elseif($item['status'] === 'available')
                                        <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">Available</span>
                                    @else
                                        <span class="text-xs bg-orange-100 text-orange-800 px-2 py-1 rounded-full">Planning</span>
                                    @endif
                                    <button class="text-gray-400 hover:text-red-500 transition-colors">
                                        <i class="ri-close-line"></i>
                                    </button>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-8">
                                <i class="ri-heart-3-line text-4xl text-gray-300 dark:text-gray-600 mb-3"></i>
                                <p class="text-gray-500 dark:text-gray-400">No items in your wishlist yet</p>
                                <button class="mt-2 text-purple-500 hover:text-purple-600 text-sm font-medium">
                                    Start adding your favorites
                                </button>
                            </div>
                            @endforelse
                        </div>
                        
                        <div class="flex justify-between items-center pt-4 border-t border-purple-200 dark:border-purple-700 mt-4">
                            <div class="text-center">
                                <span class="block text-xs text-gray-500 dark:text-gray-400">Saved Flights</span>
                                <h6 class="mb-0 font-medium text-lg text-purple-600">{{ count(array_filter($wishlistItems, fn($item) => $item['type'] === 'flight')) }}</h6>
                            </div>
                            <div class="text-center">
                                <span class="block text-xs text-gray-500 dark:text-gray-400">Saved Hotels</span>
                                <h6 class="mb-0 font-medium text-lg text-purple-600">{{ count(array_filter($wishlistItems, fn($item) => $item['type'] === 'hotel')) }}</h6>
                            </div>
                            <div class="text-center">
                                <span class="block text-xs text-gray-500 dark:text-gray-400">Dream Destinations</span>
                                <h6 class="mb-0 font-medium text-lg text-purple-600">{{ count(array_filter($wishlistItems, fn($item) => $item['type'] === 'destination')) }}</h6>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Creative Controller Feature Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-[25px] mb-[25px]">
                
                <!-- Travel Insights Card -->
                <div class="trezo-card border border-blue-200 dark:border-blue-800 p-[20px] md:p-[25px] rounded-xl bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900 dark:to-blue-800 hover:shadow-lg transition-all cursor-pointer" onclick="window.location.href='{{ route('user.dashboard.travel.insights') }}'">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-blue-500 text-white rounded-full p-3">
                            <i class="ri-compass-3-line text-xl"></i>
                        </div>
                        <span class="text-blue-600 dark:text-blue-300 text-xs font-medium bg-blue-200 dark:bg-blue-700 px-2 py-1 rounded-full">INSIGHTS</span>
                    </div>
                    <h5 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Travel Insights</h5>
                    <p class="text-gray-600 dark:text-gray-300 text-sm mb-3">Discover personalized travel patterns and recommendations based on your booking history.</p>
                    <div class="flex items-center text-blue-600 dark:text-blue-300 text-sm font-medium">
                        <span>View Insights</span>
                        <i class="ri-arrow-right-line ml-2"></i>
                    </div>
                </div>

                <!-- Weather Destinations Card -->
                <div class="trezo-card border border-orange-200 dark:border-orange-800 p-[20px] md:p-[25px] rounded-xl bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900 dark:to-orange-800 hover:shadow-lg transition-all cursor-pointer" onclick="window.location.href='/dashboard/weather'">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-orange-500 text-white rounded-full p-3">
                            <i class="ri-sun-line text-xl"></i>
                        </div>
                        <span class="text-orange-600 dark:text-orange-300 text-xs font-medium bg-orange-200 dark:bg-orange-700 px-2 py-1 rounded-full">WEATHER</span>
                    </div>
                    <h5 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Destination Weather</h5>
                    <p class="text-gray-600 dark:text-gray-300 text-sm mb-3">Check current weather conditions for your favorite destinations and plan accordingly.</p>
                    <div class="flex items-center text-orange-600 dark:text-orange-300 text-sm font-medium">
                        <span>Check Weather</span>
                        <i class="ri-arrow-right-line ml-2"></i>
                    </div>
                </div>

                <!-- Amadeus Recommendations Card -->
                <div class="trezo-card border border-purple-200 dark:border-purple-800 p-[20px] md:p-[25px] rounded-xl bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900 dark:to-purple-800 hover:shadow-lg transition-all cursor-pointer" onclick="window.location.href='/dashboard/recommendations'">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-purple-500 text-white rounded-full p-3">
                            <i class="ri-star-line text-xl"></i>
                        </div>
                        <span class="text-purple-600 dark:text-purple-300 text-xs font-medium bg-purple-200 dark:bg-purple-700 px-2 py-1 rounded-full">AMADEUS</span>
                    </div>
                    <h5 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Smart Recommendations</h5>
                    <p class="text-gray-600 dark:text-gray-300 text-sm mb-3">AI-powered flight and hotel recommendations tailored to your preferences.</p>
                    <div class="flex items-center text-purple-600 dark:text-purple-300 text-sm font-medium">
                        <span>Explore Now</span>
                        <i class="ri-arrow-right-line ml-2"></i>
                    </div>
                </div>

                <!-- Flight Alerts Card -->
                <div class="trezo-card border border-green-200 dark:border-green-800 p-[20px] md:p-[25px] rounded-xl bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900 dark:to-green-800 hover:shadow-lg transition-all cursor-pointer" onclick="window.location.href='/dashboard/flight-alerts'">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-green-500 text-white rounded-full p-3">
                            <i class="ri-notification-3-line text-xl"></i>
                        </div>
                        <span class="text-green-600 dark:text-green-300 text-xs font-medium bg-green-200 dark:bg-green-700 px-2 py-1 rounded-full">ALERTS</span>
                    </div>
                    <h5 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Flight Alerts</h5>
                    <p class="text-gray-600 dark:text-gray-300 text-sm mb-3">Get notified about price drops and special deals for your watched routes.</p>
                    <div class="flex items-center text-green-600 dark:text-green-300 text-sm font-medium">
                        <span>Manage Alerts</span>
                        <i class="ri-arrow-right-line ml-2"></i>
                    </div>
                </div>

                <!-- Loyalty Program Card -->
                <div class="trezo-card border border-yellow-200 dark:border-yellow-800 p-[20px] md:p-[25px] rounded-xl bg-gradient-to-br from-yellow-50 to-yellow-100 dark:from-yellow-900 dark:to-yellow-800 hover:shadow-lg transition-all cursor-pointer" onclick="window.location.href='/dashboard/loyalty'">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-yellow-500 text-white rounded-full p-3">
                            <i class="ri-medal-line text-xl"></i>
                        </div>
                        <span class="text-yellow-600 dark:text-yellow-300 text-xs font-medium bg-yellow-200 dark:bg-yellow-700 px-2 py-1 rounded-full">REWARDS</span>
                    </div>
                    <h5 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Loyalty Program</h5>
                    <p class="text-gray-600 dark:text-gray-300 text-sm mb-3">Track your points, tier status, and unlock exclusive travel benefits.</p>
                    <div class="flex items-center text-yellow-600 dark:text-yellow-300 text-sm font-medium">
                        <span>View Rewards</span>
                        <i class="ri-arrow-right-line ml-2"></i>
                    </div>
                </div>

                <!-- Travel Calendar Card -->
                <div class="trezo-card border border-teal-200 dark:border-teal-800 p-[20px] md:p-[25px] rounded-xl bg-gradient-to-br from-teal-50 to-teal-100 dark:from-teal-900 dark:to-teal-800 hover:shadow-lg transition-all cursor-pointer" onclick="window.location.href='/dashboard/calendar'">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-teal-500 text-white rounded-full p-3">
                            <i class="ri-calendar-check-line text-xl"></i>
                        </div>
                        <span class="text-teal-600 dark:text-teal-300 text-xs font-medium bg-teal-200 dark:bg-teal-700 px-2 py-1 rounded-full">CALENDAR</span>
                    </div>
                    <h5 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Travel Calendar</h5>
                    <p class="text-gray-600 dark:text-gray-300 text-sm mb-3">Plan and organize your upcoming trips with our smart calendar interface.</p>
                    <div class="flex items-center text-teal-600 dark:text-teal-300 text-sm font-medium">
                        <span>Open Calendar</span>
                        <i class="ri-arrow-right-line ml-2"></i>
                    </div>
                </div>

            </div>

            <!-- Additional Feature Cards Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-[25px] mb-[25px]">
                
                <!-- Budget Tracker Card -->
                <div class="trezo-card border border-red-200 dark:border-red-800 p-[20px] md:p-[25px] rounded-xl bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900 dark:to-red-800 hover:shadow-lg transition-all cursor-pointer" onclick="window.location.href='/dashboard/budget'">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-red-500 text-white rounded-full p-3">
                            <i class="ri-pie-chart-line text-xl"></i>
                        </div>
                        <span class="text-red-600 dark:text-red-300 text-xs font-medium bg-red-200 dark:bg-red-700 px-2 py-1 rounded-full">BUDGET</span>
                    </div>
                    <h5 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Budget Tracker</h5>
                    <p class="text-gray-600 dark:text-gray-300 text-sm mb-3">Monitor and manage your travel spending with smart budget insights.</p>
                    <div class="flex items-center text-red-600 dark:text-red-300 text-sm font-medium">
                        <span>Track Budget</span>
                        <i class="ri-arrow-right-line ml-2"></i>
                    </div>
                </div>

                <!-- Saved Searches Card -->
                <div class="trezo-card border border-indigo-200 dark:border-indigo-800 p-[20px] md:p-[25px] rounded-xl bg-gradient-to-br from-indigo-50 to-indigo-100 dark:from-indigo-900 dark:to-indigo-800 hover:shadow-lg transition-all cursor-pointer" onclick="window.location.href='/dashboard/saved-searches'">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-indigo-500 text-white rounded-full p-3">
                            <i class="ri-bookmark-line text-xl"></i>
                        </div>
                        <span class="text-indigo-600 dark:text-indigo-300 text-xs font-medium bg-indigo-200 dark:bg-indigo-700 px-2 py-1 rounded-full">SAVED</span>
                    </div>
                    <h5 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Saved Searches</h5>
                    <p class="text-gray-600 dark:text-gray-300 text-sm mb-3">Quick access to your frequently searched routes and preferences.</p>
                    <div class="flex items-center text-indigo-600 dark:text-indigo-300 text-sm font-medium">
                        <span>View Saved</span>
                        <i class="ri-arrow-right-line ml-2"></i>
                    </div>
                </div>

                <!-- Trip Planner Card -->
                <div class="trezo-card border border-pink-200 dark:border-pink-800 p-[20px] md:p-[25px] rounded-xl bg-gradient-to-br from-pink-50 to-pink-100 dark:from-pink-900 dark:to-pink-800 hover:shadow-lg transition-all cursor-pointer" onclick="window.location.href='/dashboard/trip-planner'">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-pink-500 text-white rounded-full p-3">
                            <i class="ri-route-line text-xl"></i>
                        </div>
                        <span class="text-pink-600 dark:text-pink-300 text-xs font-medium bg-pink-200 dark:bg-pink-700 px-2 py-1 rounded-full">PLANNER</span>
                    </div>
                    <h5 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Trip Planner</h5>
                    <p class="text-gray-600 dark:text-gray-300 text-sm mb-3">Plan multi-destination trips with our intelligent itinerary builder.</p>
                    <div class="flex items-center text-pink-600 dark:text-pink-300 text-sm font-medium">
                        <span>Plan Trip</span>
                        <i class="ri-arrow-right-line ml-2"></i>
                    </div>
                </div>

                <!-- Profile & Settings Card -->
                <div class="trezo-card border border-gray-200 dark:border-gray-700 p-[20px] md:p-[25px] rounded-xl bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-700 hover:shadow-lg transition-all cursor-pointer" onclick="window.location.href='/dashboard/profile'">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-gray-500 text-white rounded-full p-3">
                            <i class="ri-user-settings-line text-xl"></i>
                        </div>
                        <span class="text-gray-600 dark:text-gray-300 text-xs font-medium bg-gray-200 dark:bg-gray-600 px-2 py-1 rounded-full">PROFILE</span>
                    </div>
                    <h5 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Profile & Settings</h5>
                    <p class="text-gray-600 dark:text-gray-300 text-sm mb-3">Manage your account preferences and travel settings.</p>
                    <div class="flex items-center text-gray-600 dark:text-gray-300 text-sm font-medium">
                        <span>Manage Profile</span>
                        <i class="ri-arrow-right-line ml-2"></i>
                    </div>
                </div>

            </div>
            
            <div class="grow"></div>

            <!-- Footer -->
            <footer class="bg-white dark:bg-[#0c1427] rounded-t-md px-[20px] md:px-[25px] py-[15px] md:py-[20px] text-center">
                <p>
                    © <span class="text-purple-500">Trezo</span> is Proudly Owned by <a href="https://envytheme.com/" target="_blank" class="text-primary-500 transition-all hover:underline">EnvyTheme</a>
                </p>
            </footer>
            
        </div>
        <!-- End Main Content -->
@endsection

@section('scripts')
<script>
    // Add any custom JavaScript for user dashboard here
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize dashboard functionality
        console.log('User dashboard loaded');
        
        // Set up real-time updates
        setupUserDashboardUpdates();
        
        // Initialize new features
        initializeMapToggle();
        initializeWishlistFeatures();
        initializeActivityDropdowns();
    });
    
    // Map toggle functionality
    function initializeMapToggle() {
        const mapViewButtons = document.querySelectorAll('.map-view-btn');
        const currentMapViewSpan = document.getElementById('currentMapView');
        const mapViews = {
            'east-africa': document.getElementById('eastAfricaMap'),
            'africa': document.getElementById('africaMap'),
            'world': document.getElementById('worldMap')
        };
        
        mapViewButtons.forEach(button => {
            button.addEventListener('click', function() {
                const viewType = this.getAttribute('data-view');
                
                // Hide all map views
                Object.values(mapViews).forEach(view => {
                    if (view) {
                        view.classList.add('hidden');
                        view.classList.remove('active');
                    }
                });
                
                // Show selected map view
                if (mapViews[viewType]) {
                    mapViews[viewType].classList.remove('hidden');
                    mapViews[viewType].classList.add('active');
                }
                
                // Update button text
                if (currentMapViewSpan) {
                    currentMapViewSpan.textContent = this.textContent;
                }
                
                // Close dropdown
                const dropdown = this.closest('.trezo-card-dropdown-menu');
                if (dropdown) {
                    dropdown.style.display = 'none';
                }
            });
        });
        
        // Map interaction
        const countryPaths = document.querySelectorAll('.country-path');
        const destinationPins = document.querySelectorAll('.destination-pin');
        
        countryPaths.forEach(path => {
            path.addEventListener('mouseenter', function() {
                this.style.fillOpacity = '0.9';
                this.style.cursor = 'pointer';
            });
            
            path.addEventListener('mouseleave', function() {
                this.style.fillOpacity = '0.7';
            });
            
            path.addEventListener('click', function() {
                const country = this.getAttribute('data-country');
                console.log('Clicked country:', country);
                // Add country-specific functionality here
            });
        });
        
        destinationPins.forEach(pin => {
            pin.addEventListener('mouseenter', function() {
                this.style.r = '6';
                this.style.cursor = 'pointer';
            });
            
            pin.addEventListener('mouseleave', function() {
                this.style.r = this.getAttribute('r');
            });
            
            pin.addEventListener('click', function() {
                const city = this.getAttribute('data-city');
                console.log('Clicked city:', city);
                // Add city-specific functionality here
            });
        });
    }
    
    // Wishlist functionality
    function initializeWishlistFeatures() {
        // Add to wishlist functionality
        const addWishlistBtn = document.querySelector('.wishlist-add-btn');
        if (addWishlistBtn) {
            addWishlistBtn.addEventListener('click', function() {
                // Open add to wishlist modal or form
                console.log('Add to wishlist clicked');
            });
        }
        
        // Remove from wishlist
        const removeButtons = document.querySelectorAll('.wishlist-remove-btn');
        removeButtons.forEach(button => {
            button.addEventListener('click', function() {
                const item = this.closest('.wishlist-item');
                if (item && confirm('Remove this item from your wishlist?')) {
                    item.style.transition = 'all 0.3s ease';
                    item.style.opacity = '0';
                    item.style.transform = 'translateX(-100%)';
                    setTimeout(() => {
                        item.remove();
                    }, 300);
                }
            });
        });
    }
    
    // Activity dropdown functionality
    function initializeActivityDropdowns() {
        const activityDropdownBtn = document.getElementById('activityDropdownBtn');
        if (activityDropdownBtn) {
            activityDropdownBtn.addEventListener('click', function() {
                const dropdown = this.nextElementSibling;
                if (dropdown) {
                    dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
                }
            });
        }
        
        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            const dropdowns = document.querySelectorAll('.trezo-card-dropdown-menu');
            dropdowns.forEach(dropdown => {
                if (!dropdown.closest('.trezo-card-dropdown').contains(event.target)) {
                    dropdown.style.display = 'none';
                }
            });
        });
    }
    
    function setupUserDashboardUpdates() {
        // Refresh stats periodically
        setInterval(refreshUserStats, 30000); // Every 30 seconds
        
        // Set up real-time updates via Pusher if available
        if (typeof window.Echo !== 'undefined') {
            const userId = "{{ auth()->id() }}";
            window.Echo.private('dashboard.user.' + userId)
                .listen('booking.updated', (e) => {
                    console.log('Booking updated event received', e);
                    refreshUserStats();
                });
        }
    }
    
    function refreshUserStats() {
        // Fetch latest user stats
        fetch('/dashboard/stats')
            .then(response => response.json())
            .then(data => {
                // Update booking stats
                if (data.booking_stats) {
                    updateUserBookingStats(data.booking_stats);
                }
                
                // Update payment stats
                if (data.payment_stats) {
                    updateUserPaymentStats(data.payment_stats);
                }
                
                // Update travel stats
                if (data.travel_stats) {
                    updateUserTravelStats(data.travel_stats);
                }
            })
            .catch(error => {
                console.error('Error refreshing user stats:', error);
            });
    }
    
    function updateUserBookingStats(stats) {
        // Update the UI with new booking stats
        const elements = {
            'all': document.querySelector('[data-user-stat="bookings-all"]'),
            'confirmed': document.querySelector('[data-user-stat="bookings-confirmed"]'),
            'cancelled': document.querySelector('[data-user-stat="bookings-cancelled"]'),
            'processing': document.querySelector('[data-user-stat="bookings-processing"]'),
            'flights': document.querySelector('[data-user-stat="bookings-flights"]'),
            'hotels': document.querySelector('[data-user-stat="bookings-hotels"]'),
            'tours': document.querySelector('[data-user-stat="bookings-tours"]'),
            'destinations': document.querySelector('[data-user-stat="bookings-destinations"]')
        };
        
        Object.keys(elements).forEach(key => {
            if (elements[key] && stats[key] !== undefined) {
                elements[key].textContent = stats[key];
                
                // Add a highlight effect for changes
                elements[key].classList.add('animate-pulse');
                setTimeout(() => {
                    elements[key].classList.remove('animate-pulse');
                }, 1000);
            }
        });
        
        // If we have recent flight bookings data, update the table
        if (stats.recent_flights && stats.recent_flights.length > 0) {
            updateRecentFlightsTable(stats.recent_flights);
        }
    }
    
    function updateRecentFlightsTable(flights) {
        const tableBody = document.querySelector('.flight-bookings-table tbody');
        if (!tableBody) return;
        
        // Clear existing rows except for the "no bookings" row
        const noBookingsRow = tableBody.querySelector('tr td[colspan="6"]')?.closest('tr');
        if (noBookingsRow) {
            tableBody.innerHTML = '';
        } else {
            const existingRows = tableBody.querySelectorAll('tr');
            existingRows.forEach(row => {
                if (!row.querySelector('td[colspan="6"]')) {
                    row.remove();
                }
            });
        }
        
        // Add new rows
        flights.forEach(booking => {
            const row = document.createElement('tr');
            
            // Determine status class
            let statusClass = '';
            if (booking.status === 'confirmed') {
                statusClass = 'bg-success-100 dark:bg-[#15203c] text-success-600';
            } else if (booking.status === 'processing') {
                statusClass = 'bg-warning-50 dark:bg-[#15203c] text-warning-500';
            } else if (booking.status === 'cancelled') {
                statusClass = 'bg-red-50 dark:bg-[#15203c] text-red-500';
            } else {
                statusClass = 'bg-primary-50 dark:bg-[#15203c] text-primary-500';
            }
            
            // Format the row HTML
            row.innerHTML = `
                <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                    <span class="font-medium">${booking.booking_reference}</span>
                </td>
                <td class="ltr:text-left rtl:text-right px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036]">
                    <div>
                        <span class="block font-medium">
                            ${booking.flight?.departure_airport || 'N/A'} → ${booking.flight?.arrival_airport || 'N/A'}
                        </span>
                        <span class="text-sm text-gray-500">
                            ${booking.flight?.airline || 'N/A'} • ${booking.flight?.flight_number || 'N/A'}
                        </span>
                    </div>
                </td>
                <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036]">
                    <span>${new Date(booking.created_at).toLocaleDateString('en-US', { day: '2-digit', month: 'short', year: 'numeric' })}</span>
                </td>
                <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036]">
                    <span class="font-medium">$${parseFloat(booking.total_amount || 0).toFixed(2)}</span>
                </td>
                <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036]">
                    <span class="px-[8px] py-[3px] inline-block ${statusClass} rounded-sm font-medium text-xs">
                        ${booking.status ? booking.status.charAt(0).toUpperCase() + booking.status.slice(1) : 'Unknown'}
                    </span>
                </td>
                <td class="text-center whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036]">
                    <div class="flex justify-center space-x-2">
                        <a href="/bookings/${booking.id}" class="bg-primary-50 dark:bg-[#15203c] text-primary-500 p-1.5 rounded-md hover:bg-primary-500 hover:text-white transition-all">
                            <i class="ri-eye-line"></i>
                        </a>
                        <a href="/bookings/${booking.id}/download" class="bg-secondary-50 dark:bg-[#15203c] text-secondary-500 p-1.5 rounded-md hover:bg-secondary-500 hover:text-white transition-all">
                            <i class="ri-download-line"></i>
                        </a>
                    </div>
                </td>
            `;
            
            tableBody.appendChild(row);
        });
    }
    
    function updateUserPaymentStats(stats) {
        // Update the UI with new payment stats
        const elements = {
            'total': document.querySelector('[data-user-stat="payments-total"]'),
            'completed': document.querySelector('[data-user-stat="payments-completed"]'),
            'failed': document.querySelector('[data-user-stat="payments-failed"]'),
            'pending': document.querySelector('[data-user-stat="payments-pending"]')
        };
        
        Object.keys(elements).forEach(key => {
            if (elements[key] && stats[key] !== undefined) {
                elements[key].textContent = stats[key];
                
                // Add a highlight effect for changes
                elements[key].classList.add('animate-pulse');
                setTimeout(() => {
                    elements[key].classList.remove('animate-pulse');
                }, 1000);
            }
        });
    }
    
    function updateUserTravelStats(stats) {
        // Update travel statistics in the UI
        const elements = {
            'total-spent': document.querySelector('[data-user-stat="total-spent"]'),
            'countries-visited': document.querySelector('[data-user-stat="countries-visited"]'),
            'cities-visited': document.querySelector('[data-user-stat="cities-visited"]')
        };
        
        Object.keys(elements).forEach(key => {
            if (elements[key] && stats[key.replace('-', '_')] !== undefined) {
                const value = stats[key.replace('-', '_')];
                if (key === 'total-spent') {
                    elements[key].textContent = '$' + parseFloat(value).toFixed(2);
                } else {
                    elements[key].textContent = value;
                }
                
                // Add a highlight effect for changes
                elements[key].classList.add('animate-pulse');
                setTimeout(() => {
                    elements[key].classList.remove('animate-pulse');
                }, 1000);
            }
        });
    }
</script>

<style>
/* Custom styles for the new dashboard features */
.map-view {
    transition: all 0.3s ease;
}

.map-view.hidden {
    opacity: 0;
    transform: scale(0.95);
}

.map-view.active {
    opacity: 1;
    transform: scale(1);
}

.country-path {
    transition: all 0.2s ease;
}

.country-path:hover {
    filter: brightness(1.1);
}

.destination-pin {
    transition: all 0.2s ease;
    cursor: pointer;
}

.destination-pin:hover {
    filter: drop-shadow(0 0 5px rgba(239, 68, 68, 0.8));
}

.wishlist-item {
    transition: all 0.3s ease;
}

.wishlist-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Activity log animations */
.activity-item {
    opacity: 0;
    transform: translateY(20px);
    animation: slideInUp 0.3s ease forwards;
}

.activity-item:nth-child(1) { animation-delay: 0.1s; }
.activity-item:nth-child(2) { animation-delay: 0.2s; }
.activity-item:nth-child(3) { animation-delay: 0.3s; }
.activity-item:nth-child(4) { animation-delay: 0.4s; }
.activity-item:nth-child(5) { animation-delay: 0.5s; }

@keyframes slideInUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Pulse animation for real-time updates */
@keyframes pulse-glow {
    0%, 100% {
        box-shadow: 0 0 5px rgba(59, 130, 246, 0.3);
    }
    50% {
        box-shadow: 0 0 20px rgba(59, 130, 246, 0.6);
    }
}

.animate-pulse-glow {
    animation: pulse-glow 2s infinite;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .map-view svg {
        height: 180px;
    }
    
    .wishlist-item {
        padding: 0.75rem;
    }
    
    .activity-item {
        padding: 0.75rem;
    }
}

/* Dark mode specific adjustments */
@media (prefers-color-scheme: dark) {
    .country-path {
        stroke-opacity: 0.8;
    }
    
    .destination-pin:hover {
        filter: drop-shadow(0 0 5px rgba(239, 68, 68, 1));
    }
}

/* Loading states */
.loading-shimmer {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
}

@keyframes loading {
    0% {
        background-position: 200% 0;
    }
    100% {
        background-position: -200% 0;
    }
}

.dark .loading-shimmer {
    background: linear-gradient(90deg, #374151 25%, #4b5563 50%, #374151 75%);
    background-size: 200% 100%;
}
</style>
@endsection
