@extends('layouts.main')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-blue-600 rounded-xl flex items-center justify-center">
                            <i data-lucide="bar-chart" class="h-6 w-6 text-white"></i>
                        </div>
                        User Payment Analytics
                    </h1>
                    <p class="mt-2 text-gray-600">View and analyze user payments - Read-only access</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="bg-blue-50 px-4 py-2 rounded-xl border border-blue-200">
                        <span class="text-sm font-medium text-blue-600">Admin View</span>
                        <div class="w-2 h-2 bg-blue-500 rounded-full inline-block ml-2"></div>
                    </div>
                    <button onclick="exportPayments()" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl font-medium transition-colors flex items-center gap-2">
                        <i data-lucide="download" class="h-5 w-5"></i>
                        Export CSV
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">User Payments</p>
                        <p class="text-3xl font-bold text-gray-900" id="totalPayments">{{ $stats['total_payments'] }}</p>
                        <p class="text-sm text-blue-600 mt-1">
                            <i data-lucide="users" class="h-4 w-4 inline"></i>
                            Users only
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i data-lucide="credit-card" class="h-6 w-6 text-blue-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                        <p class="text-3xl font-bold text-gray-900" id="totalRevenue">${{ number_format($stats['total_revenue'], 2) }}</p>
                        <p class="text-sm text-green-600 mt-1">
                            <i data-lucide="trending-up" class="h-4 w-4 inline"></i>
                            From users
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <i data-lucide="dollar-sign" class="h-6 w-6 text-green-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Success Rate</p>
                        <p class="text-3xl font-bold text-gray-900" id="successRate">{{ $stats['success_rate'] }}%</p>
                        <p class="text-sm text-green-600 mt-1">
                            <i data-lucide="check-circle" class="h-4 w-4 inline"></i>
                            User payments
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <i data-lucide="check-circle" class="h-6 w-6 text-green-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Pending</p>
                        <p class="text-3xl font-bold text-gray-900" id="pendingPayments">{{ $stats['pending_payments'] }}</p>
                        <p class="text-sm text-yellow-600 mt-1">
                            <i data-lucide="clock" class="h-4 w-4 inline"></i>
                            Awaiting processing
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                        <i data-lucide="clock" class="h-6 w-6 text-yellow-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Payment Methods Chart -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Payment Methods</h3>
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                        <span class="text-sm text-gray-600">M-Pesa</span>
                        <div class="w-3 h-3 bg-blue-500 rounded-full ml-4"></div>
                        <span class="text-sm text-gray-600">Bank</span>
                    </div>
                </div>
                <div class="h-64">
                    <canvas id="paymentMethodsChart"></canvas>
                </div>
            </div>

            <!-- Revenue Trend Chart -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Revenue Trend</h3>
                    <div class="text-sm text-gray-500">User payments only</div>
                </div>
                <div class="h-64">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Payments Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Recent User Payments</h3>
                    <div class="flex items-center gap-4">
                        <div class="bg-amber-50 px-3 py-1 rounded-lg">
                            <span class="text-sm font-medium text-amber-600">Read-Only</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.payments.flights') }}" class="px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors">
                                Flight Payments
                            </a>
                            <a href="{{ route('admin.payments.hotels') }}" class="px-4 py-2 bg-purple-100 text-purple-700 rounded-lg hover:bg-purple-200 transition-colors">
                                Hotel Payments
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Booking</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="paymentsTable" class="bg-white divide-y divide-gray-200">
                        @foreach($recentPayments as $payment)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $payment->payment_reference }}</div>
                                <div class="text-sm text-gray-500">{{ $payment->booking->type }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                        {{ strtoupper(substr($payment->user->name, 0, 1)) }}
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $payment->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $payment->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $payment->booking->booking_reference }}</div>
                                <div class="text-sm text-gray-500">{{ ucfirst($payment->booking->type) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">${{ number_format($payment->amount, 2) }}</div>
                                <div class="text-sm text-gray-500">{{ $payment->currency }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $payment->payment_method === 'mpesa' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ $payment->payment_method === 'mpesa' ? 'M-Pesa' : 'Bank' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $payment->status_color }}-100 text-{{ $payment->status_color }}-800">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $payment->created_at->format('M d, Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button onclick="viewPayment({{ $payment->id }})" class="text-blue-600 hover:text-blue-900">
                                    <i data-lucide="eye" class="h-4 w-4"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Payment Details Modal -->
<div id="paymentModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-bold text-gray-900">Payment Details</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i data-lucide="x" class="h-6 w-6"></i>
                </button>
            </div>
        </div>
        <div id="paymentModalContent" class="p-6">
            <!-- Content will be loaded here -->
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
    initializeCharts();
    startAutoRefresh();
});

