@extends('layouts.main')

@section('content')
<!-- Hero Section -->
<div class="hero-section text-white py-10 px-4 mb-8" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="container mx-auto flex flex-col md:flex-row items-center justify-between gap-6">
        <div>
            <h1 class="text-4xl font-bold flex items-center gap-3">
                <i data-lucide="bar-chart-3" class="w-10 h-10"></i>
                All Flight Bookings & Analytics
            </h1>
            <p class="mt-2 text-lg opacity-80">Advanced analytics and management for all flight bookings.</p>
        </div>
        <div class="flex gap-4">
            <button id="exportCsv" class="bg-white text-blue-600 px-6 py-3 rounded-lg font-medium hover:bg-gray-100 transition-colors flex items-center gap-2">
                <i data-lucide="file-text" class="w-5 h-5"></i>
                Export CSV
            </button>
            <button id="exportExcel" class="bg-white text-green-600 px-6 py-3 rounded-lg font-medium hover:bg-gray-100 transition-colors flex items-center gap-2">
                <i data-lucide="file-spreadsheet" class="w-5 h-5"></i>
                Export Excel
            </button>
        </div>
    </div>
</div>

<!-- Analytics Section -->
<div class="container mx-auto px-4 mb-8">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-sm p-6 border flex flex-col items-center">
            <i data-lucide="users" class="w-8 h-8 text-blue-500 mb-2"></i>
            <div class="text-2xl font-bold">{{ $totalUsers }}</div>
            <div class="text-gray-500 text-sm">Total Users</div>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-6 border flex flex-col items-center">
            <i data-lucide="plane" class="w-8 h-8 text-purple-500 mb-2"></i>
            <div class="text-2xl font-bold">{{ $totalBookings }}</div>
            <div class="text-gray-500 text-sm">Total Bookings</div>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-6 border flex flex-col items-center">
            <i data-lucide="check-circle" class="w-8 h-8 text-green-500 mb-2"></i>
            <div class="text-2xl font-bold">{{ $completedBookings }}</div>
            <div class="text-gray-500 text-sm">Completed</div>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-6 border flex flex-col items-center">
            <i data-lucide="x-circle" class="w-8 h-8 text-red-500 mb-2"></i>
            <div class="text-2xl font-bold">{{ $failedBookings }}</div>
            <div class="text-gray-500 text-sm">Failed</div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="container mx-auto px-4 mb-8">
    <div class="bg-white rounded-lg shadow-sm p-6 border">
        <h2 class="font-semibold text-lg mb-4 flex items-center gap-2">
            <i data-lucide="activity" class="w-5 h-5"></i>
            Bookings Over Time
        </h2>
        <canvas id="bookingsChart" height="80"></canvas>
    </div>
</div>

<!-- Filters Section -->
<div class="container mx-auto px-4 mb-6">
    <div class="bg-white rounded-lg shadow-sm p-6 border flex flex-col md:flex-row gap-6 items-center justify-between">
        <div class="flex gap-4 flex-wrap">
            <div>
                <label class="font-semibold text-gray-700 mr-2">Status:</label>
                <select id="statusFilter" class="border rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="all">All</option>
                    <option value="completed">Completed</option>
                    <option value="failed">Failed</option>
                    <option value="processing">Processing</option>
                    <option value="pending">Pending</option>
                    <option value="cancelled">Cancelled</option>
                    <option value="confirmed">Confirmed</option>
                </select>
            </div>
            <div>
                <label class="font-semibold text-gray-700 mr-2">Time:</label>
                <select id="timeFilter" class="border rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="all">All</option>
                    <option value="date">Date</option>
                    <option value="week">Week</option>
                    <option value="month">Month</option>
                    <option value="year">Year</option>
                </select>
            </div>
            <div id="datePickerContainer" class="hidden">
                <input type="date" id="datePicker" class="border rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>
        <div>
            <input type="text" id="searchInput" class="border rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Search by reference, user, flight...">
        </div>
    </div>
</div>

