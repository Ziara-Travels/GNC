@extends('layouts.main')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/date-fns@2.29.3/index.min.js"></script>

<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-r from-indigo-900 via-purple-900 to-pink-800 overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-50"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-blue-600/20 to-purple-600/20"></div>
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden">
            <div class="absolute -top-40 -left-40 w-80 h-80 bg-purple-500 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-pulse"></div>
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-pink-500 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-pulse delay-1000"></div>
            <div class="absolute -bottom-40 left-40 w-80 h-80 bg-indigo-500 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-pulse delay-2000"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="text-center">
                <div class="flex justify-center mb-6">
                    <div class="w-20 h-20 bg-gradient-to-br from-cyan-400 via-blue-500 to-purple-600 rounded-3xl flex items-center justify-center shadow-2xl transform rotate-3 hover:rotate-6 transition-transform duration-300">
                        <i data-lucide="banknote" class="h-10 w-10 text-white"></i>
                    </div>
                </div>
                <h1 class="text-5xl md:text-6xl font-bold text-white mb-4 tracking-tight">
                    My Bank Payments
                    <span class="bg-gradient-to-r from-cyan-400 to-purple-400 bg-clip-text text-transparent">History</span>
                </h1>
                <p class="text-xl text-blue-100 mb-8 max-w-3xl mx-auto">
                    View your personal bank transaction history and analytics.
                </p>
                <div class="flex flex-wrap justify-center gap-4">
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl px-6 py-3 border border-white/20">
                        <span class="text-white font-semibold">🔒 Only You</span>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl px-6 py-3 border border-white/20">
                        <span class="text-white font-semibold">⚡ Real-time Data</span>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl px-6 py-3 border border-white/20">
                        <span class="text-white font-semibold">📊 Analytics</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 -mt-16 relative z-10">
            <div class="bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/50 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-600 uppercase tracking-wider">Total Payments</p>
                        <p class="text-3xl font-bold text-gray-900" id="totalPayments">{{ number_format($stats['total_payments'] ?? 0) }}</p>
                        <p class="text-sm text-emerald-600 mt-1 flex items-center">
                            <i data-lucide="trending-up" class="h-4 w-4 mr-1"></i>
                            Your payments
                        </p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <i data-lucide="credit-card" class="h-7 w-7 text-white"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/50 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-600 uppercase tracking-wider">Total Value</p>
                        <p class="text-3xl font-bold text-gray-900" id="totalValue">${{ number_format($stats['total_value'] ?? 0, 2) }}</p>
                        <p class="text-sm text-emerald-600 mt-1 flex items-center">
                            <i data-lucide="dollar-sign" class="h-4 w-4 mr-1"></i>
                            Via Jena API
                        </p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <i data-lucide="banknote" class="h-7 w-7 text-white"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/50 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-600 uppercase tracking-wider">Success Rate</p>
                        <p class="text-3xl font-bold text-gray-900" id="successRate">{{ $stats['success_rate'] ?? 0 }}%</p>
                        <p class="text-sm text-emerald-600 mt-1 flex items-center">
                            <i data-lucide="check-circle" class="h-4 w-4 mr-1"></i>
                            Completed
                        </p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <i data-lucide="check-circle" class="h-7 w-7 text-white"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/50 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-600 uppercase tracking-wider">Failed</p>
                        <p class="text-3xl font-bold text-gray-900" id="failedPayments">{{ $stats['failed'] ?? 0 }}</p>
                        <p class="text-sm text-red-600 mt-1 flex items-center">
                            <i data-lucide="x-circle" class="h-4 w-4 mr-1"></i>
                            Requires attention
                        </p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-red-500 to-pink-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <i data-lucide="x-circle" class="h-7 w-7 text-white"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payments Table -->
        <div class="bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/50 overflow-hidden">
            <div class="p-8 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-2xl font-bold text-gray-900 flex items-center">
                        <i data-lucide="database" class="h-6 w-6 mr-3 text-indigo-600"></i>
                        My Bank Payments
                        <span id="tableCount" class="ml-3 px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-sm font-medium">{{ number_format($payments->total() ?? 0) }} total</span>
                    </h3>
                    <div class="flex items-center gap-4">
                        <div class="bg-gradient-to-r from-purple-100 to-pink-100 px-4 py-2 rounded-xl border border-purple-200">
                            <span class="text-sm font-bold text-purple-700">🔒 Only You</span>
                        </div>
                        <button onclick="refreshTable()" class="px-4 py-2 bg-indigo-100 hover:bg-indigo-200 text-indigo-700 rounded-xl font-medium transition-colors flex items-center gap-2">
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
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-gray-200 transition-colors" onclick="sortTable('reference')">
                                Payment Reference
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
                                Jena API
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody id="paymentsTableBody" class="bg-white divide-y divide-gray-200">
                        @foreach($payments ?? [] as $payment)
                        @if($payment->user_id == auth()->id())
                        <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" class="payment-checkbox rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" value="{{ $payment->id }}">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-900">{{ $payment->payment_reference ?? 'REF-' . $payment->id }}</div>
                                <div class="text-sm text-gray-500 flex items-center">
                                    <i data-lucide="banknote" class="h-3 w-3 mr-1"></i>
                                    Bank Transfer
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-lg font-bold text-gray-900">${{ number_format($payment->amount ?? 0, 2) }}</div>
                                <div class="text-sm text-gray-500">{{ $payment->currency ?? 'USD' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'completed' => 'bg-green-100 text-green-800 border-green-200',
                                        'processing' => 'bg-amber-100 text-amber-800 border-amber-200',
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
                                    <span class="text-sm text-gray-600">Jena API</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <button onclick="viewPaymentDetails({{ $payment->id }})" class="text-indigo-600 hover:text-indigo-800 transition-colors">
                                        <i data-lucide="eye" class="h-5 w-5"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endif
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
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
    window.refreshTable = function() {
        location.reload();
    };
});
</script>
@endsection