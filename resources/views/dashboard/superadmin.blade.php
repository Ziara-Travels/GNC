@extends('layouts.superadmin-dashboard')

@section('content')
<div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">
    
    <!-- Real-Time Alerts Bar -->
    <div class="real-time-alerts bg-gradient-to-r from-blue-600 to-purple-600 text-white p-4 mb-6" id="realTimeAlertsBar">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse"></div>
                    <span class="font-medium">Live System Status</span>
                </div>
                <div class="text-sm opacity-90" id="lastUpdate">
                    Last updated: <span id="updateTime">{{ now()->format('H:i:s') }}</span>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <div class="text-sm">
                    <span class="opacity-90">Active Users:</span>
                    <span class="font-bold" id="activeUsers">{{ $activeUsers ?? 0 }}</span>
                </div>
                <div class="text-sm">
                    <span class="opacity-90">Pending Bookings:</span>
                    <span class="font-bold" id="pendingBookings">{{ $pendingBookings ?? 0 }}</span>
                </div>
                <button id="realTimeToggle" class="bg-white/20 hover:bg-white/30 px-3 py-1 rounded-md text-sm transition-colors">
                    Live Mode: ON
                </button>
            </div>
        </div>
    </div>

    <!-- Live Activity Feed -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-6">
        <div class="lg:col-span-3">
            <!-- Main Dashboard Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-6">
                
                <!-- Real-Time Revenue Card -->
                <div class="trezo-card bg-white dark:bg-[#0c1427] p-6 rounded-xl border border-gray-100 dark:border-[#172036]">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-green-100 dark:bg-green-900/30 text-green-600 rounded-full p-3">
                            <i class="material-symbols-outlined !text-[24px]">trending_up</i>
                        </div>
                        <span class="text-xs font-medium text-green-600 bg-green-100 dark:bg-green-900/30 px-2 py-1 rounded-full">
                            +{{ $revenueGrowth ?? 0 }}%
                        </span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-white" data-live="totalRevenue">
                        ${{ number_format($totalRevenue ?? 0, 2) }}
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">Total Revenue (Live)</p>
                    <div class="mt-3 text-xs text-gray-500">
                        Last transaction: <span id="lastTransaction">{{ $lastTransactionTime ?? 'N/A' }}</span>
                    </div>
                </div>

                <!-- Live Bookings Card -->
                <div class="trezo-card bg-white dark:bg-[#0c1427] p-6 rounded-xl border border-gray-100 dark:border-[#172036]">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-blue-100 dark:bg-blue-900/30 text-blue-600 rounded-full p-3">
                            <i class="material-symbols-outlined !text-[24px]">flight_takeoff</i>
                        </div>
                        <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-white" data-live="totalBookings">
                        {{ $totalBookings ?? 0 }}
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">Total Bookings (Live)</p>
                    <div class="mt-3 text-xs text-gray-500">
                        Today: <span data-live="todayBookings">{{ $todayBookings ?? 0 }}</span>
                    </div>
                </div>

                <!-- Active Users Card -->
                <div class="trezo-card bg-white dark:bg-[#0c1427] p-6 rounded-xl border border-gray-100 dark:border-[#172036]">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-purple-100 dark:bg-purple-900/30 text-purple-600 rounded-full p-3">
                            <i class="material-symbols-outlined !text-[24px]">group</i>
                        </div>
                        <span class="text-xs font-medium text-purple-600 bg-purple-100 dark:bg-purple-900/30 px-2 py-1 rounded-full">
                            Live
                        </span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-white" data-live="activeUsers">
                        {{ $activeUsers ?? 0 }}
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">Active Users</p>
                    <div class="mt-3 text-xs text-gray-500">
                        Online now: <span data-live="onlineUsers">{{ $onlineUsers ?? 0 }}</span>
                    </div>
                </div>

                <!-- System Health Card -->
                <div class="trezo-card bg-white dark:bg-[#0c1427] p-6 rounded-xl border border-gray-100 dark:border-[#172036]">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-orange-100 dark:bg-orange-900/30 text-orange-600 rounded-full p-3">
                            <i class="material-symbols-outlined !text-[24px]">health_and_safety</i>
                        </div>
                        <div class="flex items-center space-x-1">
                            <div class="w-2 h-2 bg-green-500 rounded-full" id="systemStatusIndicator"></div>
                            <span class="text-xs text-green-600" id="systemStatusText">Healthy</span>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-white" data-live="systemHealth">
                        {{ $systemHealth ?? 100 }}%
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">System Health</p>
                    <div class="mt-3 text-xs text-gray-500">
                        APIs: <span data-live="apiStatus">{{ $apiStatus ?? 'All Online' }}</span>
                    </div>
                </div>

            </div>

            <!-- User Management Quick Actions -->
            <div class="trezo-card bg-white dark:bg-[#0c1427] p-6 rounded-xl border border-gray-100 dark:border-[#172036] mb-6">
                <div class="trezo-card-header mb-6">
                    <h5 class="text-lg font-semibold text-gray-800 dark:text-white">Quick User Management</h5>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">Manage users and permissions with role protection</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <a href="{{ route('superadmin.users.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white p-4 rounded-lg transition-colors flex items-center space-x-3">
                        <i class="material-symbols-outlined">person_add</i>
                        <div>
                            <div class="font-medium">Create User</div>
                            <div class="text-sm opacity-90">Add superadmin, admin, or user</div>
                        </div>
                    </a>
                    
                    <a href="{{ route('superadmin.users.advanced-management') }}" class="bg-green-500 hover:bg-green-600 text-white p-4 rounded-lg transition-colors flex items-center space-x-3">
                        <i class="material-symbols-outlined">admin_panel_settings</i>
                        <div>
                            <div class="font-medium">Manage Users</div>
                            <div class="text-sm opacity-90">Advanced user operations</div>
                        </div>
                    </a>
                    
                    <a href="{{ route('superadmin.proxy-booking.flights') }}" class="bg-purple-500 hover:bg-purple-600 text-white p-4 rounded-lg transition-colors flex items-center space-x-3">
                        <i class="material-symbols-outlined">support_agent</i>
                        <div>
                            <div class="font-medium">Proxy Booking</div>
                            <div class="text-sm opacity-90">Book on behalf of users</div>
                        </div>
                    </a>
                </div>
                
                <!-- Parent Superadmin Protection Notice -->
                @if(Auth::user()->is_parent_superadmin ?? false)
                <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                    <div class="flex items-center space-x-2">
                        <i class="material-symbols-outlined text-yellow-600">shield</i>
                        <div>
                            <div class="font-medium text-yellow-800 dark:text-yellow-200">Parent Superadmin Account</div>
                            <div class="text-sm text-yellow-700 dark:text-yellow-300">This account cannot be deleted and has full system privileges. Created superadmins cannot delete this account.</div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Booking History Summary -->
            <div class="trezo-card bg-white dark:bg-[#0c1427] p-6 rounded-xl border border-gray-100 dark:border-[#172036] mb-6">
                <div class="trezo-card-header mb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h5 class="text-lg font-semibold text-gray-800 dark:text-white">All Users Booking History</h5>
                            <p class="text-gray-600 dark:text-gray-400 text-sm">Complete booking overview across all user types</p>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('superadmin.bookings.all') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                                View All Bookings
                            </a>
                            <a href="{{ route('superadmin.proxy-booking.manage') }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                                Manage Proxy Bookings
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg border border-blue-200 dark:border-blue-800">
                        <div class="text-2xl font-bold text-blue-600">{{ $superadminBookings ?? 0 }}</div>
                        <div class="text-sm text-blue-700 dark:text-blue-300">Superadmin Bookings</div>
                    </div>
                    <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg border border-green-200 dark:border-green-800">
                        <div class="text-2xl font-bold text-green-600">{{ $adminBookings ?? 0 }}</div>
                        <div class="text-sm text-green-700 dark:text-green-300">Admin Bookings</div>
                    </div>
                    <div class="bg-purple-50 dark:bg-purple-900/20 p-4 rounded-lg border border-purple-200 dark:border-purple-800">
                        <div class="text-2xl font-bold text-purple-600">{{ $userBookings ?? 0 }}</div>
                        <div class="text-sm text-purple-700 dark:text-purple-300">User Bookings</div>
                    </div>
                    <div class="bg-orange-50 dark:bg-orange-900/20 p-4 rounded-lg border border-orange-200 dark:border-orange-800">
                        <div class="text-2xl font-bold text-orange-600">{{ $proxyBookings ?? 0 }}</div>
                        <div class="text-sm text-orange-700 dark:text-orange-300">Proxy Bookings</div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Real-Time Activity Sidebar -->
        <div class="space-y-6">
            
            <!-- Live Notifications Panel -->
            <div class="trezo-card bg-white dark:bg-[#0c1427] p-6 rounded-xl border border-gray-100 dark:border-[#172036]">
                <div class="trezo-card-header mb-4">
                    <div class="flex items-center justify-between">
                        <h5 class="font-semibold text-gray-800 dark:text-white">Live Activity</h5>
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                            <span class="text-xs text-green-600">Live</span>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-3" id="liveActivityFeed">
                    <!-- Live activity items -->
                    <div class="activity-item flex items-start space-x-3 p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg">
                        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 text-blue-600 rounded-full flex items-center justify-center">
                            <i class="material-symbols-outlined !text-[16px]">flight_takeoff</i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-800 dark:text-white">New flight booking</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">User booked NYC → LAX</p>
                            <p class="text-xs text-gray-500">2 minutes ago</p>
                        </div>
                    </div>
                    
                    <div class="activity-item flex items-start space-x-3 p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg">
                        <div class="w-8 h-8 bg-green-100 dark:bg-green-900/30 text-green-600 rounded-full flex items-center justify-center">
                            <i class="material-symbols-outlined !text-[16px]">payment</i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-800 dark:text-white">Payment received</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">$1,250.00 processed</p>
                            <p class="text-xs text-gray-500">5 minutes ago</p>
                        </div>
                    </div>
                    
                    <div class="activity-item flex items-start space-x-3 p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg">
                        <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900/30 text-purple-600 rounded-full flex items-center justify-center">
                            <i class="material-symbols-outlined !text-[16px]">person_add</i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-800 dark:text-white">New user registration</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">john.doe@email.com</p>
                            <p class="text-xs text-gray-500">8 minutes ago</p>
                        </div>
                    </div>

                    <div class="activity-item flex items-start space-x-3 p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg">
                        <div class="w-8 h-8 bg-pink-100 dark:bg-pink-900/30 text-pink-600 rounded-full flex items-center justify-center">
                            <i class="material-symbols-outlined !text-[16px]">favorite</i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-800 dark:text-white">Wishlist item added</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Flight to Tokyo added</p>
                            <p class="text-xs text-gray-500">12 minutes ago</p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('superadmin.dashboard.real-time') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                        View all activity →
                    </a>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="trezo-card bg-white dark:bg-[#0c1427] p-6 rounded-xl border border-gray-100 dark:border-[#172036]">
                <h5 class="font-semibold text-gray-800 dark:text-white mb-4">Quick Stats (Live)</h5>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Today's Bookings</span>
                        <span class="font-medium text-gray-800 dark:text-white" data-live="todayBookings">{{ $todayBookings ?? 0 }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Pending Payments</span>
                        <span class="font-medium text-gray-800 dark:text-white" data-live="pendingPayments">{{ $pendingPayments ?? 0 }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">API Health</span>
                        <span class="font-medium text-green-600" data-live="apiHealth">{{ $apiHealth ?? 'Healthy' }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Total Wishlist Items</span>
                        <span class="font-medium text-gray-800 dark:text-white" data-live="totalWishlistItems">{{ $totalWishlistItems ?? 0 }}</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">User Logins Today</span>
                        <span class="font-medium text-gray-800 dark:text-white" data-live="todayLogins">{{ $todayLogins ?? 0 }}</span>
                    </div>
                </div>
            </div>

            <!-- System Alerts -->
            <div class="trezo-card bg-white dark:bg-[#0c1427] p-6 rounded-xl border border-gray-100 dark:border-[#172036]">
                <h5 class="font-semibold text-gray-800 dark:text-white mb-4">System Alerts</h5>
                
                <div class="space-y-3" id="systemAlerts">
                    <!-- System alerts -->
                    <div class="alert-item flex items-start space-x-3 p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                        <div class="w-6 h-6 bg-green-100 dark:bg-green-900/30 text-green-600 rounded-full flex items-center justify-center">
                            <i class="material-symbols-outlined !text-[14px]">check_circle</i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-green-800 dark:text-green-200">All systems operational</p>
                            <p class="text-xs text-green-600 dark:text-green-400">Last checked: {{ now()->format('H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Access Links -->
            <div class="trezo-card bg-white dark:bg-[#0c1427] p-6 rounded-xl border border-gray-100 dark:border-[#172036]">
                <h5 class="font-semibold text-gray-800 dark:text-white mb-4">Quick Access</h5>
                
                <div class="space-y-2">
                    <a href="{{ route('superadmin.analytics.revenue') }}" class="block p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        <div class="flex items-center space-x-3">
                            <i class="material-symbols-outlined text-blue-600">analytics</i>
                            <span class="text-sm font-medium text-gray-800 dark:text-white">Revenue Analytics</span>
                        </div>
                    </a>
                    
                    <a href="{{ route('superadmin.system.api-health') }}" class="block p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        <div class="flex items-center space-x-3">
                            <i class="material-symbols-outlined text-green-600">health_and_safety</i>
                            <span class="text-sm font-medium text-gray-800 dark:text-white">API Health Monitor</span>
                        </div>
                    </a>
                    
                    <a href="{{ route('superadmin.security.audit-logs') }}" class="block p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        <div class="flex items-center space-x-3">
                            <i class="material-symbols-outlined text-orange-600">security</i>
                            <span class="text-sm font-medium text-gray-800 dark:text-white">Security Audit</span>
                        </div>
                    </a>
                    
                    <a href="{{ route('superadmin.communications.broadcast') }}" class="block p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        <div class="flex items-center space-x-3">
                            <i class="material-symbols-outlined text-purple-600">campaign</i>
                            <span class="text-sm font-medium text-gray-800 dark:text-white">Broadcast Center</span>
                        </div>
                    </a>
                </div>
            </div>

        </div>
    </div>

</div>

<!-- Real-Time Data Script -->
<script>
// Real-time data update functionality
let realTimeMode = true;
let updateInterval;

document.addEventListener('DOMContentLoaded', function() {
    initializeRealTimeUpdates();
    setupEventListeners();
});

function initializeRealTimeUpdates() {
    if (realTimeMode) {
        updateInterval = setInterval(fetchRealTimeData, 5000); // Update every 5 seconds
    }
}

function fetchRealTimeData() {
    fetch('{{ route("superadmin.dashboard.real-time-data") }}')
        .then(response => response.json())
        .then(data => {
            updateDashboardData(data);
            updateActivityFeed(data.activities || []);
            updateSystemAlerts(data.alerts || []);
            updateLastUpdateTime();
        })
        .catch(error => {
            console.error('Error fetching real-time data:', error);
        });
}

function updateDashboardData(data) {
    // Update all elements with data-live attributes
    document.querySelectorAll('[data-live]').forEach(element => {
        const key = element.getAttribute('data-live');
        if (data[key] !== undefined) {
            // Add highlighting animation
            element.classList.add('stat-updated');
            element.textContent = formatValue(key, data[key]);
            
            // Remove animation class after animation completes
            setTimeout(() => {
                element.classList.remove('stat-updated');
            }, 1500);
        }
    });
}

function updateActivityFeed(activities) {
    const feedContainer = document.getElementById('liveActivityFeed');
    if (feedContainer && activities.length > 0) {
        // Add new activities to the top of the feed
        activities.slice(0, 3).forEach(activity => {
            const activityElement = createActivityElement(activity);
            feedContainer.insertBefore(activityElement, feedContainer.firstChild);
        });
        
        // Remove old activities (keep only last 8)
        const allActivities = feedContainer.children;
        while (allActivities.length > 8) {
            feedContainer.removeChild(allActivities[allActivities.length - 1]);
        }
    }
}

function createActivityElement(activity) {
    const div = document.createElement('div');
    div.className = 'activity-item flex items-start space-x-3 p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg animate-fadeIn';
    
    const iconClass = getActivityIcon(activity.type);
    const colorClass = getActivityColor(activity.type);
    
    div.innerHTML = `
        <div class="w-8 h-8 ${colorClass} rounded-full flex items-center justify-center">
            <i class="material-symbols-outlined !text-[16px]">${iconClass}</i>
        </div>
        <div class="flex-1">
            <p class="text-sm text-gray-800 dark:text-white">${activity.title}</p>
            <p class="text-xs text-gray-600 dark:text-gray-400">${activity.description}</p>
            <p class="text-xs text-gray-500">${activity.time_ago}</p>
        </div>
    `;
    
    return div;
}

function getActivityIcon(type) {
    const icons = {
        'booking': 'flight_takeoff',
        'payment': 'payment',
        'user_registration': 'person_add',
        'wishlist': 'favorite',
        'login': 'login',
        'logout': 'logout',
        'hotel_booking': 'hotel',
        'tour_booking': 'tour'
    };
    return icons[type] || 'notifications';
}

function getActivityColor(type) {
    const colors = {
        'booking': 'bg-blue-100 dark:bg-blue-900/30 text-blue-600',
        'payment': 'bg-green-100 dark:bg-green-900/30 text-green-600',
        'user_registration': 'bg-purple-100 dark:bg-purple-900/30 text-purple-600',
        'wishlist': 'bg-pink-100 dark:bg-pink-900/30 text-pink-600',
        'login': 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600',
        'logout': 'bg-gray-100 dark:bg-gray-900/30 text-gray-600',
        'hotel_booking': 'bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600',
        'tour_booking': 'bg-teal-100 dark:bg-teal-900/30 text-teal-600'
    };
    return colors[type] || 'bg-gray-100 dark:bg-gray-900/30 text-gray-600';
}

function updateSystemAlerts(alerts) {
    const alertsContainer = document.getElementById('systemAlerts');
    if (alertsContainer && alerts.length > 0) {
        alertsContainer.innerHTML = '';
        alerts.forEach(alert => {
            const alertElement = createAlertElement(alert);
            alertsContainer.appendChild(alertElement);
        });
    }
}

function createAlertElement(alert) {
    const div = document.createElement('div');
    const alertClass = getAlertClass(alert.level);
    const iconClass = getAlertIcon(alert.level);
    
    div.className = `alert-item flex items-start space-x-3 p-3 ${alertClass} border rounded-lg`;
    div.innerHTML = `
        <div class="w-6 h-6 rounded-full flex items-center justify-center">
            <i class="material-symbols-outlined !text-[14px]">${iconClass}</i>
        </div>
        <div class="flex-1">
            <p class="text-sm">${alert.message}</p>
            <p class="text-xs">${alert.time}</p>
        </div>
    `;
    
    return div;
}

function getAlertClass(level) {
    const classes = {
        'success': 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800 text-green-800 dark:text-green-200',
        'warning': 'bg-yellow-50 dark:bg-yellow-900/20 border-yellow-200 dark:border-yellow-800 text-yellow-800 dark:text-yellow-200',
        'error': 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800 text-red-800 dark:text-red-200',
        'info': 'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-200'
    };
    return classes[level] || classes['info'];
}

function getAlertIcon(level) {
    const icons = {
        'success': 'check_circle',
        'warning': 'warning',
        'error': 'error',
        'info': 'info'
    };
    return icons[level] || 'info';
}

function setupEventListeners() {
    // Real-time toggle button
    const realTimeToggle = document.getElementById('realTimeToggle');
    if (realTimeToggle) {
        realTimeToggle.addEventListener('click', function() {
            realTimeMode = !realTimeMode;
            
            if (realTimeMode) {
                this.textContent = 'Live Mode: ON';
                this.classList.remove('bg-white/30');
                this.classList.add('bg-white/20');
                initializeRealTimeUpdates();
            } else {
                this.textContent = 'Live Mode: OFF';
                this.classList.remove('bg-white/20');
                this.classList.add('bg-white/30');
                clearInterval(updateInterval);
            }
        });
    }
}

function updateLastUpdateTime() {
    const updateTimeElement = document.getElementById('updateTime');
    if (updateTimeElement) {
        updateTimeElement.textContent = new Date().toLocaleTimeString();
    }
}

function formatValue(key, value) {
    // Format values based on their type
    if (key.includes('revenue') || key.includes('amount')) {
        return '$' + new Intl.NumberFormat().format(value);
    }
    if (typeof value === 'number') {
        return new Intl.NumberFormat().format(value);
    }
    return value;
}

// Cleanup on page unload
window.addEventListener('beforeunload', function() {
    if (updateInterval) {
        clearInterval(updateInterval);
    }
});
</script>
@endsection
        {{-- <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-[25px] mb-[25px]">
                <div class="lg:col-span-2">

                    <!-- Welcome -->
                    <div class="trezo-card bg-primary-500 mb-[25px] p-[20px] md:p-[25px] rounded-md">
                        <div class="trezo-card-content relative ltr:md:pr-[230px] rtl:md:pl-[230px]">
                            <div class="md:pt-[5px] md:pb-[5px]">
                                <h5 class="!mb-[5px] md:!mb-[2px] !font-semibold !text-white">
                                    Good Morning, <span class="text-[#ffcea9]">Olivia!</span>
                                </h5>
                                <p class="text-[#d5d9e2]">
                                    Here's what's happening with your store today. 
                                </p>
                                <div class="border-t border-primary-400 mt-[15px] mb-[15px] md:mt-[30px] md:mb-[33px]"></div>
                                <div class="sm:flex items-center">
                                    <div class="flex items-center ltr:sm:mr-[20px] ltr:2xl:mr-[40px] rtl:sm:ml-[20px] rtl:2xl:ml-[40px]">
                                        <div class="w-[42px] h-[42px] rtl:ml-[12px] ltr:mr-[12px] bg-primary-50 text-primary-500 rounded-md flex items-center justify-center">
                                            <i class="material-symbols-outlined">
                                                shopping_bag
                                            </i>
                                        </div>
                                        <div>
                                            <span class="text-[15px] md:text-md text-white block font-semibold mb-px md:mb-0">
                                                86 New Orders
                                            </span>
                                            <span class="block text-gray-200">
                                                Awaiting processing
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex items-center mt-[15px] sm:mt-0">
                                        <div class="w-[42px] h-[42px] rtl:ml-[12px] ltr:mr-[12px] bg-danger-50 text-danger-500 rounded-md flex items-center justify-center">
                                            <i class="material-symbols-outlined">
                                                chat_error
                                            </i>
                                        </div>
                                        <div>
                                            <span class="text-[15px] md:text-md text-white block font-semibold mb-px md:mb-0">
                                                35 Products
                                            </span>
                                            <span class="block text-gray-200">
                                                Out of stock
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center md:absolute ltr:right-0 rtl:left-0 md:max-w-[208.04px] md:top-1/2 md:-translate-y-1/2 mt-[20px] md:mt-0">
                                <img src="{{ asset('images/welcome.png') }}" class="inline-block" alt="welcome-image">
                            </div>
                        </div>
                    </div>

                    <!-- Total Sales -->
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                        <div class="trezo-card-header mb-[20px] md:mb-[25px] flex items-center justify-between">
                            <div class="trezo-card-title">
                                <h5 class="!mb-0">
                                    Total Sales
                                </h5>
                            </div>
                            <div class="trezo-card-subtitle">
                                <div class="trezo-card-dropdown relative">
                                    <button type="button" class="trezo-card-dropdown-btn inline-block rounded-md border border-gray-100 py-[5px] md:py-[6.5px] px-[12px] md:px-[19px] transition-all hover:bg-gray-50 dark:border-[#172036] dark:hover:bg-[#0a0e19]" id="dropdownToggleBtn">
                                        <span class="inline-block relative ltr:pr-[17px] ltr:md:pr-[20px] rtl:pl-[17px] rtl:ml:pr-[20px]">
                                            Monthly
                                            <i class="ri-arrow-down-s-line text-lg absolute ltr:-right-[3px] rtl:-left-[3px] top-1/2 -translate-y-1/2"></i>
                                        </span>
                                    </button>
                                    <ul class="trezo-card-dropdown-menu transition-all bg-white shadow-3xl rounded-md top-full py-[15px] absolute ltr:right-0 rtl:left-0 w-[195px] z-[5] dark:bg-dark dark:shadow-none">
                                        <li>
                                            <button type="button" class="block w-full transition-all text-black ltr:text-left rtl:text-right relative py-[8px] px-[20px] hover:bg-gray-50 dark:text-white dark:hover:bg-black">
                                                Weekly
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="block w-full transition-all text-black ltr:text-left rtl:text-right relative py-[8px] px-[20px] hover:bg-gray-50 dark:text-white dark:hover:bg-black">
                                                Monthly
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="block w-full transition-all text-black ltr:text-left rtl:text-right relative py-[8px] px-[20px] hover:bg-gray-50 dark:text-white dark:hover:bg-black">
                                                Yearly
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="trezo-card-content">
                            <div class="-mb-[15px] ltr:-ml-[10px] rtl:-mr-[10px] -mt-[5px]">
                                <div id="ecommerceTotalSalesChart"></div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="lg:col-span-1">

                    <!-- Total Bookins -->
                    <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[25px] rounded-md">
                        <div class="trezo-card-content">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <span class="block">
                                        Total Orders
                                    </span>
                                    <span class="inline-block px-[8.3px] py-[1px] text-orange-700 border border-orange-200 bg-danger-100 dark:bg-[#15203c] dark:border-[#172036] text-sm ltr:ml-[10px] rtl:mr-[10px] rounded-[100px]">
                                        -7.6%
                                    </span>
                                </div>
                                <span class="block text-sm">
                                    Last 7 days
                                </span>
                            </div>
                            <h5 class="!text-lg md:!text-[20px] !mb-0 !mt-[5px]">
                                $72,458
                            </h5>
                            <div class="mx-auto max-w-[150px] -mt-[10px] -mb-[10px] md:-mt-[25px] md:-mb-[16px]">
                                <div id="ecommerceTotalOrdersChart"></div>
                            </div>
                            <ul>
                                <li class="text-sm ltr:pl-[30px] rtl:pr-[30px] flex justify-between relative mb-[6px] last:mb-0">
                                    <span class="inline-block bg-primary-500 ltr:left-0 rtl:right-0 absolute top-1/2 -translate-y-1/2 w-[20px] h-[5px] rounded-md"></span>
                                    <span class="block">
                                        Completed
                                    </span>
                                    <span class="block">
                                        62%
                                    </span>
                                </li>
                                <li class="text-sm ltr:pl-[30px] rtl:pr-[30px] flex justify-between relative mb-[6px] last:mb-0">
                                    <span class="inline-block bg-primary-200 ltr:left-0 rtl:right-0 absolute top-1/2 -translate-y-1/2 w-[20px] h-[5px] rounded-md"></span>
                                    <span class="block">
                                        Pending
                                    </span>
                                    <span class="block">
                                        38%
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Total Customers -->
                    <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[25px] rounded-md">
                        <div class="trezo-card-content">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <span class="block">
                                        Total Customers
                                    </span>
                                    <span class="inline-block px-[8.3px] py-[1px] text-success-700 border border-success-300 bg-success-100 dark:bg-[#15203c] dark:border-[#172036] text-sm ltr:ml-[10px] rtl:mr-[10px] rounded-[100px]">
                                        +5.4
                                    </span>
                                </div>
                                <span class="block text-sm">
                                    Last 7 days
                                </span>
                            </div>
                            <h5 class="!text-lg md:!text-[20px] !mb-0 !mt-[4px]">
                                1,528
                            </h5>
                            <div class="mx-auto max-w-[300px] -mt-[33px] -mb-[20px]">
                                <div id="ecommerceTotalCustomersChart"></div>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="block">
                                    1 June
                                </span>
                                <span class="block">
                                    7 June
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Total Revenue -->
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[25px] rounded-md">
                        <div class="trezo-card-content">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <span class="block">
                                        Total Revenue
                                    </span>
                                    <span class="inline-block px-[8.3px] py-[1px] text-success-700 border border-success-300 bg-success-100 dark:bg-[#15203c] dark:border-[#172036] text-sm ltr:ml-[10px] rtl:mr-[10px] rounded-[100px]">
                                        +10%
                                    </span>
                                </div>
                                <span class="block text-sm">
                                    Last 30 days
                                </span>
                            </div>
                            <h5 class="!text-lg md:!text-[20px] !mb-0 !mt-[4px]">
                                $165,458
                            </h5>
                            <div class="mx-auto max-w-[200px] -mt-[20px] -mb-[10px] md:-mt-[23px] md:-mb-[16px]">
                                <div id="ecommerceTotalRevenueChart"></div>
                            </div>
                            <ul>
                                <li class="text-sm ltr:pl-[30px] rtl:pr-[30px] flex justify-between relative mb-[5px] last:mb-0">
                                    <span class="inline-block bg-primary-500 ltr:left-0 rtl:right-0 absolute top-1/2 -translate-y-1/2 w-[20px] h-[5px] rounded-md"></span>
                                    <span class="block">
                                        Fashion
                                    </span>
                                    <span class="block">
                                        75%
                                    </span>
                                </li>
                                <li class="text-sm ltr:pl-[30px] rtl:pr-[30px] flex justify-between relative mb-[5px] last:mb-0">
                                    <span class="inline-block bg-primary-200 ltr:left-0 rtl:right-0 absolute top-1/2 -translate-y-1/2 w-[20px] h-[5px] rounded-md"></span>
                                    <span class="block">
                                        Others
                                    </span>
                                    <span class="block">
                                        25%
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-5 gap-[25px] mb-[25px]">
                <div class="lg:col-span-2">

                    <!-- Sales by Locations -->
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                        <div class="trezo-card-header mb-[20px] md:mb-[25px] flex items-center justify-between">
                            <div class="trezo-card-title">
                                <h5 class="!mb-0">
                                    Sales by Locations
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
                            <div class="flex items-center justify-center min-h-[160px]">
                                <div id="salesByLocationsJsVectorMap"></div>
                            </div>
                            <ul class="mt-[20px]">
                                <li class="flex items-center mb-[14.4px] last:mb-0">
                                    <div class="shrink-0 ltr:mr-[15px] rtl:ml-[15px]">
                                        <img src="assets/images/flags/usa.svg" class="inline-block" alt="usa">
                                    </div>
                                    <div class="grow">
                                        <span class="block font-medium mb-[2px]">
                                            United States
                                        </span>
                                        <div class="leading-none flex items-center">
                                            <div class="flex w-full h-[4px] overflow-hidden rounded-md bg-primary-50 dark:bg-[#172036]">
                                                <div class="flex flex-col justify-center overflow-hidden bg-primary-500 rounded-md" style="width: 85%;"></div>
                                            </div>
                                            <span class="block ltr:ml-[15px] rtl:mr-[15px]">
                                                85%
                                            </span>
                                        </div>
                                    </div>
                                </li>
                                <li class="flex items-center mb-[14.4px] last:mb-0">
                                    <div class="shrink-0 ltr:mr-[15px] rtl:ml-[15px]">
                                        <img src="assets/images/flags/germany.svg" class="inline-block" alt="germany">
                                    </div>
                                    <div class="grow">
                                        <span class="block font-medium mb-[2px]">
                                            Germany
                                        </span>
                                        <div class="leading-none flex items-center">
                                            <div class="flex w-full h-[4px] overflow-hidden rounded-md bg-primary-50 dark:bg-[#172036]">
                                                <div class="flex flex-col justify-center overflow-hidden bg-primary-500 rounded-md" style="width: 75%;"></div>
                                            </div>
                                            <span class="block ltr:ml-[15px] rtl:mr-[15px]">
                                                75%
                                            </span>
                                        </div>
                                    </div>
                                </li>
                                <li class="flex items-center mb-[14.4px] last:mb-0">
                                    <div class="shrink-0 ltr:mr-[15px] rtl:ml-[15px]">
                                        <img src="assets/images/flags/uk.svg" class="inline-block" alt="uk">
                                    </div>
                                    <div class="grow">
                                        <span class="block font-medium mb-[2px]">
                                            United Kingdom
                                        </span>
                                        <div class="leading-none flex items-center">
                                            <div class="flex w-full h-[4px] overflow-hidden rounded-md bg-primary-50 dark:bg-[#172036]">
                                                <div class="flex flex-col justify-center overflow-hidden bg-primary-500 rounded-md" style="width: 40%;"></div>
                                            </div>
                                            <span class="block ltr:ml-[15px] rtl:mr-[15px]">
                                                40%
                                            </span>
                                        </div>
                                    </div>
                                </li>
                                <li class="flex items-center mb-[14.4px] last:mb-0">
                                    <div class="shrink-0 ltr:mr-[15px] rtl:ml-[15px]">
                                        <img src="assets/images/flags/canada.svg" class="inline-block" alt="canada">
                                    </div>
                                    <div class="grow">
                                        <span class="block font-medium mb-[2px]">
                                            Canada
                                        </span>
                                        <div class="leading-none flex items-center">
                                            <div class="flex w-full h-[4px] overflow-hidden rounded-md bg-primary-50 dark:bg-[#172036]">
                                                <div class="flex flex-col justify-center overflow-hidden bg-primary-500 rounded-md" style="width: 10%;"></div>
                                            </div>
                                            <span class="block ltr:ml-[15px] rtl:mr-[15px]">
                                                10%
                                            </span>
                                        </div>
                                    </div>
                                </li>
                                <li class="flex items-center mb-[14.4px] last:mb-0">
                                    <div class="shrink-0 ltr:mr-[15px] rtl:ml-[15px]">
                                        <img src="assets/images/flags/portugal.svg" class="inline-block" alt="portugal">
                                    </div>
                                    <div class="grow">
                                        <span class="block font-medium mb-[2px]">
                                            Portugal
                                        </span>
                                        <div class="leading-none flex items-center">
                                            <div class="flex w-full h-[4px] overflow-hidden rounded-md bg-primary-50 dark:bg-[#172036]">
                                                <div class="flex flex-col justify-center overflow-hidden bg-primary-500 rounded-md" style="width: 5%;"></div>
                                            </div>
                                            <span class="block ltr:ml-[15px] rtl:mr-[15px]">
                                                05%
                                            </span>
                                        </div>
                                    </div>
                                </li>
                                <li class="flex items-center mb-[14.4px] last:mb-0">
                                    <div class="shrink-0 ltr:mr-[15px] rtl:ml-[15px]">
                                        <img src="assets/images/flags/spain.svg" class="inline-block" alt="spain">
                                    </div>
                                    <div class="grow">
                                        <span class="block font-medium mb-[2px]">
                                            Spain
                                        </span>
                                        <div class="leading-none flex items-center">
                                            <div class="flex w-full h-[4px] overflow-hidden rounded-md bg-primary-50 dark:bg-[#172036]">
                                                <div class="flex flex-col justify-center overflow-hidden bg-primary-500 rounded-md" style="width: 15%;"></div>
                                            </div>
                                            <span class="block ltr:ml-[15px] rtl:mr-[15px]">
                                                15%
                                            </span>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>
                <div class="lg:col-span-3">
                    
                    <!-- Top Selling Products -->
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                        <div class="trezo-card-header mb-[20px] md:mb-[25px] flex items-center justify-between">
                            <div class="trezo-card-title">
                                <h5 class="!mb-0">
                                    Top Selling Products
                                </h5>
                            </div>
                            <div class="trezo-card-subtitle">
                                <div class="trezo-card-dropdown relative">
                                    <button type="button" class="trezo-card-dropdown-btn inline-block rounded-md border border-gray-100 py-[5px] md:py-[6.5px] px-[12px] md:px-[19px] transition-all hover:bg-gray-50 dark:border-[#172036] dark:hover:bg-[#0a0e19]" id="dropdownToggleBtn">
                                        <span class="inline-block relative ltr:pr-[17px] ltr:md:pr-[20px] rtl:pl-[17px] rtl:ml:pr-[20px]">
                                            Today
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
                            <div class="table-responsive overflow-x-auto">
                                <table class="w-full">
                                    <thead class="text-black dark:text-white">
                                        <tr>
                                            <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap ltr:first:rounded-tl-md ltr:last:rounded-tr-md rtl:first:rounded-tr-md rtl:last:rounded-tl-md">
                                                Product
                                            </th>
                                            <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap ltr:first:rounded-tl-md ltr:last:rounded-tr-md rtl:first:rounded-tr-md rtl:last:rounded-tl-md">
                                                Price
                                            </th>
                                            <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap ltr:first:rounded-tl-md ltr:last:rounded-tr-md rtl:first:rounded-tr-md rtl:last:rounded-tl-md">
                                                Order
                                            </th>
                                            <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap ltr:first:rounded-tl-md ltr:last:rounded-tr-md rtl:first:rounded-tr-md rtl:last:rounded-tl-md">
                                                Stock
                                            </th>
                                            <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap ltr:first:rounded-tr-md ltr:last:rounded-tr-md rtl:first:rounded-tr-md rtl:last:rounded-tl-md">
                                                Amount
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-black dark:text-white">
                                        <tr>
                                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                                <div class="flex items-center">
                                                    <div class="rounded-md w-[40px]">
                                                        <img src="assets/images/products/product1.jpg" class="inline-block rounded-md" alt="product-image">
                                                    </div>
                                                    <div class="ltr:ml-[12px] rtl:mr-[12px]">
                                                        <a href="product-details.html" class="font-medium inline-block text-[14px] md:text-[15px] transition-all hover:text-primary-500">
                                                            Smart Band
                                                        </a>
                                                        <span class="block text-sm text-gray-500 dark:text-gray-400">
                                                            08 Jun 2025
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                                $35.00
                                            </td>
                                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                                75
                                            </td>
                                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                                750
                                            </td>
                                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                                $2,625
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                                <div class="flex items-center">
                                                    <div class="rounded-md w-[40px]">
                                                        <img src="assets/images/products/product2.jpg" class="inline-block rounded-md" alt="product-image">
                                                    </div>
                                                    <div class="ltr:ml-[12px] rtl:mr-[12px]">
                                                        <a href="product-details.html" class="font-medium inline-block text-[14px] md:text-[15px] transition-all hover:text-primary-500">
                                                            Headphone
                                                        </a>
                                                        <span class="block text-sm text-gray-500 dark:text-gray-400">
                                                            07 Jun 2025
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                                $49.00
                                            </td>
                                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                                25
                                            </td>
                                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                                825
                                            </td>
                                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                                $1,225
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                                <div class="flex items-center">
                                                    <div class="rounded-md w-[40px]">
                                                        <img src="assets/images/products/product3.jpg" class="inline-block rounded-md" alt="product-image">
                                                    </div>
                                                    <div class="ltr:ml-[12px] rtl:mr-[12px]">
                                                        <a href="product-details.html" class="font-medium inline-block text-[14px] md:text-[15px] transition-all hover:text-primary-500">
                                                            iPhone 15 Plus
                                                        </a>
                                                        <span class="block text-sm text-gray-500 dark:text-gray-400">
                                                            06 Jun 2025
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                                $99.00
                                            </td>
                                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                                55
                                            </td>
                                            <td class="text-danger-400 ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                                Stock Out
                                            </td>
                                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                                $5,445
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                                <div class="flex items-center">
                                                    <div class="rounded-md w-[40px]">
                                                        <img src="assets/images/products/product4.jpg" class="inline-block rounded-md" alt="product-image">
                                                    </div>
                                                    <div class="ltr:ml-[12px] rtl:mr-[12px]">
                                                        <a href="product-details.html" class="font-medium inline-block text-[14px] md:text-[15px] transition-all hover:text-primary-500">
                                                            Bluetooth Speaker
                                                        </a>
                                                        <span class="block text-sm text-gray-500 dark:text-gray-400">
                                                            05 Jun 2025
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                                $59.00
                                            </td>
                                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                                40
                                            </td>
                                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                                535
                                            </td>
                                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                                $2,360
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                                <div class="flex items-center">
                                                    <div class="rounded-md w-[40px]">
                                                        <img src="assets/images/products/product5.jpg" class="inline-block rounded-md" alt="product-image">
                                                    </div>
                                                    <div class="ltr:ml-[12px] rtl:mr-[12px]">
                                                        <a href="product-details.html" class="font-medium inline-block text-[14px] md:text-[15px] transition-all hover:text-primary-500">
                                                            Airbuds 2nd Gen
                                                        </a>
                                                        <span class="block text-sm text-gray-500 dark:text-gray-400">
                                                            04 Jun 2025
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                                $79.00
                                            </td>
                                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                                56
                                            </td>
                                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                                460
                                            </td>
                                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                                $4,424
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="px-[20px] py-[12px] md:py-[14px] rounded-b-md border-l border-r border-b border-gray-100 dark:border-[#172036] sm:flex sm:items-center justify-between">
                                <p class="!mb-0 text-sm">
                                    Showing 5 of 36 results
                                </p>
                                <ol class="mt-[10px] sm:mt-0">
                                    <li class="inline-block mx-[1px] ltr:first:ml-0 ltr:last:mr-0 rtl:first:mr-0 rtl:last:ml-0">
                                        <a href="javascript:void(0);" class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500">
                                            <span class="opacity-0">
                                                0
                                            </span>
                                            <i class="material-symbols-outlined left-0 right-0 absolute top-1/2 -translate-y-1/2">
                                                chevron_left
                                            </i>
                                        </a>
                                    </li>
                                    <li class="inline-block mx-[1px] ltr:first:ml-0 ltr:last:mr-0 rtl:first:mr-0 rtl:last:ml-0">
                                        <a href="javascript:void(0);" class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-primary-500 bg-primary-500 text-white">
                                            1
                                        </a>
                                    </li>
                                    <li class="inline-block mx-[1px] ltr:first:ml-0 ltr:last:mr-0 rtl:first:mr-0 rtl:last:ml-0">
                                        <a href="javascript:void(0);" class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500">
                                            2
                                        </a>
                                    </li>
                                    <li class="inline-block mx-[1px] ltr:first:ml-0 ltr:last:mr-0 rtl:first:mr-0 rtl:last:ml-0">
                                        <a href="javascript:void(0);" class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500">
                                            3
                                        </a>
                                    </li>
                                    <li class="inline-block mx-[1px] ltr:first:ml-0 ltr:last:mr-0 rtl:first:mr-0 rtl:last:ml-0">
                                        <a href="javascript:void(0);" class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500">
                                            4
                                        </a>
                                    </li>
                                    <li class="inline-block mx-[1px] ltr:first:ml-0 ltr:last:mr-0 rtl:first:mr-0 rtl:last:ml-0">
                                        <a href="javascript:void(0);" class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500">
                                            <span class="opacity-0">
                                                0
                                            </span>
                                            <i class="material-symbols-outlined left-0 right-0 absolute top-1/2 -translate-y-1/2">
                                                chevron_right
                                            </i>
                                        </a>
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-[25px] mb-[25px]">
                <div class="lg:col-span-2">

                    <!-- Recent Orders -->
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                        <div class="trezo-card-header mb-[20px] md:mb-[25px] sm:flex sm:items-center sm:justify-between">
                            <div class="trezo-card-title">
                                <h5 class="!mb-0">
                                    Recent Orders
                                </h5>
                            </div>
                            <div class="trezo-card-subtitle sm:flex sm:items-center">
                                <form class="relative sm:w-[240px] ltr:sm:mr-[20px] rtl:sm:ml-[20px] my-[13px] sm:my-0">
                                    <label class="leading-none absolute ltr:left-[13px] rtl:right-[13px] text-black dark:text-white mt-px top-1/2 -translate-y-1/2">
                                        <i class="material-symbols-outlined !text-[20px]">
                                            search
                                        </i>
                                    </label>
                                    <input type="text" placeholder="Search here....." class="bg-gray-50 border border-gray-50 h-[36px] text-xs rounded-md w-full block text-black pt-[11px] pb-[12px] ltr:pl-[38px] rtl:pr-[38px] ltr:pr-[13px] ltr:md:pr-[16px] rtl:pl-[13px] rtl:md:pl-[16px] placeholder:text-gray-500 outline-0 dark:bg-[#15203c] dark:text-white dark:border-[#15203c] dark:placeholder:text-gray-400">
                                </form>
                                <div class="trezo-card-dropdown relative">
                                    <button type="button" class="trezo-card-dropdown-btn inline-block rounded-md border border-gray-100 py-[5px] md:py-[6.5px] px-[12px] md:px-[19px] transition-all hover:bg-gray-50 dark:border-[#172036] dark:hover:bg-[#0a0e19]" id="dropdownToggleBtn">
                                        <span class="inline-block relative ltr:pr-[17px] ltr:md:pr-[20px] rtl:pl-[17px] rtl:ml:pr-[20px]">
                                            Show All
                                            <i class="ri-arrow-down-s-line text-lg absolute ltr:-right-[3px] rtl:-left-[3px] top-1/2 -translate-y-1/2"></i>
                                        </span>
                                    </button>
                                    <ul class="trezo-card-dropdown-menu transition-all bg-white shadow-3xl rounded-md top-full py-[15px] absolute ltr:left-0 sm:ltr:right-0 rtl:right-0 sm:rtl:left-0 w-[195px] z-[5] dark:bg-dark dark:shadow-none">
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
                            <div class="table-responsive overflow-x-auto">
                                <table class="w-full">
                                    <thead class="text-black dark:text-white">
                                        <tr>
                                            <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap first:rounded-tl-md">
                                                Order ID
                                            </th>
                                            <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap first:rounded-tl-md">
                                                Customer
                                            </th>
                                            <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap first:rounded-tl-md">
                                                Created
                                            </th>
                                            <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap first:rounded-tl-md">
                                                Total
                                            </th>
                                            <th class="font-medium text-left px-[20px] py-[11px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap first:rounded-tr-md">
                                                Status
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-black dark:text-white">
                                        <tr>
                                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                                #JAN-2345
                                            </td>
                                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                                <div class="flex items-center">
                                                    <div class="rounded-md w-[40px]">
                                                        <img src="assets/images/users/user1.jpg" class="inline-block rounded-md" alt="product-image">
                                                    </div>
                                                    <div class="ltr:ml-[12px] rtl:mr-[12px]">
                                                        <span class="block font-medium">
                                                            Sarah Johnson
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                                12 Jun 2025
                                            </td>
                                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                                $10,490
                                            </td>
                                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                                <span class="px-[8px] py-[3px] inline-block bg-success-100 dark:bg-[#15203c] text-success-600 rounded-sm font-medium text-xs">
                                                    Shipped
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                                #JAN-1323
                                            </td>
                                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                                <div class="flex items-center">
                                                    <div class="rounded-md w-[40px]">
                                                        <img src="assets/images/users/user2.jpg" class="inline-block rounded-md" alt="product-image">
                                                    </div>
                                                    <div class="ltr:ml-[12px] rtl:mr-[12px]">
                                                        <span class="block font-medium">
                                                            Michael Smith
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                                11 Jun 2025
                                            </td>
                                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                                $6,575
                                            </td>
                                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                                <span class="px-[8px] py-[3px] inline-block bg-primary-50 dark:bg-[#15203c] text-primary-500 rounded-sm font-medium text-xs">
                                                    Confirmed
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                                #DEC-1234
                                            </td>
                                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                                <div class="flex items-center">
                                                    <div class="rounded-md w-[40px]">
                                                        <img src="assets/images/users/user3.jpg" class="inline-block rounded-md" alt="product-image">
                                                    </div>
                                                    <div class="ltr:ml-[12px] rtl:mr-[12px]">
                                                        <span class="block font-medium">
                                                            Emily Brown
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                                10 Jun 2025
                                            </td>
                                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                                $12,870
                                            </td>
                                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                                <span class="px-[8px] py-[3px] inline-block bg-warning-50 dark:bg-[#15203c] text-warning-700 rounded-sm font-medium text-xs">
                                                    Pending
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                                #DEC-3567
                                            </td>
                                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                                <div class="flex items-center">
                                                    <div class="rounded-md w-[40px]">
                                                        <img src="assets/images/users/user4.jpg" class="inline-block rounded-md" alt="product-image">
                                                    </div>
                                                    <div class="ltr:ml-[12px] rtl:mr-[12px]">
                                                        <span class="block font-medium">
                                                            Jason Lee
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                                09 Jun 2025
                                            </td>
                                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                                $7,895
                                            </td>
                                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                                <span class="px-[8px] py-[3px] inline-block bg-success-100 dark:bg-[#15203c] text-success-600 rounded-sm font-medium text-xs">
                                                    Shipped
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                                #DEC-1098
                                            </td>
                                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                                <div class="flex items-center">
                                                    <div class="rounded-md w-[40px]">
                                                        <img src="assets/images/users/user5.jpg" class="inline-block rounded-md" alt="product-image">
                                                    </div>
                                                    <div class="ltr:ml-[12px] rtl:mr-[12px]">
                                                        <span class="block font-medium">
                                                            Ashley Davis
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                                08 Jun 2025
                                            </td>
                                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                                $4,680
                                            </td>
                                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                                <span class="px-[8px] py-[3px] inline-block bg-danger-100 dark:bg-[#15203c] text-danger-500 rounded-sm font-medium text-xs">
                                                    Rejected
                                                </span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="px-[20px] py-[12px] md:py-[14px] rounded-b-md border-l border-r border-b border-gray-100 dark:border-[#172036] sm:flex sm:items-center justify-between">
                                <p class="!mb-0 text-sm">
                                    Showing 5 of 36 results
                                </p>
                                <ol class="mt-[10px] sm:mt-0">
                                    <li class="inline-block mx-[1px] ltr:first:ml-0 ltr:last:mr-0 rtl:first:mr-0 rtl:last:ml-0">
                                        <a href="javascript:void(0);" class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500">
                                            <span class="opacity-0">
                                                0
                                            </span>
                                            <i class="material-symbols-outlined left-0 right-0 absolute top-1/2 -translate-y-1/2">
                                                chevron_left
                                            </i>
                                        </a>
                                    </li>
                                    <li class="inline-block mx-[1px] ltr:first:ml-0 ltr:last:mr-0 rtl:first:mr-0 rtl:last:ml-0">
                                        <a href="javascript:void(0);" class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-primary-500 bg-primary-500 text-white">
                                            1
                                        </a>
                                    </li>
                                    <li class="inline-block mx-[1px] ltr:first:ml-0 ltr:last:mr-0 rtl:first:mr-0 rtl:last:ml-0">
                                        <a href="javascript:void(0);" class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500">
                                            2
                                        </a>
                                    </li>
                                    <li class="inline-block mx-[1px] ltr:first:ml-0 ltr:last:mr-0 rtl:first:mr-0 rtl:last:ml-0">
                                        <a href="javascript:void(0);" class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500">
                                            3
                                        </a>
                                    </li>
                                    <li class="inline-block mx-[1px] ltr:first:ml-0 ltr:last:mr-0 rtl:first:mr-0 rtl:last:ml-0">
                                        <a href="javascript:void(0);" class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500">
                                            4
                                        </a>
                                    </li>
                                    <li class="inline-block mx-[1px] ltr:first:ml-0 ltr:last:mr-0 rtl:first:mr-0 rtl:last:ml-0">
                                        <a href="javascript:void(0);" class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500">
                                            <span class="opacity-0">
                                                0
                                            </span>
                                            <i class="material-symbols-outlined left-0 right-0 absolute top-1/2 -translate-y-1/2">
                                                chevron_right
                                            </i>
                                        </a>
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="lg:col-span-1">

                    <!-- Order Summary -->
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                        <div class="trezo-card-header mb-[20px] md:mb-[25px] flex items-center justify-between">
                            <div class="trezo-card-title">
                                <h5 class="!mb-0">
                                    Order Summary
                                </h5>
                            </div>
                            <div class="trezo-card-subtitle">
                                <div class="trezo-card-dropdown relative">
                                    <button type="button" class="trezo-card-dropdown-btn inline-block rounded-md border border-gray-100 py-[5px] md:py-[6.5px] px-[12px] md:px-[19px] transition-all hover:bg-gray-50 dark:border-[#172036] dark:hover:bg-[#0a0e19]" id="dropdownToggleBtn">
                                        <span class="inline-block relative ltr:pr-[17px] ltr:md:pr-[20px] rtl:pl-[17px] rtl:ml:pr-[20px]">
                                            Today
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
                            <div id="ecommerceOrderSummaryChart"></div>
                            <ul class="-mt-[4px]">
                                <li class="mb-[10px] md:mb-[12px] last:mb-0">
                                    <span class="block font-medium mb-[2px] md:mb-[4px]">
                                        Completed Order
                                    </span>
                                    <div class="leading-none flex items-center">
                                        <div class="flex w-full h-[4px] overflow-hidden rounded-md bg-primary-50 dark:bg-[#172036]">
                                            <div class="flex flex-col justify-center overflow-hidden bg-success-500 rounded-md" style="width: 60%;"></div>
                                        </div>
                                        <span class="block ml-[15px]">
                                            60%
                                        </span>
                                    </div>
                                </li>
                                <li class="mb-[10px] md:mb-[12px] last:mb-0">
                                    <span class="block font-medium mb-[2px] md:mb-[4px]">
                                        New Order
                                    </span>
                                    <div class="leading-none flex items-center">
                                        <div class="flex w-full h-[4px] overflow-hidden rounded-md bg-primary-50 dark:bg-[#172036]">
                                            <div class="flex flex-col justify-center overflow-hidden bg-primary-500 rounded-md" style="width: 30%;"></div>
                                        </div>
                                        <span class="block ml-[15px]">
                                            30%
                                        </span>
                                    </div>
                                </li>
                                <li class="mb-[10px] md:mb-[12px] last:mb-0">
                                    <span class="block font-medium mb-[2px] md:mb-[4px]">
                                        Pending Order
                                    </span>
                                    <div class="leading-none flex items-center">
                                        <div class="flex w-full h-[4px] overflow-hidden rounded-md bg-primary-50 dark:bg-[#172036]">
                                            <div class="flex flex-col justify-center overflow-hidden bg-purple-500 rounded-md" style="width: 10%;"></div>
                                        </div>
                                        <span class="block ml-[15px]">
                                            10%
                                        </span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
                
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-[25px] mb-[25px]">
                <div class="lg:col-span-1">

                    <!-- Recent Transactions -->
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                        <div class="trezo-card-header mb-[20px] md:mb-[25px] flex items-center justify-between">
                            <div class="trezo-card-title">
                                <h5 class="!mb-0">
                                    Recent Transactions
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
                                <li class="flex items-center justify-between mb-[15px] md:mb-[18px] lg:mb-[22px] last:mb-0">
                                    <div class="flex items-center">
                                        <div class="w-[41px] h-[41px] ltr:mr-[12px] rtl:ml-[12px] bg-primary-100 dark:bg-[#15203c] text-primary-500 rounded-full flex items-center justify-center">
                                            <i class="material-symbols-outlined">
                                                credit_card
                                            </i>
                                        </div>
                                        <div>
                                            <span class="block font-medium text-black dark:text-white text-base md:text-[16px] mb-[3px] md:mb-px font-medium">
                                                Master Card
                                            </span>
                                            <span class="block text-sm">
                                                16 Jun 2025 - 7:12 pm
                                            </span>
                                        </div>
                                    </div>
                                    <span class="block text-success-600">
                                        +1,520
                                    </span>
                                </li>
                                <li class="flex items-center justify-between mb-[15px] md:mb-[18px] lg:mb-[22px] last:mb-0">
                                    <div class="flex items-center">
                                        <div class="w-[41px] h-[41px] ltr:mr-[12px] rtl:ml-[12px] bg-danger-100 dark:bg-[#15203c] text-danger-500 rounded-full flex items-center justify-center">
                                            <i class="material-symbols-outlined">
                                                redeem
                                            </i>
                                        </div>
                                        <div>
                                            <span class="block font-medium text-black dark:text-white text-base md:text-[16px] mb-[3px] md:mb-px font-medium">
                                                Paypal
                                            </span>
                                            <span class="block text-sm">
                                                15 Jun 2025 - 1:42 am
                                            </span>
                                        </div>
                                    </div>
                                    <span class="block text-danger-500">
                                        -2,250
                                    </span>
                                </li>
                                <li class="flex items-center justify-between mb-[15px] md:mb-[18px] lg:mb-[22px] last:mb-0">
                                    <div class="flex items-center">
                                        <div class="w-[41px] h-[41px] ltr:mr-[12px] rtl:ml-[12px] bg-purple-100 dark:bg-[#15203c] text-purple-500 rounded-full flex items-center justify-center">
                                            <i class="material-symbols-outlined">
                                                account_balance
                                            </i>
                                        </div>
                                        <div>
                                            <span class="block font-medium text-black dark:text-white text-base md:text-[16px] mb-[3px] md:mb-px font-medium">
                                                Wise
                                            </span>
                                            <span class="block text-sm">
                                                14 Jun 2025 - 4:21 pm
                                            </span>
                                        </div>
                                    </div>
                                    <span class="block text-success-600">
                                        +3,560
                                    </span>
                                </li>
                                <li class="flex items-center justify-between mb-[15px] md:mb-[18px] lg:mb-[22px] last:mb-0">
                                    <div class="flex items-center">
                                        <div class="w-[41px] h-[41px] ltr:mr-[12px] rtl:ml-[12px] bg-secondary-100 dark:bg-[#15203c] text-secondary-500 rounded-full flex items-center justify-center">
                                            <i class="material-symbols-outlined">
                                                currency_ruble
                                            </i>
                                        </div>
                                        <div>
                                            <span class="block font-medium text-black dark:text-white text-base md:text-[16px] mb-[3px] md:mb-px font-medium">
                                                Payoneer
                                            </span>
                                            <span class="block text-sm">
                                                13 Jun 2025 - 2:42 am
                                            </span>
                                        </div>
                                    </div>
                                    <span class="block text-success-600">
                                        +6,500
                                    </span>
                                </li>
                                <li class="flex items-center justify-between mb-[15px] md:mb-[18px] lg:mb-[22px] last:mb-0">
                                    <div class="flex items-center">
                                        <div class="w-[41px] h-[41px] ltr:mr-[12px] rtl:ml-[12px] bg-success-100 dark:bg-[#15203c] text-success-600 rounded-full flex items-center justify-center">
                                            <i class="material-symbols-outlined">
                                                credit_score
                                            </i>
                                        </div>
                                        <div>
                                            <span class="block font-medium text-black dark:text-white text-base md:text-[16px] mb-[3px] md:mb-px font-medium">
                                                Credit Card
                                            </span>
                                            <span class="block text-sm">
                                                12 Jun 2025 - 3:20 pm
                                            </span>
                                        </div>
                                    </div>
                                    <span class="block text-danger-500">
                                        -4,320
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>
                <div class="lg:col-span-2">

                    <!-- Returning Customer Rate -->
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                        <div class="trezo-card-header mb-[20px] md:mb-[25px] flex items-center justify-between">
                            <div class="trezo-card-title">
                                <h5 class="!mb-0">
                                    Returning Customer Rate
                                </h5>
                            </div>
                            <div class="trezo-card-subtitle">
                                <div class="trezo-card-dropdown relative">
                                    <button type="button" class="trezo-card-dropdown-btn inline-block rounded-md border border-gray-100 py-[5px] md:py-[6.5px] px-[12px] md:px-[19px] transition-all hover:bg-gray-50 dark:border-[#172036] dark:hover:bg-[#0a0e19]" id="dropdownToggleBtn">
                                        <span class="inline-block relative ltr:pr-[17px] ltr:md:pr-[20px] rtl:pl-[17px] rtl:ml:pr-[20px]">
                                            This Month
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
                            <div class="-mb-[15px] -mt-[5px] md:-mt-[22px] ltr:-ml-[10px] rtl:-mr-[10px]">
                                <div id="ecommerceReturningCustomerRateChart"></div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="grow"></div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-[25px] mb-[25px]">
                <div class="lg:col-span-3">
                    <!-- Activity & Logs Section -->
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                        <div class="trezo-card-header mb-[20px] md:mb-[25px] flex items-center justify-between">
                            <div class="trezo-card-title">
                                <h5 class="!mb-0">
                                    Recent Activities & Logs
                                </h5>
                            </div>
                            <div class="trezo-card-subtitle">
                                <div class="trezo-card-dropdown relative">
                                    <button type="button" class="trezo-card-dropdown-btn inline-block rounded-md border border-gray-100 py-[5px] px-[19px] transition-all hover:bg-gray-50 dark:border-[#172036] dark:hover:bg-[#0a0e19]" id="activityDropdownBtn">
                                        <span class="inline-block relative ltr:pr-[17px] rtl:pl-[17px]">
                                            All Users
                                            <i class="ri-arrow-down-s-line text-lg absolute ltr:-right-[3px] rtl:-left-[3px] top-1/2 -translate-y-1/2"></i>
                                        </span>
                                    </button>
                                    <ul class="trezo-card-dropdown-menu transition-all bg-white shadow-3xl rounded-md top-full py-[15px] absolute ltr:right-0 rtl:left-0 w-[195px] z-[5] dark:bg-dark dark:shadow-none">
                                        <li>
                                            <button type="button" class="block w-full transition-all text-black ltr:text-left rtl:text-right py-[8px] px-[20px] hover:bg-gray-50 dark:text-white dark:hover:bg-black">
                                                All Users
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="block w-full transition-all text-black ltr:text-left rtl:text-right py-[8px] px-[20px] hover:bg-gray-50 dark:text-white dark:hover:bg-black">
                                                Superadmins
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="block w-full transition-all text-black ltr:text-left rtl:text-right py-[8px] px-[20px] hover:bg-gray-50 dark:text-white dark:hover:bg-black">
                                                Admins
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="block w-full transition-all text-black ltr:text-left rtl:text-right py-[8px] px-[20px] hover:bg-gray-50 dark:text-white dark:hover:bg-black">
                                                Users
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
                                            <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap">
                                                User
                                            </th>
                                            <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap">
                                                Role
                                            </th>
                                            <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap">
                                                Activity
                                            </th>
                                            <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap">
                                                Time
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-black dark:text-white">
                                        @forelse($activities as $activity)
                                            <tr>
                                                <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036]">
                                                    <div class="flex items-center">
                                                        <div class="rounded-md w-[40px]">
                                                            <img src="{{ $activity->user->profile->avatar ?? asset('images/default-avatar.png') }}" class="inline-block rounded-md" alt="user-avatar">
                                                        </div>
                                                        <div class="ltr:ml-[12px] rtl:mr-[12px]">
                                                            <span class="block font-medium">
                                                                {{ $activity->user->name }}
                                                            </span>
                                                            <span class="block text-xs text-gray-500">
                                                                {{ $activity->user->email }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036]">
                                                    <span class="px-[8px] py-[3px] inline-block bg-primary-50 dark:bg-[#15203c] text-primary-500 rounded-sm font-medium text-xs">
                                                        {{ ucfirst($activity->user->roles->first()->name ?? 'User') }}
                                                    </span>
                                                </td>
                                                <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036]">
                                                    {{ $activity->action }}
                                                    @if($activity->description)
                                                        <span class="block text-xs text-gray-400">{{ $activity->description }}</span>
                                                    @endif
                                                </td>
                                                <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036]">
                                                    {{ $activity->created_at->diffForHumans() }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-[20px] text-gray-500">
                                                    No recent activities found.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="px-[20px] py-[12px] md:py-[14px] rounded-b-md border-l border-r border-b border-gray-100 dark:border-[#172036] flex items-center justify-between">
                                <p class="!mb-0 text-sm">
                                    Showing {{ $activities->count() }} of {{ $activities->total() }} activities
                                </p>
                                @if(method_exists($activities, 'links'))
                                {{ $activities->links() }}
                            @else
                                <ol class="mt-[10px] sm:mt-0">
                                    <li class="inline-block mx-[1px]">
                                        <a href="#" class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-primary-500 bg-primary-500 text-white">
                                            1
                                        </a>
                                    </li>
                                </ol>
                            @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Footer -->
            <footer class="bg-white dark:bg-[#0c1427] rounded-t-md px-[20px] md:px-[25px] py-[15px] md:py-[20px] text-center">
                <p>
                    © <span class="text-purple-500">Trezo</span> is Proudly Owned by <a href="https://envytheme.com/" target="_blank" class="text-primary-500 transition-all hover:underline">EnvyTheme</a>
                </p>
            </footer>

        </div> --}}

        @section('content')

   <!-- Main Content -->
        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-[25px] mb-[25px]">
                <div class="lg:col-span-2">

                    <!-- Welcome -->
                    <div class="trezo-card bg-primary-500 mb-[25px] p-[20px] md:p-[25px] rounded-md">
                        <div class="trezo-card-content relative ltr:md:pr-[230px] rtl:md:pl-[230px]">
                            <div class="md:pt-[5px] md:pb-[5px]">
                                <h5 class="!mb-[5px] md:!mb-[2px] !font-semibold !text-white">
                                    Good {{ now()->format('A') == 'AM' ? 'Morning' : (now()->format('H') < 18 ? 'Afternoon' : 'Evening') }}, <span class="text-[#ffcea9]">{{ auth()->user()->name ?? 'Admin' }}!</span>
                                </h5>
                                <p class="text-[#d5d9e2]">
                                    Here's what's happening with your flight booking system today. 
                                </p>
                                <div class="border-t border-primary-400 mt-[15px] mb-[15px] md:mt-[30px] md:mb-[33px]"></div>
                                <div class="sm:flex items-center">
                                    <div class="flex items-center ltr:sm:mr-[20px] ltr:2xl:mr-[40px] rtl:sm:ml-[20px] rtl:2xl:ml-[40px]">
                                        <div class="w-[42px] h-[42px] rtl:ml-[12px] ltr:mr-[12px] bg-primary-50 text-primary-500 rounded-md flex items-center justify-center">
                                            <i class="material-symbols-outlined">
                                                flight_takeoff
                                            </i>
                                        </div>
                                        <div>
                                            <span class="text-[15px] md:text-md text-white block font-semibold mb-px md:mb-0">
                                                {{ $newBookings }} New Bookings
                                            </span>
                                            <span class="block text-gray-200">
                                                Awaiting processing
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex items-center mt-[15px] sm:mt-0">
                                        <div class="w-[42px] h-[42px] rtl:ml-[12px] ltr:mr-[12px] bg-danger-50 text-danger-500 rounded-md flex items-center justify-center">
                                            <i class="material-symbols-outlined">
                                                flight_land
                                            </i>
                                        </div>
                                        <div>
                                            <span class="text-[15px] md:text-md text-white block font-semibold mb-px md:mb-0">
                                                {{ $cancelledFlights }} Flights
                                            </span>
                                            <span class="block text-gray-200">
                                                Cancelled today
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center md:absolute ltr:right-0 rtl:left-0 md:max-w-[208.04px] md:top-1/2 md:-translate-y-1/2 mt-[20px] md:mt-0">
                                <img src="{{ asset('images/welcome.png') }}" class="inline-block" alt="welcome-image">
                            </div>
                        </div>
                    </div>

                    <!-- Total Sales -->
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                        <div class="trezo-card-header mb-[20px] md:mb-[25px] flex items-center justify-between">
                            <div class="trezo-card-title">
                                <h5 class="!mb-0">
                                    Booking Trends
                                </h5>
                            </div>
                            <div class="trezo-card-subtitle">
                                <div class="trezo-card-dropdown relative">
                                    <button type="button" class="trezo-card-dropdown-btn inline-block rounded-md border border-gray-100 py-[5px] md:py-[6.5px] px-[12px] md:px-[19px] transition-all hover:bg-gray-50 dark:border-[#172036] dark:hover:bg-[#0a0e19]" id="dropdownToggleBtn">
                                        <span class="inline-block relative ltr:pr-[17px] ltr:md:pr-[20px] rtl:pl-[17px] rtl:ml:pr-[20px]">
                                            Monthly
                                            <i class="ri-arrow-down-s-line text-lg absolute ltr:-right-[3px] rtl:-left-[3px] top-1/2 -translate-y-1/2"></i>
                                        </span>
                                    </button>
                                    <ul class="trezo-card-dropdown-menu transition-all bg-white shadow-3xl rounded-md top-full py-[15px] absolute ltr:right-0 rtl:left-0 w-[195px] z-[5] dark:bg-dark dark:shadow-none">
                                        <li>
                                            <button type="button" class="block w-full transition-all text-black ltr:text-left rtl:text-right relative py-[8px] px-[20px] hover:bg-gray-50 dark:text-white dark:hover:bg-black">
                                                Weekly
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="block w-full transition-all text-black ltr:text-left rtl:text-right relative py-[8px] px-[20px] hover:bg-gray-50 dark:text-white dark:hover:bg-black">
                                                Monthly
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="block w-full transition-all text-black ltr:text-left rtl:text-right relative py-[8px] px-[20px] hover:bg-gray-50 dark:text-white dark:hover:bg-black">
                                                Yearly
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="trezo-card-content">
                            <div class="-mb-[15px] ltr:-ml-[10px] rtl:-mr-[10px] -mt-[5px]">
                                <div id="ecommerceTotalSalesChart" data-bookings="{{ json_encode($monthlyBookings) }}"></div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="lg:col-span-1">

                    <!-- Total Bookings -->
                    <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[25px] rounded-md">
                        <div class="trezo-card-content">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <span class="block">
                                        Total Bookings
                                    </span>
                                    <span class="inline-block px-[8.3px] py-[1px] {{ $bookingsPercentageChange >= 0 ? 'text-success-700 border-success-300 bg-success-100' : 'text-orange-700 border-orange-200 bg-danger-100' }} dark:bg-[#15203c] dark:border-[#172036] text-sm ltr:ml-[10px] rtl:mr-[10px] rounded-[100px]">
                                        {{ $bookingsPercentageChange >= 0 ? '+' : '' }}{{ $bookingsPercentageChange }}%
                                    </span>
                                </div>
                                <span class="block text-sm">
                                    Last 7 days
                                </span>
                            </div>
                            <h5 class="!text-lg md:!text-[20px] !mb-0 !mt-[5px]">
                                {{ number_format($totalBookingsThisWeek) }}
                            </h5>
                            <div class="mx-auto max-w-[150px] -mt-[10px] -mb-[10px] md:-mt-[25px] md:-mb-[16px]">
                                <div id="ecommerceTotalOrdersChart"></div>
                            </div>
                            <ul>
                                <li class="text-sm ltr:pl-[30px] rtl:pr-[30px] flex justify-between relative mb-[6px] last:mb-0">
                                    <span class="inline-block bg-primary-500 ltr:left-0 rtl:right-0 absolute top-1/2 -translate-y-1/2 w-[20px] h-[5px] rounded-md"></span>
                                    <span class="block">
                                        Completed
                                    </span>
                                    <span class="block">
                                        {{ $completedPercentage }}%
                                    </span>
                                </li>
                                <li class="text-sm ltr:pl-[30px] rtl:pr-[30px] flex justify-between relative mb-[6px] last:mb-0">
                                    <span class="inline-block bg-primary-200 ltr:left-0 rtl:right-0 absolute top-1/2 -translate-y-1/2 w-[20px] h-[5px] rounded-md"></span>
                                    <span class="block">
                                        Pending
                                    </span>
                                    <span class="block">
                                        {{ $pendingPercentage }}%
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Total Customers -->
                    <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[25px] rounded-md">
                        <div class="trezo-card-content">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <span class="block">
                                        New Customers
                                    </span>
                                    <span class="inline-block px-[8.3px] py-[1px] {{ $customersPercentageChange >= 0 ? 'text-success-700 border-success-300 bg-success-100' : 'text-orange-700 border-orange-200 bg-danger-100' }} dark:bg-[#15203c] dark:border-[#172036] text-sm ltr:ml-[10px] rtl:mr-[10px] rounded-[100px]">
                                        {{ $customersPercentageChange >= 0 ? '+' : '' }}{{ $customersPercentageChange }}%
                                    </span>
                                </div>
                                <span class="block text-sm">
                                    Last 7 days
                                </span>
                            </div>
                            <h5 class="!text-lg md:!text-[20px] !mb-0 !mt-[4px]">
                                {{ number_format($totalCustomersThisWeek) }}
                            </h5>
                            <div class="mx-auto max-w-[300px] -mt-[33px] -mb-[20px]">
                                <div id="ecommerceTotalCustomersChart" data-customers="{{ json_encode($weeklyCustomers) }}"></div>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="block">
                                    {{ \Carbon\Carbon::now()->subDays(6)->format('j M') }}
                                </span>
                                <span class="block">
                                    {{ \Carbon\Carbon::now()->format('j M') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Total Revenue -->
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[25px] rounded-md">
                        <div class="trezo-card-content">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <span class="block">
                                        Total Revenue
                                    </span>
                                    <span class="inline-block px-[8.3px] py-[1px] {{ $revenuePercentageChange >= 0 ? 'text-success-700 border-success-300 bg-success-100' : 'text-orange-700 border-orange-200 bg-danger-100' }} dark:bg-[#15203c] dark:border-[#172036] text-sm ltr:ml-[10px] rtl:mr-[10px] rounded-[100px]">
                                        {{ $revenuePercentageChange >= 0 ? '+' : '' }}{{ $revenuePercentageChange }}%
                                    </span>
                                </div>
                                <span class="block text-sm">
                                    Last 30 days
                                </span>
                            </div>
                            <h5 class="!text-lg md:!text-[20px] !mb-0 !mt-[4px]">
                                ${{ number_format($totalRevenueThisMonth, 2) }}
                            </h5>
                            <div class="mx-auto max-w-[200px] -mt-[20px] -mb-[10px] md:-mt-[23px] md:-mb-[16px]">
                                <div id="ecommerceTotalRevenueChart"></div>
                            </div>
                            <ul>
                                <li class="text-sm ltr:pl-[30px] rtl:pr-[30px] flex justify-between relative mb-[5px] last:mb-0">
                                    <span class="inline-block bg-primary-500 ltr:left-0 rtl:right-0 absolute top-1/2 -translate-y-1/2 w-[20px] h-[5px] rounded-md"></span>
                                    <span class="block">
                                        Domestic
                                    </span>
                                    <span class="block">
                                        {{ $domesticPercentage }}%
                                    </span>
                                </li>
                                <li class="text-sm ltr:pl-[30px] rtl:pr-[30px] flex justify-between relative mb-[5px] last:mb-0">
                                    <span class="inline-block bg-primary-200 ltr:left-0 rtl:right-0 absolute top-1/2 -translate-y-1/2 w-[20px] h-[5px] rounded-md"></span>
                                    <span class="block">
                                        International
                                    </span>
                                    <span class="block">
                                        {{ $internationalPercentage }}%
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-5 gap-[25px] mb-[25px]">
                <div class="lg:col-span-2">

                    <!-- Bookings by Destinations -->
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                        <div class="trezo-card-header mb-[20px] md:mb-[25px] flex items-center justify-between">
                            <div class="trezo-card-title">
                                <h5 class="!mb-0">
                                    Bookings by Destinations
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
                            <div class="flex items-center justify-center min-h-[160px]">
                                <div id="salesByLocationsJsVectorMap"></div>
                            </div>
                            <ul class="mt-[20px]">
                                @foreach($bookingsByDestination as $destination)
                                <li class="flex items-center mb-[14.4px] last:mb-0">
                                    <div class="shrink-0 ltr:mr-[15px] rtl:ml-[15px]">
                                        <img src="assets/images/flags/{{ strtolower(str_replace(' ', '-', $destination->destination_country)) }}.svg" class="inline-block" alt="{{ strtolower($destination->destination_country) }}" onerror="this.src='assets/images/flags/default.svg'">
                                    </div>
                                    <div class="grow">
                                        <span class="block font-medium mb-[2px]">
                                            {{ $destination->destination_country }}
                                        </span>
                                        <div class="leading-none flex items-center">
                                            <div class="flex w-full h-[4px] overflow-hidden rounded-md bg-primary-50 dark:bg-[#172036]">
                                                <div class="flex flex-col justify-center overflow-hidden bg-primary-500 rounded-md" style="width: {{ $destination->percentage }}%;"></div>
                                            </div>
                                            <span class="block ltr:ml-[15px] rtl:mr-[15px]">
                                                {{ $destination->percentage }}%
                                            </span>
                                        </div>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                </div>
                <div class="lg:col-span-3">
                    
                    <!-- Top Selling Flights -->
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                        <div class="trezo-card-header mb-[20px] md:mb-[25px] flex items-center justify-between">
                            <div class="trezo-card-title">
                                <h5 class="!mb-0">
                                    Most Booked Flights
                                </h5>
                            </div>
                            <div class="trezo-card-subtitle">
                                <div class="trezo-card-dropdown relative">
                                    <button type="button" class="trezo-card-dropdown-btn inline-block rounded-md border border-gray-100 py-[5px] md:py-[6.5px] px-[12px] md:px-[19px] transition-all hover:bg-gray-50 dark:border-[#172036] dark:hover:bg-[#0a0e19]" id="dropdownToggleBtn">
                                        <span class="inline-block relative ltr:pr-[17px] ltr:md:pr-[20px] rtl:pl-[17px] rtl:ml:pr-[20px]">
                                            Today
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
                            <div class="table-responsive overflow-x-auto">
                                <table class="w-full">
                                    <thead class="text-black dark:text-white">
                                        <tr>
                                            <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap ltr:first:rounded-tl-md ltr:last:rounded-tr-md rtl:first:rounded-tr-md rtl:last:rounded-tl-md">
                                                Flight
                                            </th>
                                            <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap ltr:first:rounded-tl-md ltr:last:rounded-tr-md rtl:first:rounded-tr-md rtl:last:rounded-tl-md">
                                                Price
                                            </th>
                                            <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap ltr:first:rounded-tl-md ltr:last:rounded-tr-md rtl:first:rounded-tr-md rtl:last:rounded-tl-md">
                                                Bookings
                                            </th>
                                            <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap ltr:first:rounded-tl-md ltr:last:rounded-tr-md rtl:first:rounded-tr-md rtl:last:rounded-tl-md">
                                                Status
                                            </th>
                                            <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap ltr:first:rounded-tr-md ltr:last:rounded-tr-md rtl:first:rounded-tr-md rtl:last:rounded-tl-md">
                                                Revenue
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-black dark:text-white">
                                        @foreach($topFlights as $flight)
                                        <tr>
                                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                                <div class="flex items-center">
                                                    <div class="rounded-md w-[40px] h-[40px] bg-primary-100 dark:bg-[#15203c] flex items-center justify-center">
                                                        <i class="material-symbols-outlined text-primary-500">
                                                            flight
                                                        </i>
                                                    </div>
                                                    <div class="ltr:ml-[12px] rtl:mr-[12px]">
                                                        <a href="{{ route('admin.flights.show', $flight->id) }}" class="font-medium inline-block text-[14px] md:text-[15px] transition-all hover:text-primary-500">
                                                            {{ $flight->airline }} {{ $flight->flight_number }}
                                                        </a>
                                                        <span class="block text-sm text-gray-500 dark:text-gray-400">
                                                            {{ $flight->departure_city }} → {{ $flight->arrival_city }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                                ${{ number_format($flight->price, 2) }}
                                            </td>
                                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                                {{ $flight->booking_count }}
                                            </td>
                                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                                @if($flight->status == 'cancelled')
                                                    <span class="text-danger-400">Cancelled</span>
                                                @else
                                                    <span class="text-success-600">{{ ucfirst($flight->status) }}</span>
                                                @endif
                                            </td>
                                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                                ${{ number_format($flight->total_revenue, 2) }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="px-[20px] py-[12px] md:py-[14px] rounded-b-md border-l border-r border-b border-gray-100 dark:border-[#172036] sm:flex sm:items-center justify-between">
                                <p class="!mb-0 text-sm">
                                    Showing {{ $topFlights->count() }} of {{ \App\Models\Flight::count() }} results
                                </p>
                                <ol class="mt-[10px] sm:mt-0">
                                    <li class="inline-block mx-[1px] ltr:first:ml-0 ltr:last:mr-0 rtl:first:mr-0 rtl:last:ml-0">
                                        <a href="javascript:void(0);" class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500">
                                            <span class="opacity-0">
                                                0
                                            </span>
                                            <i class="material-symbols-outlined left-0 right-0 absolute top-1/2 -translate-y-1/2">
                                                chevron_left
                                            </i>
                                        </a>
                                    </li>
                                    <li class="inline-block mx-[1px] ltr:first:ml-0 ltr:last:mr-0 rtl:first:mr-0 rtl:last:ml-0">
                                        <a href="javascript:void(0);" class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-primary-500 bg-primary-500 text-white">
                                            1
                                        </a>
                                    </li>
                                    <li class="inline-block mx-[1px] ltr:first:ml-0 ltr:last:mr-0 rtl:first:mr-0 rtl:last:ml-0">
                                        <a href="javascript:void(0);" class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500">
                                            2
                                        </a>
                                    </li>
                                    <li class="inline-block mx-[1px] ltr:first:ml-0 ltr:last:mr-0 rtl:first:mr-0 rtl:last:ml-0">
                                        <a href="javascript:void(0);" class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500">
                                            3
                                        </a>
                                    </li>
                                    <li class="inline-block mx-[1px] ltr:first:ml-0 ltr:last:mr-0 rtl:first:mr-0 rtl:last:ml-0">
                                        <a href="javascript:void(0);" class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500">
                                            4
                                        </a>
                                    </li>
                                    <li class="inline-block mx-[1px] ltr:first:ml-0 ltr:last:mr-0 rtl:first:mr-0 rtl:last:ml-0">
                                        <a href="javascript:void(0);" class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500">
                                            <span class="opacity-0">
                                                0
                                            </span>
                                            <i class="material-symbols-outlined left-0 right-0 absolute top-1/2 -translate-y-1/2">
                                                chevron_right
                                            </i>
                                        </a>
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-[25px] mb-[25px]">
        <div class="lg:col-span-1">
            <!-- Recent Transactions -->
            <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                <div class="trezo-card-header mb-[20px] md:mb-[25px] flex items-center justify-between">
                    <div class="trezo-card-title">
                        <h5 class="!mb-0">Recent Transactions</h5>
                    </div>
                </div>
                <div class="trezo-card-content">
                    <ul>
                        @foreach($recentTransactions as $transaction)
                        <li class="flex items-center justify-between mb-[15px] md:mb-[18px] lg:mb-[22px] last:mb-0">
                            <div class="flex items-center">
                                <div class="w-[41px] h-[41px] ltr:mr-[12px] rtl:ml-[12px] bg-primary-100 dark:bg-[#15203c] text-primary-500 rounded-full flex items-center justify-center">
                                    <i class="material-symbols-outlined">credit_card</i>
                                </div>
                                <div>
                                    <span class="block font-medium text-black dark:text-white text-base md:text-[16px] mb-[3px] md:mb-px">
                                        {{ $transaction->booking->user->name ?? 'Unknown' }}
                                    </span>
                                    <span class="block text-sm">
                                        {{ $transaction->created_at->format('d M Y - H:i') }}
                                    </span>
                                </div>
                            </div>
                            <span class="block {{ $transaction->status == 'completed' ? 'text-success-600' : 'text-danger-500' }}">
                                {{ $transaction->status == 'completed' ? '+' : '-' }}${{ number_format($transaction->amount, 2) }}
                            </span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-[25px] mb-[25px]">
                <!-- System Performance Metrics -->
                <div class="lg:col-span-1">
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                        <div class="trezo-card-header mb-[20px] flex items-center justify-between">
                            <div class="trezo-card-title">
                                <h5 class="!mb-0">System Stats</h5>
                            </div>
                        </div>
                        <div class="trezo-card-content">
                            <ul class="space-y-4">
                                <li class="flex items-center justify-between">
                                    <span class="text-sm">Active Users</span>
                                    <span class="font-semibold">{{ number_format($totalUsers) }}</span>
                                </li>
                                <li class="flex items-center justify-between">
                                    <span class="text-sm">Total Flights</span>
                                    <span class="font-semibold">{{ number_format(\App\Models\Flight::count()) }}</span>
                                </li>
                                <li class="flex items-center justify-between">
                                    <span class="text-sm">API Calls Today</span>
                                    <span class="font-semibold">{{ number_format($apiStats['flight_searches'] ?? 0) }}</span>
                                </li>
                                <li class="flex items-center justify-between">
                                    <span class="text-sm">Success Rate</span>
                                    <span class="font-semibold text-success-600">98.5%</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="lg:col-span-3">
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                        <div class="trezo-card-header mb-[20px] flex items-center justify-between">
                            <div class="trezo-card-title">
                                <h5 class="!mb-0">Quick Actions</h5>
                            </div>
                        </div>
                        <div class="trezo-card-content">
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <a href="" class="flex flex-col items-center p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                                    <i class="material-symbols-outlined text-primary-500 text-2xl mb-2">add_circle</i>
                                    <span class="text-sm font-medium">Add Flight</span>
                                </a>
                                <a href="" class="flex flex-col items-center p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                                    <i class="material-symbols-outlined text-blue-500 text-2xl mb-2">assignment</i>
                                    <span class="text-sm font-medium">View Bookings</span>
                                </a>
                                <a href="" class="flex flex-col items-center p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                                    <i class="material-symbols-outlined text-green-500 text-2xl mb-2">people</i>
                                    <span class="text-sm font-medium">Manage Users</span>
                                </a>
                                <a href="" class="flex flex-col items-center p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                                    <i class="material-symbols-outlined text-purple-500 text-2xl mb-2">analytics</i>
                                    <span class="text-sm font-medium">Reports</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monthly Revenue Chart -->
            <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                <div class="trezo-card-header mb-[20px] md:mb-[25px] flex items-center justify-between">
                    <div class="trezo-card-title">
                        <h5 class="!mb-0">Monthly Revenue</h5>
                    </div>
                </div>
                <div class="trezo-card-content">
                    <div class="-mb-[15px] -mt-[5px] md:-mt-[22px] ltr:-ml-[10px] rtl:-mr-[10px]">
                        <div id="monthlyRevenueChart" data-revenue="{{ json_encode($monthlyRevenue) }}"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <!-- Recent Activities Section -->
        <div class="grid grid-cols-1 mb-[25px]">
            <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                <div class="trezo-card-header mb-[20px] md:mb-[25px] flex items-center justify-between">
                    <div class="trezo-card-title">
                        <h5 class="!mb-0">Recent System Activities</h5>
                    </div>
                    <div class="trezo-card-subtitle">
                        <button class="text-primary-500 text-sm hover:underline">View All</button>
                    </div>
                </div>
                <div class="trezo-card-content">
                    <div class="space-y-4">
                        @foreach($recentActivities as $activity)
                        <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                            <div class="w-10 h-10 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center mr-3">
                                <i class="material-symbols-outlined text-primary-500">{{ $activity->icon ?? 'info' }}</i>
                            </div>
                            <div class="flex-1">
                                <h6 class="font-medium text-sm">{{ $activity->title }}</h6>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $activity->description }}</p>
                            </div>
                            <div class="text-xs text-gray-400">
                                {{ \Carbon\Carbon::parse($activity->created_at)->diffForHumans() }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-[25px] mb-[25px]">
                <div class="lg:col-span-2">

                    <!-- Recent Bookings -->
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                        <div class="trezo-card-header mb-[20px] md:mb-[25px] sm:flex sm:items-center sm:justify-between">
                            <div class="trezo-card-title">
                                <h5 class="!mb-0">
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
                                    <input type="text" placeholder="Search bookings....." class="bg-gray-50 border border-gray-50 h-[36px] text-xs rounded-md w-full block text-black pt-[11px] pb-[12px] ltr:pl-[38px] rtl:pr-[38px] ltr:pr-[13px] ltr:md:pr-[16px] rtl:pl-[13px] rtl:md:pl-[16px] placeholder:text-gray-500 outline-0 dark:bg-[#15203c] dark:text-white dark:border-[#15203c] dark:placeholder:text-gray-400">
                                </form>
                                <div class="trezo-card-dropdown relative">
                                    <button type="button" class="trezo-card-dropdown-btn inline-block rounded-md border border-gray-100 py-[5px] md:py-[6.5px] px-[12px] md:px-[19px] transition-all hover:bg-gray-50 dark:border-[#172036] dark:hover:bg-[#0a0e19]" id="dropdownToggleBtn">
                                        <span class="inline-block relative ltr:pr-[17px] ltr:md:pr-[20px] rtl:pl-[17px] rtl:ml:pr-[20px]">
                                            Show All
                                            <i class="ri-arrow-down-s-line text-lg absolute ltr:-right-[3px] rtl:-left-[3px] top-1/2 -translate-y-1/2"></i>
                                        </span>
                                    </button>
                                    <ul class="trezo-card-dropdown-menu transition-all bg-white shadow-3xl rounded-md top-full py-[15px] absolute ltr:left-0 sm:ltr:right-0 rtl:right-0 sm:rtl:left-0 w-[195px] z-[5] dark:bg-dark dark:shadow-none">
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
                            <div class="table-responsive overflow-x-auto">
                                <table class="w-full">
                                    <thead class="text-black dark:text-white">
                                        <tr>
                                            <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap first:rounded-tl-md">
                                                Booking ID
                                            </th>
                                            <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap first:rounded-tl-md">
                                                Customer
                                            </th>
                                            <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap first:rounded-tl-md">
                                                Created
                                            </th>
                                            <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap first:rounded-tl-md">
                                                Total
                                            </th>
                                            <th class="font-medium text-left px-[20px] py-[11px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap first:rounded-tr-md">
                                                Status
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-black dark:text-white">
                                        @foreach($recentBookings as $booking)
                                        <tr>
                                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                                #{{ strtoupper($booking->booking_reference) }}
                                            </td>
                                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                                <div class="flex items-center">
                                                    <div class="rounded-md w-[40px] h-[40px] bg-primary-100 dark:bg-[#15203c] flex items-center justify-center">
                                                        <span class="text-primary-500 font-semibold text-sm">
                                                            {{ strtoupper(substr($booking->user->name, 0, 2)) }}
                                                        </span>
                                                    </div>
                                                    <div class="ltr:ml-[12px] rtl:mr-[12px]">
                                                        <span class="block font-medium">
                                                            {{ $booking->user->name }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                                {{ $booking->created_at->format('d M Y') }}
                                            </td>
                                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                                ${{ number_format($booking->payment->amount ?? 0, 2) }}
                                            </td>
                                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                                @switch($booking->status)
                                                    @case('completed')
                                                        <span class="px-[8px] py-[3px] inline-block bg-success-100 dark:bg-[#15203c] text-success-600 rounded-sm font-medium text-xs">
                                                            Completed
                                                        </span>
                                                        @break
                                                    @case('confirmed')
                                                        <span class="px-[8px] py-[3px] inline-block bg-primary-50 dark:bg-[#15203c] text-primary-500 rounded-sm font-medium text-xs">
                                                            Confirmed
                                                        </span>
                                                        @break
                                                    @case('pending')
                                                        <span class="px-[8px] py-[3px] inline-block bg-warning-50 dark:bg-[#15203c] text-warning-700 rounded-sm font-medium text-xs">
                                                            Pending
                                                        </span>
                                                        @break
                                                    @case('cancelled')
                                                        <span class="px-[8px] py-[3px] inline-block bg-danger-100 dark:bg-[#15203c] text-danger-500 rounded-sm font-medium text-xs">
                                                            Cancelled
                                                        </span>
                                                        @break
                                                    @default
                                                        <span class="px-[8px] py-[3px] inline-block bg-gray-100 dark:bg-[#15203c] text-gray-600 rounded-sm font-medium text-xs">
                                                            {{ ucfirst($booking->status) }}
                                                        </span>
                                                @endswitch
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="px-[20px] py-[12px] md:py-[14px] rounded-b-md border-l border-r border-b border-gray-100 dark:border-[#172036] sm:flex sm:items-center justify-between">
                                <p class="!mb-0 text-sm">
                                    Showing {{ $recentBookings->count() }} of {{ \App\Models\Booking::count() }} results
                                </p>
                               @if(method_exists($recentBookings, 'links'))
                            {{ $recentBookings->links() }}
                        @else
                            <ol class="mt-[10px] sm:mt-0">
                                <li class="inline-block mx-[1px]">
                                    <a href="#" class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-primary-500 bg-primary-500 text-white">
                                        1
                                    </a>
                                </li>
                            </ol>
                        @endif
                            </div>
                        </div>
                    </div>

                </div>
                <div class="lg:col-span-1">

                    <!-- Booking Summary -->
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                        <div class="trezo-card-header mb-[20px] md:mb-[25px] flex items-center justify-between">
                            <div class="trezo-card-title">
                                <h5 class="!mb-0">
                                    Booking Summary
                                </h5>
                            </div>
                            <div class="trezo-card-subtitle">
                                <div class="trezo-card-dropdown relative">
                                    <button type="button" class="trezo-card-dropdown-btn inline-block rounded-md border border-gray-100 py-[5px] md:py-[6.5px] px-[12px] md:px-[19px] transition-all hover:bg-gray-50 dark:border-[#172036] dark:hover:bg-[#0a0e19]" id="dropdownToggleBtn">
                                        <span class="inline-block relative ltr:pr-[17px] ltr:md:pr-[20px] rtl:pl-[17px] rtl:ml:pr-[20px]">
                                            Today
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
                            <div id="ecommerceOrderSummaryChart" data-completed="{{ $completedOrdersPercentage }}" data-new="{{ $newOrdersPercentage }}" data-pending="{{ $pendingOrdersPercentage }}"></div>
                            <ul class="-mt-[4px]">
                                <li class="mb-[10px] md:mb-[12px] last:mb-0">
                                    <span class="block font-medium mb-[2px] md:mb-[4px]">
                                        Completed Bookings
                                    </span>
                                    <div class="leading-none flex items-center">
                                        <div class="flex w-full h-[4px] overflow-hidden rounded-md bg-primary-50 dark:bg-[#172036]">
                                            <div class="flex flex-col justify-center bg-primary-500" style="width: {{ $completedOrdersPercentage }}%;"></div>   
                                        </div>
                                        <span class="ltr:ml-[10px] rtl:mr-[10px] text-xs font-medium">
                                            {{ $completedOrdersPercentage }}%
                                        </span>
                                    </div>
                                </li>
                                <li class="mb-[10px] md:mb-[12px] last:mb-0">
                                    <span class="block font-medium mb-[2px] md:mb-[4px]">
                                        New Bookings        
                                    </span>
                                    <div class="leading-none flex items-center">    
                                        <div class="flex w-full h-[4px] overflow-hidden rounded-md bg-primary-50 dark:bg-[#172036]">
                                            <div class="flex flex-col justify-center bg-primary-500" style="width: {{ $newOrdersPercentage }}%;"></div>   
                                        </div>
                                        <span class="ltr:ml-[10px] rtl:mr-[10px] text-xs font-medium">
                                            {{ $newOrdersPercentage }}%
                                        </span>
                                    </div>
                                </li>
                                <li class="mb-[10px] md:mb-[12px] last:mb-0">
                                    <span class="block font-medium mb-[2px] md:mb-[4px]">
                                        Pending Bookings
                                    </span>
                                    <div class="leading-none flex items-center">
                                        <div class="flex w-full h-[4px] overflow-hidden rounded-md bg-primary-50 dark:bg-[#172036]">
                                            <div class="flex flex-col justify-center bg-primary-500" style="width: {{ $pendingOrdersPercentage }}%;"></div>   
                                        </div>
                                        <span class="ltr:ml-[10px] rtl:mr-[10px] text-xs font-medium">
                                            {{ $pendingOrdersPercentage }}%
                                        </span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-[25px] mb-[25px]">
                <div class="lg:col-span-1">

                    <!-- Top Customers -->
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                        <div class="trezo-card-header mb-[20px] md:mb-[25px] flex items-center justify-between">
                            <div class="trezo-card-title">
                                <h5 class="!mb-0">
                                    Top Customers
                                </h5>
                            </div>
                            <div class="trezo-card-subtitle">
                                <div class="trezo-card-subtitle">
                                    <p class="!mb-0 text-sm text-gray-500 dark:text-gray-400">
                                        Here are your top customers based on their booking activity.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="trezo-card-body">
                            <div class="trezo-card-content">
                                <p class="!mb-0 text-sm text-gray-500 dark:text-gray-400">
                                    Here is the detailed view of your top customers.
                                </p>
                            </div>
                        </div>
                        <div class="trezo-card-content">
                            <div class="table-responsive overflow-x-auto">
                                <table class="w-full">
                                    <thead class="text-black    dark:text-white">
                                        <tr>
                                            <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap first:rounded-tl-md">
                                                Customer
                                            </th>
                                            <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap first:rounded-tl-md">
                                                Total Bookings
                                            </th>
                                            <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap first:rounded-tl-md">
                                                Total Spent
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-black dark:text-white">
                                        @foreach($topCustomers as $customer)
                                        <tr>
                                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                                <div class="flex items-center">
                                                    <div class="rounded-md w-[40px] h-[40px] bg-primary-100 dark:bg-[#15203c] flex items-center justify-center">
                                                        <span class="text-primary-500 font-semibold text-sm">
                                                            {{ strtoupper(substr($customer->name, 0, 2)) }}
                                                        </span>
                                                    </div>
                                                    <div class="ltr:ml-[12px] rtl:mr-[12px]">
                                                        <span class="block font-medium">
                                                            {{ $customer->name }}
                                                        </span>
                                                        <span class="block text-sm text-gray-500 dark:text-gray-400">
                                                            {{ $customer->email }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                                {{ $customer->bookings_count }}
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    ({{ number_format(($customer->bookings_count / \App\Models\Booking::count()) * 100, 2) }}%)
                                                </div>
                                            </td>
                                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                                {{ number_format($customer->total_spent, 2) }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Main Content -->

@endsection

<style>

    /* Dashboard Custom Styles */

.trezo-card {
    border: 1px solid #e5e7eb;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    transition: all 0.3s ease;
}

.trezo-card:hover {
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

.trezo-card-dropdown-menu {
    display: none;
    opacity: 0;
    transform: translateY(-10px);
    transition: all 0.3s ease;
    z-index: 50;
}

.trezo-card-dropdown-menu:not(.hidden) {
    display: block;
    opacity: 1;
    transform: translateY(0);
}

.trezo-card-dropdown-menu button:hover {
    background-color: #f8fafc;
    color: #605DFF;
}

/* Dark mode styles */
.dark .trezo-card {
    border-color: #374151;
}

.dark .trezo-card-dropdown-menu button:hover {
    background-color: #1f2937;
}

/* Chart container styles */
.apexcharts-canvas {
    font-family: inherit !important;
}

.apexcharts-tooltip {
    background: #ffffff !important;
    border: 1px solid #e5e7eb !important;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
}

.dark .apexcharts-tooltip {
    background: #1f2937 !important;
    border-color: #374151 !important;
    color: #ffffff !important;
}

/* Table responsive styles */
.table-responsive {
    border-radius: 0.375rem;
    overflow: hidden;
}

.table-responsive table {
    border-collapse: separate;
    border-spacing: 0;
}

.table-responsive th:first-child,
.table-responsive td:first-child {
    border-left: 1px solid #e5e7eb;
}

.table-responsive th:last-child,
.table-responsive td:last-child {
    border-right: 1px solid #e5e7eb;
}

.dark .table-responsive th:first-child,
.dark .table-responsive th:last-child,
.dark .table-responsive td:first-child,
.dark .table-responsive td:last-child {
    border-color: #374151;
}

/* Status badges */
.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.75rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.status-completed {
    background-color: #d1fae5;
    color: #065f46;
}

.status-pending {
    background-color: #fef3c7;
    color: #92400e;
}

.status-cancelled {
    background-color: #fee2e2;
    color: #991b1b;
}

.dark .status-completed {
    background-color: #064e3b;
    color: #6ee7b7;
}

.dark .status-pending {
    background-color: #78350f;
    color: #fcd34d;
}

.dark .status-cancelled {
    background-color: #7f1d1d;
    color: #fca5a5;
}

/* Progress bars */
.progress-bar {
    background-color: #f3f4f6;
    border-radius: 0.25rem;
    overflow: hidden;
}

.progress-fill {
    height: 0.25rem;
    background-color: #605DFF;
    transition: width 0.3s ease;
}

/* Animation for cards on load */
.trezo-card {
    animation: fadeInUp 0.6s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Stagger animation for multiple cards */
.trezo-card:nth-child(1) { animation-delay: 0.1s; }
.trezo-card:nth-child(2) { animation-delay: 0.2s; }
.trezo-card:nth-child(3) { animation-delay: 0.3s; }
.trezo-card:nth-child(4) { animation-delay: 0.4s; }

/* Loading state for charts */
.chart-loading {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 200px;
    color: #9ca3af;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .grid {
        gap: 1rem;
    }
    
    .trezo-card {
        padding: 1rem;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
}

/* Vector map container */
#salesByLocationsJsVectorMap {
    width: 100%;
    height: 160px;
}

/* Custom scrollbar for tables */
.table-responsive::-webkit-scrollbar {
    height: 8px;
}

.table-responsive::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 10px;
}

.table-responsive::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}

.table-responsive::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

.dark .table-responsive::-webkit-scrollbar-track {
    background: #1e293b;
}

.dark .table-responsive::-webkit-scrollbar-thumb {
    background: #475569;
}

.dark .table-responsive::-webkit-scrollbar-thumb:hover {
    background: #64748b;
}

/* Stat update animation */
@keyframes stat-highlight {
    0% {
        background-color: rgba(96, 93, 255, 0);
    }
    50% {
        background-color: rgba(96, 93, 255, 0.2);
    }
    100% {
        background-color: rgba(96, 93, 255, 0);
    }
}

.stat-updated {
    animation: stat-highlight 1.5s ease-in-out;
    border-radius: 0.25rem;
}

.dark .stat-updated {
    animation: stat-highlight 1.5s ease-in-out;
}

</style>    

<!-- Scripts -->
@section('scripts')

<script>

// Dashboard Charts and Interactions
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all charts
    initializeBookingTrendsChart();
    initializeTotalOrdersChart();
    initializeTotalCustomersChart();
    initializeTotalRevenueChart();
    initializeOrderSummaryChart();
    
    // Initialize dropdown functionality
    initializeDropdowns();
    
    // Setup real-time updates
    setupRealtimeUpdates();
});

// Booking Trends Chart
function initializeBookingTrendsChart() {
    const chartElement = document.getElementById('ecommerceTotalSalesChart');
    if (!chartElement) return;
    
    const bookingsData = JSON.parse(chartElement.dataset.bookings || '[]');
    
    const options = {
        series: [{
            name: 'Bookings',
            data: bookingsData.map(item => item.bookings)
        }],
        chart: {
            type: 'area',
            height: 350,
            toolbar: {
                show: false
            }
        },
        colors: ['#605DFF'],
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 2
        },
        xaxis: {
            categories: bookingsData.map(item => item.month),
            labels: {
                style: {
                    colors: '#64748B'
                }
            }
        },
        yaxis: {
            labels: {
                style: {
                    colors: '#64748B'
                }
            }
        },
        grid: {
            strokeDashArray: 4,
            borderColor: '#E2E8F0'
        },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.7,
                opacityTo: 0.3,
                stops: [0, 90, 100]
            }
        }
    };

    if (typeof ApexCharts !== 'undefined') {
        const chart = new ApexCharts(chartElement, options);
        chart.render();
    }
}

// Total Orders Donut Chart
function initializeTotalOrdersChart() {
    const chartElement = document.getElementById('ecommerceTotalOrdersChart');
    if (!chartElement) return;
    
    const options = {
        series: [75, 25], // Completed vs Pending percentages
        chart: {
            type: 'donut',
            height: 150
        },
        colors: ['#605DFF', '#D1D5DB'],
        legend: {
            show: false
        },
        dataLabels: {
            enabled: false
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '70%'
                }
            }
        }
    };

    if (typeof ApexCharts !== 'undefined') {
        const chart = new ApexCharts(chartElement, options);
        chart.render();
    }
}

// Total Customers Line Chart
function initializeTotalCustomersChart() {
    const chartElement = document.getElementById('ecommerceTotalCustomersChart');
    if (!chartElement) return;
    
    const customersData = JSON.parse(chartElement.dataset.customers || '[]');
    
    const options = {
        series: [{
            data: customersData.map(item => item.customers)
        }],
        chart: {
            type: 'line',
            height: 100,
            sparkline: {
                enabled: true
            }
        },
        colors: ['#10B981'],
        stroke: {
            curve: 'smooth',
            width: 3
        },
        markers: {
            size: 0
        },
        tooltip: {
            enabled: false
        }
    };

    if (typeof ApexCharts !== 'undefined') {
        const chart = new ApexCharts(chartElement, options);
        chart.render();
    }
}

// Total Revenue Area Chart
function initializeTotalRevenueChart() {
    const chartElement = document.getElementById('ecommerceTotalRevenueChart');
    if (!chartElement) return;
    
    const options = {
        series: [{
            data: [30, 45, 35, 50, 40, 60, 45]
        }],
        chart: {
            type: 'area',
            height: 100,
            sparkline: {
                enabled: true
            }
        },
        colors: ['#F59E0B'],
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.4,
                opacityTo: 0.1,
                stops: [0, 90, 100]
            }
        },
        stroke: {
            curve: 'smooth',
            width: 2
        }
    };

    if (typeof ApexCharts !== 'undefined') {
        const chart = new ApexCharts(chartElement, options);
        chart.render();
    }
}

// Order Summary Donut Chart
function initializeOrderSummaryChart() {
    const chartElement = document.getElementById('ecommerceOrderSummaryChart');
    if (!chartElement) return;
    
    const completed = parseInt(chartElement.dataset.completed || '0');
    const newOrders = parseInt(chartElement.dataset.new || '0');
    const pending = parseInt(chartElement.dataset.pending || '0');
    
    const options = {
        series: [completed, newOrders, pending],
        chart: {
            type: 'donut',
            height: 250
        },
        colors: ['#605DFF', '#3B82F6', '#10B981'],
        labels: ['Completed', 'New', 'Pending'],
        legend: {
            show: false
        },
        dataLabels: {
            enabled: false
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '70%'
                }
            }
        }
    };

    if (typeof ApexCharts !== 'undefined') {
        const chart = new ApexCharts(chartElement, options);
        chart.render();
    }
}

// Initialize dropdown functionality
function initializeDropdowns() {
    const dropdownButtons = document.querySelectorAll('.trezo-card-dropdown-btn');
    
    dropdownButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const dropdown = this.closest('.trezo-card-dropdown');
            const menu = dropdown.querySelector('.trezo-card-dropdown-menu');
            
            // Close other dropdowns
            document.querySelectorAll('.trezo-card-dropdown-menu').forEach(otherMenu => {
                if (otherMenu !== menu) {
                    otherMenu.classList.add('hidden');
                }
            });
            
            // Toggle current dropdown
            menu.classList.toggle('hidden');
        });
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', function() {
        document.querySelectorAll('.trezo-card-dropdown-menu').forEach(menu => {
            menu.classList.add('hidden');
        });
    });

    // Handle dropdown item clicks
    const dropdownItems = document.querySelectorAll('.trezo-card-dropdown-menu button');
    dropdownItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            
            const dropdown = this.closest('.trezo-card-dropdown');
            const button = dropdown.querySelector('.trezo-card-dropdown-btn span');
            const menu = dropdown.querySelector('.trezo-card-dropdown-menu');
            
            // Update button text
            button.firstChild.textContent = this.textContent.trim();
            
            // Close menu
            menu.classList.add('hidden');
            
            // Here you would typically trigger a data refresh based on the selection
            console.log('Selected:', this.textContent.trim());
        });
    });
}

