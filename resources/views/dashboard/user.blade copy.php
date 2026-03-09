{{-- filepath: resources/views/dashboard/index.blade.php --}}
@extends('layouts.dashboard')

@section('content')


<!-- Hero Section -->
<div class="full-width-container">
    <div class="max-w-8xl mx-auto px-4 py-8">
        <!-- Welcome Hero Section -->
        <div data-aos="fade-down" class="relative rounded-xl bg-[#2c2e3a] p-8 mb-10 text-white overflow-hidden shadow-2xl">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-10 rounded-full -mr-20 -mt-20"></div>
            <div class="absolute bottom-0 left-0 w-40 h-40 bg-white opacity-10 rounded-full -ml-10 -mb-10"></div>
            
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <!-- Left Section: Welcome Message and Buttons -->
                <div class="mb-6 md:mb-0 md:mr-6">
                    <span id="greeting" class="text-2xl font-medium text-white">Good day,</span>
                    <h1 class="text-4xl text-[#b88700] font-bold mb-2">Welcome back, {{ Auth::user()->name ?? 'User' }}!</h1>
                    <p class="text-lg opacity-90 text-[#b88700]">{{ now()->format('l, F j, Y') }}</p>
                    <p class="mt-4 max-w-2xl text-[#b88700]">
                        @if(auth()->user()->role === 'superadmin')
                            Manage the entire system, monitor performance, and oversee all operations from this centralized dashboard.
                        @elseif(auth()->user()->role === 'admin')
                            Manage bookings, users, and system operations. Monitor business performance and handle administrative tasks.
                        @else
                            Manage your travel bookings, track your trips, and access all your travel tools from this centralized dashboard.
                        @endif
                    </p>
                    
                    <div class="mt-6 flex space-x-4">
                        <a href="#tools" class="bg-white text-[2c2e3a] font-semibold px-5 py-2 rounded-lg hover:bg-opacity-90 transition duration-300 flex items-center">
                            <i class="fas fa-toolbox mr-2"></i> 
                            @if(in_array(auth()->user()->role, ['admin', 'superadmin']))
                                Management Tools
                            @else
                                Travel Tools
                            @endif
                        </a>
                        <a href="#help" class="bg-[#b88700] text-white font-semibold px-5 py-2 rounded-lg hover:bg-[#a07600] transition duration-300 flex items-center">
                            <i class="fas fa-question-circle mr-2"></i> Get Help
                        </a>
                    </div>
                </div>

                <!-- Right Section: Widgets -->
                <div class="flex flex-col space-y-4">
                    <!-- Weather Widget -->
                    <div class="p-4 bg-white/20 backdrop-blur-md rounded-lg text-white hover:bg-white/30 transition-all duration-300 shadow-lg">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-[2c2e3a]/60 mr-4 flex items-center justify-center">
                                    <img id="weather-icon" src="https://openweathermap.org/img/wn/01d@2x.png" alt="Weather Icon" class="w-8 h-8">
                                </div>
                                <div>
                                    <h3 class="font-bold text-white text-lg">Weather</h3>
                                    <p id="weather-info" class="text-white">Loading weather...</p>
                                    <p id="weather-location" class="text-xs text-white/70">Detecting location...</p>
                                </div>
                            </div>
                            <button id="refresh-weather" class="p-2 rounded-full bg-[2c2e3a]/50 hover:bg-[2c2e3a]/70 transition-colors">
                                <i class="fas fa-sync-alt text-[#b88700]"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Time Widget -->
                    <div class="p-4 bg-white/20 backdrop-blur-md rounded-lg text-white hover:bg-white/30 transition-all duration-300 shadow-lg time-widget">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-[2c2e3a]/60 mr-4 flex items-center justify-center">
                                    <i class="fas fa-clock text-xl text-[#b88700]"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-white text-lg">Current Time</h3>
                                    <p id="current-time" class="text-white text-xl">Loading time...</p>
                                    <p id="current-date" class="text-xs text-white/70"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Updates Widget -->
                    <div class="p-4 bg-white/20 backdrop-blur-md rounded-lg text-white hover:bg-white/30 transition-all duration-300 shadow-lg">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-[2c2e3a]/60 mr-4 flex items-center justify-center">
                                <i class="fas fa-bell text-xl text-[#b88700]"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-[#b88700] text-lg">Quick Updates</h3>
                                <p class="text-white">Your account is active and ready</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards Section -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @if(in_array(auth()->user()->role, ['admin', 'superadmin']))
            <!-- Admin/SuperAdmin Cards -->
            <!-- Pending Card -->
            <div data-aos="fade-up" data-aos-delay="100">
                <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 overflow-hidden relative">
                    <div class="absolute top-0 left-0 w-2 h-full bg-amber-500"></div>
                    <div class="p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="text-3xl font-bold text-gray-800" id="pending-count">{{ $stats['pending'] ?? 0 }}</h3>
                                <p class="text-sm font-medium text-gray-500 mt-1">Pending</p>
                                <div class="flex items-center mt-2 text-xs font-medium text-amber-600">
                                    <i data-lucide="trending-up" class="h-3.5 w-3.5 mr-1"></i>
                                    <span>+5% from last week</span>
                                </div>
                            </div>
                            <div class="bg-amber-100 rounded-lg p-3">
                                <i data-lucide="clock" class="h-6 w-6 text-amber-500"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="h-2 w-full bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-amber-500 rounded-full" style="width: 65%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Earnings Card -->
            <div data-aos="fade-up" data-aos-delay="200">
                <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 overflow-hidden relative">
                    <div class="absolute top-0 left-0 w-2 h-full bg-emerald-500"></div>
                    <div class="p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="text-3xl font-bold text-gray-800">KES {{ number_format($stats['earnings'] ?? 0, 2) }}</h3>
                                <p class="text-sm font-medium text-gray-500 mt-1">Earnings</p>
                                <div class="flex items-center mt-2 text-xs font-medium text-emerald-600">
                                    <i data-lucide="trending-up" class="h-3.5 w-3.5 mr-1"></i>
                                    <span>+12% from last month</span>
                                </div>
                            </div>
                            <div class="bg-emerald-100 rounded-lg p-3">
                                <i data-lucide="bar-chart-2" class="h-6 w-6 text-emerald-500"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="h-2 w-full bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-emerald-500 rounded-full" style="width: 78%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Bookings Card -->
            <div data-aos="fade-up" data-aos-delay="300">
                <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 overflow-hidden relative">
                    <div class="absolute top-0 left-0 w-2 h-full bg-blue-500"></div>
                    <div class="p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="text-3xl font-bold text-gray-800">{{ $stats['total_bookings'] ?? 0 }}</h3>
                                <p class="text-sm font-medium text-gray-500 mt-1">Total Bookings</p>
                                <div class="flex items-center mt-2 text-xs font-medium text-blue-600">
                                    <i data-lucide="trending-up" class="h-3.5 w-3.5 mr-1"></i>
                                    <span>+8% this month</span>
                                </div>
                            </div>
                            <div class="bg-blue-100 rounded-lg p-3">
                                <i data-lucide="calendar-check" class="h-6 w-6 text-blue-500"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="h-2 w-full bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-blue-500 rounded-full" style="width: 85%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Completed Card -->
            <div data-aos="fade-up" data-aos-delay="400">
                <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 overflow-hidden relative">
                    <div class="absolute top-0 left-0 w-2 h-full bg-green-500"></div>
                    <div class="p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="text-3xl font-bold text-gray-800">{{ $stats['completed'] ?? 0 }}</h3>
                                <p class="text-sm font-medium text-gray-500 mt-1">Completed</p>
                                <div class="flex items-center mt-2 text-xs font-medium text-green-600">
                                    <i data-lucide="trending-up" class="h-3.5 w-3.5 mr-1"></i>
                                    <span>+15% completion rate</span>
                                </div>
                            </div>
                            <div class="bg-green-100 rounded-lg p-3">
                                <i data-lucide="check-circle" class="h-6 w-6 text-green-500"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="h-2 w-full bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-green-500 rounded-full" style="width: 92%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- User Cards -->
            <!-- Pending Bookings Card -->
            <div data-aos="fade-up" data-aos-delay="100">
                <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 overflow-hidden relative">
                    <div class="absolute top-0 left-0 w-2 h-full bg-amber-500"></div>
                    <div class="p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="text-3xl font-bold text-gray-800">{{ $userStats['pending_bookings'] ?? 0 }}</h3>
                                <p class="text-sm font-medium text-gray-500 mt-1">Pending Bookings</p>
                                <div class="flex items-center mt-2 text-xs font-medium text-gray-600">
                                    <i data-lucide="info" class="h-3.5 w-3.5 mr-1"></i>
                                    <span>Awaiting confirmation</span>
                                </div>
                            </div>
                            <div class="bg-amber-100 rounded-lg p-3">
                                <i data-lucide="hourglass" class="h-6 w-6 text-amber-500"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="h-2 w-full bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-amber-500 rounded-full" style="width: {{ ($userStats['pending_bookings'] ?? 0) > 0 ? 50 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Completed Bookings Card -->
            <div data-aos="fade-up" data-aos-delay="200">
                <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 overflow-hidden relative">
                    <div class="absolute top-0 left-0 w-2 h-full bg-green-500"></div>
                    <div class="p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="text-3xl font-bold text-gray-800">{{ $userStats['completed_bookings'] ?? 0 }}</h3>
                                <p class="text-sm font-medium text-gray-500 mt-1">Completed Bookings</p>
                                <div class="flex items-center mt-2 text-xs font-medium text-green-600">
                                    <i data-lucide="check-check" class="h-3.5 w-3.5 mr-1"></i>
                                    <span>Successfully completed</span>
                                </div>
                            </div>
                            <div class="bg-green-100 rounded-lg p-3">
                                <i data-lucide="check-circle" class="h-6 w-6 text-green-500"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="h-2 w-full bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-green-500 rounded-full" style="width: {{ ($userStats['completed_bookings'] ?? 0) > 0 ? 100 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cancelled Bookings Card -->
            <div data-aos="fade-up" data-aos-delay="300">
                <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 overflow-hidden relative">
                    <div class="absolute top-0 left-0 w-2 h-full bg-red-500"></div>
                    <div class="p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="text-3xl font-bold text-gray-800">{{ $userStats['cancelled_bookings'] ?? 0 }}</h3>
                                <p class="text-sm font-medium text-gray-500 mt-1">Cancelled Bookings</p>
                                <div class="flex items-center mt-2 text-xs font-medium text-red-600">
                                    <i data-lucide="alert-triangle" class="h-3.5 w-3.5 mr-1"></i>
                                    <span>Need attention</span>
                                </div>
                            </div>
                            <div class="bg-red-100 rounded-lg p-3">
                                <i data-lucide="x-circle" class="h-6 w-6 text-red-500"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="h-2 w-full bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-red-500 rounded-full" style="width: {{ ($userStats['cancelled_bookings'] ?? 0) > 0 ? 30 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Bookings Card -->
            <div data-aos="fade-up" data-aos-delay="400">
                <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 overflow-hidden relative">
                    <div class="absolute top-0 left-0 w-2 h-full bg-indigo-500"></div>
                    <div class="p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="text-3xl font-bold text-gray-800">{{ $userStats['total_bookings'] ?? 0 }}</h3>
                                <p class="text-sm font-medium text-gray-500 mt-1">Total Bookings</p>
                                <div class="flex items-center mt-2 text-xs font-medium text-indigo-600">
                                    <i data-lucide="pie-chart" class="h-3.5 w-3.5 mr-1"></i>
                                    <span>All time bookings</span>
                                </div>
                            </div>
                            <div class="bg-indigo-100 rounded-lg p-3">
                                <i data-lucide="clipboard-list" class="h-6 w-6 text-indigo-500"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="h-2 w-full bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-indigo-500 rounded-full" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Charts and Recent Bookings Section -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Pie Chart - Bookings Status -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden" data-aos="fade-up" data-aos-delay="100">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <i data-lucide="pie-chart" class="h-5 w-5 text-blue-500 mr-2"></i>
                    <h2 class="text-lg font-medium text-gray-900">Booking Status Overview</h2>
                </div>
                <p class="text-sm text-gray-500 mb-6">Distribution of all bookings</p>
                
                <div class="flex items-center justify-center">
                    <div class="w-[280px] h-[280px]">
                        <canvas id="bookingStatusChart"></canvas>
                    </div>
                </div>
                
                <div class="grid grid-cols-3 gap-3 mt-6">
                    <div class="text-center">
                        <div class="flex items-center justify-center mb-1">
                            <div class="w-3 h-3 rounded-full bg-emerald-500 mr-2"></div>
                            <span class="text-sm text-gray-600">Completed</span>
                        </div>
                        <div class="text-lg font-semibold text-gray-900">{{ $chartData['completed'] ?? 0 }}</div>
                    </div>
                    <div class="text-center">
                        <div class="flex items-center justify-center mb-1">
                            <div class="w-3 h-3 rounded-full bg-amber-500 mr-2"></div>
                            <span class="text-sm text-gray-600">Processing</span>
                        </div>
                        <div class="text-lg font-semibold text-gray-900">{{ $chartData['processing'] ?? 0 }}</div>
                    </div>
                    <div class="text-center">
                        <div class="flex items-center justify-center mb-1">
                            <div class="w-3 h-3 rounded-full bg-red-500 mr-2"></div>
                            <span class="text-sm text-gray-600">Cancelled</span>
                        </div>
                        <div class="text-lg font-semibold text-gray-900">{{ $chartData['cancelled'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Line Chart - Daily Logins -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden" data-aos="fade-up" data-aos-delay="200">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <i data-lucide="line-chart" class="h-5 w-5 text-green-500 mr-2"></i>
                    <h2 class="text-lg font-medium text-gray-900">Daily Login Activity</h2>
                </div>
                <p class="text-sm text-gray-500 mb-6">
                    @if(in_array(auth()->user()->role, ['admin', 'superadmin']))
                        System-wide daily logins
                    @else
                        Your login activity
                    @endif
                </p>

                <div class="h-[280px]">
                    <canvas id="dailyLoginsChart"></canvas>
                </div>

                <div class="flex justify-between items-center mt-6">
                    <div class="flex items-center text-sm text-gray-500">
                        <i data-lucide="calendar" class="h-4 w-4 mr-1"></i>
                        Last 7 days
                    </div>
                    <div class="flex items-center">
                        <div class="bg-green-500 text-white text-xs py-1 px-2 rounded mr-2 flex items-center">
                            <i data-lucide="trending-up" class="h-3 w-3 mr-1"></i>
                            +{{ $loginGrowth ?? '0' }}%
                        </div>
                        <span class="text-xs text-gray-500">vs last week</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Bookings Table -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden" data-aos="fade-up" data-aos-delay="300">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <div class="flex items-center mb-1">
                            <i data-lucide="history" class="h-5 w-5 text-blue-500 mr-2"></i>
                            <h2 class="text-lg font-medium text-gray-900">Recent Bookings</h2>
                        </div>
                        <p class="text-sm text-gray-500">Latest booking activities</p>
                    </div>
                    <a href="{{ route('dashboard.bookings') }}" class="inline-flex items-center px-3 py-1.5 border border-blue-500 text-blue-500 rounded-lg text-sm font-medium hover:bg-blue-50 transition-colors duration-200">
                        View All
                    </a>
                </div>

                <div class="overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-3 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-3 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                                    <th class="px-3 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    <th class="px-3 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paid</th>
                                    <th class="px-3 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-3 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($recentBookings ?? [] as $booking)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-3 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                        #{{ $booking->id }}
                                    </td>
                                    <td class="px-3 py-3 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if($booking->type === 'flight')
                                                <div class="flex-shrink-0 h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                                    <i data-lucide="plane" class="h-4 w-4 text-blue-600"></i>
                                                </div>
                                            @else
                                                <div class="flex-shrink-0 h-8 w-8 rounded-full bg-green-100 flex items-center justify-center">
                                                    <i data-lucide="bed" class="h-4 w-4 text-green-600"></i>
                                                </div>
                                            @endif
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900">{{ ucfirst($booking->type) }}</div>
                                                <div class="text-xs text-gray-500">{{ Str::limit($booking->title ?? 'N/A', 20) }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">KES {{ number_format($booking->total_amount ?? 0, 0) }}</div>
                                    </td>
                                    <td class="px-3 py-3 whitespace-nowrap">
                                        <div class="text-sm text-green-600">KES {{ number_format($booking->paid_amount ?? 0, 0) }}</div>
                                    </td>
                                    <td class="px-3 py-3 whitespace-nowrap">
                                        @php
                                            $statusClasses = [
                                                'completed' => 'bg-green-100 text-green-800',
                                                'processing' => 'bg-blue-100 text-blue-800',
                                                'pending' => 'bg-yellow-100 text-yellow-800',
                                                'cancelled' => 'bg-red-100 text-red-800',
                                            ];
                                            $status = strtolower($booking->status ?? 'pending');
                                            $statusClass = $statusClasses[$status] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                            {{ ucfirst($booking->status ?? 'Pending') }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-500">
                                        {{ $booking->created_at ? $booking->created_at->format('M d') : 'N/A' }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-3 py-8 text-center">
                                        <div class="flex flex-col items-center">
                                            <i data-lucide="inbox" class="h-10 w-10 text-gray-400 mb-2"></i>
                                            <p class="text-gray-500">No recent bookings</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Calendar and Flight Dashboard Section -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Calendar Section -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden" data-aos="fade-up" data-aos-delay="100">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <div class="flex items-center mb-1">
                            <i data-lucide="calendar" class="h-5 w-5 text-blue-500 mr-2"></i>
                            <h2 class="text-lg font-medium text-gray-900">Booking Calendar</h2>
                        </div>
                        <p class="text-sm text-gray-500">View your upcoming bookings</p>
                    </div>
                    <div class="flex space-x-2">
                        <select id="monthFilter" class="block rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                            <option value="0">January</option>
                            <option value="1">February</option>
                            <option value="2">March</option>
                            <option value="3">April</option>
                            <option value="4">May</option>
                            <option value="5">June</option>
                            <option value="6">July</option>
                            <option value="7">August</option>
                            <option value="8">September</option>
                            <option value="9">October</option>
                            <option value="10">November</option>
                            <option value="11">December</option>
                        </select>
                        <input type="date" class="block rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" id="datePicker">
                    </div>
                </div>

                <div class="calendar-container">
                    <div class="flex justify-between items-center mb-4">
                        <button id="prevMonth" class="inline-flex items-center justify-center p-2 rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50">
                            <i data-lucide="chevron-left" class="h-4 w-4"></i>
                        </button>
                        <h3 class="text-base font-medium text-gray-900" id="currentMonthYear"></h3>
                        <button id="nextMonth" class="inline-flex items-center justify-center p-2 rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50">
                            <i data-lucide="chevron-right" class="h-4 w-4"></i>
                        </button>
                    </div>

                    <div class="grid grid-cols-7 gap-1">
                        <div class="text-center text-xs font-medium text-gray-500 py-2">Sun</div>
                        <div class="text-center text-xs font-medium text-gray-500 py-2">Mon</div>
                        <div class="text-center text-xs font-medium text-gray-500 py-2">Tue</div>
                        <div class="text-center text-xs font-medium text-gray-500 py-2">Wed</div>
                        <div class="text-center text-xs font-medium text-gray-500 py-2">Thu</div>
                        <div class="text-center text-xs font-medium text-gray-500 py-2">Fri</div>
                        <div class="text-center text-xs font-medium text-gray-500 py-2">Sat</div>
                    </div>
                    
                    <div class="grid grid-cols-7 gap-1" id="calendarBody">
                        <!-- Calendar days will be populated by JavaScript -->
                    </div>
                    
                    <div class="flex justify-center space-x-6 mt-6">
                        <div class="flex items-center">
                            <i data-lucide="plane" class="h-4 w-4 text-blue-500 mr-2"></i>
                            <span class="text-xs text-gray-600">Flight</span>
                        </div>
                        <div class="flex items-center">
                            <i data-lucide="bed" class="h-4 w-4 text-green-500 mr-2"></i>
                            <span class="text-xs text-gray-600">Hotel</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-3 h-3 rounded-full bg-blue-500 mr-2"></div>
                            <span class="text-xs text-gray-600">Today</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Flight Dashboard Section -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden" data-aos="fade-up" data-aos-delay="200">
            <div class="p-6">
                <div class="flex items-center mb-6">
                    <i data-lucide="plane-takeoff" class="h-5 w-5 text-blue-500 mr-2"></i>
                    <h2 class="text-lg font-medium text-gray-900">Flight Dashboard</h2>
                </div>
                <p class="text-sm text-gray-500 mb-6">Quick flight information and status</p>

                <div class="space-y-6">
                    <!-- Next Flight Card -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-4 rounded-xl border border-blue-100">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-blue-700 font-medium">Next Flight</h3>
                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Confirmed</span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div class="text-center">
                                <div class="text-lg font-bold text-gray-900">NBO</div>
                                <div class="text-sm text-gray-600">Nairobi</div>
                                <div class="text-sm font-medium text-gray-900 mt-1">14:30</div>
                            </div>
                            
                            <div class="flex flex-col items-center">
                                <div class="w-32 h-0.5 bg-blue-200 relative">
                                    <i data-lucide="plane" class="h-5 w-5 text-blue-500 absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2"></i>
                                </div>
                                <div class="text-xs text-gray-500 mt-2">2h 45m</div>
                            </div>
                            
                            <div class="text-center">
                                <div class="text-lg font-bold text-gray-900">MBA</div>
                                <div class="text-sm text-gray-600">Mombasa</div>
                                <div class="text-sm font-medium text-gray-900 mt-1">17:15</div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-3 gap-4 mt-4 text-center">
                            <div>
                                <div class="text-xs text-gray-500">Flight</div>
                                <div class="font-semibold text-gray-900">KQ312</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500">Gate</div>
                                <div class="font-semibold text-gray-900">A12</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500">Seat</div>
                                <div class="font-semibold text-gray-900">12A</div>
                            </div>
                        </div>
                    </div>

                    <!-- Flight Stats -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-white rounded-xl border border-gray-200 p-4 flex items-center">
                            <div class="rounded-full bg-amber-100 p-3 mr-4">
                                <i data-lucide="clock" class="h-6 w-6 text-amber-500"></i>
                            </div>
                            <div>
                                <div class="text-xl font-bold text-gray-900">{{ $flightStats['on_time_percentage'] ?? 85 }}%</div>
                                <div class="text-sm text-gray-600">On Time</div>
                            </div>
                        </div>
                        <div class="bg-white rounded-xl border border-gray-200 p-4 flex items-center">
                            <div class="rounded-full bg-blue-100 p-3 mr-4">
                                <i data-lucide="map" class="h-6 w-6 text-blue-500"></i>
                            </div>
                            <div>
                                <div class="text-xl font-bold text-gray-900">{{ $flightStats['total_miles'] ?? 0 }}</div>
                                <div class="text-sm text-gray-600">Miles Flown</div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-700 mb-3">Quick Actions</h3>
                        <div class="flex flex-wrap gap-2">
                            <button class="inline-flex items-center px-3 py-2 border border-blue-500 text-blue-600 rounded-lg text-sm font-medium hover:bg-blue-50 transition-colors duration-200">
                                <i data-lucide="search" class="h-4 w-4 mr-1.5"></i>
                                Flight Status
                            </button>
                            <button class="inline-flex items-center px-3 py-2 border border-green-500 text-green-600 rounded-lg text-sm font-medium hover:bg-green-50 transition-colors duration-200">
                                <i data-lucide="smartphone" class="h-4 w-4 mr-1.5"></i>
                                Check-in
                            </button>
                            <button class="inline-flex items-center px-3 py-2 border border-blue-400 text-blue-500 rounded-lg text-sm font-medium hover:bg-blue-50 transition-colors duration-200">
                                <i data-lucide="map-pin" class="h-4 w-4 mr-1.5"></i>
                                Airport Map
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Notifications and Activity Tracker Section -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Notifications Section -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden" data-aos="fade-up" data-aos-delay="100">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <div class="flex items-center mb-1">
                            <i data-lucide="bell" class="h-5 w-5 text-amber-500 mr-2"></i>
                            <h2 class="text-lg font-medium text-gray-900">Notifications</h2>
                        </div>
                        <p class="text-sm text-gray-500">Recent activities and updates</p>
                    </div>
                    <div class="flex space-x-2">
                        <button id="markAllRead" class="inline-flex items-center px-3 py-1.5 border border-blue-500 text-blue-500 rounded-lg text-sm font-medium hover:bg-blue-50 transition-colors duration-200">
                            <i data-lucide="check-check" class="h-4 w-4 mr-1.5"></i>
                            Mark All Read
                        </button>
                        <button id="notificationSettings" class="inline-flex items-center p-1.5 border border-gray-200 text-gray-500 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                            <i data-lucide="settings" class="h-4 w-4"></i>
                        </button>
                    </div>
                </div>

                <div class="divide-y divide-gray-100 max-h-[400px] overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
                    @forelse($notifications ?? [] as $notification)
                    <div class="py-4 flex gap-4 {{ $notification->read_at ? '' : 'bg-blue-50' }} relative">
                        <div class="{{ $notification->read_at ? '' : 'relative' }}">
                            @if(!$notification->read_at)
                                <div class="absolute top-0 right-0 h-2 w-2 bg-blue-500 rounded-full"></div>
                            @endif
                            
                            @if($notification->type === 'flight_booked')
                                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                    <i data-lucide="plane" class="h-5 w-5 text-blue-600"></i>
                                </div>
                            @elseif($notification->type === 'hotel_booked')
                                <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                                    <i data-lucide="bed" class="h-5 w-5 text-green-600"></i>
                                </div>
                            @elseif($notification->type === 'blog_post')
                                <div class="h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center">
                                    <i data-lucide="file-text" class="h-5 w-5 text-purple-600"></i>
                                </div>
                            @elseif($notification->type === 'payment_success')
                                <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                                    <i data-lucide="credit-card" class="h-5 w-5 text-green-600"></i>
                                </div>
                            @else
                                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                    <i data-lucide="info" class="h-5 w-5 text-blue-600"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h3 class="text-sm font-medium text-gray-900">{{ $notification->title }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ $notification->message }}</p>
                            <div class="flex items-center mt-2 text-xs text-gray-500">
                                <i data-lucide="clock" class="h-3.5 w-3.5 mr-1"></i>
                                <span>{{ $notification->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <!-- Sample notifications for demo -->
                    <div class="py-4 flex gap-4 bg-blue-50 relative">
                        <div class="relative">
                            <div class="absolute top-0 right-0 h-2 w-2 bg-blue-500 rounded-full"></div>
                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                <i data-lucide="plane" class="h-5 w-5 text-blue-600"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-sm font-medium text-gray-900">Flight Booking Confirmed</h3>
                            <p class="text-sm text-gray-600 mt-1">Your flight to Mombasa has been successfully booked and confirmed.</p>
                            <div class="flex items-center mt-2 text-xs text-gray-500">
                                <i data-lucide="clock" class="h-3.5 w-3.5 mr-1"></i>
                                <span>2 hours ago</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="py-4 flex gap-4">
                        <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                            <i data-lucide="bed" class="h-5 w-5 text-green-600"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-sm font-medium text-gray-900">Hotel Reservation Complete</h3>
                            <p class="text-sm text-gray-600 mt-1">Your reservation at Serena Hotel has been confirmed for 3 nights.</p>
                            <div class="flex items-center mt-2 text-xs text-gray-500">
                                <i data-lucide="clock" class="h-3.5 w-3.5 mr-1"></i>
                                <span>5 hours ago</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="py-4 flex gap-4 bg-blue-50 relative">
                        <div class="relative">
                            <div class="absolute top-0 right-0 h-2 w-2 bg-blue-500 rounded-full"></div>
                            <div class="h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center">
                                <i data-lucide="file-text" class="h-5 w-5 text-purple-600"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-sm font-medium text-gray-900">New Travel Guide Published</h3>
                            <p class="text-sm text-gray-600 mt-1">Check out our latest guide: "Best Safari Destinations in Kenya"</p>
                            <div class="flex items-center mt-2 text-xs text-gray-500">
                                <i data-lucide="clock" class="h-3.5 w-3.5 mr-1"></i>
                                <span>1 day ago</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="py-4 flex gap-4">
                        <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                            <i data-lucide="credit-card" class="h-5 w-5 text-green-600"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-sm font-medium text-gray-900">Payment Successful</h3>
                            <p class="text-sm text-gray-600 mt-1">Your payment of KES 45,000 has been processed successfully.</p>
                            <div class="flex items-center mt-2 text-xs text-gray-500">
                                <i data-lucide="clock" class="h-3.5 w-3.5 mr-1"></i>
                                <span>2 days ago</span>
                            </div>
                        </div>
                    </div>
                    @endforelse
                </div>

                <div class="mt-6 text-center">
                    <a href="{{ route('dashboard.notifications') }}" class="text-blue-600 text-sm font-medium hover:text-blue-800 hover:underline transition-colors">
                        View All Notifications
                    </a>
                </div>
            </div>
        </div>

        <!-- Usage and Activity Tracker Section -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden" data-aos="fade-up" data-aos-delay="200">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <div class="flex items-center mb-1">
                            <i data-lucide="activity" class="h-5 w-5 text-green-500 mr-2"></i>
                            <h2 class="text-lg font-medium text-gray-900">Usage & Activity Tracker</h2>
                        </div>
                        <p class="text-sm text-gray-500">Monitor your account activity</p>
                    </div>
                    <select id="activityFilter" class="block rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm">
                        <option value="today">Today</option>
                        <option value="week">This Week</option>
                        <option value="month">This Month</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-4 rounded-xl flex items-center">
                        <div class="rounded-full bg-blue-100 border border-blue-200 p-2 mr-4">
                            <i data-lucide="log-in" class="h-5 w-5 text-blue-600"></i>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-gray-900">{{ $activityStats['login_count'] ?? 12 }}</div>
                            <div class="text-sm text-gray-600">Logins</div>
                        </div>
                    </div>
                    <div class="bg-gradient-to-r from-indigo-50 to-indigo-100 p-4 rounded-xl flex items-center">
                        <div class="rounded-full bg-indigo-100 border border-indigo-200 p-2 mr-4">
                            <i data-lucide="mouse-pointer" class="h-5 w-5 text-indigo-600"></i>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-gray-900">{{ $activityStats['page_views'] ?? 45 }}</div>
                            <div class="text-sm text-gray-600">Page Views</div>
                        </div>
                    </div>
                    <div class="bg-gradient-to-r from-amber-50 to-amber-100 p-4 rounded-xl flex items-center">
                        <div class="rounded-full bg-amber-100 border border-amber-200 p-2 mr-4">
                            <i data-lucide="search" class="h-5 w-5 text-amber-600"></i>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-gray-900">{{ $activityStats['searches'] ?? 8 }}</div>
                            <div class="text-sm text-gray-600">Searches</div>
                        </div>
                    </div>
                    <div class="bg-gradient-to-r from-green-50 to-green-100 p-4 rounded-xl flex items-center">
                        <div class="rounded-full bg-green-100 border border-green-200 p-2 mr-4">
                            <i data-lucide="calendar-check" class="h-5 w-5 text-green-600"></i>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-gray-900">{{ $activityStats['bookings'] ?? 3 }}</div>
                            <div class="text-sm text-gray-600">Bookings</div>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-base font-medium text-gray-900 mb-4">Recent Activity</h3>
                    <div class="space-y-4 max-h-[220px] overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
                        @forelse($activityLogs ?? [] as $activity)
                        <div class="flex">
                            <div class="flex-shrink-0 mr-3">
                                <div class="relative mt-1.5">
                                    <div class="h-3 w-3 rounded-full border-2 border-white bg-{{ $activity->type }}-500"></div>
                                    <div class="absolute h-full w-0.5 bg-gray-200 top-3 left-1.5"></div>
                                </div>
                            </div>
                            <div class="flex-1 pb-4">
                                <div class="text-sm font-medium text-gray-900">{{ $activity->description }}</div>
                                <div class="text-xs text-gray-500 mt-1">{{ $activity->created_at->format('M d, H:i') }}</div>
                            </div>
                        </div>
                        @empty
                        <!-- Sample activity logs for demo -->
                        <div class="flex">
                            <div class="flex-shrink-0 mr-3">
                                <div class="relative mt-1.5">
                                    <div class="h-3 w-3 rounded-full border-2 border-white bg-green-500"></div>
                                    <div class="absolute h-full w-0.5 bg-gray-200 top-3 left-1.5"></div>
                                </div>
                            </div>
                            <div class="flex-1 pb-4">
                                <div class="text-sm font-medium text-gray-900">Successful login from Nairobi, Kenya</div>
                                <div class="text-xs text-gray-500 mt-1">{{ now()->format('M d, H:i') }}</div>
                            </div>
                        </div>
                        
                        <div class="flex">
                            <div class="flex-shrink-0 mr-3">
                                <div class="relative mt-1.5">
                                    <div class="h-3 w-3 rounded-full border-2 border-white bg-blue-500"></div>
                                    <div class="absolute h-full w-0.5 bg-gray-200 top-3 left-1.5"></div>
                                </div>
                            </div>
                            <div class="flex-1 pb-4">
                                <div class="text-sm font-medium text-gray-900">Profile information updated</div>
                                <div class="text-xs text-gray-500 mt-1">{{ now()->subHours(2)->format('M d, H:i') }}</div>
                            </div>
                        </div>
                        
                        <div class="flex">
                            <div class="flex-shrink-0 mr-3">
                                <div class="relative mt-1.5">
                                    <div class="h-3 w-3 rounded-full border-2 border-white bg-indigo-500"></div>
                                    <div class="absolute h-full w-0.5 bg-gray-200 top-3 left-1.5"></div>
                                </div>
                            </div>
                            <div class="flex-1 pb-4">
                                <div class="text-sm font-medium text-gray-900">Flight search: Nairobi to Dubai</div>
                                <div class="text-xs text-gray-500 mt-1">{{ now()->subHours(4)->format('M d, H:i') }}</div>
                            </div>
                        </div>
                        
                        <div class="flex">
                            <div class="flex-shrink-0 mr-3">
                                <div class="relative mt-1.5">
                                    <div class="h-3 w-3 rounded-full border-2 border-white bg-amber-500"></div>
                                    <div class="absolute h-full w-0.5 bg-gray-200 top-3 left-1.5"></div>
                                </div>
                            </div>
                            <div class="flex-1 pb-4">
                                <div class="text-sm font-medium text-gray-900">Password change attempt</div>
                                <div class="text-xs text-gray-500 mt-1">{{ now()->subDay()->format('M d, H:i') }}</div>
                            </div>
                        </div>
                        
                        <div class="flex">
                            <div class="flex-shrink-0 mr-3">
                                <div class="relative mt-1.5">
                                    <div class="h-3 w-3 rounded-full border-2 border-white bg-green-500"></div>
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="text-sm font-medium text-gray-900">Account created successfully</div>
                                <div class="text-xs text-gray-500 mt-1">{{ now()->subDays(7)->format('M d, H:i') }}</div>
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Quick Actions and Travel Tools Section -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Quick Actions Panel -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden" data-aos="fade-up" data-aos-delay="100">
            <div class="p-6">
                <div class="flex items-center mb-6">
                    <i data-lucide="zap" class="h-5 w-5 text-amber-500 mr-2"></i>
                    <h2 class="text-lg font-medium text-gray-900">Quick Actions</h2>
                </div>
                <p class="text-sm text-gray-500 mb-6">Frequently used actions</p>

                <div class="grid grid-cols-2 gap-4">
                    <!-- Flight Search -->
                    <a href="{{ route('flights.search') }}" class="flex flex-col items-center justify-center p-4 rounded-xl border border-gray-200 hover:border-blue-400 hover:bg-blue-50 transition-colors duration-300 group">
                        <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center mb-3 group-hover:bg-blue-200 transition-colors duration-300">
                            <i data-lucide="plane" class="h-6 w-6 text-blue-600"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700 group-hover:text-blue-700">Search Flights</span>
                    </a>
                    
                    <!-- Hotel Search -->
                    <a href="{{ route('hotels.search') }}" class="flex flex-col items-center justify-center p-4 rounded-xl border border-gray-200 hover:border-green-400 hover:bg-green-50 transition-colors duration-300 group">
                        <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center mb-3 group-hover:bg-green-200 transition-colors duration-300">
                            <i data-lucide="bed" class="h-6 w-6 text-green-600"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700 group-hover:text-green-700">Find Hotels</span>
                    </a>
                    
                    <!-- My Bookings -->
                    <a href="{{ route('dashboard.bookings') }}" class="flex flex-col items-center justify-center p-4 rounded-xl border border-gray-200 hover:border-indigo-400 hover:bg-indigo-50 transition-colors duration-300 group">
                        <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center mb-3 group-hover:bg-indigo-200 transition-colors duration-300">
                            <i data-lucide="calendar-check" class="h-6 w-6 text-indigo-600"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700 group-hover:text-indigo-700">My Bookings</span>
                    </a>
                    
                    <!-- Customer Support -->
                    <a href="{{ route('support') }}" class="flex flex-col items-center justify-center p-4 rounded-xl border border-gray-200 hover:border-purple-400 hover:bg-purple-50 transition-colors duration-300 group">
                        <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center mb-3 group-hover:bg-purple-200 transition-colors duration-300">
                            <i data-lucide="headphones" class="h-6 w-6 text-purple-600"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700 group-hover:text-purple-700">Support</span>
                    </a>
                    
                    @if(in_array(auth()->user()->role, ['admin', 'superadmin']))
                    <!-- Admin Quick Actions -->
                    <a href="{{ route('dashboard.users.index') }}" class="flex flex-col items-center justify-center p-4 rounded-xl border border-gray-200 hover:border-red-400 hover:bg-red-50 transition-colors duration-300 group">
                        <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center mb-3 group-hover:bg-red-200 transition-colors duration-300">
                            <i data-lucide="users" class="h-6 w-6 text-red-600"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700 group-hover:text-red-700">Manage Users</span>
                    </a>
                    
                    <a href="{{ route('dashboard.reports.earnings') }}" class="flex flex-col items-center justify-center p-4 rounded-xl border border-gray-200 hover:border-emerald-400 hover:bg-emerald-50 transition-colors duration-300 group">
                        <div class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center mb-3 group-hover:bg-emerald-200 transition-colors duration-300">
                            <i data-lucide="bar-chart-2" class="h-6 w-6 text-emerald-600"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700 group-hover:text-emerald-700">View Reports</span>
                    </a>
                    @else
                    <!-- User Quick Actions -->
                    <a href="{{ route('dashboard.wishlist') }}" class="flex flex-col items-center justify-center p-4 rounded-xl border border-gray-200 hover:border-pink-400 hover:bg-pink-50 transition-colors duration-300 group">
                        <div class="w-12 h-12 rounded-full bg-pink-100 flex items-center justify-center mb-3 group-hover:bg-pink-200 transition-colors duration-300">
                            <i data-lucide="heart" class="h-6 w-6 text-pink-600"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700 group-hover:text-pink-700">Wishlist</span>
                    </a>
                    <a href="{{ route('dashboard.settings') }}" class="flex flex-col items-center justify-center p-4 rounded-xl border border-gray-200 hover:border-gray-400 hover:bg-gray-50 transition-colors duration-300 group">
                        <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center mb-3 group-hover:bg-gray-200 transition-colors duration-300">
                            <i data-lucide="settings" class="h-6 w-6 text-gray-600"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900">Settings</span>
                    </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Travel Tools Panel -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden" data-aos="fade-up" data-aos-delay="200">
            <div class="p-6">
                <div class="flex items-center mb-6">
                    <i data-lucide="tool" class="h-5 w-5 text-green-500 mr-2"></i>
                    <h2 class="text-lg font-medium text-gray-900">Travel Tools</h2>
                </div>
                <p class="text-sm text-gray-500 mb-6">Useful tools for your travels</p>
                
                <div class="grid grid-cols-2 gap-4">
                    <!-- Currency Converter -->
                    {{-- <a href="{{ route('tools.currency_converter') }}" class="flex flex-col items-center justify-center p-4 rounded-xl border border-gray-200 hover:border-yellow-400 hover:bg-yellow-50 transition-colors duration-300 group">
                        <div class="w-12 h-12 rounded-full bg-yellow-100 flex items-center justify-center mb-3 group-hover:bg-yellow-200 transition-colors duration-300">
                            <i data-lucide="dollar-sign" class="h-6 w-6 text-yellow-600"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700 group-hover:text-yellow-700">Currency Converter</span>
                    </a>
                     --}}
                    <!-- Weather Forecast -->
                    {{-- <a href="{{ route('tools.weather_forecast') }}" class="flex flex-col items-center justify-center p-4 rounded-xl border border-gray-200 hover:border-blue-400 hover:bg-blue-50 transition-colors duration-300 group">
                        <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center mb-3 group-hover:bg-blue-200 transition-colors duration-300">
                            <i data-lucide="sun" class="h-6 w-6 text-blue-600"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700 group-hover:text-blue-700">Weather Forecast</span>
                    </a>
                     --}}
                    <!-- Travel Insurance -->
                    {{-- <a href="{{ route('tools.travel_insurance') }}" class="flex flex-col items-center justify-center p-4 rounded-xl border border-gray-200 hover:border-purple-400 hover:bg-purple-50 transition-colors duration-300 group">
                        <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center mb-3 group-hover:bg-purple-200 transition-colors duration-300">
                            <i data-lucide="shield" class="h-6 w-6 text-purple-600"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700 group-hover:text-purple-700">Travel Insurance</span>
                    </a> --}}
                    
                    <!-- Packing Checklist -->
                    {{-- <a href="{{ route('tools.packing_checklist') }}" class="flex flex-col items-center justify-center p-4 rounded-xl border border-gray-200 hover:border-pink-400 hover:bg-pink-50 transition-colors duration-300 group">
                        <div class="w-12 h-12 rounded-full bg-pink-100 flex items-center justify-center mb-3 group-hover:bg-pink-200 transition-colors duration-300">
                            <i data-lucide="check-square" class="h-6 w-6 text-pink-600"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700 group-hover:text-pink-700">Packing Checklist</span>
                    </a> --}}
                </div>
            </div>
        </div>

        <!-- News & Updates Panel -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden" data-aos="fade-up" data-aos-delay="300">
            <div class="p-6">
                <div class="flex items-center mb-6">
                    <i data-lucide="newspaper" class="h-5 w-5 text-blue-500 mr-2"></i>
                    <h2 class="text-lg font-medium text-gray-900">News & Updates</h2>
                </div>
                <p class="text-sm text-gray-500 mb-6">Latest travel information</p>
                
                <div class="space-y-4">
                    <div class="border-l-4 border-blue-500 pl-4 py-1">
                        <h3 class="text-sm font-medium text-gray-900">New Routes Available</h3>
                        <p class="text-xs text-gray-500 mt-1">New flights to Zanzibar starting next month</p>
                        <div class="flex items-center mt-2">
                            <i data-lucide="clock" class="h-3 w-3 text-gray-400 mr-1"></i>
                            <span class="text-xs text-gray-400">2 hours ago</span>
                        </div>
                    </div>
                    
                    <div class="border-l-4 border-amber-500 pl-4 py-1">
                        <h3 class="text-sm font-medium text-gray-900">Travel Advisory</h3>
                        <p class="text-xs text-gray-500 mt-1">Updated entry requirements for Dubai</p>
                        <div class="flex items-center mt-2">
                            <i data-lucide="clock" class="h-3 w-3 text-gray-400 mr-1"></i>
                            <span class="text-xs text-gray-400">1 day ago</span>
                        </div>
                    </div>
                    
                    <div class="border-l-4 border-green-500 pl-4 py-1">
                        <h3 class="text-sm font-medium text-gray-900">Special Offers</h3>
                        <p class="text-xs text-gray-500 mt-1">25% off on hotel bookings this weekend</p>
                        <div class="flex items-center mt-2">
                            <i data-lucide="clock" class="h-3 w-3 text-gray-400 mr-1"></i>
                            <span class="text-xs text-gray-400">3 days ago</span>
                        </div>
                    </div>
                    
                    <a href="#" class="block text-blue-600 text-sm font-medium hover:text-blue-800 hover:underline mt-4 text-center">
                        View All Updates
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Revenue Analytics (Admin/SuperAdmin) or Expense Tracker (User) -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        @if(in_array(auth()->user()->role, ['admin', 'superadmin']))
        <!-- Revenue Analytics -->
        <div class="col-span-3">
            <div class="bg-white rounded-xl shadow-md overflow-hidden h-full" data-aos="fade-up" data-aos-delay="100">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <div class="flex items-center mb-1">
                                <i data-lucide="line-chart" class="h-5 w-5 text-green-500 mr-2"></i>
                                <h2 class="text-lg font-medium text-gray-900">Revenue Analytics</h2>
                            </div>
                            <p class="text-sm text-gray-500">Monthly revenue breakdown</p>
                        </div>
                        <div class="flex space-x-2">
                            <select id="revenueFilter" class="rounded-md border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="6months">Last 6 Months</option>
                                <option value="year">This Year</option>
                                <option value="custom">Custom Range</option>
                            </select>
                            <button onclick="exportRevenue()" class="inline-flex items-center px-3 py-2 border border-blue-500 text-sm font-medium rounded-md text-blue-600 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                <i data-lucide="download" class="h-4 w-4 mr-1"></i>
                                Export
                            </button>
                        </div>
                    </div>
                    <div class="h-[350px]">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Top Destinations -->
        <div class="col-span-1">
            <div class="bg-white rounded-xl shadow-md overflow-hidden h-full" data-aos="fade-up" data-aos-delay="200">
                <div class="p-6">
                    <div class="flex items-center mb-6">
                        <i data-lucide="map-pin" class="h-5 w-5 text-blue-500 mr-2"></i>
                        <h2 class="text-lg font-medium text-gray-900">Top Destinations</h2>
                    </div>
                    <p class="text-sm text-gray-500 mb-6">Most booked destinations</p>
                    
                    <div class="space-y-4">
                        @foreach($topDestinations ?? [] as $index => $destination)
                        <div class="flex items-center py-2">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-600 font-medium mr-4">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-1">
                                <h3 class="text-sm font-medium text-gray-900">{{ $destination['name'] ?? 'Dubai, UAE' }}</h3>
                                <p class="text-xs text-gray-500">{{ $destination['bookings'] ?? 45 }} bookings</p>
                            </div>
                            <div class="text-sm font-medium text-green-600">
                                KES {{ number_format($destination['revenue'] ?? 125000, 0) }}
                            </div>
                        </div>
                        @endforeach
                        
                        @if(empty($topDestinations))
                        <!-- Sample data -->
                        <div class="flex items-center py-2">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-600 font-medium mr-4">
                                1
                            </div>
                            <div class="flex-1">
                                <h3 class="text-sm font-medium text-gray-900">Dubai, UAE</h3>
                                <p class="text-xs text-gray-500">45 bookings</p>
                            </div>
                            <div class="text-sm font-medium text-green-600">
                                KES 1,250,000
                            </div>
                        </div>
                        
                        <div class="flex items-center py-2">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-600 font-medium mr-4">
                                2
                            </div>
                            <div class="flex-1">
                                <h3 class="text-sm font-medium text-gray-900">Mombasa, Kenya</h3>
                                <p class="text-xs text-gray-500">38 bookings</p>
                            </div>
                            <div class="text-sm font-medium text-green-600">
                                KES 890,000
                            </div>
                        </div>
                        
                        <div class="flex items-center py-2">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-600 font-medium mr-4">
                                3
                            </div>
                            <div class="flex-1">
                                <h3 class="text-sm font-medium text-gray-900">London, UK</h3>
                                <p class="text-xs text-gray-500">32 bookings</p>
                            </div>
                            <div class="text-sm font-medium text-green-600">
                                KES 1,450,000
                            </div>
                        </div>
                        
                        <div class="flex items-center py-2">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-600 font-medium mr-4">
                                4
                            </div>
                            <div class="flex-1">
                                <h3 class="text-sm font-medium text-gray-900">Cape Town, SA</h3>
                                <p class="text-xs text-gray-500">28 bookings</p>
                            </div>
                            <div class="text-sm font-medium text-green-600">
                                KES 720,000
                            </div>
                        </div>
                        
                        <div class="flex items-center py-2">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-600 font-medium mr-4">
                                5
                            </div>
                            <div class="flex-1">
                                <h3 class="text-sm font-medium text-gray-900">Istanbul, Turkey</h3>
                                <p class="text-xs text-gray-500">25 bookings</p>
                            </div>
                            <div class="text-sm font-medium text-green-600">
                                KES 680,000
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @else
        <!-- User Expense Tracker -->
        <div class="col-span-3">
            <div class="bg-white rounded-xl shadow-md overflow-hidden h-full" data-aos="fade-up" data-aos-delay="100">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <div class="flex items-center mb-1">
                                <i data-lucide="wallet" class="h-5 w-5 text-blue-500 mr-2"></i>
                                <h2 class="text-lg font-medium text-gray-900">Travel Expense Tracker</h2>
                            </div>
                            <p class="text-sm text-gray-500">Track your travel spending</p>
                        </div>
                        <div class="flex space-x-2">
                            <select id="expenseFilter" class="rounded-md border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="month">This Month</option>
                                <option value="quarter">This Quarter</option>
                                <option value="year">This Year</option>
                            </select>
                            <button onclick="addExpense()" class="inline-flex items-center px-3 py-2 border border-blue-500 text-sm font-medium rounded-md text-blue-600 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                <i data-lucide="plus" class="h-4 w-4 mr-1"></i>
                                Add Expense
                            </button>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-4 gap-4 mb-6">
                        <div class="bg-blue-50 rounded-xl p-4 flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 mr-3">
                                <i data-lucide="plane" class="h-5 w-5 text-blue-600"></i>
                            </div>
                            <div>
                                <div class="text-base font-semibold text-gray-900">KES {{ number_format($userExpenses['flights'] ?? 85000, 0) }}</div>
                                <div class="text-xs text-gray-600">Flights</div>
                            </div>
                        </div>
                        
                        <div class="bg-green-50 rounded-xl p-4 flex items-center">
                            <div class="p-3 rounded-full bg-green-100 mr-3">
                                <i data-lucide="bed" class="h-5 w-5 text-green-600"></i>
                            </div>
                            <div>
                                <div class="text-base font-semibold text-gray-900">KES {{ number_format($userExpenses['hotels'] ?? 45000, 0) }}</div>
                                <div class="text-xs text-gray-600">Hotels</div>
                            </div>
                        </div>
                        
                        <div class="bg-amber-50 rounded-xl p-4 flex items-center">
                            <div class="p-3 rounded-full bg-amber-100 mr-3">
                                <i data-lucide="utensils" class="h-5 w-5 text-amber-600"></i>
                            </div>
                            <div>
                                <div class="text-base font-semibold text-gray-900">KES {{ number_format($userExpenses['food'] ?? 25000, 0) }}</div>
                                <div class="text-xs text-gray-600">Food & Dining</div>
                            </div>
                        </div>
                        
                        <div class="bg-purple-50 rounded-xl p-4 flex items-center">
                            <div class="p-3 rounded-full bg-purple-100 mr-3">
                                <i data-lucide="shopping-bag" class="h-5 w-5 text-purple-600"></i>
                            </div>
                            <div>
                                <div class="text-base font-semibold text-gray-900">KES {{ number_format($userExpenses['other'] ?? 15000, 0) }}</div>
                                <div class="text-xs text-gray-600">Other</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="h-[250px]">
                        <canvas id="expenseChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Budget Planning -->
        <div class="col-span-1">
            <div class="bg-white rounded-xl shadow-md overflow-hidden h-full" data-aos="fade-up" data-aos-delay="200">
                <div class="p-6">
                    <div class="flex items-center mb-6">
                        <i data-lucide="calculator" class="h-5 w-5 text-green-500 mr-2"></i>
                        <h2 class="text-lg font-medium text-gray-900">Budget Planner</h2>
                    </div>
                    <p class="text-sm text-gray-500 mb-6">Plan your travel budget</p>
                    
                    <div class="space-y-6">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center">
                                <div class="p-2 rounded-full bg-blue-100 mr-3">
                                    <i data-lucide="target" class="h-4 w-4 text-blue-600"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-900">Monthly Budget</span>
                            </div>
                            <span class="text-base font-semibold text-blue-600">KES {{ number_format($userBudget['monthly'] ?? 50000, 0) }}</span>
                        </div>
                        
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Spent this month</span>
                                <span class="text-gray-900 font-medium">{{ $budgetPercentage ?? 68 }}%</span>
                            </div>
                            <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full bg-blue-500 rounded-full" style="width: {{ $budgetPercentage ?? 68 }}%"></div>
                            </div>
                            <p class="text-sm text-gray-500">Remaining: KES {{ number_format($userBudget['remaining'] ?? 16000, 0) }}</p>
                        </div>
                        
                        <div class="space-y-3">
                            <h3 class="text-sm font-medium text-gray-900">Budget Tips</h3>
                            <div class="flex items-start space-x-2">
                                <i data-lucide="lightbulb" class="h-4 w-4 text-amber-500 mt-0.5"></i>
                                <span class="text-xs text-gray-600">Book flights in advance to save up to 30%</span>
                            </div>
                            <div class="flex items-start space-x-2">
                                <i data-lucide="lightbulb" class="h-4 w-4 text-amber-500 mt-0.5"></i>
                                <span class="text-xs text-gray-600">Compare hotel prices across platforms</span>
                            </div>
                            <div class="flex items-start space-x-2">
                                <i data-lucide="lightbulb" class="h-4 w-4 text-amber-500 mt-0.5"></i>
                                <span class="text-xs text-gray-600">Travel during off-peak seasons</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Support and Help Section -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- FAQ and Help -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden" data-aos="fade-up" data-aos-delay="100">
            <div class="p-6">
                <div class="flex items-center mb-6">
                    <i data-lucide="help-circle" class="h-5 w-5 text-blue-500 mr-2"></i>
                    <h2 class="text-lg font-medium text-gray-900">Frequently Asked Questions</h2>
                </div>
                <p class="text-sm text-gray-500 mb-6">Quick answers to common questions</p>
                
                <div class="space-y-4 divide-y divide-gray-100">
                    <div class="pt-4 first:pt-0">
                        <button class="flex w-full justify-between items-center text-left" onclick="toggleFAQ(this)">
                            <span class="text-sm font-medium text-gray-900">How can I cancel my booking?</span>
                            <i data-lucide="chevron-down" class="h-4 w-4 text-gray-500"></i>
                        </button>
                        <div class="mt-2 hidden">
                            <p class="text-sm text-gray-600">You can cancel your booking by visiting the "My Bookings" section and clicking the cancel button. Cancellation fees may apply based on the booking terms.</p>
                        </div>
                    </div>
                    
                    <div class="pt-4">
                        <button class="flex w-full justify-between items-center text-left" onclick="toggleFAQ(this)">
                            <span class="text-sm font-medium text-gray-900">What payment methods do you accept?</span>
                            <i data-lucide="chevron-down" class="h-4 w-4 text-gray-500"></i>
                        </button>
                        <div class="mt-2 hidden">
                            <p class="text-sm text-gray-600">We accept all major credit cards, M-Pesa, bank transfers, and PayPal for secure payments.</p>
                        </div>
                    </div>
                    
                    <div class="pt-4">
                        <button class="flex w-full justify-between items-center text-left" onclick="toggleFAQ(this)">
                            <span class="text-sm font-medium text-gray-900">How do I modify my booking?</span>
                            <i data-lucide="chevron-down" class="h-4 w-4 text-gray-500"></i>
                        </button>
                        <div class="mt-2 hidden">
                            <p class="text-sm text-gray-600">Booking modifications can be made through your dashboard. Additional charges may apply for changes to dates or destinations.</p>
                        </div>
                    </div>
                    
                    <div class="pt-4">
                        <button class="flex w-full justify-between items-center text-left" onclick="toggleFAQ(this)">
                            <span class="text-sm font-medium text-gray-900">Is travel insurance included?</span>
                            <i data-lucide="chevron-down" class="h-4 w-4 text-gray-500"></i>
                        </button>
                        <div class="mt-2 hidden">
                            <p class="text-sm text-gray-600">Travel insurance is optional and can be added during the booking process for comprehensive coverage.</p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 text-center">
                    <a href="{{ route('help') }}" class="inline-flex items-center px-4 py-2 border border-blue-500 text-sm font-medium rounded-md text-blue-600 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        View All FAQs
                    </a>
                </div>
            </div>
        </div>

        <!-- Contact Support -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden" data-aos="fade-up" data-aos-delay="200">
            <div class="p-6">
                <div class="flex items-center mb-6">
                    <i data-lucide="headphones" class="h-5 w-5 text-green-500 mr-2"></i>
                    <h2 class="text-lg font-medium text-gray-900">Need Help?</h2>
                </div>
                <p class="text-sm text-gray-500 mb-6">Get in touch with our support team</p>
                
                <div class="grid grid-cols-1 gap-4">
                    <div class="flex items-center justify-between p-4 rounded-xl border border-gray-200 hover:border-blue-300 transition-colors">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 mr-4">
                                <i data-lucide="phone" class="h-5 w-5 text-blue-600"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-900">Phone Support</h3>
                                <p class="text-sm text-gray-600">+254 700 123 456</p>
                                <p class="text-xs text-gray-500">Available 24/7</p>
                            </div>
                        </div>
                        <button class="inline-flex items-center px-3 py-2 border border-blue-500 text-sm font-medium rounded-md text-blue-600 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            Call Now
                        </button>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 rounded-xl border border-gray-200 hover:border-green-300 transition-colors">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 mr-4">
                                <i data-lucide="message-circle" class="h-5 w-5 text-green-600"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-900">Live Chat</h3>
                                <p class="text-sm text-gray-600">Chat with our agents</p>
                                <p class="text-xs text-gray-500">Response within 2 minutes</p>
                            </div>
                        </div>
                        <button onclick="openLiveChat()" class="inline-flex items-center px-3 py-2 border border-green-500 text-sm font-medium rounded-md text-green-600 bg-white hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                            Start Chat
                        </button>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 rounded-xl border border-gray-200 hover:border-amber-300 transition-colors">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-amber-100 mr-4">
                                <i data-lucide="mail" class="h-5 w-5 text-amber-600"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-900">Email Support</h3>
                                <p class="text-sm text-gray-600">support@ziaratravels.com</p>
                                <p class="text-xs text-gray-500">Response within 4 hours</p>
                            </div>
                        </div>
                        <button class="inline-flex items-center px-3 py-2 border border-amber-500 text-sm font-medium rounded-md text-amber-600 bg-white hover:bg-amber-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-colors">
                            Send Email
                        </button>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 rounded-xl border border-gray-200 hover:border-green-300 transition-colors">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 mr-4">
                                <i data-lucide="message-square" class="h-5 w-5 text-green-600"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-900">WhatsApp</h3>
                                <p class="text-sm text-gray-600">+254 700 123 456</p>
                                <p class="text-xs text-gray-500">Quick responses</p>
                            </div>
                        </div>
                        <button class="inline-flex items-center px-3 py-2 border border-green-500 text-sm font-medium rounded-md text-green-600 bg-white hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                            WhatsApp
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@section('scripts')