let autoRefreshInterval;

function initializeCharts() {
    // Payment Methods Chart
    const paymentMethodsCtx = document.getElementById('paymentMethodsChart').getContext('2d');
    new Chart(paymentMethodsCtx, {
        type: 'doughnut',
        data: {
            labels: @json($paymentMethodsData->pluck('payment_method')),
            datasets: [{
                data: @json($paymentMethodsData->pluck('count')),
                backgroundColor: ['#10B981', '#3B82F6', '#F59E0B'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: @json($monthlyData->pluck('month')),
            datasets: [{
                label: 'Revenue',
                data: @json($monthlyData->pluck('total')),
                borderColor: '#10B981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

function startAutoRefresh() {
    autoRefreshInterval = setInterval(function() {
        updateRealTimeData();
    }, 60000); // Update every minute
}

function updateRealTimeData() {
    fetch('/admin/payments/real-time-updates')
        .then(response => response.json())
        .then(data => {
            document.getElementById('totalPayments').textContent = data.stats.total_payments;
            document.getElementById('totalRevenue').textContent = '$' + parseFloat(data.stats.total_revenue).toLocaleString();
            document.getElementById('successRate').textContent = data.stats.success_rate + '%';
            document.getElementById('pendingPayments').textContent = data.stats.pending_payments;
        })
        .catch(error => {
            console.error('Error updating real-time data:', error);
        });
}

function viewPayment(paymentId) {
    fetch(`/admin/payments/${paymentId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('paymentModalContent').innerHTML = `
                <div class="space-y-6">
                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <i data-lucide="info" class="h-5 w-5 text-amber-600 mr-2"></i>
                            <span class="text-sm font-medium text-amber-800">Read-Only View</span>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Payment Reference</label>
                            <p class="mt-1 text-sm text-gray-900">${data.payment_reference}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Amount</label>
                            <p class="mt-1 text-sm text-gray-900">$${parseFloat(data.amount).toLocaleString()} ${data.currency}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Method</label>
                            <p class="mt-1 text-sm text-gray-900">${data.payment_method}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <p class="mt-1 text-sm text-gray-900">${data.status}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">User</label>
                            <p class="mt-1 text-sm text-gray-900">${data.user.name}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Booking</label>
                            <p class="mt-1 text-sm text-gray-900">${data.booking.booking_reference}</p>
                        </div>
                    </div>
                </div>
            `;
            
            document.getElementById('paymentModal').classList.remove('hidden');
            document.getElementById('paymentModal').classList.add('flex');
        })
        .catch(error => {
            console.error('Error fetching payment details:', error);
        });
}

function closeModal() {
    document.getElementById('paymentModal').classList.add('hidden');
    document.getElementById('paymentModal').classList.remove('flex');
}

function exportPayments() {
    const startDate = prompt('Enter start date (YYYY-MM-DD):');
    const endDate = prompt('Enter end date (YYYY-MM-DD):');
    
    let url = '/admin/payments/export?';
    if (startDate) url += `start_date=${startDate}&`;
    if (endDate) url += `end_date=${endDate}&`;
    
    window.location.href = url;
}
</script>
@endsection