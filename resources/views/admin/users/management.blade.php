@extends('layouts.admin-dashboard')

@section('title', 'Advanced User Management')

@section('content')
<div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">
    
    <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
        
        <!-- Header -->
        <div class="trezo-card-header mb-[20px] md:mb-[25px] flex items-center justify-between">
            <div class="trezo-card-title">
                <h5 class="mb-0 md:text-xl font-medium text-gray-800 dark:text-white flex items-center">
                    <i class="ri-user-settings-line mr-2 text-blue-500"></i>
                    Advanced User Management
                </h5>
                <p class="text-gray-600 dark:text-gray-300 text-sm mt-1">
                    Comprehensive user analytics, behavior tracking, and advanced management tools
                </p>
            </div>
            <div class="flex space-x-2">
                <button class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 transition-colors">
                    <i class="ri-add-line mr-2"></i>
                    Add User
                </button>
                <button class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition-colors">
                    <i class="ri-download-line mr-2"></i>
                    Export Data
                </button>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary">
                    <i class="ri-arrow-left-line mr-2"></i>
                    Back to Dashboard
                </a>
            </div>
        </div>

        <!-- User Statistics Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-[25px] mb-[25px]">
            
            <!-- Total Users -->
            <div class="trezo-card border border-blue-200 dark:border-blue-800 p-[20px] md:p-[25px] rounded-xl bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900 dark:to-blue-800">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-blue-500 text-white rounded-full p-3">
                        <i class="ri-user-line text-xl"></i>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-bold text-blue-600">{{ number_format($user_stats['total_users'] ?? 12450) }}</p>
                        <p class="text-xs text-blue-500">+{{ $user_stats['new_users_today'] ?? 45 }} today</p>
                    </div>
                </div>
                <h6 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Total Users</h6>
                <div class="flex items-center space-x-2">
                    <span class="text-green-600 text-sm font-medium">+{{ $user_stats['growth_percentage'] ?? 12.5 }}%</span>
                    <span class="text-gray-500 text-xs">vs last month</span>
                </div>
            </div>

            <!-- Active Users -->
            <div class="trezo-card border border-green-200 dark:border-green-800 p-[20px] md:p-[25px] rounded-xl bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900 dark:to-green-800">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-green-500 text-white rounded-full p-3">
                        <i class="ri-user-heart-line text-xl"></i>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-bold text-green-600">{{ number_format($user_stats['active_users'] ?? 8950) }}</p>
                        <p class="text-xs text-green-500">{{ $user_stats['active_percentage'] ?? 71.9 }}% active</p>
                    </div>
                </div>
                <h6 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Active Users</h6>
                <div class="flex items-center space-x-2">
                    <span class="text-green-600 text-sm font-medium">+{{ $user_stats['active_growth'] ?? 8.3 }}%</span>
                    <span class="text-gray-500 text-xs">activity rate</span>
                </div>
            </div>

            <!-- Premium Users -->
            <div class="trezo-card border border-purple-200 dark:border-purple-800 p-[20px] md:p-[25px] rounded-xl bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900 dark:to-purple-800">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-purple-500 text-white rounded-full p-3">
                        <i class="ri-vip-crown-line text-xl"></i>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-bold text-purple-600">{{ number_format($user_stats['premium_users'] ?? 2340) }}</p>
                        <p class="text-xs text-purple-500">{{ $user_stats['premium_percentage'] ?? 18.8 }}% premium</p>
                    </div>
                </div>
                <h6 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Premium Users</h6>
                <div class="flex items-center space-x-2">
                    <span class="text-purple-600 text-sm font-medium">${{ number_format($user_stats['premium_revenue'] ?? 145600) }}</span>
                    <span class="text-gray-500 text-xs">monthly revenue</span>
                </div>
            </div>

            <!-- Churn Risk -->
            <div class="trezo-card border border-orange-200 dark:border-orange-800 p-[20px] md:p-[25px] rounded-xl bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900 dark:to-orange-800">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-orange-500 text-white rounded-full p-3">
                        <i class="ri-alarm-warning-line text-xl"></i>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-bold text-orange-600">{{ number_format($user_stats['churn_risk'] ?? 324) }}</p>
                        <p class="text-xs text-orange-500">{{ $user_stats['churn_percentage'] ?? 2.6 }}% at risk</p>
                    </div>
                </div>
                <h6 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Churn Risk</h6>
                <div class="flex items-center space-x-2">
                    <span class="text-red-600 text-sm font-medium">-{{ $user_stats['churn_reduction'] ?? 15.2 }}%</span>
                    <span class="text-gray-500 text-xs">vs last month</span>
                </div>
            </div>

        </div>

        <!-- Advanced Filters and Search -->
        <div class="trezo-card border border-gray-100 dark:border-[#172036] p-[20px] md:p-[25px] rounded-md mb-[25px]">
            <div class="trezo-card-header mb-[20px]">
                <h5 class="mb-0 flex items-center">
                    <i class="ri-search-2-line mr-2 text-blue-500"></i>
                    Advanced User Search & Filters
                </h5>
            </div>
            <div class="trezo-card-content">
                <form class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                    <!-- Search Input -->
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search Users</label>
                        <div class="relative">
                            <input type="text" placeholder="Name, email, ID, phone..." 
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                            <i class="ri-search-line absolute left-3 top-3 text-gray-400"></i>
                        </div>
                    </div>
                    
                    <!-- User Status Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                        <select class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="">All Statuses</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="suspended">Suspended</option>
                            <option value="pending">Pending Verification</option>
                        </select>
                    </div>
                    
                    <!-- User Type Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">User Type</label>
                        <select class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="">All Types</option>
                            <option value="regular">Regular</option>
                            <option value="premium">Premium</option>
                            <option value="vip">VIP</option>
                            <option value="corporate">Corporate</option>
                        </select>
                    </div>
                    
                    <!-- Registration Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Registration Date</label>
                        <select class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="">All Time</option>
                            <option value="today">Today</option>
                            <option value="week">This Week</option>
                            <option value="month">This Month</option>
                            <option value="quarter">This Quarter</option>
                            <option value="year">This Year</option>
                        </select>
                    </div>
                    
                    <!-- Last Activity -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Last Activity</label>
                        <select class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="">Any Time</option>
                            <option value="today">Today</option>
                            <option value="week">This Week</option>
                            <option value="month">This Month</option>
                            <option value="3months">3+ Months Ago</option>
                        </select>
                    </div>
                    
                    <!-- Booking Count -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Booking Count</label>
                        <select class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="">Any Count</option>
                            <option value="0">No Bookings</option>
                            <option value="1-5">1-5 Bookings</option>
                            <option value="6-20">6-20 Bookings</option>
                            <option value="21+">21+ Bookings</option>
                        </select>
                    </div>
                    
                    <!-- Location -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Location</label>
                        <input type="text" placeholder="Country, city..." 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                    </div>
                    
                    <!-- Buttons -->
                    <div class="flex items-end space-x-2">
                        <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-600 transition-colors">
                            <i class="ri-search-line mr-2"></i>
                            Search
                        </button>
                        <button type="reset" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition-colors">
                            <i class="ri-refresh-line mr-2"></i>
                            Reset
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- User Behavior Analytics -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-[25px] mb-[25px]">
            
            <!-- User Activity Heatmap -->
            <div class="trezo-card border border-gray-100 dark:border-[#172036] p-[20px] md:p-[25px] rounded-md">
                <div class="trezo-card-header mb-[20px]">
                    <h5 class="mb-0 flex items-center">
                        <i class="ri-fire-line mr-2 text-orange-500"></i>
                        User Activity Heatmap
                    </h5>
                </div>
                <div class="trezo-card-content">
                    <!-- Activity heatmap visualization placeholder -->
                    <div class="h-64 bg-gradient-to-br from-orange-50 to-red-50 dark:from-orange-900 dark:to-red-900 rounded-lg flex items-center justify-center">
                        <div class="text-center">
                            <i class="ri-bar-chart-2-line text-4xl text-orange-500 mb-4"></i>
                            <p class="text-gray-600 dark:text-gray-300 font-medium">Activity Heatmap</p>
                            <p class="text-sm text-gray-400">Peak hours: 9-11 AM, 2-4 PM</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Segmentation -->
            <div class="trezo-card border border-gray-100 dark:border-[#172036] p-[20px] md:p-[25px] rounded-md">
                <div class="trezo-card-header mb-[20px]">
                    <h5 class="mb-0 flex items-center">
                        <i class="ri-pie-chart-line mr-2 text-purple-500"></i>
                        User Segmentation
                    </h5>
                </div>
                <div class="trezo-card-content">
                    <div class="space-y-4">
                        @php
                            $segments = [
                                ['name' => 'Frequent Travelers', 'count' => $user_segments['frequent_travelers'] ?? 3240, 'percentage' => 26, 'color' => 'blue'],
                                ['name' => 'Occasional Users', 'count' => $user_segments['occasional_users'] ?? 5670, 'percentage' => 46, 'color' => 'green'],
                                ['name' => 'Business Travelers', 'count' => $user_segments['business_travelers'] ?? 2180, 'percentage' => 17, 'color' => 'purple'],
                                ['name' => 'First-time Users', 'count' => $user_segments['first_time_users'] ?? 1360, 'percentage' => 11, 'color' => 'orange']
                            ];
                        @endphp
                        
                        @foreach($segments as $segment)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-4 h-4 bg-{{ $segment['color'] }}-500 rounded-full"></div>
                                <span class="font-medium text-gray-800 dark:text-white">{{ $segment['name'] }}</span>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-gray-800 dark:text-white">{{ number_format($segment['count']) }}</p>
                                <p class="text-sm text-{{ $segment['color'] }}-600">{{ $segment['percentage'] }}%</p>
                            </div>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="bg-{{ $segment['color'] }}-500 h-2 rounded-full" style="width: {{ $segment['percentage'] }}%"></div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>

        <!-- Advanced User Actions -->
        <div class="trezo-card border border-gray-100 dark:border-[#172036] p-[20px] md:p-[25px] rounded-md mb-[25px]">
            <div class="trezo-card-header mb-[20px]">
                <h5 class="mb-0 flex items-center">
                    <i class="ri-tools-line mr-2 text-indigo-500"></i>
                    Advanced User Actions
                </h5>
            </div>
            <div class="trezo-card-content">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    
                    <!-- Bulk Actions -->
                    <div class="bg-blue-50 dark:bg-blue-900 p-4 rounded-lg">
                        <i class="ri-check-double-line text-2xl text-blue-500 mb-3"></i>
                        <h6 class="font-semibold text-gray-800 dark:text-white mb-2">Bulk Actions</h6>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">Perform actions on multiple users</p>
                        <div class="space-y-2">
                            <button class="w-full text-left text-sm bg-white dark:bg-gray-700 p-2 rounded hover:bg-gray-50 dark:hover:bg-gray-600">
                                <i class="ri-mail-send-line mr-2 text-blue-500"></i>Send Email
                            </button>
                            <button class="w-full text-left text-sm bg-white dark:bg-gray-700 p-2 rounded hover:bg-gray-50 dark:hover:bg-gray-600">
                                <i class="ri-forbid-line mr-2 text-red-500"></i>Suspend Users
                            </button>
                            <button class="w-full text-left text-sm bg-white dark:bg-gray-700 p-2 rounded hover:bg-gray-50 dark:hover:bg-gray-600">
                                <i class="ri-download-line mr-2 text-green-500"></i>Export Data
                            </button>
                        </div>
                    </div>

                    <!-- AI Insights -->
                    <div class="bg-purple-50 dark:bg-purple-900 p-4 rounded-lg">
                        <i class="ri-brain-line text-2xl text-purple-500 mb-3"></i>
                        <h6 class="font-semibold text-gray-800 dark:text-white mb-2">AI Insights</h6>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">AI-powered user analytics</p>
                        <div class="space-y-2">
                            <button class="w-full text-left text-sm bg-white dark:bg-gray-700 p-2 rounded hover:bg-gray-50 dark:hover:bg-gray-600">
                                <i class="ri-line-chart-line mr-2 text-purple-500"></i>Predict Churn
                            </button>
                            <button class="w-full text-left text-sm bg-white dark:bg-gray-700 p-2 rounded hover:bg-gray-50 dark:hover:bg-gray-600">
                                <i class="ri-user-heart-line mr-2 text-pink-500"></i>Lifetime Value
                            </button>
                            <button class="w-full text-left text-sm bg-white dark:bg-gray-700 p-2 rounded hover:bg-gray-50 dark:hover:bg-gray-600">
                                <i class="ri-target-line mr-2 text-indigo-500"></i>Segments
                            </button>
                        </div>
                    </div>

                    <!-- Communication Tools -->
                    <div class="bg-green-50 dark:bg-green-900 p-4 rounded-lg">
                        <i class="ri-message-line text-2xl text-green-500 mb-3"></i>
                        <h6 class="font-semibold text-gray-800 dark:text-white mb-2">Communication</h6>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">Engage with users effectively</p>
                        <div class="space-y-2">
                            <button class="w-full text-left text-sm bg-white dark:bg-gray-700 p-2 rounded hover:bg-gray-50 dark:hover:bg-gray-600">
                                <i class="ri-mail-line mr-2 text-green-500"></i>Email Campaign
                            </button>
                            <button class="w-full text-left text-sm bg-white dark:bg-gray-700 p-2 rounded hover:bg-gray-50 dark:hover:bg-gray-600">
                                <i class="ri-notification-line mr-2 text-blue-500"></i>Push Notifications
                            </button>
                            <button class="w-full text-left text-sm bg-white dark:bg-gray-700 p-2 rounded hover:bg-gray-50 dark:hover:bg-gray-600">
                                <i class="ri-message-2-line mr-2 text-purple-500"></i>SMS Campaign
                            </button>
                        </div>
                    </div>

                    <!-- Reports & Analytics -->
                    <div class="bg-orange-50 dark:bg-orange-900 p-4 rounded-lg">
                        <i class="ri-file-chart-line text-2xl text-orange-500 mb-3"></i>
                        <h6 class="font-semibold text-gray-800 dark:text-white mb-2">Reports</h6>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">Generate detailed reports</p>
                        <div class="space-y-2">
                            <button class="w-full text-left text-sm bg-white dark:bg-gray-700 p-2 rounded hover:bg-gray-50 dark:hover:bg-gray-600">
                                <i class="ri-file-excel-line mr-2 text-green-600"></i>User Report
                            </button>
                            <button class="w-full text-left text-sm bg-white dark:bg-gray-700 p-2 rounded hover:bg-gray-50 dark:hover:bg-gray-600">
                                <i class="ri-pie-chart-line mr-2 text-blue-500"></i>Behavior Analytics
                            </button>
                            <button class="w-full text-left text-sm bg-white dark:bg-gray-700 p-2 rounded hover:bg-gray-50 dark:hover:bg-gray-600">
                                <i class="ri-line-chart-line mr-2 text-purple-500"></i>Retention Analysis
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="trezo-card border border-gray-100 dark:border-[#172036] p-[20px] md:p-[25px] rounded-md">
            <div class="trezo-card-header mb-[20px] flex items-center justify-between">
                <h5 class="mb-0 flex items-center">
                    <i class="ri-table-line mr-2 text-gray-500"></i>
                    Users Overview
                </h5>
                <div class="flex items-center space-x-2">
                    <select class="px-3 py-1 border border-gray-300 dark:border-gray-600 rounded text-sm dark:bg-gray-700 dark:text-white">
                        <option>Show 25</option>
                        <option>Show 50</option>
                        <option>Show 100</option>
                    </select>
                    <button class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600">
                        <i class="ri-refresh-line mr-1"></i>Refresh
                    </button>
                </div>
            </div>
            <div class="trezo-card-content">
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-700">
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    <input type="checkbox" class="rounded">
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">User</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Type</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Bookings</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Lifetime Value</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Last Activity</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($users ?? [] as $user)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-4 py-4">
                                    <input type="checkbox" class="rounded">
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-purple-500 rounded-full flex items-center justify-center text-white font-semibold">
                                            {{ substr($user->name ?? 'JD', 0, 2) }}
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800 dark:text-white">{{ $user->name ?? 'John Doe' }}</p>
                                            <p class="text-sm text-gray-500">{{ $user->email ?? 'john@example.com' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    @php
                                        $status = $user->status ?? 'active';
                                        $statusColor = match($status) {
                                            'active' => 'green',
                                            'inactive' => 'gray',
                                            'suspended' => 'red',
                                            'pending' => 'yellow',
                                            default => 'gray'
                                        };
                                    @endphp
                                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-{{ $statusColor }}-100 text-{{ $statusColor }}-800">
                                        {{ ucfirst($status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="text-sm text-gray-600 dark:text-gray-300">
                                        {{ $user->type ?? 'Regular' }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="text-sm font-medium text-gray-800 dark:text-white">
                                        {{ $user->bookings_count ?? rand(0, 25) }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="text-sm font-medium text-green-600">
                                        ${{ number_format($user->lifetime_value ?? rand(500, 5000)) }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="text-sm text-gray-600 dark:text-gray-300">
                                        {{ $user->last_activity ?? rand(1, 30) . ' days ago' }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center space-x-2">
                                        <button class="text-blue-600 hover:text-blue-800" title="View">
                                            <i class="ri-eye-line"></i>
                                        </button>
                                        <button class="text-green-600 hover:text-green-800" title="Edit">
                                            <i class="ri-edit-line"></i>
                                        </button>
                                        <button class="text-orange-600 hover:text-orange-800" title="Message">
                                            <i class="ri-message-line"></i>
                                        </button>
                                        <button class="text-red-600 hover:text-red-800" title="Suspend">
                                            <i class="ri-forbid-line"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            @for($i = 1; $i <= 10; $i++)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-4 py-4">
                                    <input type="checkbox" class="rounded">
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-purple-500 rounded-full flex items-center justify-center text-white font-semibold">
                                            {{ substr(['John Doe', 'Jane Smith', 'Mike Johnson', 'Sarah Wilson', 'Tom Brown'][$i % 5], 0, 2) }}
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800 dark:text-white">{{ ['John Doe', 'Jane Smith', 'Mike Johnson', 'Sarah Wilson', 'Tom Brown'][$i % 5] }}</p>
                                            <p class="text-sm text-gray-500">{{ strtolower(str_replace(' ', '.', ['John Doe', 'Jane Smith', 'Mike Johnson', 'Sarah Wilson', 'Tom Brown'][$i % 5])) }}@example.com</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    @php
                                        $statuses = ['active', 'inactive', 'pending'];
                                        $status = $statuses[$i % 3];
                                        $statusColor = match($status) {
                                            'active' => 'green',
                                            'inactive' => 'gray',
                                            'pending' => 'yellow',
                                            default => 'gray'
                                        };
                                    @endphp
                                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-{{ $statusColor }}-100 text-{{ $statusColor }}-800">
                                        {{ ucfirst($status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="text-sm text-gray-600 dark:text-gray-300">
                                        {{ ['Regular', 'Premium', 'VIP', 'Corporate'][$i % 4] }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="text-sm font-medium text-gray-800 dark:text-white">
                                        {{ rand(0, 25) }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="text-sm font-medium text-green-600">
                                        ${{ number_format(rand(500, 5000)) }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="text-sm text-gray-600 dark:text-gray-300">
                                        {{ rand(1, 30) }} days ago
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center space-x-2">
                                        <button class="text-blue-600 hover:text-blue-800" title="View">
                                            <i class="ri-eye-line"></i>
                                        </button>
                                        <button class="text-green-600 hover:text-green-800" title="Edit">
                                            <i class="ri-edit-line"></i>
                                        </button>
                                        <button class="text-orange-600 hover:text-orange-800" title="Message">
                                            <i class="ri-message-line"></i>
                                        </button>
                                        <button class="text-red-600 hover:text-red-800" title="Suspend">
                                            <i class="ri-forbid-line"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endfor
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-6 flex items-center justify-between">
                    <div class="text-sm text-gray-600 dark:text-gray-300">
                        Showing 1 to 10 of {{ number_format($total_users ?? 12450) }} results
                    </div>
                    <div class="flex items-center space-x-2">
                        <button class="px-3 py-1 border border-gray-300 dark:border-gray-600 rounded text-sm hover:bg-gray-50 dark:hover:bg-gray-700">
                            Previous
                        </button>
                        <button class="px-3 py-1 bg-blue-500 text-white rounded text-sm">1</button>
                        <button class="px-3 py-1 border border-gray-300 dark:border-gray-600 rounded text-sm hover:bg-gray-50 dark:hover:bg-gray-700">2</button>
                        <button class="px-3 py-1 border border-gray-300 dark:border-gray-600 rounded text-sm hover:bg-gray-50 dark:hover:bg-gray-700">3</button>
                        <button class="px-3 py-1 border border-gray-300 dark:border-gray-600 rounded text-sm hover:bg-gray-50 dark:hover:bg-gray-700">
                            Next
                        </button>
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
    // Bulk action functionality
    const selectAllCheckbox = document.querySelector('thead input[type="checkbox"]');
    const rowCheckboxes = document.querySelectorAll('tbody input[type="checkbox"]');
    
    selectAllCheckbox?.addEventListener('change', function() {
        rowCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    // Search functionality
    const searchForm = document.querySelector('form');
    searchForm?.addEventListener('submit', function(e) {
        e.preventDefault();
        // Add search functionality here
        console.log('Search submitted');
    });

    // Filter functionality
    const filterSelects = document.querySelectorAll('select');
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            // Add filter functionality here
            console.log('Filter changed:', this.value);
        });
    });
});
</script>
@endsection
