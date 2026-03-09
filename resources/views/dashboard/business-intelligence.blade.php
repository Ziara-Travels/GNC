@extends('layouts.dashboard')

@section('title', 'Business Intelligence Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Business Intelligence Dashboard</h1>
            <p class="text-muted">Advanced analytics and insights for {{ $role === 'superadmin' ? 'system-wide' : 'administrative' }} overview</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" onclick="refreshDashboard()">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-download"></i> Export
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('dashboard.business-intelligence.export', ['format' => 'pdf']) }}">
                        <i class="fas fa-file-pdf"></i> PDF Report
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('dashboard.business-intelligence.export', ['format' => 'excel']) }}">
                        <i class="fas fa-file-excel"></i> Excel Report
                    </a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Revenue
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                ${{ number_format($kpiData['total_revenue'], 2) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Bookings
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($kpiData['total_bookings']) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Avg Booking Value
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                ${{ number_format($kpiData['average_booking_value'], 2) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Conversion Rate
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $kpiData['conversion_rate'] }}%
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-percentage fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <!-- Revenue Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Revenue Overview</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                            <a class="dropdown-item" href="#" onclick="switchRevenueView('monthly')">Monthly View</a>
                            <a class="dropdown-item" href="#" onclick="switchRevenueView('weekly')">Weekly View</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Destinations -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Top 10 Destinations</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="destinationsChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        @foreach($topDestinations->take(5) as $destination)
                        <span class="mr-2">
                            <i class="fas fa-circle text-primary"></i> {{ $destination['destination'] }}
                        </span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Booking Trends and Refunds -->
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Booking Trends (Last 30 Days)</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="bookingTrendsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Refund Trends (Last 30 Days)</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="refundTrendsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- User Statistics and Performance -->
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">User Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="text-center">
                                <div class="h4 font-weight-bold text-primary">{{ number_format($userStats['total']) }}</div>
                                <div class="text-muted small">Total Users</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <div class="h4 font-weight-bold text-success">{{ number_format($userStats['active']) }}</div>
                                <div class="text-muted small">Active Users</div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-6">
                            <div class="text-center">
                                <div class="h4 font-weight-bold text-info">{{ number_format($userStats['new_this_month']) }}</div>
                                <div class="text-muted small">New This Month</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <div class="h4 font-weight-bold text-warning">{{ $userStats['retention_rate'] }}%</div>
                                <div class="text-muted small">Retention Rate</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Performance Metrics</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="text-center">
                                <div class="h4 font-weight-bold text-success">{{ $performanceMetrics['system_health']['uptime'] }}%</div>
                                <div class="text-muted small">System Uptime</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <div class="h4 font-weight-bold text-info">{{ $performanceMetrics['api_performance']['success_rate'] }}%</div>
                                <div class="text-muted small">API Success Rate</div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-6">
                            <div class="text-center">
                                <div class="h4 font-weight-bold text-primary">{{ $performanceMetrics['system_health']['avg_page_load_time'] }}s</div>
                                <div class="text-muted small">Avg Page Load</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <div class="h4 font-weight-bold text-warning">{{ $performanceMetrics['user_engagement']['bounce_rate'] }}%</div>
                                <div class="text-muted small">Bounce Rate</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Bookings Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Recent Bookings</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Reference</th>
                            <th>User</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentBookings as $booking)
                        <tr>
                            <td>{{ $booking['reference'] }}</td>
                            <td>{{ $booking['user'] }}</td>
                            <td>
                                <span class="badge badge-{{ $booking['type'] === 'flight' ? 'primary' : 'success' }}">
                                    {{ ucfirst($booking['type']) }}
                                </span>
                            </td>
                            <td>${{ number_format($booking['amount'], 2) }}</td>
                            <td>
                                <span class="badge badge-{{ $booking['status'] === 'completed' ? 'success' : ($booking['status'] === 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($booking['status']) }}
                                </span>
                            </td>
                            <td>{{ $booking['created_at'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($revenueData['monthly']->pluck('month')) !!},
            datasets: [{
                label: 'Revenue',
                data: {!! json_encode($revenueData['monthly']->pluck('revenue')) !!},
                borderColor: 'rgb(78, 115, 223)',
                backgroundColor: 'rgba(78, 115, 223, 0.1)',
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Destinations Chart
    const destinationsCtx = document.getElementById('destinationsChart').getContext('2d');
    const destinationsChart = new Chart(destinationsCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($topDestinations->pluck('destination')) !!},
            datasets: [{
                data: {!! json_encode($topDestinations->pluck('bookings')) !!},
                backgroundColor: [
                    '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b',
                    '#5a5c69', '#858796', '#6f42c1', '#e83e8c', '#fd7e14'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Booking Trends Chart
    const bookingTrendsCtx = document.getElementById('bookingTrendsChart').getContext('2d');
    const bookingTrendsChart = new Chart(bookingTrendsCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($bookingTrends->pluck('date')) !!},
            datasets: [{
                label: 'Bookings',
                data: {!! json_encode($bookingTrends->pluck('bookings')) !!},
                backgroundColor: 'rgba(28, 200, 138, 0.8)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Refund Trends Chart
    const refundTrendsCtx = document.getElementById('refundTrendsChart').getContext('2d');
    const refundTrendsChart = new Chart(refundTrendsCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($refundTrends->pluck('date')) !!},
            datasets: [{
                label: 'Refunds',
                data: {!! json_encode($refundTrends->pluck('refunds')) !!},
                borderColor: 'rgb(231, 74, 59)',
                backgroundColor: 'rgba(231, 74, 59, 0.1)',
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Functions
    function refreshDashboard() {
        location.reload();
    }

    function switchRevenueView(view) {
        // Implementation for switching between monthly and weekly views
        console.log('Switching to', view, 'view');
    }
</script>
@endpush
@endsection