// Vector Map for Sales by Location (if jsvectormap is available)
function initializeSalesLocationMap() {
    const mapElement = document.getElementById('salesByLocationsJsVectorMap');
    if (!mapElement || typeof jsVectorMap === 'undefined') return;
    
    const map = new jsVectorMap({
        selector: '#salesByLocationsJsVectorMap',
        map: 'world',
        backgroundColor: 'transparent',
        regionStyle: {
            initial: {
                fill: '#E2E8F0'
            },
            hover: {
                fill: '#605DFF',
                fillOpacity: 0.8
            }
        },
        markerStyle: {
            initial: {
                fill: '#605DFF',
                stroke: '#ffffff',
                strokeWidth: 2,
                r: 8
            }
        },
        markers: [
            { latLng: [40.7128, -74.0060], name: 'New York' },
            { latLng: [51.5074, -0.1278], name: 'London' },
            { latLng: [35.6762, 139.6503], name: 'Tokyo' },
            { latLng: [48.8566, 2.3522], name: 'Paris' }
        ]
    });
}

// Initialize map when page loads
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(initializeSalesLocationMap, 100);
});

</script>

<script>
// Add the monthly revenue chart
function initializeMonthlyRevenueChart() {
    const chartElement = document.getElementById('monthlyRevenueChart');
    if (!chartElement) return;
    
    const revenueData = JSON.parse(chartElement.dataset.revenue || '[]');
    
    const options = {
        series: [{
            name: 'Revenue',
            data: revenueData.map(item => item.revenue)
        }],
        chart: {
            type: 'bar',
            height: 300,
            toolbar: {
                show: false
            }
        },
        colors: ['#605DFF'],
        plotOptions: {
            bar: {
                borderRadius: 4,
                columnWidth: '60%'
            }
        },
        dataLabels: {
            enabled: false
        },
        xaxis: {
            categories: revenueData.map(item => item.month),
            labels: {
                style: {
                    colors: '#64748B'
                }
            }
        },
        yaxis: {
            labels: {
                style: {
                    colors: '#64748B'
                },
                formatter: function(value) {
                    return '$' + (value/1000).toFixed(0) + 'K';
                }
            }
        },
        grid: {
            strokeDashArray: 4,
            borderColor: '#E2E8F0'
        }
    };

    if (typeof ApexCharts !== 'undefined') {
        const chart = new ApexCharts(chartElement, options);
        chart.render();
    }
}

