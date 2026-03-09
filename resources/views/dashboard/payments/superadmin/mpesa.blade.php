@extends('layouts.main')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/date-fns@2.29.3/index.min.js"></script>

<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-r from-green-900 via-blue-900 to-yellow-800 overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-50"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-green-600/20 to-yellow-600/20"></div>
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden">
            <div class="absolute -top-40 -left-40 w-80 h-80 bg-green-500 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-pulse"></div>
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-yellow-500 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-pulse delay-1000"></div>
            <div class="absolute -bottom-40 left-40 w-80 h-80 bg-blue-500 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-pulse delay-2000"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="text-center">
                <div class="flex justify-center mb-6">
                    <div class="w-20 h-20 bg-gradient-to-br from-green-400 via-blue-500 to-yellow-400 rounded-3xl flex items-center justify-center shadow-2xl transform rotate-3 hover:rotate-6 transition-transform duration-300">
                        <i data-lucide="smartphone" class="h-10 w-10 text-white"></i>
                    </div>
                </div>
                <h1 class="text-5xl md:text-6xl font-bold text-white mb-4 tracking-tight">
                    Mpesa Payments
                    <span class="bg-gradient-to-r from-green-400 to-yellow-400 bg-clip-text text-transparent">Analytics</span>
                </h1>
                <p class="text-xl text-green-100 mb-8 max-w-3xl mx-auto">
                    Complete oversight of all Mpesa transactions - Superadmin control center with real-time insights
                </p>
                <div class="flex flex-wrap justify-center gap-4">
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl px-6 py-3 border border-white/20">
                        <span class="text-white font-semibold">🔒 Superadmin Access</span>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl px-6 py-3 border border-white/20">
                        <span class="text-white font-semibold">⚡ Real-time Data</span>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl px-6 py-3 border border-white/20">
                        <span class="text-white font-semibold">📊 Advanced Analytics</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8 -mt-16 relative z-10">
            <div class="bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/50 p-6 hover:transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-600 uppercase tracking-wider">Total Payments</p>
                        <p class="text-3xl font-bold text-gray-900" id="totalPayments">{{ number_format($stats['total_payments'] ?? 0) }}</p>
                        <p class="text-sm text-green-600 mt-1 flex items-center">
                            <i data-lucide="trending-up" class="h-4 w-4 mr-1"></i>
                            All roles
                        </p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-yellow-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <i data-lucide="smartphone" class="h-7 w-7 text-white"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/50 p-6 hover:transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-600 uppercase tracking-wider">Total Value</p>
                        <p class="text-3xl font-bold text-gray-900" id="totalValue">Ksh {{ number_format($stats['total_value'] ?? 0, 2) }}</p>
                        <p class="text-sm text-green-600 mt-1 flex items-center">
                            <i data-lucide="dollar-sign" class="h-4 w-4 mr-1"></i>
                            Via Mpesa API
                        </p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-yellow-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <i data-lucide="banknote" class="h-7 w-7 text-white"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/50 p-6 hover:transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-600 uppercase tracking-wider">Success Rate</p>
                        <p class="text-3xl font-bold text-gray-900" id="successRate">{{ $stats['success_rate'] ?? 0 }}%</p>
                        <p class="text-sm text-green-600 mt-1 flex items-center">
                            <i data-lucide="check-circle" class="h-4 w-4 mr-1"></i>
                            Completed
                        </p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-yellow-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <i data-lucide="check-circle" class="h-7 w-7 text-white"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/50 p-6 hover:transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-600 uppercase tracking-wider">Processing</p>
                        <p class="text-3xl font-bold text-gray-900" id="processingPayments">{{ $stats['processing'] ?? 0 }}</p>
                        <p class="text-sm text-yellow-600 mt-1 flex items-center">
                            <i data-lucide="loader" class="h-4 w-4 mr-1"></i>
                            In progress
                        </p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-yellow-500 to-green-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <i data-lucide="loader" class="h-7 w-7 text-white"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/50 p-6 hover:transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-600 uppercase tracking-wider">Failed</p>
                        <p class="text-3xl font-bold text-gray-900" id="failedPayments">{{ $stats['failed'] ?? 0 }}</p>
                        <p class="text-sm text-red-600 mt-1 flex items-center">
                            <i data-lucide="x-circle" class="h-4 w-4 mr-1"></i>
                            Requires attention
                        </p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-red-500 to-yellow-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <i data-lucide="x-circle" class="h-7 w-7 text-white"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Advanced Filters -->
        <div class="bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/50 p-8 mb-8">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-gray-900 flex items-center">
                    <i data-lucide="filter" class="h-6 w-6 mr-3 text-green-600"></i>
                    Advanced Filters
                </h3>
                <div class="flex gap-3">
                    <button onclick="resetFilters()" class="px-6 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-medium transition-colors flex items-center gap-2">
                        <i data-lucide="refresh-cw" class="h-4 w-4"></i>
                        Reset
                    </button>
                    <button onclick="applyFilters()" class="px-6 py-2 bg-gradient-to-r from-green-600 to-yellow-600 hover:from-green-700 hover:to-yellow-700 text-white rounded-xl font-medium transition-colors flex items-center gap-2">
                        <i data-lucide="search" class="h-4 w-4"></i>
                        Apply Filters
                    </button>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                    <select id="statusFilter" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent bg-white">
                        <option value="">All Statuses</option>
                        <option value="completed">✅ Completed</option>
                        <option value="processing">⏳ Processing</option>
                        <option value="failed">❌ Failed</option>
                        <option value="pending">🕒 Pending</option>
                    </select>
                </div>
                <!-- Date Range Filter -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Date Range</label>
                    <select id="dateRangeFilter" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent bg-white">
                        <option value="">All Time</option>
                        <option value="today">📅 Today</option>
                        <option value="week">📊 This Week</option>
                        <option value="month">📈 This Month</option>
                        <option value="quarter">🔥 This Quarter</option>
                        <option value="year">🎯 This Year</option>
                        <option value="custom">🔧 Custom Range</option>
                    </select>
                </div>
                <!-- User Role Filter -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">User Role</label>
                    <select id="roleFilter" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent bg-white">
                        <option value="">All Roles</option>
                        <option value="superadmin">🔴 Superadmin</option>
                        <option value="admin">🟡 Admin</option>
                        <option value="user">🔵 User</option>
                    </select>
                </div>
                <!-- Amount Range Filter -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Amount Range</label>
                    <select id="amountFilter" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent bg-white">
                        <option value="">All Amounts</option>
                        <option value="0-100">💰 Ksh 0 - 100</option>
                        <option value="100-500">💎 Ksh 100 - 500</option>
                        <option value="500-1000">🏆 Ksh 500 - 1,000</option>
                        <option value="1000-5000">🚀 Ksh 1,000 - 5,000</option>
                        <option value="5000+">👑 Ksh 5,000+</option>
                    </select>
                </div>
                <!-- Custom Date Range -->
                <div id="customDateRange" class="hidden col-span-2">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">From Date</label>
                            <input type="date" id="startDate" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">To Date</label>
                            <input type="date" id="endDate" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>
                    </div>
                </div>
                <!-- Search Filter -->
                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Search</label>
                    <div class="relative">
                        <input type="text" id="searchFilter" placeholder="Search by user name, phone, reference..." 
                               class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <i data-lucide="search" class="h-5 w-5 text-gray-400 absolute left-4 top-1/2 transform -translate-y-1/2"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <div class="bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/50 p-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900 flex items-center">
                        <i data-lucide="pie-chart" class="h-5 w-5 mr-2 text-green-600"></i>
                        Payment Status Distribution
                    </h3>
                    <div class="flex items-center gap-2 text-sm">
                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                        <span class="text-gray-600">Completed</span>
                        <div class="w-3 h-3 bg-yellow-500 rounded-full ml-3"></div>
                        <span class="text-gray-600">Processing</span>
                        <div class="w-3 h-3 bg-red-500 rounded-full ml-3"></div>
                        <span class="text-gray-600">Failed</span>
                    </div>
                </div>
                <div class="h-80">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
            <div class="bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/50 p-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900 flex items-center">
                        <i data-lucide="trending-up" class="h-5 w-5 mr-2 text-green-600"></i>
                        Revenue Trend (30 Days)
                    </h3>
                    <div class="text-sm text-gray-500">Mpesa payments via API</div>
                </div>
                <div class="h-80">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
            <div class="bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/50 p-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900 flex items-center">
                        <i data-lucide="users" class="h-5 w-5 mr-2 text-green-600"></i>
                        Payments by User Role
                    </h3>
                </div>
                <div class="h-80">
                    <canvas id="roleChart"></canvas>
                </div>
            </div>
            <div class="bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/50 p-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900 flex items-center">
                        <i data-lucide="bar-chart" class="h-5 w-5 mr-2 text-green-600"></i>
                        Daily Transaction Volume
                    </h3>
                </div>
                <div class="h-80">
                    <canvas id="volumeChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Export Actions -->
        <div class="bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/50 p-8 mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold text-gray-900 flex items-center mb-2">
                        <i data-lucide="download" class="h-5 w-5 mr-2 text-green-600"></i>
                        Export Data
                    </h3>
                    <p class="text-gray-600">Download Mpesa payment data in various formats</p>
                </div>
                <div class="flex gap-3">
                    <button onclick="exportData('csv')" class="px-6 py-3 bg-gradient-to-r from-green-600 to-yellow-600 hover:from-green-700 hover:to-yellow-700 text-white rounded-xl font-medium transition-colors flex items-center gap-2">
                        <i data-lucide="file-text" class="h-4 w-4"></i>
                        Export CSV
                    </button>
                    <button onclick="exportData('excel')" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-green-600 hover:from-blue-700 hover:to-green-700 text-white rounded-xl font-medium transition-colors flex items-center gap-2">
                        <i data-lucide="file-spreadsheet" class="h-4 w-4"></i>
                        Export Excel
                    </button>
                    <button onclick="exportData('pdf')" class="px-6 py-3 bg-gradient-to-r from-red-600 to-yellow-600 hover:from-red-700 hover:to-yellow-700 text-white rounded-xl font-medium transition-colors flex items-center gap-2">
                        <i data-lucide="file-type" class="h-4 w-4"></i>
                        Export PDF
                    </button>
                </div>
            </div>
        </div>

        <!-- Payments Table -->
        <div class="bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/50 overflow-hidden">
            <div class="p-8 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-2xl font-bold text-gray-900 flex items-center">
                        <i data-lucide="database" class="h-6 w-6 mr-3 text-green-600"></i>
                        All Mpesa Payments
                        <span id="tableCount" class="ml-3 px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">{{ number_format($payments->total() ?? 0) }} total</span>
                    </h3>
                    <div class="flex items-center gap-4">
                        <div class="bg-gradient-to-r from-green-100 to-yellow-100 px-4 py-2 rounded-xl border border-green-200">
                            <span class="text-sm font-bold text-green-700">🔒 Superadmin Access</span>
                        </div>
                        <button onclick="refreshTable()" class="px-4 py-2 bg-green-100 hover:bg-green-200 text-green-700 rounded-xl font-medium transition-colors flex items-center gap-2">
                            <i data-lucide="refresh-cw" class="h-4 w-4"></i>
                            Refresh
                        </button>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-gray-200 transition-colors" onclick="sortTable('reference')">
                                Mpesa Reference
                                <i data-lucide="arrow-up-down" class="h-3 w-3 inline ml-1"></i>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-gray-200 transition-colors" onclick="sortTable('user')">
                                User Details
                                <i data-lucide="arrow-up-down" class="h-3 w-3 inline ml-1"></i>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-gray-200 transition-colors" onclick="sortTable('amount')">
                                Amount
                                <i data-lucide="arrow-up-down" class="h-3 w-3 inline ml-1"></i>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-gray-200 transition-colors" onclick="sortTable('status')">
                                Status
                                <i data-lucide="arrow-up-down" class="h-3 w-3 inline ml-1"></i>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-gray-200 transition-colors" onclick="sortTable('date')">
                                Date & Time
                                <i data-lucide="arrow-up-down" class="h-3 w-3 inline ml-1"></i>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                Mpesa API
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody id="paymentsTableBody" class="bg-white divide-y divide-gray-200">
                        @foreach($payments ?? [] as $payment)
                        <tr class="hover:bg-gradient-to-r hover:from-green-50 hover:to-yellow-50 transition-all duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" class="payment-checkbox rounded border-gray-300 text-green-600 focus:ring-green-500" value="{{ $payment->id }}">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-900">{{ $payment->mpesa_reference ?? 'MPESA-' . $payment->id }}</div>
                                <div class="text-sm text-gray-500 flex items-center">
                                    <i data-lucide="smartphone" class="h-3 w-3 mr-1"></i>
                                    Mpesa
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-br from-{{ $payment->user->role === 'superadmin' ? 'red' : ($payment->user->role === 'admin' ? 'yellow' : 'blue') }}-500 to-{{ $payment->user->role === 'superadmin' ? 'pink' : ($payment->user->role === 'admin' ? 'orange' : 'indigo') }}-600 rounded-full flex items-center justify-center text-white font-bold text-sm shadow-lg">
                                        {{ strtoupper(substr($payment->user->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-bold text-gray-900">{{ $payment->user->name ?? 'Unknown User' }}</div>
                                        <div class="text-sm text-gray-500">{{ $payment->user->phone ?? 'No phone' }}</div>
                                        <div class="text-xs font-semibold {{ $payment->user->role === 'superadmin' ? 'text-red-600' : ($payment->user->role === 'admin' ? 'text-yellow-600' : 'text-blue-600') }}">
                                            {{ ucfirst($payment->user->role ?? 'user') }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-lg font-bold text-gray-900">Ksh {{ number_format($payment->amount ?? 0, 2) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'completed' => 'bg-green-100 text-green-800 border-green-200',
                                        'processing' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                        'failed' => 'bg-red-100 text-red-800 border-red-200',
                                        'pending' => 'bg-gray-100 text-gray-800 border-gray-200'
                                    ];
                                    $status = $payment->status ?? 'pending';
                                @endphp
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold {{ $statusColors[$status] ?? 'bg-gray-100 text-gray-800 border-gray-200' }} border">
                                    <i data-lucide="{{ $status === 'completed' ? 'check-circle' : ($status === 'processing' ? 'loader' : ($status === 'failed' ? 'x-circle' : 'clock')) }}" class="h-4 w-4 mr-1"></i>
                                    {{ ucfirst($status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">{{ $payment->created_at->format('Y-m-d H:i') }}</div>
                                <div class="text-xs text-gray-400">{{ $payment->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <i data-lucide="check-circle" class="h-5 w-5 text-green-500 mr-2"></i>
                                    <span class="text-sm text-gray-600">Mpesa API</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <button onclick="viewPaymentDetails({{ $payment->id }})" class="text-green-600 hover:text-green-800 transition-colors">
                                        <i data-lucide="eye" class="h-5 w-5"></i>
                                    </button>
                                    <button onclick="editPayment({{ $payment->id }})" class="ml-3 text-yellow-600 hover:text-yellow-800 transition-colors">
                                        <i data-lucide="edit" class="h-5 w-5"></i>
                                    </button>
                                    <button onclick="deletePayment({{ $payment->id }})" class="ml-3 text-red-600 hover:text-red-800 transition-colors">
                                        <i data-lucide="trash" class="h-5 w-5"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4 bg-white/90 backdrop-blur-lg rounded-b-3xl border-t border-gray-200">
                {{ $payments->links('vendor.pagination.tailwind') }}
            </div>
        </div>
    </div>
</div>
<script>
    // Initialize charts
    const statusChart = new Chart(document.getElementById('statusChart'), {
        type: 'pie',
        data: {
            labels: ['Completed', 'Processing', 'Failed'],
            datasets: [{
                data: [{{ $stats['completed'] ?? 0 }}, {{ $stats['processing'] ?? 0 }}, {{ $stats['failed'] ?? 0 }}],
                backgroundColor: ['#34D399', '#FBBF24', '#EF4444'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.label + ': ' + tooltipItem.raw;
                        }
                    }
                }
            }
        }
    });

    const revenueChart = new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: {
            labels: @json($revenueLabels),
            datasets: [{
                label: 'Revenue',
                data: @json($revenueData),
                borderColor: '#22c55e',
                backgroundColor: 'rgba(34, 211, 99, 0.2)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: { mode: 'index' }
            },
            scales: {
                xAxes: [{ gridLines: { display: false } }],
                yAxes: [{ ticks: { beginAtZero: true } }]
            }
        }
    });

    const roleChart = new Chart(document.getElementById('roleChart'), {
        type: 'bar',
        data: {
            labels: ['Superadmin', 'Admin', 'User'],
            datasets: [{
                label: 'Payments by Role',
                data: [{{ $stats['superadmin_payments'] ?? 0 }}, {{ $stats['admin_payments'] ?? 0 }}, {{ $stats['user_payments'] ?? 0 }}],
                backgroundColor: ['#EF4444', '#FBBF24', '#34D399'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.label + ': ' + tooltipItem.raw;
                        }
                    }
                }
            }
        }
    });
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();

    function filterPayments() {
        const status = document.getElementById('statusFilter').value;
        const role = document.getElementById('roleFilter').value;
        const dateRange = document.getElementById('dateRangeFilter').value;
        const search = document.getElementById('searchFilter').value.toLowerCase();
        const rows = document.querySelectorAll('#paymentsTableBody tr');
        rows.forEach(row => {
            let show = true;
            if (status && !row.textContent.toLowerCase().includes(status)) show = false;
            if (role && !row.textContent.toLowerCase().includes(role)) show = false;
            if (search.length > 0 && !row.textContent.toLowerCase().includes(search)) show = false;
            row.style.display = show ? '' : 'none';
        });
    }

    document.getElementById('statusFilter').addEventListener('change', filterPayments);
    document.getElementById('roleFilter').addEventListener('change', filterPayments);
    document.getElementById('searchFilter').addEventListener('input', filterPayments);

    window.exportData = function(type) {
        let csv = [];
        const rows = document.querySelectorAll('#paymentsTableBody tr');
        for (let row of rows) {
            if (row.style.display === 'none') continue;
            let cols = row.querySelectorAll('th, td');
            let rowData = [];
            for (let col of cols) {
                rowData.push('"' + col.innerText.replace(/"/g, '""') + '"');
            }
            csv.push(rowData.join(','));
        }
        let mime = type === 'excel' ? 'application/vnd.ms-excel' : 'text/csv';
        let ext = type === 'excel' ? 'xls' : 'csv';
        let csvFile = new Blob([csv.join('\n')], { type: mime });
        let downloadLink = document.createElement('a');
        downloadLink.download = 'mpesa_payments.' + ext;
        downloadLink.href = window.URL.createObjectURL(csvFile);
        downloadLink.click();
    };

    window.resetFilters = function() {
        document.getElementById('statusFilter').value = '';
        document.getElementById('roleFilter').value = '';
        document.getElementById('searchFilter').value = '';
        filterPayments();
    };

    window.refreshTable = function() {
        location.reload();
    };
});
</script>
@endsection