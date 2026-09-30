@extends('layouts.dashboard')

@section('content')
<div class="container px-4">
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-[#1e293b] mb-1">Booking History</h1>
            <p class="text-[#64748b]">Comprehensive overview of all booking activities across the platform</p>
        </div>
        <div class="flex items-center gap-2 mt-4 lg:mt-0">
            <button onclick="exportData('pdf')" class="flex items-center gap-2 bg-[#2563eb] text-white py-2 px-4 rounded-lg hover:bg-[#1d4ed8] transition-all duration-200">
                <i data-lucide="file-text" class="w-4 h-4"></i>
                <span>Export PDF</span>
            </button>
            <button onclick="exportData('csv')" class="flex items-center gap-2 bg-[#0f766e] text-white py-2 px-4 rounded-lg hover:bg-[#0f5e57] transition-all duration-200">
                <i data-lucide="file-spreadsheet" class="w-4 h-4"></i>
                <span>Export CSV</span>
            </button>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white rounded-xl shadow-sm border border-[#e2e8f0] p-5 mb-6">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <label class="block text-sm font-medium text-[#64748b] mb-1" for="periodFilter">Time Period</label>
                <select id="periodFilter" onchange="applyFilters()" class="w-full border border-[#e2e8f0] rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#2563eb] focus:border-transparent">
                    <option value="all" {{ $period == 'all' ? 'selected' : '' }}>All Time</option>
                    <option value="today" {{ $period == 'today' ? 'selected' : '' }}>Today</option>
                    <option value="yesterday" {{ $period == 'yesterday' ? 'selected' : '' }}>Yesterday</option>
                    <option value="week" {{ $period == 'week' ? 'selected' : '' }}>Past Week</option>
                    <option value="month" {{ $period == 'month' ? 'selected' : '' }}>Past Month</option>
                </select>
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium text-[#64748b] mb-1" for="typeFilter">Booking Type</label>
                <select id="typeFilter" onchange="applyFilters()" class="w-full border border-[#e2e8f0] rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#2563eb] focus:border-transparent">
                    <option value="all" {{ $type == 'all' ? 'selected' : '' }}>All Types</option>
                    <option value="flight" {{ $type == 'flight' ? 'selected' : '' }}>Flights</option>
                    <option value="hotel" {{ $type == 'hotel' ? 'selected' : '' }}>Hotels</option>
                </select>
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium text-[#64748b] mb-1" for="statusFilter">Status</label>
                <select id="statusFilter" onchange="applyFilters()" class="w-full border border-[#e2e8f0] rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#2563eb] focus:border-transparent">
                    <option value="all" {{ $status == 'all' ? 'selected' : '' }}>All Statuses</option>
                    <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ $status == 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ $status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium text-[#64748b] mb-1" for="paymentFilter">Payment</label>
                <select id="paymentFilter" onchange="applyFilters()" class="w-full border border-[#e2e8f0] rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#2563eb] focus:border-transparent">
                    <option value="all" {{ $payment == 'all' ? 'selected' : '' }}>All Payments</option>
                    <option value="paid" {{ $payment == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="unpaid" {{ $payment == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                    <option value="partial" {{ $payment == 'partial' ? 'selected' : '' }}>Partial</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-[#e2e8f0] p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[#64748b] text-sm">Total Bookings</p>
                    <h3 class="text-2xl font-bold text-[#1e293b] mt-1">{{ array_sum($stats['status_counts']) }}</h3>
                </div>
                <div class="h-12 w-12 bg-[#2563eb] bg-opacity-10 rounded-full flex items-center justify-center">
                    <i data-lucide="calendar" class="h-6 w-6 text-[#2563eb]"></i>
                </div>
            </div>
            <div class="mt-3 flex items-center text-sm">
                <span class="text-[#10b981]">
                    <i data-lucide="trending-up" class="h-4 w-4 inline"></i> 
                    12% increase
                </span>
                <span class="text-[#64748b] ml-2">from last month</span>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-[#e2e8f0] p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[#64748b] text-sm">Flight Bookings</p>
                    <h3 class="text-2xl font-bold text-[#1e293b] mt-1">{{ $stats['type_distribution']['flight'] ?? 0 }}</h3>
                </div>
                <div class="h-12 w-12 bg-[#3b82f6] bg-opacity-10 rounded-full flex items-center justify-center">
                    <i data-lucide="plane" class="h-6 w-6 text-[#3b82f6]"></i>
                </div>
            </div>
            <div class="mt-3 flex items-center text-sm">
                <span class="text-[#10b981]">
                    <i data-lucide="trending-up" class="h-4 w-4 inline"></i> 
                    8% increase
                </span>
                <span class="text-[#64748b] ml-2">from last month</span>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-[#e2e8f0] p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[#64748b] text-sm">Hotel Bookings</p>
                    <h3 class="text-2xl font-bold text-[#1e293b] mt-1">{{ $stats['type_distribution']['hotel'] ?? 0 }}</h3>
                </div>
                <div class="h-12 w-12 bg-[#10b981] bg-opacity-10 rounded-full flex items-center justify-center">
                    <i data-lucide="building" class="h-6 w-6 text-[#10b981]"></i>
                </div>
            </div>
            <div class="mt-3 flex items-center text-sm">
                <span class="text-[#10b981]">
                    <i data-lucide="trending-up" class="h-4 w-4 inline"></i> 
                    15% increase
                </span>
                <span class="text-[#64748b] ml-2">from last month</span>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-[#e2e8f0] p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[#64748b] text-sm">Revenue</p>
                    <h3 class="text-2xl font-bold text-[#1e293b] mt-1">KES {{ number_format(array_sum($stats['revenue_values'] ?? [0]), 2) }}</h3>
                </div>
                <div class="h-12 w-12 bg-[#f59e0b] bg-opacity-10 rounded-full flex items-center justify-center">
                    <i data-lucide="wallet" class="h-6 w-6 text-[#f59e0b]"></i>
                </div>
            </div>
            <div class="mt-3 flex items-center text-sm">
                <span class="text-[#10b981]">
                    <i data-lucide="trending-up" class="h-4 w-4 inline"></i> 
                    18% increase
                </span>
                <span class="text-[#64748b] ml-2">from last month</span>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Left Column - Table -->
        <div class="lg:col-span-2">
            <!-- Responsive Table -->
            <div class="bg-white rounded-xl shadow-sm border border-[#e2e8f0] overflow-hidden">
                <div class="px-5 py-4 border-b border-[#e2e8f0]">
                    <h2 class="font-semibold text-[#1e293b]">Booking Records</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-[#e2e8f0]">
                        <thead class="bg-[#f8fafc]">
                            <tr>
                                <th scope="col" class="px-5 py-3 text-left text-xs font-medium text-[#64748b] uppercase tracking-wider">Booking ID</th>
                                <th scope="col" class="px-5 py-3 text-left text-xs font-medium text-[#64748b] uppercase tracking-wider">Customer</th>
                                <th scope="col" class="px-5 py-3 text-left text-xs font-medium text-[#64748b] uppercase tracking-wider">Type</th>
                                <th scope="col" class="px-5 py-3 text-left text-xs font-medium text-[#64748b] uppercase tracking-wider">Date</th>
                                <th scope="col" class="px-5 py-3 text-left text-xs font-medium text-[#64748b] uppercase tracking-wider">Amount</th>
                                <th scope="col" class="px-5 py-3 text-left text-xs font-medium text-[#64748b] uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-5 py-3 text-left text-xs font-medium text-[#64748b] uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-[#e2e8f0]">
                            @forelse ($bookings as $booking)
                                <tr class="hover:bg-[#f8fafc] transition-colors duration-200">
                                    <td class="px-5 py-4 whitespace-nowrap text-sm text-[#1e293b]">
                                        #{{ $booking->id }}
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8">
                                                <div class="h-8 w-8 rounded-full bg-[#2563eb] text-white flex items-center justify-center text-sm font-medium">
                                                    {{ substr($booking->user->name ?? 'U', 0, 1) }}
                                                </div>
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-[#1e293b]">{{ $booking->user->name ?? 'Unknown User' }}</div>
                                                <div class="text-xs text-[#64748b]">{{ $booking->user->email ?? '' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if($booking->type == 'flight')
                                                <i data-lucide="plane" class="h-4 w-4 text-[#3b82f6] mr-1"></i>
                                                <span class="text-sm">Flight</span>
                                            @else
                                                <i data-lucide="building" class="h-4 w-4 text-[#10b981] mr-1"></i>
                                                <span class="text-sm">Hotel</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap text-sm text-[#64748b]">
                                        {{ $booking->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap text-sm font-medium text-[#1e293b]">
                                        KES {{ number_format($booking->total_amount ?? 0, 2) }}
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        @php
                                            $statusColor = [
                                                'pending' => 'bg-[#f59e0b] bg-opacity-10 text-[#f59e0b]',
                                                'processing' => 'bg-[#3b82f6] bg-opacity-10 text-[#3b82f6]',
                                                'completed' => 'bg-[#10b981] bg-opacity-10 text-[#10b981]',
                                                'cancelled' => 'bg-[#ef4444] bg-opacity-10 text-[#ef4444]',
                                            ][$booking->status] ?? 'bg-[#64748b] bg-opacity-10 text-[#64748b]';
                                            
                                            $statusIcon = [
                                                'pending' => 'clock',
                                                'processing' => 'loader',
                                                'completed' => 'check-circle',
                                                'cancelled' => 'x-circle',
                                            ][$booking->status] ?? 'help-circle';
                                        @endphp
                                        
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColor }}">
                                            <i data-lucide="{{ $statusIcon }}" class="h-3 w-3 mr-1"></i>
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap text-sm">
                                        <div class="flex items-center space-x-2">
                                            <button onclick="viewDetails({{ $booking->id }})" class="text-[#2563eb] hover:text-[#1d4ed8]">
                                                <i data-lucide="eye" class="h-4 w-4"></i>
                                            </button>
                                            <a href="{{ route('bookings.show', $booking->id) }}" class="text-[#10b981] hover:text-[#059669]">
                                                <i data-lucide="printer" class="h-4 w-4"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-5 py-6 text-center text-[#64748b]">
                                        <div class="flex flex-col items-center">
                                            <i data-lucide="calendar-x" class="h-10 w-10 text-[#94a3b8] mb-2"></i>
                                            <p>No booking records found</p>
                                            <p class="text-sm text-[#94a3b8] mt-1">Try changing filters or check back later</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-5 py-3 border-t border-[#e2e8f0]">
                    {{ $bookings->withQueryString()->links() }}
                </div>
            </div>
        </div>
        
        <!-- Right Column - Calendar + Charts -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Calendar -->
            <div class="bg-white rounded-xl shadow-sm border border-[#e2e8f0] p-5">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-semibold text-[#1e293b]">Booking Calendar</h3>
                    <div class="flex items-center space-x-1">
                        <button id="prevMonth" class="p-1 rounded hover:bg-[#f1f5f9]">
                            <i data-lucide="chevron-left" class="h-4 w-4 text-[#64748b]"></i>
                        </button>
                        <button id="nextMonth" class="p-1 rounded hover:bg-[#f1f5f9]">
                            <i data-lucide="chevron-right" class="h-4 w-4 text-[#64748b]"></i>
                        </button>
                    </div>
                </div>
                
                <h4 id="currentMonthYear" class="text-center text-[#1e293b] mb-3 font-medium">July 2025</h4>
                
                <div class="grid grid-cols-7 gap-1 mb-1">
                    <div class="text-center text-[#64748b] text-xs font-medium">Sun</div>
                    <div class="text-center text-[#64748b] text-xs font-medium">Mon</div>
                    <div class="text-center text-[#64748b] text-xs font-medium">Tue</div>
                    <div class="text-center text-[#64748b] text-xs font-medium">Wed</div>
                    <div class="text-center text-[#64748b] text-xs font-medium">Thu</div>
                    <div class="text-center text-[#64748b] text-xs font-medium">Fri</div>
                    <div class="text-center text-[#64748b] text-xs font-medium">Sat</div>
                </div>
                
                <div id="calendarBody" class="grid grid-cols-7 gap-1">
                    <!-- Calendar days will be inserted here via JavaScript -->
                </div>
                
                <div class="mt-4 flex justify-center gap-4">
                    <div class="flex items-center">
                        <span class="h-3 w-3 rounded-full bg-[#3b82f6] inline-block mr-1"></span>
                        <span class="text-xs text-[#64748b]">Flights</span>
                    </div>
                    <div class="flex items-center">
                        <span class="h-3 w-3 rounded-full bg-[#10b981] inline-block mr-1"></span>
                        <span class="text-xs text-[#64748b]">Hotels</span>
                    </div>
                </div>
            </div>
            
            <!-- Booking Distribution Chart -->
            <div class="bg-white rounded-xl shadow-sm border border-[#e2e8f0] p-5">
                <h3 class="font-semibold text-[#1e293b] mb-4">Booking Distribution</h3>
                <canvas id="bookingDistributionChart" height="200"></canvas>
            </div>
            
            <!-- Revenue Trends -->
            <div class="bg-white rounded-xl shadow-sm border border-[#e2e8f0] p-5">
                <h3 class="font-semibold text-[#1e293b] mb-4">Revenue Trends</h3>
                <canvas id="revenueTrendsChart" height="200"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Additional Reports Section -->
    <h2 class="text-xl font-semibold text-[#1e293b] mb-4">Advanced Analytics</h2>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Demographics -->
        <div class="bg-white rounded-xl shadow-sm border border-[#e2e8f0] p-5">
            <h3 class="font-semibold text-[#1e293b] mb-4">Customer Demographics</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <canvas id="ageGroupChart" height="200"></canvas>
                </div>
                <div>
                    <canvas id="genderChart" height="200"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Predicted Analysis -->
        <div class="bg-white rounded-xl shadow-sm border border-[#e2e8f0] p-5">
            <h3 class="font-semibold text-[#1e293b] mb-4">Predictive Booking Analysis</h3>
            <div class="flex items-center gap-4 mb-4">
                <div class="font-medium text-[#1e293b]">Forecast accuracy:</div>
                <div class="w-full bg-[#e2e8f0] rounded-full h-2.5">
                    <div class="bg-[#2563eb] h-2.5 rounded-full" style="width: 85%"></div>
                </div>
                <div class="font-medium text-[#1e293b]">85%</div>
            </div>
            <canvas id="predictionChart" height="200"></canvas>
            <div class="mt-4">
                <h4 class="font-medium text-[#1e293b] mb-2">Key Insights:</h4>
                <ul class="space-y-1">
                    <li class="flex items-start gap-2">
                        <i data-lucide="trending-up" class="h-4 w-4 text-[#10b981] mt-0.5"></i>
                        <span class="text-sm text-[#64748b]">Expected 18% increase in flight bookings next month</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i data-lucide="trending-down" class="h-4 w-4 text-[#ef4444] mt-0.5"></i>
                        <span class="text-sm text-[#64748b]">Seasonal decline in hotel bookings expected</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i data-lucide="alert-circle" class="h-4 w-4 text-[#f59e0b] mt-0.5"></i>
                        <span class="text-sm text-[#64748b]">Recommendation: Prepare special offers for hotels</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Booking Details Modal -->
<div id="bookingDetailsModal" class="fixed inset-0 bg-black bg-opacity-50 z-[1001] hidden overflow-y-auto">
    <div class="min-h-screen px-4 text-center flex items-center justify-center">
        <div class="bg-white w-full max-w-3xl rounded-xl shadow-lg p-6 transform transition-all">
            <div class="flex justify-between items-start mb-4">
                <h3 class="text-xl font-semibold text-[#1e293b]">Booking Details</h3>
                <button onclick="closeModal()" class="text-[#64748b] hover:text-[#1e293b] transition-colors duration-200">
                    <i data-lucide="x" class="h-5 w-5"></i>
                </button>
            </div>
            
            <div id="bookingDetailsContent" class="mt-2">
                <!-- Content will be loaded here via AJAX -->
                <div class="flex justify-center">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-[#2563eb]"></div>
                </div>
            </div>
            
            <div class="flex justify-end mt-6 gap-2">
                <button onclick="printBookingDetails()" class="flex items-center gap-2 bg-[#10b981] text-white py-2 px-4 rounded-lg hover:bg-[#059669] transition-all duration-200">
                    <i data-lucide="printer" class="w-4 h-4"></i>
                    <span>Print</span>
                </button>
                <button onclick="closeModal()" class="flex items-center gap-2 bg-[#e2e8f0] text-[#64748b] py-2 px-4 rounded-lg hover:bg-[#cbd5e1] transition-all duration-200">
                    <i data-lucide="x" class="w-4 h-4"></i>
                    <span>Close</span>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Calendar functionality
    document.addEventListener('DOMContentLoaded', function() {
        let currentDate = new Date();
        let currentMonth = currentDate.getMonth();
        let currentYear = currentDate.getFullYear();
        
        // Calendar data from PHP
        const calendarData = @json($calendarData);
        
        function updateCalendar() {
            const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
            
            document.getElementById('currentMonthYear').textContent = `${monthNames[currentMonth]} ${currentYear}`;
            
            const firstDay = new Date(currentYear, currentMonth, 1).getDay();
            const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
            const calendarBody = document.getElementById('calendarBody');
            
            calendarBody.innerHTML = '';
            
            // Fill in empty cells for days before the first day of the month
            for (let i = 0; i < firstDay; i++) {
                const emptyCell = document.createElement('div');
                emptyCell.className = 'h-9 flex items-center justify-center text-xs text-[#94a3b8] opacity-50';
                calendarBody.appendChild(emptyCell);
            }
            
            // Fill in the days of the month
            for (let day = 1; day <= daysInMonth; day++) {
                const dateCell = document.createElement('div');
                const formattedDate = `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                
                let isToday = false;
                if (currentYear === new Date().getFullYear() && 
                    currentMonth === new Date().getMonth() && 
                    day === new Date().getDate()) {
                    isToday = true;
                }
                
                dateCell.className = `relative h-9 flex flex-col items-center justify-center rounded ${isToday ? 'bg-[#2563eb] bg-opacity-10' : ''}`;
                
                // Day number
                const dayNumber = document.createElement('span');
                dayNumber.className = `text-xs ${isToday ? 'font-bold text-[#2563eb]' : 'text-[#1e293b]'}`;
                dayNumber.textContent = day;
                dateCell.appendChild(dayNumber);
                
                // Check if there are any bookings for this date
                if (calendarData[formattedDate]) {
                    const booking = calendarData[formattedDate];
                    const indicators = document.createElement('div');
                    indicators.className = 'flex gap-0.5 mt-0.5';
                    
                    if (booking.flight > 0) {
                        const flightIndicator = document.createElement('span');
                        flightIndicator.className = 'h-1.5 w-1.5 rounded-full bg-[#3b82f6]';
                        flightIndicator.setAttribute('title', `${booking.flight} flight bookings`);
                        indicators.appendChild(flightIndicator);
                    }
                    
                    if (booking.hotel > 0) {
                        const hotelIndicator = document.createElement('span');
                        hotelIndicator.className = 'h-1.5 w-1.5 rounded-full bg-[#10b981]';
                        hotelIndicator.setAttribute('title', `${booking.hotel} hotel bookings`);
                        indicators.appendChild(hotelIndicator);
                    }
                    
                    dateCell.appendChild(indicators);
                    
                    // Make date cell clickable
                    dateCell.classList.add('cursor-pointer', 'hover:bg-[#f1f5f9]');
                    dateCell.onclick = function() {
                        // Redirect to filtered view for this date
                        window.location.href = `?period=custom&date=${formattedDate}`;
                    };
                }
                
                calendarBody.appendChild(dateCell);
            }
        }
        
        // Initialize calendar
        updateCalendar();
        
        // Add event listeners for navigation
        document.getElementById('prevMonth').addEventListener('click', function() {
            currentMonth--;
            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            }
            updateCalendar();
        });
        
        document.getElementById('nextMonth').addEventListener('click', function() {
            currentMonth++;
            if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }
            updateCalendar();
        });
        
        // Charts
        const statusCounts = @json($stats['status_counts'] ?? ['pending' => 0, 'processing' => 0, 'completed' => 0, 'cancelled' => 0]);
        const typeDistribution = @json($stats['type_distribution'] ?? ['flight' => 0, 'hotel' => 0]);
        const revenueLabels = @json($stats['revenue_labels'] ?? []);
        const revenueValues = @json($stats['revenue_values'] ?? []);
        
        // Booking Distribution Chart
        const distributionCtx = document.getElementById('bookingDistributionChart').getContext('2d');
        new Chart(distributionCtx, {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Processing', 'Completed', 'Cancelled'],
                datasets: [{
                    data: [
                        statusCounts['pending'] ?? 0,
                        statusCounts['processing'] ?? 0,
                        statusCounts['completed'] ?? 0,
                        statusCounts['cancelled'] ?? 0
                    ],
                    backgroundColor: ['#f59e0b', '#3b82f6', '#10b981', '#ef4444'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    }
                }
            }
        });
        
        // Revenue Trends Chart
        const revenueCtx = document.getElementById('revenueTrendsChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: revenueLabels,
                datasets: [{
                    label: 'Revenue',
                    data: revenueValues,
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    borderWidth: 2,
                    fill: true,
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
                                return 'KES ' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
        
        // Age Group Chart (Demographics)
        const ageGroupCtx = document.getElementById('ageGroupChart').getContext('2d');
        new Chart(ageGroupCtx, {
            type: 'pie',
            data: {
                labels: ['Under 20', '20-30', '31-40', '41-50', 'Over 50'],
                datasets: [{
                    data: [5, 35, 25, 20, 15],
                    backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#6366f1', '#8b5cf6'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            boxWidth: 8
                        }
                    },
                    title: {
                        display: true,
                        text: 'Age Groups',
                        color: '#1e293b',
                        font: {
                            size: 14,
                            weight: 'normal'
                        }
                    }
                }
            }
        });
        
        // Gender Chart
        const genderCtx = document.getElementById('genderChart').getContext('2d');
        new Chart(genderCtx, {
            type: 'pie',
            data: {
                labels: ['Male', 'Female', 'Other'],
                datasets: [{
                    data: [45, 52, 3],
                    backgroundColor: ['#3b82f6', '#ec4899', '#8b5cf6'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            boxWidth: 8
                        }
                    },
                    title: {
                        display: true,
                        text: 'Gender Distribution',
                        color: '#1e293b',
                        font: {
                            size: 14,
                            weight: 'normal'
                        }
                    }
                }
            }
        });
        
        // Prediction Chart
        const predictionCtx = document.getElementById('predictionChart').getContext('2d');
        new Chart(predictionCtx, {
            type: 'line',
            data: {
                labels: ['Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Actual Bookings',
                    data: [150, 180, 200, 220, 240, null],
                    borderColor: '#2563eb',
                    backgroundColor: 'transparent',
                    borderWidth: 2,
                    pointBackgroundColor: '#2563eb',
                    tension: 0.3
                }, {
                    label: 'Predicted Bookings',
                    data: [null, null, null, null, 240, 280],
                    borderColor: '#f59e0b',
                    borderWidth: 2,
                    borderDash: [5, 5],
                    pointBackgroundColor: '#f59e0b',
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
    
    // Apply filters function
    function applyFilters() {
        const period = document.getElementById('periodFilter').value;
        const type = document.getElementById('typeFilter').value;
        const status = document.getElementById('statusFilter').value;
        const payment = document.getElementById('paymentFilter').value;
        
        window.location.href = `{{ route('admin.bookings.all') }}?period=${period}&type=${type}&status=${status}&payment=${payment}`;
    }
    
    // Export data function
    function exportData(format) {
        const period = document.getElementById('periodFilter').value;
        const type = document.getElementById('typeFilter').value;
        const status = document.getElementById('statusFilter').value;
        const payment = document.getElementById('paymentFilter').value;
        
        window.open(`{{ route('superadmin.data.export') }}?format=${format}&period=${period}&type=${type}&status=${status}&payment=${payment}`, '_blank');
    }
    
    // View booking details
    function viewDetails(bookingId) {
        const modal = document.getElementById('bookingDetailsModal');
        const content = document.getElementById('bookingDetailsContent');
        
        // Show modal
        modal.classList.remove('hidden');
        
        // Show loading spinner
        content.innerHTML = '<div class="flex justify-center"><div class="animate-spin rounded-full h-12 w-12 border-b-2 border-[#2563eb]"></div></div>';
        
        // Fetch booking details
        fetch(`/dashboard/booking/${bookingId}/details`)
            .then(response => response.text())
            .then(html => {
                content.innerHTML = html;
                // Re-initialize Lucide icons
                if (window.lucide) {
                    window.lucide.createIcons();
                }
            })
            .catch(error => {
                content.innerHTML = '<div class="text-[#ef4444] p-4 text-center">Failed to load booking details. Please try again.</div>';
                console.error('Error fetching booking details:', error);
            });
    }
    
    // Close modal
    function closeModal() {
        document.getElementById('bookingDetailsModal').classList.add('hidden');
    }
    
    // Print booking details
    function printBookingDetails() {
        window.print();
    }
    
    // Close modal when clicking outside
    document.getElementById('bookingDetailsModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
</script>
@endsection