// Initialize all charts when page loads
document.addEventListener('DOMContentLoaded', function() {
    // Your existing chart initialization functions
    initializeBookingTrendsChart();
    initializeTotalOrdersChart();
    initializeTotalCustomersChart();
    initializeTotalRevenueChart();
    initializeOrderSummaryChart();
    
    // New chart function
    initializeMonthlyRevenueChart();
    
    // Initialize dropdowns
    initializeDropdowns();
});

// Setup real-time dashboard updates
function setupRealtimeUpdates() {
    // Set up periodic refresh for dashboard stats
    setInterval(refreshDashboardStats, 30000); // Refresh every 30 seconds
    
    // Set up Pusher for real-time updates (if available)
    if (typeof window.Echo !== 'undefined') {
        window.Echo.private('dashboard.admin')
            .listen('booking.updated', (e) => {
                console.log('Booking updated event received', e);
                refreshDashboardStats();
            });
    }
}

function refreshDashboardStats() {
    // Fetch updated stats using AJAX
    fetch('/superadmin/dashboard/stats')
        .then(response => response.json())
        .then(data => {
            // Update booking stats
            if (data.booking_stats) {
                updateBookingStatsDisplay(data.booking_stats);
            }
            
            // Update payment stats
            if (data.payment_stats) {
                updatePaymentStatsDisplay(data.payment_stats);
            }
            
            // Refresh charts if needed
            if (window.bookingTrendsChart) {
                window.bookingTrendsChart.updateOptions({
                    series: [{
                        name: 'Bookings',
                        data: data.booking_trends || []
                    }]
                });
            }
        })
        .catch(error => {
            console.error('Error refreshing dashboard stats:', error);
        });
}

