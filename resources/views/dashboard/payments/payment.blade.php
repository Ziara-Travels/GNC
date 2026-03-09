<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payments Analytics Dashboard - Superadmin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/date-fns@2.29.3/index.min.js"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-r from-indigo-900 via-purple-900 to-pink-800 overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-50"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-blue-600/20 to-purple-600/20"></div>
        
        <!-- Animated background elements -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden">
            <div class="absolute -top-40 -left-40 w-80 h-80 bg-purple-500 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-pulse"></div>
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-pink-500 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-pulse delay-1000"></div>
            <div class="absolute -bottom-40 left-40 w-80 h-80 bg-indigo-500 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-pulse delay-2000"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="text-center">
                <div class="flex justify-center mb-6">
                    <div class="w-20 h-20 bg-gradient-to-br from-cyan-400 via-blue-500 to-purple-600 rounded-3xl flex items-center justify-center shadow-2xl transform rotate-3 hover:rotate-6 transition-transform duration-300">
                        <i data-lucide="bar-chart-3" class="h-10 w-10 text-white"></i>
                    </div>
                </div>
                <h1 class="text-5xl md:text-6xl font-bold text-white mb-4 tracking-tight">
                    Payments
                    <span class="bg-gradient-to-r from-cyan-400 to-purple-400 bg-clip-text text-transparent">Analytics</span>
                </h1>
                <p class="text-xl text-blue-100 mb-8 max-w-3xl mx-auto">
                    Complete oversight of all transactions across Bank Payments, M-Pesa, and all payment methods - Superadmin control center with real-time insights
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
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl px-6 py-3 border border-white/20">
                        <span class="text-white font-semibold">💰 Multi-Payment Methods</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Overview Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-6 mb-8 -mt-16 relative z-10">
            <!-- Total Payments -->
            <div class="bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/50 p-6 hover:transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-600 uppercase tracking-wider">Total Payments</p>
                        <p class="text-3xl font-bold text-gray-900" id="totalPayments">12,547</p>
                        <p class="text-sm text-emerald-600 mt-1 flex items-center">
                            <i data-lucide="trending-up" class="h-4 w-4 mr-1"></i>
                            +12.5% vs last month
                        </p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <i data-lucide="credit-card" class="h-7 w-7 text-white"></i>
                    </div>
                </div>
            </div>

            <!-- Total Revenue -->
            <div class="bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/50 p-6 hover:transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-600 uppercase tracking-wider">Total Revenue</p>
                        <p class="text-3xl font-bold text-gray-900" id="totalRevenue">$847,290</p>
                        <p class="text-sm text-emerald-600 mt-1 flex items-center">
                            <i data-lucide="dollar-sign" class="h-4 w-4 mr-1"></i>
                            +18.2% growth
                        </p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <i data-lucide="banknote" class="h-7 w-7 text-white"></i>
                    </div>
                </div>
            </div>

            <!-- Bank Payments -->
            <div class="bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/50 p-6 hover:transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-600 uppercase tracking-wider">Bank Payments</p>
                        <p class="text-3xl font-bold text-gray-900" id="bankPayments">8,234</p>
                        <p class="text-sm text-blue-600 mt-1 flex items-center">
                            <i data-lucide="building-2" class="h-4 w-4 mr-1"></i>
                            $524,850 value
                        </p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <i data-lucide="building-2" class="h-7 w-7 text-white"></i>
                    </div>
                </div>
            </div>

            <!-- M-Pesa Payments -->
            <div class="bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/50 p-6 hover:transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-600 uppercase tracking-wider">M-Pesa Payments</p>
                        <p class="text-3xl font-bold text-gray-900" id="mpesaPayments">4,313</p>
                        <p class="text-sm text-green-600 mt-1 flex items-center">
                            <i data-lucide="smartphone" class="h-4 w-4 mr-1"></i>
                            $322,440 value
                        </p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <i data-lucide="smartphone" class="h-7 w-7 text-white"></i>
                    </div>
                </div>
            </div>

            <!-- Success Rate -->
            <div class="bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/50 p-6 hover:transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-600 uppercase tracking-wider">Success Rate</p>
                        <p class="text-3xl font-bold text-gray-900" id="successRate">94.7%</p>
                        <p class="text-sm text-emerald-600 mt-1 flex items-center">
                            <i data-lucide="check-circle" class="h-4 w-4 mr-1"></i>
                            Excellent performance
                        </p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <i data-lucide="check-circle" class="h-7 w-7 text-white"></i>
                    </div>
                </div>
            </div>

            <!-- Failed Payments -->
            <div class="bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/50 p-6 hover:transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-600 uppercase tracking-wider">Failed Payments</p>
                        <p class="text-3xl font-bold text-gray-900" id="failedPayments">667</p>
                        <p class="text-sm text-red-600 mt-1 flex items-center">
                            <i data-lucide="x-circle" class="h-4 w-4 mr-1"></i>
                            Needs attention
                        </p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-red-500 to-pink-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <i data-lucide="x-circle" class="h-7 w-7 text-white"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Role Analytics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Superadmin Stats -->
            <div class="bg-gradient-to-br from-red-500 to-pink-600 rounded-3xl shadow-2xl border border-white/50 p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold flex items-center">
                        <i data-lucide="shield" class="h-6 w-6 mr-2"></i>
                        Superadmin Payments
                    </h3>
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        <i data-lucide="crown" class="h-6 w-6"></i>
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span>Total Transactions:</span>
                        <span class="font-bold">1,247</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Total Value:</span>
                        <span class="font-bold">$187,350</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Success Rate:</span>
                        <span class="font-bold">98.2%</span>
                    </div>
                </div>
            </div>

            <!-- Admin Stats -->
            <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-3xl shadow-2xl border border-white/50 p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold flex items-center">
                        <i data-lucide="user-check" class="h-6 w-6 mr-2"></i>
                        Admin Payments
                    </h3>
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        <i data-lucide="star" class="h-6 w-6"></i>
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span>Total Transactions:</span>
                        <span class="font-bold">3,892</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Total Value:</span>
                        <span class="font-bold">$342,120</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Success Rate:</span>
                        <span class="font-bold">95.8%</span>
                    </div>
                </div>
            </div>

            <!-- User Stats -->
            <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-3xl shadow-2xl border border-white/50 p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold flex items-center">
                        <i data-lucide="users" class="h-6 w-6 mr-2"></i>
                        User Payments
                    </h3>
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        <i data-lucide="user" class="h-6 w-6"></i>
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span>Total Transactions:</span>
                        <span class="font-bold">7,408</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Total Value:</span>
                        <span class="font-bold">$317,820</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Success Rate:</span>
                        <span class="font-bold">93.4%</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Payment Methods Distribution -->
            <div class="bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/50 p-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900 flex items-center">
                        <i data-lucide="pie-chart" class="h-5 w-5 mr-2 text-indigo-600"></i>
                        Payment Methods Distribution
                    </h3>
                    <div class="flex items-center gap-4 text-sm">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                            <span class="text-gray-600">Bank</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            <span class="text-gray-600">M-Pesa</span>
                        </div>
                    </div>
                </div>
                <div class="h-80">
                    <canvas id="paymentMethodsChart"></canvas>
                </div>
            </div>

            <!-- Revenue Trend -->
            <div class="bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/50 p-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900 flex items-center">
                        <i data-lucide="trending-up" class="h-5 w-5 mr-2 text-indigo-600"></i>
                        Revenue Trend (30 Days)
                    </h3>
                    <div class="text-sm text-gray-500">All payment methods</div>
                </div>
                <div class="h-80">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <!-- User Role Performance -->
            <div class="bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/50 p-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900 flex items-center">
                        <i data-lucide="users" class="h-5 w-5 mr-2 text-indigo-600"></i>
                        Payments by User Role
                    </h3>
                </div>
                <div class="h-80">
                    <canvas id="roleChart"></canvas>
                </div>
            </div>

            <!-- Payment Status Overview -->
            <div class="bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/50 p-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900 flex items-center">
                        <i data-lucide="activity" class="h-5 w-5 mr-2 text-indigo-600"></i>
                        Payment Status Overview
                    </h3>
                </div>
                <div class="h-80">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Payment Methods Breakdown -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Bank Payments Details -->
            <div class="bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/50 p-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900 flex items-center">
                        <i data-lucide="building-2" class="h-5 w-5 mr-2 text-blue-600"></i>
                        Bank Payments Analysis
                    </h3>
                    <div class="bg-blue-100 px-3 py-1 rounded-full text-blue-800 text-sm font-semibold">
                        Jena API
                    </div>
                </div>
                <div class="space-y-6">
                    <div class="flex items-center justify-between p-4 bg-blue-50 rounded-2xl">
                        <div>
                            <p class="text-sm font-semibold text-blue-700">Total Bank Transactions</p>
                            <p class="text-2xl font-bold text-blue-900">8,234</p>
                        </div>
                        <div class="text-blue-600">
                            <i data-lucide="trending-up" class="h-8 w-8"></i>
                        </div>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-emerald-50 rounded-2xl">
                        <div>
                            <p class="text-sm font-semibold text-emerald-700">Total Bank Revenue</p>
                            <p class="text-2xl font-bold text-emerald-900">$524,850</p>
                        </div>
                        <div class="text-emerald-600">
                            <i data-lucide="dollar-sign" class="h-8 w-8"></i>
                        </div>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-purple-50 rounded-2xl">
                        <div>
                            <p class="text-sm font-semibold text-purple-700">Average Transaction</p>
                            <p class="text-2xl font-bold text-purple-900">$63.75</p>
                        </div>
                        <div class="text-purple-600">
                            <i data-lucide="calculator" class="h-8 w-8"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- M-Pesa Payments Details -->
            <div class="bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/50 p-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900 flex items-center">
                        <i data-lucide="smartphone" class="h-5 w-5 mr-2 text-green-600"></i>
                        M-Pesa Payments Analysis
                    </h3>
                    <div class="bg-green-100 px-3 py-1 rounded-full text-green-800 text-sm font-semibold">
                        Safaricom API
                    </div>
                </div>
                <div class="space-y-6">
                    <div class="flex items-center justify-between p-4 bg-green-50 rounded-2xl">
                        <div>
                            <p class="text-sm font-semibold text-green-700">Total M-Pesa Transactions</p>
                            <p class="text-2xl font-bold text-green-900">4,313</p>
                        </div>
                        <div class="text-green-600">
                            <i data-lucide="smartphone" class="h-8 w-8"></i>
                        </div>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-emerald-50 rounded-2xl">
                        <div>
                            <p class="text-sm font-semibold text-emerald-700">Total M-Pesa Revenue</p>
                            <p class="text-2xl font-bold text-emerald-900">$322,440</p>
                        </div>
                        <div class="text-emerald-600">
                            <i data-lucide="banknote" class="h-8 w-8"></i>
                        </div>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-orange-50 rounded-2xl">
                        <div>
                            <p class="text-sm font-semibold text-orange-700">Average Transaction</p>
                            <p class="text-2xl font-bold text-orange-900">$74.80</p>
                        </div>
                        <div class="text-orange-600">
                            <i data-lucide="calculator" class="h-8 w-8"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Advanced Filters -->
        <div class="bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/50 p-8 mb-8">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-gray-900 flex items-center">
                    <i data-lucide="filter" class="h-6 w-6 mr-3 text-indigo-600"></i>
                    Advanced Analytics Filters
                </h3>
                <div class="flex gap-3">
                    <button onclick="resetFilters()" class="px-6 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-medium transition-colors flex items-center gap-2">
                        <i data-lucide="refresh-cw" class="h-4 w-4"></i>
                        Reset
                    </button>
                    <button onclick="applyFilters()" class="px-6 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-xl font-medium transition-colors flex items-center gap-2">
                        <i data-lucide="search" class="h-4 w-4"></i>
                        Apply Filters
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
                <!-- Payment Method Filter -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Payment Method</label>
                    <select id="paymentMethodFilter" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white">
                        <option value="">All Methods</option>
                        <option value="bank">🏦 Bank Payments</option>
                        <option value="mpesa">📱 M-Pesa</option>
                        <option value="card">💳 Card Payments</option>
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                    <select id="statusFilter" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white">
                        <option value="">All Statuses</option>
                        <option value="completed">✅ Completed</option>
                        <option value="processing">⏳ Processing</option>
                        <option value="failed">❌ Failed</option>
                        <option value="pending">🕒 Pending</option>
                    </select>
                </div>

                <!-- User Role Filter -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">User Role</label>
                    <select id="roleFilter" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white">
                        <option value="">All Roles</option>
                        <option value="superadmin">🔴 Superadmin</option>
                        <option value="admin">🟡 Admin</option>
                        <option value="user">🔵 User</option>
                    </select>
                </div>

                <!-- Date Range Filter -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Date Range</label>
                    <select id="dateRangeFilter" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white">
                        <option value="">All Time</option>
                        <option value="today">📅 Today</option>
                        <option value="week">📊 This Week</option>
                        <option value="month">📈 This Month</option>
                        <option value="quarter">🔥 This Quarter</option>
                        <option value="year">🎯 This Year</option>
                    </select>
                </div>

                <!-- Amount Range Filter -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Amount Range</label>
                    <select id="amountFilter" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white">
                        <option value="">All Amounts</option>
                        <option value="0-50">💰 $0 - $50</option>
                        <option value="50-100">💎 $50 - $100</option>
                        <option value="100-500">🏆 $100 - $500</option>
                        <option value="500-1000">🚀 $500 - $1,000</option>
                        <option value="1000+">👑 $1,000+</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Export & Actions -->
        <div class="bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/50 p-8 mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold text-gray-900 flex items-center mb-2">
                        <i data-lucide="download" class="h-5 w-5 mr-2 text-indigo-600"></i>
                        Export Analytics Data
                    </h3>
                    <p class="text-gray-600">Download comprehensive payment analytics in various formats</p>
                </div>
                <div class="flex gap-3">
                    <button onclick="exportData('csv')" class="px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-xl font-medium transition-colors flex items-center gap-2">
                        <i data-lucide="file-text" class="h-4 w-4"></i>
                        Export CSV
                    </button>
                    <button onclick="exportData('excel')" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-xl font-medium transition-colors flex items-center gap-2">
                        <i data-lucide="file-spreadsheet" class="h-4 w-4"></i>
                        Export Excel
                    </button>
                    <button onclick="exportData('pdf')" class="px-6 py-3 bg-gradient-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700 text-white rounded-xl font-medium transition-colors flex items-center gap-2">
                        <i data-lucide="file-type" class="h-4 w-4"></i>
                        Export PDF Report
                    </button>
                </div>
            </div>
        </div>

        <!-- All Payments Table -->
        <div class="bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/50 overflow-hidden">
            <div class="p-8 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-2xl font-bold text-gray-900 flex items-center">
                        <i data-lucide="database" class="h-6 w-6 mr-3 text-indigo-600"></i>
                        All Payments Overview
                        <span id="tableCount" class="ml-3 px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-sm font-medium">12,547 total</span>
                    </h3>
                    <div class="flex items-center gap-4">
                        <div class="bg-gradient-to-r from-purple-100 to-pink-100 px-4 py-2 rounded-xl border border-purple-200">
                            <span class="text-sm font-bold text-purple-700">🔒 Superadmin Analytics</span>
                        </div>
                        <button onclick="refreshData()" class="px-4 py-2 bg-indigo-100 hover:bg-indigo-200 text-indigo-700 rounded-xl font-medium transition-colors flex items-center gap-2">
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
                                <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-gray-200 transition-colors">
                                Payment Reference
                                <i data-lucide="arrow-up-down" class="h-3 w-3 inline ml-1"></i>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-gray-200 transition-colors">
                                User Details
                                <i data-lucide="arrow-up-down" class="h-3 w-3 inline ml-1"></i>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-gray-200 transition-colors">
                                Payment Method
                                <i data-lucide="arrow-up-down" class="h-3 w-3 inline ml-1"></i>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-gray-200 transition-colors">
                                Amount
                                <i data-lucide="arrow-up-down" class="h-3 w-3 inline ml-1"></i>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-gray-200 transition-colors">
                                Status
                                <i data-lucide="arrow-up-down" class="h-3 w-3 inline ml-1"></i>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-gray-200 transition-colors">
                                Date & Time
                                <i data-lucide="arrow-up-down" class="h-3 w-3 inline ml-1"></i>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody id="paymentsTableBody" class="bg-white divide-y divide-gray-200">
                        <!-- Sample data rows -->
                        <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" class="payment-checkbox rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" value="1">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-900">REF-001234</div>
                                <div class="text-sm text-gray-500 flex items-center">
                                    <i data-lucide="building-2" class="h-3 w-3 mr-1"></i>
                                    Bank Transfer
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-pink-600 rounded-full flex items-center justify-center text-white font-bold text-sm shadow-lg">
                                        J
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-bold text-gray-900">John Doe</div>
                                        <div class="text-sm text-gray-500">john@example.com</div>
                                        <div class="text-xs font-semibold text-red-600">Superadmin</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                        <i data-lucide="building-2" class="h-4 w-4 text-blue-600"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900">Bank Transfer</div>
                                        <div class="text-xs text-gray-500">Jena API</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-lg font-bold text-gray-900">$150.00</div>
                                <div class="text-sm text-gray-500">USD</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-200">
                                    <i data-lucide="check-circle" class="h-4 w-4 mr-1"></i>
                                    Completed
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">2024-01-15 14:30</div>
                                <div class="text-xs text-gray-400">2 hours ago</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-2">
                                    <button class="text-indigo-600 hover:text-indigo-800 transition-colors">
                                        <i data-lucide="eye" class="h-5 w-5"></i>
                                    </button>
                                    <button class="text-yellow-600 hover:text-yellow-800 transition-colors">
                                        <i data-lucide="edit" class="h-5 w-5"></i>
                                    </button>
                                    <button class="text-red-600 hover:text-red-800 transition-colors">
                                        <i data-lucide="trash" class="h-5 w-5"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" class="payment-checkbox rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" value="2">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-900">MP-005678</div>
                                <div class="text-sm text-gray-500 flex items-center">
                                    <i data-lucide="smartphone" class="h-3 w-3 mr-1"></i>
                                    M-Pesa
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold text-sm shadow-lg">
                                        M
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-bold text-gray-900">Mary Smith</div>
                                        <div class="text-sm text-gray-500">mary@example.com</div>
                                        <div class="text-xs font-semibold text-blue-600">User</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                        <i data-lucide="smartphone" class="h-4 w-4 text-green-600"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900">M-Pesa</div>
                                        <div class="text-xs text-gray-500">Safaricom API</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-lg font-bold text-gray-900">$75.50</div>
                                <div class="text-sm text-gray-500">KES</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                                    <i data-lucide="loader" class="h-4 w-4 mr-1"></i>
                                    Processing
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">2024-01-15 16:45</div>
                                <div class="text-xs text-gray-400">30 minutes ago</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-2">
                                    <button class="text-indigo-600 hover:text-indigo-800 transition-colors">
                                        <i data-lucide="eye" class="h-5 w-5"></i>
                                    </button>
                                    <button class="text-yellow-600 hover:text-yellow-800 transition-colors">
                                        <i data-lucide="edit" class="h-5 w-5"></i>
                                    </button>
                                    <button class="text-red-600 hover:text-red-800 transition-colors">
                                        <i data-lucide="trash" class="h-5 w-5"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="p-6 bg-white/90 backdrop-blur-lg rounded-b-3xl border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center text-sm text-gray-700">
                        <span>Showing</span>
                        <span class="font-semibold mx-1">1</span>
                        <span>to</span>
                        <span class="font-semibold mx-1">10</span>
                        <span>of</span>
                        <span class="font-semibold mx-1">12,547</span>
                        <span>results</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                            Previous
                        </button>
                        <button class="px-3 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-lg hover:bg-indigo-700">
                            1
                        </button>
                        <button class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                            2
                        </button>
                        <button class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                            3
                        </button>
                        <span class="px-3 py-2 text-sm font-medium text-gray-500">...</span>
                        <button class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                            Next
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Initialize Lucide icons
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
            initializeCharts();
        });

        // Initialize all charts
        function initializeCharts() {
            // Payment Methods Distribution Chart
            const paymentMethodsChart = new Chart(document.getElementById('paymentMethodsChart'), {
                type: 'doughnut',
                data: {
                    labels: ['Bank Payments', 'M-Pesa', 'Card Payments'],
                    datasets: [{
                        data: [8234, 4313, 0],
                        backgroundColor: ['#3B82F6', '#10B981', '#F59E0B'],
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((context.raw / total) * 100).toFixed(1);
                                    return context.label + ': ' + context.raw.toLocaleString() + ' (' + percentage + '%)';
                                }
                            }
                        }
                    }
                }
            });

            // Revenue Trend Chart
            const revenueChart = new Chart(document.getElementById('revenueChart'), {
                type: 'line',
                data: {
                    labels: ['Jan 1', 'Jan 5', 'Jan 10', 'Jan 15', 'Jan 20', 'Jan 25', 'Jan 30'],
                    datasets: [{
                        label: 'Revenue',
                        data: [12000, 19000, 15000, 25000, 22000, 30000, 28000],
                        borderColor: '#6366F1',
                        backgroundColor: 'rgba(99, 102, 241, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointBackgroundColor: '#6366F1',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            callbacks: {
                                label: function(context) {
                                    return 'Revenue:  + context.raw.toLocaleString();
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            },
                            ticks: {
                                callback: function(value) {
                                    return ' + (value / 1000) + 'K';
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            // User Role Chart
            const roleChart = new Chart(document.getElementById('roleChart'), {
                type: 'bar',
                data: {
                    labels: ['Superadmin', 'Admin', 'User'],
                    datasets: [{
                        label: 'Number of Payments',
                        data: [1247, 3892, 7408],
                        backgroundColor: ['#EF4444', '#F59E0B', '#3B82F6'],
                        borderRadius: 8,
                        borderSkipped: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.label + ': ' + context.raw.toLocaleString() + ' payments';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            // Payment Status Chart
            const statusChart = new Chart(document.getElementById('statusChart'), {
                type: 'pie',
                data: {
                    labels: ['Completed', 'Processing', 'Failed', 'Pending'],
                    datasets: [{
                        data: [11880, 300, 267, 100],
                        backgroundColor: ['#10B981', '#F59E0B', '#EF4444', '#6B7280'],
                        borderWidth: 0,
                        hoverOffset: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((context.raw / total) * 100).toFixed(1);
                                    return context.label + ': ' + context.raw.toLocaleString() + ' (' + percentage + '%)';
                                }
                            }
                        }
                    }
                }
            });
        }

        // Filter functions
        function applyFilters() {
            const paymentMethod = document.getElementById('paymentMethodFilter').value;
            const status = document.getElementById('statusFilter').value;
            const role = document.getElementById('roleFilter').value;
            const dateRange = document.getElementById('dateRangeFilter').value;
            const amount = document.getElementById('amountFilter').value;

            // Apply filters to table rows
            const rows = document.querySelectorAll('#paymentsTableBody tr');
            let visibleCount = 0;

            rows.forEach(row => {
                let show = true;
                const rowText = row.textContent.toLowerCase();

                if (paymentMethod && !rowText.includes(paymentMethod.toLowerCase())) show = false;
                if (status && !rowText.includes(status.toLowerCase())) show = false;
                if (role && !rowText.includes(role.toLowerCase())) show = false;
                // Add more filter logic as needed

                row.style.display = show ? '' : 'none';
                if (show) visibleCount++;
            });

            // Update table count
            document.getElementById('tableCount').textContent = visibleCount.toLocaleString() + ' filtered';
            
            // Show notification
            showNotification('Filters applied successfully!', 'success');
        }

        function resetFilters() {
            document.getElementById('paymentMethodFilter').value = '';
            document.getElementById('statusFilter').value = '';
            document.getElementById('roleFilter').value = '';
            document.getElementById('dateRangeFilter').value = '';
            document.getElementById('amountFilter').value = '';

            // Show all rows
            const rows = document.querySelectorAll('#paymentsTableBody tr');
            rows.forEach(row => row.style.display = '');

            // Reset table count
            document.getElementById('tableCount').textContent = '12,547 total';
            
            showNotification('Filters reset successfully!', 'info');
        }

        // Export functions
        function exportData(format) {
            showNotification(`Exporting data as ${format.toUpperCase()}...`, 'info');
            
            // Simulate export process
            setTimeout(() => {
                showNotification(`${format.toUpperCase()} export completed successfully!`, 'success');
            }, 2000);
        }

        function refreshData() {
            showNotification('Refreshing analytics data...', 'info');
            
            // Simulate data refresh
            setTimeout(() => {
                showNotification('Analytics data refreshed successfully!', 'success');
            }, 1500);
        }

        // Notification system
        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full`;
            
            const colors = {
                success: 'bg-green-500 text-white',
                error: 'bg-red-500 text-white',
                info: 'bg-blue-500 text-white',
                warning: 'bg-yellow-500 text-black'
            };
            
            notification.className += ` ${colors[type] || colors.info}`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <i data-lucide="${type === 'success' ? 'check-circle' : type === 'error' ? 'x-circle' : 'info'}" class="h-5 w-5 mr-2"></i>
                    ${message}
                </div>
            `;
            
            document.body.appendChild(notification);
            lucide.createIcons();
            
            // Animate in
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);
            
            // Animate out and remove
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }

        // Select all functionality
        document.getElementById('selectAll').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.payment-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    </script>
</body>
</html>