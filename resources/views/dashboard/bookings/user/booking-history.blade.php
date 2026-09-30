@extends('layouts.dashboard')

@section('content')
<div class="container px-4">
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-[#1e293b] mb-1">My Booking History</h1>
            <p class="text-[#64748b]">View and manage your travel bookings</p>
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
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-[#e2e8f0] p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[#64748b] text-sm">Total Spent</p>
                    <h3 class="text-2xl font-bold text-[#1e293b] mt-1">KES {{ number_format(array_sum($stats['revenue_values'] ?? [0]), 2) }}</h3>
                </div>
                <div class="h-12 w-12 bg-[#f59e0b] bg-opacity-10 rounded-full flex items-center justify-center">
                    <i data-lucide="wallet" class="h-6 w-6 text-[#f59e0b]"></i>
                </div>
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
                    <h2 class="font-semibold text-[#1e293b]">My Bookings</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-[#e2e8f0]">
                        <thead class="bg-[#f8fafc]">
                            <tr>
                                <th scope="col" class="px-5 py-3 text-left text-xs font-medium text-[#64748b] uppercase tracking-wider">Booking ID</th>
                                <th scope="col" class="px-5 py-3 text-left text-xs font-medium text-[#64748b] uppercase tracking-wider">Type</th>
                                <th scope="col" class="px-5 py-3 text-left text-xs font-medium text-[#64748b] uppercase tracking-wider">Details</th>
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
                                            @if($booking->type == 'flight')
                                                <i data-lucide="plane" class="h-4 w-4 text-[#3b82f6] mr-1"></i>
                                                <span class="text-sm">Flight</span>
                                            @else
                                                <i data-lucide="building" class="h-4 w-4 text-[#10b981] mr-1"></i>
                                                <span class="text-sm">Hotel</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap text-sm text-[#1e293b]">
                                        @if($booking->type == 'flight')
                                            {{ $booking->departure_city ?? '' }} to {{ $booking->arrival_city ?? '' }}
                                        @else
                                            {{ $booking->hotel_name ?? '' }}
                                        @endif
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
                                            @if($booking->status == 'pending')
                                                <button onclick="cancelBooking({{ $booking->id }})" class="text-[#ef4444] hover:text-[#dc2626]">
                                                    <i data-lucide="x-circle" class="h-4 w-4"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-5 py-6 text-center text-[#64748b]">
                                        <div class="flex flex-col items-center">
                                            <i data-lucide="calendar-x" class="h-10 w-10 text-[#94a3b8] mb-2"></i>
                                            <p>No booking records found</p>
                                            <p class="text-sm text-[#94a3b8] mt-1">Try changing filters or book a trip!</p>
                                            <a href="{{ route('flights.search') }}" class="mt-3 inline-flex items-center bg-[#2563eb] text-white py-2 px-4 rounded-lg hover:bg-[#1d4ed8] transition-all duration-200">
                                                <i data-lucide="plane" class="h-4 w-4 mr-1"></i>
                                                <span>Book a Flight</span>
                                            </a>
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
                    <h3 class="font-semibold text-[#1e293b]">My Travel Calendar</h3>
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
                <h3 class="font-semibold text-[#1e293b] mb-4">My Booking Distribution</h3>
                <canvas id="bookingDistributionChart" height="200"></canvas>
            </div>
            
            <!-- Spending Trends -->
            <div class="bg-white rounded-xl shadow-sm border border-[#e2e8f0] p-5">
                <h3 class="font-semibold text-[#1e293b] mb-4">My Spending Trends</h3>
                <canvas id="spendingTrendsChart" height="200"></canvas>
            </div>
            
            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-[#e2e8f0] p-5">
                <h3 class="font-semibold text-[#1e293b] mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('flights.search') }}" class="flex items-center justify-between p-3 bg-[#f1f5f9] rounded-lg hover:bg-[#e2e8f0] transition-colors duration-200">
                        <div class="flex items-center">
                            <div class="h-8 w-8 rounded-full bg-[#3b82f6] bg-opacity-10 flex items-center justify-center mr-3">
                                <i data-lucide="plane" class="h-4 w-4 text-[#3b82f6]"></i>
                            </div>
                            <span class="text-sm font-medium text-[#1e293b]">Book a Flight</span>
                        </div>
                        <i data-lucide="chevron-right" class="h-4 w-4 text-[#64748b]"></i>
                    </a>
                    <a href="{{ route('hotels.search') }}" class="flex items-center justify-between p-3 bg-[#f1f5f9] rounded-lg hover:bg-[#e2e8f0] transition-colors duration-200">
                        <div class="flex items-center">
                            <div class="h-8 w-8 rounded-full bg-[#10b981] bg-opacity-10 flex items-center justify-center mr-3">
                                <i data-lucide="building" class="h-4 w-4 text-[#10b981]"></i>
                            </div>
                            <span class="text-sm font-medium text-[#1e293b]">Book a Hotel</span>
                        </div>
                        <i data-lucide="chevron-right" class="h-4 w-4 text-[#64748b]"></i>
                    </a>
                    <a href="{{ route('user.dashboard.profile') }}" class="flex items-center justify-between p-3 bg-[#f1f5f9] rounded-lg hover:bg-[#e2e8f0] transition-colors duration-200">
                        <div class="flex items-center">
                            <div class="h-8 w-8 rounded-full bg-[#8b5cf6] bg-opacity-10 flex items-center justify-center mr-3">
                                <i data-lucide="user" class="h-4 w-4 text-[#8b5cf6]"></i>
                            </div>
                            <span class="text-sm font-medium text-[#1e293b]">Update Profile</span>
                        </div>
                        <i data-lucide="chevron-right" class="h-4 w-4 text-[#64748b]"></i>
                    </a>
                </div>
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

<!-- Cancel Booking Confirmation Modal -->
<div id="cancelBookingModal" class="fixed inset-0 bg-black bg-opacity-50 z-[1001] hidden overflow-y-auto">
    <div class="min-h-screen px-4 text-center flex items-center justify-center">
        <div class="bg-white w-full max-w-md rounded-xl shadow-lg p-6 transform transition-all">
            <div class="flex flex-col items-center justify-center mb-4">
                <div class="h-16 w-16 rounded-full bg-[#ef4444] bg-opacity-10 flex items-center justify-center mb-4">
                    <i data-lucide="alert-triangle" class="h-8 w-8 text-[#ef4444]"></i>
                </div>
                <h3 class="text-xl font-semibold text-[#1e293b]">Cancel Booking</h3>
                <p class="text-[#64748b] mt-2 text-center">Are you sure you want to cancel this booking? This action cannot be undone.</p>
            </div>
            
            <div class="flex justify-center mt-6 gap-3">
                <button id="confirmCancelBtn" class="flex items-center gap-2 bg-[#ef4444] text-white py-2 px-6 rounded-lg hover:bg-[#dc2626] transition-all duration-200">
                    <i data-lucide="check" class="w-4 h-4"></i>
                    <span>Yes, Cancel</span>
                </button>
                <button onclick="closeCancelModal()" class="flex items-center gap-2 bg-[#e2e8f0] text-[#64748b] py-2 px-6 rounded-lg hover:bg-[#cbd5e1] transition-all duration-200">
                    <i data-lucide="x" class="w-4 h-4"></i>
                    <span>No, Keep</span>
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
        
        // Spending Trends Chart (similar to Revenue Trends)
        const spendingCtx = document.getElementById('spendingTrendsChart').getContext('2d');
        new Chart(spendingCtx, {
            type: 'line',
            data: {
                labels: revenueLabels,
                datasets: [{
                    label: 'Spending',
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
    });
    
    // Apply filters function
    function applyFilters() {
        const period = document.getElementById('periodFilter').value;
        const type = document.getElementById('typeFilter').value;
        const status = document.getElementById('statusFilter').value;
        const payment = document.getElementById('paymentFilter').value;
        
        window.location.href = `{{ route('user.bookings.all') }}?period=${period}&type=${type}&status=${status}&payment=${payment}`;
    }
    
    // Export data function
    function exportData(format) {
        const period = document.getElementById('periodFilter').value;
        const type = document.getElementById('typeFilter').value;
        const status = document.getElementById('statusFilter').value;
        const payment = document.getElementById('paymentFilter').value;
        
        window.open(`{{ route('gdpr.export-data') }}?format=${format}&period=${period}&type=${type}&status=${status}&payment=${payment}`, '_blank');
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
    
    // Cancel booking
    function cancelBooking(bookingId) {
        const modal = document.getElementById('cancelBookingModal');
        const confirmBtn = document.getElementById('confirmCancelBtn');
        
        // Show modal
        modal.classList.remove('hidden');
        
        // Set up confirm button
        confirmBtn.onclick = function() {
            // Send cancel request
            fetch(`/dashboard/booking/${bookingId}/cancel`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeCancelModal();
                    // Refresh the page to show updated status
                    window.location.reload();
                } else {
                    alert('Failed to cancel booking: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error cancelling booking:', error);
                alert('An error occurred. Please try again.');
            });
        };
    }
    
    // Close cancel modal
    function closeCancelModal() {
        document.getElementById('cancelBookingModal').classList.add('hidden');
    }
    
    // Close modals when clicking outside
    document.getElementById('bookingDetailsModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
    
    document.getElementById('cancelBookingModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeCancelModal();
        }
    });
</script>
@endsection