function updateBookingStatsDisplay(stats) {
    // Update booking stats in the UI
    const elements = {
        'all': document.querySelector('[data-stat="bookings-all"]'),
        'completed': document.querySelector('[data-stat="bookings-completed"]'),
        'failed': document.querySelector('[data-stat="bookings-failed"]'),
        'processing': document.querySelector('[data-stat="bookings-processing"]')
    };
    
    // Update each element if it exists
    Object.keys(elements).forEach(key => {
        if (elements[key] && stats[key] !== undefined) {
            elements[key].textContent = stats[key];
            
            // Add a brief highlight effect
            elements[key].classList.add('stat-updated');
            setTimeout(() => {
                elements[key].classList.remove('stat-updated');
            }, 1000);
        }
    });
}

function updatePaymentStatsDisplay(stats) {
    // Update payment stats in the UI
    const elements = {
        'total': document.querySelector('[data-stat="payments-total"]'),
        'completed': document.querySelector('[data-stat="payments-completed"]'),
        'failed': document.querySelector('[data-stat="payments-failed"]'),
        'pending': document.querySelector('[data-stat="payments-pending"]')
    };
    
    // Update each element if it exists
    Object.keys(elements).forEach(key => {
        if (elements[key] && stats[key] !== undefined) {
            elements[key].textContent = stats[key];
            
            // Add a brief highlight effect
            elements[key].classList.add('stat-updated');
            setTimeout(() => {
                elements[key].classList.remove('stat-updated');
            }, 1000);
        }
    });
}
</script>




@endsection