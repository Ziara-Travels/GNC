@extends('layouts.superadmin-dashboard')

@section('title', 'Real-Time Dashboard Overview')

@section('content')
<div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">
    
    <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
        
        <!-- Header -->
        <div class="trezo-card-header mb-[20px] md:mb-[25px] flex items-center justify-between">
            <div class="trezo-card-title">
                <h5 class="mb-0 md:text-xl font-medium text-gray-800 dark:text-white flex items-center">
                    <i class="ri-dashboard-3-line mr-2 text-blue-500"></i>
                    Real-Time Superadmin Dashboard
                    <span class="ml-3 bg-green-100 text-green-800 text-xs font-medium px-2 py-1 rounded-full animate-pulse">LIVE</span>
                </h5>
                <p class="text-gray-600 dark:text-gray-300 text-sm mt-1">
                    Monitor live system activity, user interactions, and business metrics with auto-refresh capabilities
                </p>
            </div>
            <div class="flex space-x-2">
                <button id="autoRefreshToggle" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition-colors">
                    <i class="ri-refresh-line mr-2"></i>
                    Auto-Refresh: ON
                </button>
                <a href="{{ route('superadmin.dashboard') }}" class="btn btn-outline-primary">
                    <i class="ri-arrow-left-line mr-2"></i>
                    Back to Dashboard
                </a>
            </div>
        </div>

        <!-- Real-Time Statistics Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-[25px] mb-[25px]">
            
            <!-- Live User Activity -->
            <div class="trezo-card border border-blue-200 dark:border-blue-800 p-[20px] md:p-[25px] rounded-xl bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900 dark:to-blue-800">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-blue-500 text-white rounded-full p-3">
                        <i class="ri-user-line text-xl"></i>
                    </div>
                    <span class="text-blue-600 dark:text-blue-300 text-xs font-medium bg-blue-200 dark:bg-blue-700 px-2 py-1 rounded-full animate-pulse">LIVE</span>
                </div>
                <h6 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">User Activity</h6>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-300">Active Sessions:</span>
                        <span class="font-medium text-blue-600" data-live="active-sessions">{{ $liveStats['active_sessions']['total'] ?? 245 }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-300">New Signups (1h):</span>
                        <span class="font-medium text-green-600" data-live="new-signups">{{ $liveStats['user_activity']['new_signups'] ?? 12 }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-300">Wishlists Added:</span>
                        <span class="font-medium text-purple-600" data-live="wishlists-added">{{ $liveStats['user_activity']['wishlists_added'] ?? 8 }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-300">Avg Session:</span>
                        <span class="font-medium text-blue-600" data-live="avg-session">{{ $liveStats['active_sessions']['average_session_duration'] ?? '24 min' }}</span>
                    </div>
                </div>
            </div>

            <!-- Live Bookings -->
            <div class="trezo-card border border-green-200 dark:border-green-800 p-[20px] md:p-[25px] rounded-xl bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900 dark:to-green-800">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-green-500 text-white rounded-full p-3">
                        <i class="ri-bookmark-3-line text-xl"></i>
                    </div>
                    <span class="text-green-600 dark:text-green-300 text-xs font-medium bg-green-200 dark:bg-green-700 px-2 py-1 rounded-full animate-pulse">LIVE</span>
                </div>
                <h6 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Live Bookings</h6>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-300">Processing:</span>
                        <span class="font-medium text-orange-600" data-live="processing-bookings">{{ $liveStats['live_bookings']['processing'] ?? 23 }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-300">Completed (1h):</span>
                        <span class="font-medium text-green-600" data-live="completed-bookings">{{ $liveStats['live_bookings']['completed_last_hour'] ?? 147 }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-300">Failed (1h):</span>
                        <span class="font-medium text-red-600" data-live="failed-bookings">{{ $liveStats['live_bookings']['failed_last_hour'] ?? 3 }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-300">Avg Time:</span>
                        <span class="font-medium text-green-600" data-live="avg-booking-time">{{ $liveStats['live_bookings']['average_completion_time'] ?? '4.2 min' }}</span>
                    </div>
                </div>
            </div>

            <!-- Payment Processing -->
            <div class="trezo-card border border-purple-200 dark:border-purple-800 p-[20px] md:p-[25px] rounded-xl bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900 dark:to-purple-800">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-purple-500 text-white rounded-full p-3">
                        <i class="ri-money-dollar-circle-line text-xl"></i>
                    </div>
                    <span class="text-purple-600 dark:text-purple-300 text-xs font-medium bg-purple-200 dark:bg-purple-700 px-2 py-1 rounded-full animate-pulse">LIVE</span>
                </div>
                <h6 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Payment Status</h6>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-300">Processing:</span>
                        <span class="font-medium text-orange-600" data-live="processing-payments">{{ $liveStats['payment_processing']['processing'] ?? 8 }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-300">Success (1h):</span>
                        <span class="font-medium text-green-600" data-live="success-payments">{{ $liveStats['payment_processing']['completed_last_hour'] ?? 142 }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-300">Failed (1h):</span>
                        <span class="font-medium text-red-600" data-live="failed-payments">{{ $liveStats['payment_processing']['failed_last_hour'] ?? 5 }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-300">Success Rate:</span>
                        <span class="font-medium text-green-600" data-live="success-rate">{{ $liveStats['payment_processing']['success_rate'] ?? '96.8%' }}</span>
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
                        <span class="text-gray-600 dark:text-gray-300">API Health:</span>
                        <span class="font-medium text-green-600" data-live="api-health">{{ $liveStats['system_performance']['api_health'] ?? '99.8%' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-300">Response Time:</span>
                        <span class="font-medium text-blue-600" data-live="response-time">{{ $liveStats['system_performance']['response_time'] ?? '180ms' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-300">Uptime:</span>
                        <span class="font-medium text-green-600" data-live="uptime">{{ $liveStats['system_performance']['uptime'] ?? '99.9%' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-300">Server Load:</span>
                        <span class="font-medium text-orange-600" data-live="server-load">{{ $liveStats['system_performance']['server_load'] ?? '68%' }}</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- Revenue & Analytics Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-[25px] mb-[25px]">
            
            <!-- Real-Time Revenue -->
            <div class="trezo-card border border-gray-100 dark:border-[#172036] p-[20px] md:p-[25px] rounded-md">
                <div class="trezo-card-header mb-[20px] md:mb-[25px] flex items-center justify-between">
                    <div class="trezo-card-title">
                        <h5 class="mb-0 flex items-center">
                            <i class="ri-line-chart-line mr-2 text-green-500"></i>
                            Real-Time Revenue
                        </h5>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-bold text-green-600" data-live="revenue-today">${{ number_format($liveStats['revenue_tracking']['today'] ?? 24680, 2) }}</p>
                        <p class="text-xs text-gray-500">Today</p>
                    </div>
                </div>
                <div class="trezo-card-content">
                    <div class="grid grid-cols-3 gap-4 mb-4">
                        <div class="text-center">
                            <p class="text-lg font-semibold text-gray-800 dark:text-white" data-live="revenue-week">${{ number_format($liveStats['revenue_tracking']['this_week'] ?? 125000, 0) }}</p>
                            <p class="text-xs text-gray-500">This Week</p>
                        </div>
                        <div class="text-center">
                            <p class="text-lg font-semibold text-gray-800 dark:text-white" data-live="revenue-month">${{ number_format($liveStats['revenue_tracking']['this_month'] ?? 456000, 0) }}</p>
                            <p class="text-xs text-gray-500">This Month</p>
                        </div>
                        <div class="text-center">
                            <p class="text-lg font-semibold text-green-600" data-live="revenue-per-hour">${{ number_format($liveStats['revenue_tracking']['hourly_trend'][0]['revenue'] ?? 2450, 0) }}</p>
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

            <!-- User Management Overview -->
            <div class="trezo-card border border-gray-100 dark:border-[#172036] p-[20px] md:p-[25px] rounded-md">
                <div class="trezo-card-header mb-[20px] md:mb-[25px] flex items-center justify-between">
                    <div class="trezo-card-title">
                        <h5 class="mb-0 flex items-center">
                            <i class="ri-user-settings-line mr-2 text-purple-500"></i>
                            User Management
                        </h5>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-purple-600 font-medium" data-live="total-users">{{ $liveStats['user_management']['total_users'] ?? 2847 }}</span>
                        <div class="w-2 h-2 bg-purple-500 rounded-full animate-pulse"></div>
                    </div>
                </div>
                <div class="trezo-card-content">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 rounded-full bg-blue-500"></div>
                                <span class="font-medium text-gray-800 dark:text-white">Superadmins</span>
                            </div>
                            <span class="text-sm font-medium text-blue-600" data-live="superadmin-count">
                                {{ $liveStats['user_management']['superadmin_count'] ?? 3 }}
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 rounded-full bg-green-500"></div>
                                <span class="font-medium text-gray-800 dark:text-white">Admins</span>
                            </div>
                            <span class="text-sm font-medium text-green-600" data-live="admin-count">
                                {{ $liveStats['user_management']['admin_count'] ?? 15 }}
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 rounded-full bg-purple-500"></div>
                                <span class="font-medium text-gray-800 dark:text-white">Regular Users</span>
                            </div>
                            <span class="text-sm font-medium text-purple-600" data-live="user-count">
                                {{ $liveStats['user_management']['user_count'] ?? 2829 }}
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 rounded-full bg-orange-500"></div>
                                <span class="font-medium text-gray-800 dark:text-white">Suspended</span>
                            </div>
                            <span class="text-sm font-medium text-orange-600" data-live="suspended-count">
                                {{ $liveStats['user_management']['suspended_count'] ?? 7 }}
                            </span>
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
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    @php
                        $funnelData = [
                            ['stage' => 'Visitors', 'count' => $liveStats['conversion_funnel']['visitors'] ?? 10000, 'color' => 'blue', 'percentage' => 100],
                            ['stage' => 'Searches', 'count' => $liveStats['conversion_funnel']['searches'] ?? 8000, 'color' => 'indigo', 'percentage' => 80],
                            ['stage' => 'Bookings', 'count' => $liveStats['conversion_funnel']['bookings'] ?? 800, 'color' => 'yellow', 'percentage' => 10],
                            ['stage' => 'Completed', 'count' => $liveStats['conversion_funnel']['completed'] ?? 750, 'color' => 'green', 'percentage' => 93.75]
                        ];
                    @endphp
                    
                    @foreach($funnelData as $index => $stage)
                    <div class="text-center">
                        <div class="bg-{{ $stage['color'] }}-100 dark:bg-{{ $stage['color'] }}-900 rounded-lg p-6 mb-4">
                            <h3 class="text-2xl font-bold text-{{ $stage['color'] }}-600" data-live="funnel-{{ strtolower($stage['stage']) }}">{{ number_format($stage['count']) }}</h3>
                            <p class="text-{{ $stage['color'] }}-800 dark:text-{{ $stage['color'] }}-200 font-medium">{{ $stage['stage'] }}</p>
                            @if($index > 0)
                            <p class="text-xs text-{{ $stage['color'] }}-600 mt-2">{{ $stage['percentage'] }}% from previous</p>
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

        <!-- Recent Activity Feed -->
        <div class="trezo-card border border-gray-100 dark:border-[#172036] p-[20px] md:p-[25px] rounded-md">
            <div class="trezo-card-header mb-[20px] md:mb-[25px]">
                <h5 class="mb-0 flex items-center">
                    <i class="ri-time-line mr-2 text-blue-500"></i>
                    Live Activity Feed
                    <span class="ml-3 bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded-full">REAL-TIME</span>
                </h5>
            </div>
            <div class="trezo-card-content">
                <div class="space-y-3 max-h-80 overflow-y-auto" id="live-activity-feed">
                    <!-- Activity items will be populated via JavaScript -->
                    <div class="flex items-center p-3 bg-green-50 dark:bg-green-900 rounded-lg">
                        <div class="w-2 h-2 bg-green-500 rounded-full mr-3 animate-pulse"></div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-800 dark:text-white">New booking completed</p>
                            <p class="text-xs text-gray-500">Flight NBO → LHR - John Doe - $1,250</p>
                        </div>
                        <span class="text-xs text-gray-400">Just now</span>
                    </div>
                    
                    <div class="flex items-center p-3 bg-blue-50 dark:bg-blue-900 rounded-lg">
                        <div class="w-2 h-2 bg-blue-500 rounded-full mr-3 animate-pulse"></div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-800 dark:text-white">New user registration</p>
                            <p class="text-xs text-gray-500">jane.smith@email.com joined as user</p>
                        </div>
                        <span class="text-xs text-gray-400">2 min ago</span>
                    </div>
                    
                    <div class="flex items-center p-3 bg-purple-50 dark:bg-purple-900 rounded-lg">
                        <div class="w-2 h-2 bg-purple-500 rounded-full mr-3 animate-pulse"></div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-800 dark:text-white">Wishlist item added</p>
                            <p class="text-xs text-gray-500">Hotel booking for Maldives Resort</p>
                        </div>
                        <span class="text-xs text-gray-400">5 min ago</span>
                    </div>
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
        fetch('{{ route("superadmin.dashboard.real-time-data") }}')
            .then(response => response.json())
            .then(data => {
                updateLiveData(data);
                updateActivityFeed(data.recent_activities || []);
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

    // Update activity feed
    function updateActivityFeed(activities) {
        const feed = document.getElementById('live-activity-feed');
        if (activities.length > 0) {
            // Add new activities to the top of the feed
            activities.forEach(activity => {
                const activityElement = createActivityElement(activity);
                feed.insertBefore(activityElement, feed.firstChild);
            });
            
            // Remove old activities to prevent infinite growth
            while (feed.children.length > 10) {
                feed.removeChild(feed.lastChild);
            }
        }
    }

    // Create activity element
    function createActivityElement(activity) {
        const div = document.createElement('div');
        div.className = 'flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg';
        div.innerHTML = `
            <div class="w-2 h-2 bg-${activity.color || 'blue'}-500 rounded-full mr-3 animate-pulse"></div>
            <div class="flex-1">
                <p class="text-sm font-medium text-gray-800 dark:text-white">${activity.title}</p>
                <p class="text-xs text-gray-500">${activity.description}</p>
            </div>
            <span class="text-xs text-gray-400">${activity.time || 'Just now'}</span>
        `;
        return div;
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

/* Live indicator animation */
@keyframes live-pulse {
    0% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7); }
    70% { box-shadow: 0 0 0 10px rgba(34, 197, 94, 0); }
    100% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
}

.animate-pulse {
    animation: live-pulse 2s infinite;
}
</style>
@endsection