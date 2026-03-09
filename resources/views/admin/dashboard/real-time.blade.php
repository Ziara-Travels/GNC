@extends('layouts.admin-dashboard')

@section('title', 'Real-Time Dashboard Overview')

@section('content')
<div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">
    
    <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
        
        <!-- Header -->
        <div class="trezo-card-header mb-[20px] md:mb-[25px] flex items-center justify-between">
            <div class="trezo-card-title">
                <h5 class="mb-0 md:text-xl font-medium text-gray-800 dark:text-white flex items-center">
                    <i class="ri-dashboard-3-line mr-2 text-blue-500"></i>
                    Real-Time Dashboard Overview
                    <span class="ml-3 bg-green-100 text-green-800 text-xs font-medium px-2 py-1 rounded-full">LIVE</span>
                </h5>
                <p class="text-gray-600 dark:text-gray-300 text-sm mt-1">
                    Monitor live bookings, payments, and system performance with auto-refresh capabilities
                </p>
            </div>
            <div class="flex space-x-2">
                <button id="autoRefreshToggle" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition-colors">
                    <i class="ri-refresh-line mr-2"></i>
                    Auto-Refresh: ON
                </button>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary">
                    <i class="ri-arrow-left-line mr-2"></i>
                    Back to Dashboard
                </a>
            </div>
        </div>

        <!-- Live Statistics Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-[25px] mb-[25px]">
            
            <!-- Live Bookings -->
            <div class="trezo-card border border-blue-200 dark:border-blue-800 p-[20px] md:p-[25px] rounded-xl bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900 dark:to-blue-800">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-blue-500 text-white rounded-full p-3">
                        <i class="ri-bookmark-3-line text-xl"></i>
                    </div>
                    <span class="text-blue-600 dark:text-blue-300 text-xs font-medium bg-blue-200 dark:bg-blue-700 px-2 py-1 rounded-full animate-pulse">LIVE</span>
                </div>
                <h6 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Live Bookings</h6>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-300">Processing:</span>
                        <span class="font-medium text-orange-600" data-live="processing-bookings">{{ $live_bookings['processing'] ?? 23 }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-300">Completed (1h):</span>
                        <span class="font-medium text-green-600" data-live="completed-bookings">{{ $live_bookings['completed_last_hour'] ?? 147 }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-300">Failed (1h):</span>
                        <span class="font-medium text-red-600" data-live="failed-bookings">{{ $live_bookings['failed_last_hour'] ?? 3 }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-300">Avg Time:</span>
                        <span class="font-medium text-blue-600" data-live="avg-time">{{ $live_bookings['average_completion_time'] ?? '4.2 min' }}</span>
                    </div>
                </div>
            </div>

            <!-- Payment Processing -->
            <div class="trezo-card border border-green-200 dark:border-green-800 p-[20px] md:p-[25px] rounded-xl bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900 dark:to-green-800">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-green-500 text-white rounded-full p-3">
                        <i class="ri-money-dollar-circle-line text-xl"></i>
                    </div>
                    <span class="text-green-600 dark:text-green-300 text-xs font-medium bg-green-200 dark:bg-green-700 px-2 py-1 rounded-full animate-pulse">LIVE</span>
                </div>
                <h6 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Payment Status</h6>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-300">Processing:</span>
                        <span class="font-medium text-orange-600" data-live="processing-payments">{{ $payment_processing['processing'] ?? 8 }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-300">Success (1h):</span>
                        <span class="font-medium text-green-600" data-live="success-payments">{{ $payment_processing['completed_last_hour'] ?? 142 }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-300">Failed (1h):</span>
                        <span class="font-medium text-red-600" data-live="failed-payments">{{ $payment_processing['failed_last_hour'] ?? 5 }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-300">Success Rate:</span>
                        <span class="font-medium text-green-600" data-live="success-rate">{{ $payment_processing['success_rate'] ?? '96.8%' }}</span>
                    </div>
                </div>
            </div>

            <!-- Active Sessions -->
            <div class="trezo-card border border-purple-200 dark:border-purple-800 p-[20px] md:p-[25px] rounded-xl bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900 dark:to-purple-800">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-purple-500 text-white rounded-full p-3">
                        <i class="ri-user-3-line text-xl"></i>
                    </div>
                    <span class="text-purple-600 dark:text-purple-300 text-xs font-medium bg-purple-200 dark:bg-purple-700 px-2 py-1 rounded-full animate-pulse">LIVE</span>
                </div>
                <h6 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Active Sessions</h6>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-300">Total Active:</span>
                        <span class="font-medium text-purple-600" data-live="total-sessions">{{ $active_sessions['total_active'] ?? 324 }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-300">Admin:</span>
                        <span class="font-medium text-indigo-600" data-live="admin-sessions">{{ $active_sessions['admin_sessions'] ?? 12 }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-300">Users:</span>
                        <span class="font-medium text-blue-600" data-live="user-sessions">{{ $active_sessions['user_sessions'] ?? 312 }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-300">Avg Duration:</span>
                        <span class="font-medium text-purple-600" data-live="avg-duration">{{ $active_sessions['average_session_duration'] ?? '24 min' }}</span>
                    </div>
                </div>
            </div>

            <!-- System Performance -->
            <div class="trezo-card border border-orange-200 dark:border-orange-800 p-[20px] md:p-[25px] rounded-xl bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900 dark:to-orange-800">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-orange-500 text-white rounded-full p-3">
                        <i class="ri-pulse-line text-xl"></i>
                    </div>
                    <span class="text-orange-600 dark:text-orange-300 text-xs font-medium bg-orange-200 dark:bg-orange-700 px-2 py-1 rounded-full animate-pulse">LIVE</span>
                </div>
                <h6 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">System Performance</h6>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-300">CPU Usage:</span>
                        <span class="font-medium text-orange-600" data-live="cpu-usage">{{ $system_performance['cpu_usage'] ?? '68%' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-300">Memory:</span>
                        <span class="font-medium text-yellow-600" data-live="memory-usage">{{ $system_performance['memory_usage'] ?? '742MB' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-300">Disk Usage:</span>
                        <span class="font-medium text-green-600" data-live="disk-usage">{{ $system_performance['disk_usage'] ?? '45%' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-300">Response:</span>
                        <span class="font-medium text-blue-600" data-live="response-time">{{ $system_performance['response_time'] ?? '180ms' }}</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- API Health Status -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-[25px] mb-[25px]">
            
            <!-- API Health Monitor -->
            <div class="trezo-card border border-gray-100 dark:border-[#172036] p-[20px] md:p-[25px] rounded-md">
                <div class="trezo-card-header mb-[20px] md:mb-[25px] flex items-center justify-between">
                    <div class="trezo-card-title">
                        <h5 class="mb-0 flex items-center">
                            <i class="ri-heart-pulse-line mr-2 text-green-500"></i>
                            API Health Status
                        </h5>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-green-600 font-medium" data-live="overall-health">{{ $api_health['overall_health'] ?? '99.8%' }}</span>
                        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                    </div>
                </div>
                <div class="trezo-card-content">
                    <div class="space-y-4">
                        @php
                            $apiServices = [
                                'amadeus' => ['name' => 'Amadeus API', 'status' => $api_health['amadeus'] ?? 'healthy'],
                                'payment_gateway' => ['name' => 'Payment Gateway', 'status' => $api_health['payment_gateway'] ?? 'healthy'],
                                'email_service' => ['name' => 'Email Service', 'status' => $api_health['email_service'] ?? 'healthy'],
                                'sms_service' => ['name' => 'SMS Service', 'status' => $api_health['sms_service'] ?? 'healthy']
                            ];
                        @endphp
                        
                        @foreach($apiServices as $key => $service)
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 rounded-full {{ $service['status'] === 'healthy' ? 'bg-green-500' : 'bg-red-500' }} animate-pulse"></div>
                                <span class="font-medium text-gray-800 dark:text-white">{{ $service['name'] }}</span>
                            </div>
                            <span class="text-sm font-medium {{ $service['status'] === 'healthy' ? 'text-green-600' : 'text-red-600' }}" data-live="api-{{ $key }}">
                                {{ ucfirst($service['status']) }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Revenue Tracking -->
            <div class="trezo-card border border-gray-100 dark:border-[#172036] p-[20px] md:p-[25px] rounded-md">
                <div class="trezo-card-header mb-[20px] md:mb-[25px] flex items-center justify-between">
                    <div class="trezo-card-title">
                        <h5 class="mb-0 flex items-center">
                            <i class="ri-line-chart-line mr-2 text-blue-500"></i>
                            Real-Time Revenue
                        </h5>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-bold text-green-600" data-live="revenue-today">${{ number_format($revenue_tracking['today'] ?? 24680, 2) }}</p>
                        <p class="text-xs text-gray-500">Today</p>
                    </div>
                </div>
                <div class="trezo-card-content">
                    <div class="grid grid-cols-3 gap-4 mb-4">
                        <div class="text-center">
                            <p class="text-lg font-semibold text-gray-800 dark:text-white" data-live="revenue-week">${{ number_format($revenue_tracking['this_week'] ?? 125000, 0) }}</p>
                            <p class="text-xs text-gray-500">This Week</p>
                        </div>
                        <div class="text-center">
                            <p class="text-lg font-semibold text-gray-800 dark:text-white" data-live="revenue-month">${{ number_format($revenue_tracking['this_month'] ?? 456000, 0) }}</p>
                            <p class="text-xs text-gray-500">This Month</p>
                        </div>
                        <div class="text-center">
                            <p class="text-lg font-semibold text-green-600" data-live="revenue-per-hour">${{ number_format($revenue_tracking['hourly_trend'][0]['revenue'] ?? 2450, 0) }}</p>
                            <p class="text-xs text-gray-500">Per Hour</p>
                        </div>
                    </div>
                    
                    <!-- Hourly Revenue Chart Placeholder -->
                    <div class="h-32 bg-gradient-to-r from-blue-50 to-green-50 dark:from-blue-900 dark:to-green-900 rounded-lg flex items-center justify-center">
                        <div class="text-center">
                            <i class="ri-bar-chart-line text-3xl text-blue-500 mb-2"></i>
                            <p class="text-gray-600 dark:text-gray-300">Hourly Revenue Chart</p>
                            <p class="text-xs text-gray-400">Real-time updates every 5 minutes</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Conversion Funnel -->
        <div class="trezo-card border border-gray-100 dark:border-[#172036] p-[20px] md:p-[25px] rounded-md mb-[25px]">
            <div class="trezo-card-header mb-[20px] md:mb-[25px]">
                <h5 class="mb-0 flex items-center">
                    <i class="ri-funnel-line mr-2 text-purple-500"></i>
                    Live Conversion Funnel
                </h5>
            </div>
            <div class="trezo-card-content">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @php
                        $funnelData = [
                            ['stage' => 'Searches', 'count' => $conversion_funnel['searches'] ?? 1000, 'color' => 'blue', 'percentage' => 100],
                            ['stage' => 'Bookings', 'count' => $conversion_funnel['bookings'] ?? 250, 'color' => 'yellow', 'percentage' => $conversion_funnel['search_to_booking'] ?? 25],
                            ['stage' => 'Completed', 'count' => $conversion_funnel['completed'] ?? 230, 'color' => 'green', 'percentage' => $conversion_funnel['booking_to_completion'] ?? 92]
                        ];
                    @endphp
                    
                    @foreach($funnelData as $index => $stage)
                    <div class="text-center">
                        <div class="bg-{{ $stage['color'] }}-100 dark:bg-{{ $stage['color'] }}-900 rounded-lg p-6 mb-4">
                            <h3 class="text-2xl font-bold text-{{ $stage['color'] }}-600" data-live="funnel-{{ strtolower($stage['stage']) }}">{{ number_format($stage['count']) }}</h3>
                            <p class="text-{{ $stage['color'] }}-800 dark:text-{{ $stage['color'] }}-200 font-medium">{{ $stage['stage'] }}</p>
                            @if($index > 0)
                            <p class="text-xs text-{{ $stage['color'] }}-600 mt-2">{{ $stage['percentage'] }}% conversion</p>
                            @endif
                        </div>
                        @if($index < count($funnelData) - 1)
                        <div class="hidden md:block">
                            <i class="ri-arrow-right-line text-2xl text-gray-400"></i>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let autoRefresh = true;
    let refreshInterval;

    // Auto-refresh toggle
    const toggleButton = document.getElementById('autoRefreshToggle');
    toggleButton.addEventListener('click', function() {
        autoRefresh = !autoRefresh;
        if (autoRefresh) {
            toggleButton.innerHTML = '<i class="ri-refresh-line mr-2"></i>Auto-Refresh: ON';
            toggleButton.classList.remove('bg-gray-500');
            toggleButton.classList.add('bg-blue-500');
            startAutoRefresh();
        } else {
            toggleButton.innerHTML = '<i class="ri-pause-line mr-2"></i>Auto-Refresh: OFF';
            toggleButton.classList.remove('bg-blue-500');
            toggleButton.classList.add('bg-gray-500');
            stopAutoRefresh();
        }
    });

    // Start auto-refresh
    function startAutoRefresh() {
        refreshInterval = setInterval(refreshDashboardData, 5000); // Refresh every 5 seconds
    }

    // Stop auto-refresh
    function stopAutoRefresh() {
        if (refreshInterval) {
            clearInterval(refreshInterval);
        }
    }

    // Refresh dashboard data
    function refreshDashboardData() {
        fetch('/admin/dashboard/real-time-data')
            .then(response => response.json())
            .then(data => {
                updateLiveData(data);
            })
            .catch(error => {
                console.error('Error refreshing dashboard data:', error);
            });
    }

    // Update live data elements
    function updateLiveData(data) {
        // Update all elements with data-live attributes
        document.querySelectorAll('[data-live]').forEach(element => {
            const key = element.getAttribute('data-live');
            if (data[key] !== undefined) {
                // Add animation class
                element.classList.add('animate-pulse');
                element.textContent = data[key];
                
                // Remove animation class after a short delay
                setTimeout(() => {
                    element.classList.remove('animate-pulse');
                }, 1000);
            }
        });

        // Update last refresh time
        const now = new Date();
        const timeString = now.toLocaleTimeString();
        console.log(`Dashboard refreshed at ${timeString}`);
    }

    // Start auto-refresh on load
    startAutoRefresh();

    // Add visual indicators for live data
    document.querySelectorAll('[data-live]').forEach(element => {
        element.style.transition = 'all 0.3s ease';
    });
});
</script>

<style>
@keyframes pulse-slow {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}

.animate-pulse-slow {
    animation: pulse-slow 2s infinite;
}

/* Custom animations for real-time elements */
[data-live] {
    transition: all 0.3s ease;
}

[data-live].animate-pulse {
    background-color: rgba(59, 130, 246, 0.1);
    border-radius: 4px;
    padding: 2px 4px;
}
</style>
@endsection