<!-- Bookings Table -->
<div class="container mx-auto px-4">
    <div class="bg-white rounded-lg shadow-sm p-6 border">
        <h2 class="font-semibold text-lg mb-4 flex items-center gap-2">
            <i data-lucide="table" class="w-5 h-5"></i>
            All Flight Bookings
        </h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200" id="bookingsTable">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Reference</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Flight No.</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">From</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">To</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Departure</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Arrival</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Created At</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($bookings as $index => $booking)
                        <tr>
                            <td class="px-4 py-2 text-sm text-gray-700">{{ $index + 1 }}</td>
                            <td class="px-4 py-2 text-sm text-blue-600 font-semibold">{{ $booking->booking_reference }}</td>
                            <td class="px-4 py-2 text-sm text-gray-700 flex items-center gap-2">
                                <i data-lucide="user" class="w-4 h-4"></i>
                                {{ $booking->user->name ?? 'N/A' }}
                            </td>
                            <td class="px-4 py-2 text-sm">
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs font-medium
                                    @if($booking->role === 'superadmin') bg-purple-100 text-purple-700
                                    @elseif($booking->role === 'admin') bg-blue-100 text-blue-700
                                    @else bg-gray-100 text-gray-700 @endif">
                                    <i data-lucide="shield" class="w-3 h-3"></i>
                                    {{ ucfirst($booking->role ?? 'user') }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-sm">{{ $booking->flight_number ?? '-' }}</td>
                            <td class="px-4 py-2 text-sm">{{ $booking->departure_airport ?? '-' }}</td>
                            <td class="px-4 py-2 text-sm">{{ $booking->arrival_airport ?? '-' }}</td>
                            <td class="px-4 py-2 text-sm">{{ $booking->departure_time ? date('M d, H:i', strtotime($booking->departure_time)) : '-' }}</td>
                            <td class="px-4 py-2 text-sm">{{ $booking->arrival_time ? date('M d, H:i', strtotime($booking->arrival_time)) : '-' }}</td>
                            <td class="px-4 py-2 text-sm">
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs font-medium
                                    @if($booking->status === 'completed') bg-green-100 text-green-700
                                    @elseif($booking->status === 'failed') bg-red-100 text-red-700
                                    @elseif($booking->status === 'processing') bg-yellow-100 text-yellow-700
                                    @elseif($booking->status === 'pending') bg-gray-100 text-gray-700
                                    @elseif($booking->status === 'cancelled') bg-gray-200 text-gray-500
                                    @elseif($booking->status === 'confirmed') bg-blue-100 text-blue-700
                                    @endif">
                                    <i data-lucide="check-circle" class="w-3 h-3"></i>
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-sm font-bold text-green-600">{{ number_format($booking->amount, 2) }} {{ $booking->currency }}</td>
                            <td class="px-4 py-2 text-sm">{{ $booking->created_at ? date('M d, Y', strtotime($booking->created_at)) : '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if(count($bookings) === 0)
                <div class="text-center py-12 text-gray-500">
                    <i data-lucide="plane-off" class="w-10 h-10 mx-auto mb-4"></i>
                    No bookings found.
                </div>
            @endif
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();

    // Show/hide date picker based on time filter
    document.getElementById('timeFilter').addEventListener('change', function() {
        const val = this.value;
        document.getElementById('datePickerContainer').style.display = (val !== 'all') ? 'inline-block' : 'none';
    });

    // Filter table rows by status, time, and search
    function filterTable() {
        const status = document.getElementById('statusFilter').value;
        const time = document.getElementById('timeFilter').value;
        const date = document.getElementById('datePicker').value;
        const search = document.getElementById('searchInput').value.toLowerCase();

        const rows = document.querySelectorAll('#bookingsTable tbody tr');
        rows.forEach(row => {
            let show = true;

            // Status filter
            if (status !== 'all') {
                const statusText = row.querySelector('td:nth-child(10)').textContent.trim().toLowerCase();
                show = show && statusText === status;
            }

            // Time filter
            if (time !== 'all' && date) {
                const createdAt = row.querySelector('td:nth-child(12)').textContent.trim();
                const rowDate = new Date(createdAt);
                const filterDate = new Date(date);

                if (time === 'date') {
                    show = show && rowDate.toDateString() === filterDate.toDateString();
                } else if (time === 'week') {
                    const weekStart = new Date(filterDate);
                    weekStart.setDate(filterDate.getDate() - filterDate.getDay());
                    const weekEnd = new Date(weekStart);
                    weekEnd.setDate(weekStart.getDate() + 6);
                    show = show && rowDate >= weekStart && rowDate <= weekEnd;
                } else if (time === 'month') {
                    show = show && rowDate.getMonth() === filterDate.getMonth() && rowDate.getFullYear() === filterDate.getFullYear();
                } else if (time === 'year') {
                    show = show && rowDate.getFullYear() === filterDate.getFullYear();
                }
            }

            // Search filter
            if (search.length > 0) {
                show = show && row.textContent.toLowerCase().includes(search);
            }

            row.style.display = show ? '' : 'none';
        });
    }

    document.getElementById('statusFilter').addEventListener('change', filterTable);
    document.getElementById('timeFilter').addEventListener('change', filterTable);
    document.getElementById('datePicker').addEventListener('input', filterTable);
    document.getElementById('searchInput').addEventListener('input', filterTable);

    // Chart.js bookings analytics
    const ctx = document.getElementById('bookingsChart').getContext('2d');
    const bookingsChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [{
                label: 'Bookings',
                data: {!! json_encode($chartData) !!},
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99,102,241,0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            }
        }
    });

    // Export to CSV
    document.getElementById('exportCsv').addEventListener('click', function() {
        let csv = [];
        const rows = document.querySelectorAll('#bookingsTable tr');
        for (let row of rows) {
            let cols = row.querySelectorAll('th, td');
            let rowData = [];
            for (let col of cols) {
                rowData.push('"' + col.innerText.replace(/"/g, '""') + '"');
            }
            csv.push(rowData.join(','));
        }
        let csvFile = new Blob([csv.join('\n')], { type: 'text/csv' });
        let downloadLink = document.createElement('a');
        downloadLink.download = 'bookings.csv';
        downloadLink.href = window.URL.createObjectURL(csvFile);
        downloadLink.click();
    });

    // Export to Excel (simple CSV for Excel)
    document.getElementById('exportExcel').addEventListener('click', function() {
        let csv = [];
        const rows = document.querySelectorAll('#bookingsTable tr');
        for (let row of rows) {
            let cols = row.querySelectorAll('th, td');
            let rowData = [];
            for (let col of cols) {
                rowData.push('"' + col.innerText.replace(/"/g, '""') + '"');
            }
            csv.push(rowData.join(','));
        }
        let csvFile = new Blob([csv.join('\n')], { type: 'application/vnd.ms-excel' });
        let downloadLink = document.createElement('a');
        downloadLink.download = 'bookings.xls';
        downloadLink.href = window.URL.createObjectURL(csvFile);
        downloadLink.click();
    });
});
</script>
@endsection