@extends('layouts.user-dashboard')

@section('title', 'Travel Insights')

@section('content')
<div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">
    
    <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
        
        <!-- Header -->
        <div class="trezo-card-header mb-[20px] md:mb-[25px] flex items-center justify-between">
            <div class="trezo-card-title">
                <h5 class="mb-0 md:text-xl font-medium text-gray-800 dark:text-white">
                    <i class="ri-compass-3-line mr-2 text-blue-500"></i>
                    Travel Insights
                </h5>
                <p class="text-gray-600 dark:text-gray-300 text-sm mt-1">
                    Discover personalized travel patterns and recommendations based on your booking history
                </p>
            </div>
            <a href="{{ route('user.dashboard') }}" class="btn btn-outline-primary">
                <i class="ri-arrow-left-line mr-2"></i>
                Back to Dashboard
            </a>
        </div>

        <!-- Insights Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-[25px]">
            
            <!-- Travel Patterns -->
            <div class="trezo-card border border-blue-200 dark:border-blue-800 p-[20px] md:p-[25px] rounded-xl bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900 dark:to-blue-800">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-blue-500 text-white rounded-full p-3">
                        <i class="ri-map-2-line text-xl"></i>
                    </div>
                    <span class="text-blue-600 dark:text-blue-300 text-xs font-medium bg-blue-200 dark:bg-blue-700 px-2 py-1 rounded-full">PATTERNS</span>
                </div>
                <h6 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Travel Patterns</h6>
                <div class="space-y-2 text-sm text-gray-600 dark:text-gray-300">
                    <div class="flex justify-between">
                        <span>Most visited region:</span>
                        <span class="font-medium">East Africa</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Preferred travel time:</span>
                        <span class="font-medium">July-August</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Average trip duration:</span>
                        <span class="font-medium">7 days</span>
                    </div>
                </div>
            </div>

            <!-- Spending Analysis -->
            <div class="trezo-card border border-green-200 dark:border-green-800 p-[20px] md:p-[25px] rounded-xl bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900 dark:to-green-800">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-green-500 text-white rounded-full p-3">
                        <i class="ri-money-dollar-circle-line text-xl"></i>
                    </div>
                    <span class="text-green-600 dark:text-green-300 text-xs font-medium bg-green-200 dark:bg-green-700 px-2 py-1 rounded-full">SPENDING</span>
                </div>
                <h6 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Spending Analysis</h6>
                <div class="space-y-2 text-sm text-gray-600 dark:text-gray-300">
                    <div class="flex justify-between">
                        <span>Avg per trip:</span>
                        <span class="font-medium">${{ number_format($insights['average_spending'] ?? 1200, 0) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Best value month:</span>
                        <span class="font-medium">{{ $insights['best_value_month'] ?? 'February' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Savings potential:</span>
                        <span class="font-medium text-green-600">{{ $insights['savings_potential'] ?? '15%' }}</span>
                    </div>
                </div>
            </div>

            <!-- Recommendations -->
            <div class="trezo-card border border-purple-200 dark:border-purple-800 p-[20px] md:p-[25px] rounded-xl bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900 dark:to-purple-800">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-purple-500 text-white rounded-full p-3">
                        <i class="ri-lightbulb-line text-xl"></i>
                    </div>
                    <span class="text-purple-600 dark:text-purple-300 text-xs font-medium bg-purple-200 dark:bg-purple-700 px-2 py-1 rounded-full">SUGGESTIONS</span>
                </div>
                <h6 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Smart Suggestions</h6>
                <div class="space-y-2 text-sm text-gray-600 dark:text-gray-300">
                    <div>
                        <span class="block font-medium">Based on your travel history:</span>
                        <span>Consider visiting {{ $insights['recommended_destination'] ?? 'Tanzania' }} next</span>
                    </div>
                    <div>
                        <span class="block font-medium">Optimal booking time:</span>
                        <span>{{ $insights['optimal_booking_time'] ?? '6-8 weeks' }} before travel</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- Detailed Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-[25px] mt-[25px]">
            
            <!-- Monthly Spending Trend -->
            <div class="trezo-card border border-gray-100 dark:border-[#172036] p-[20px] md:p-[25px] rounded-md">
                <h6 class="text-lg font-medium mb-4 text-gray-800 dark:text-white">
                    <i class="ri-line-chart-line mr-2"></i>
                    Monthly Spending Trend
                </h6>
                <div class="h-64 flex items-center justify-center bg-gray-50 dark:bg-gray-800 rounded-md">
                    <div class="text-center text-gray-500 dark:text-gray-400">
                        <i class="ri-bar-chart-line text-4xl mb-2"></i>
                        <p>Interactive chart will be displayed here</p>
                        <p class="text-sm">Chart.js integration coming soon</p>
                    </div>
                </div>
            </div>

            <!-- Destination Frequency -->
            <div class="trezo-card border border-gray-100 dark:border-[#172036] p-[20px] md:p-[25px] rounded-md">
                <h6 class="text-lg font-medium mb-4 text-gray-800 dark:text-white">
                    <i class="ri-pie-chart-line mr-2"></i>
                    Destination Frequency
                </h6>
                <div class="space-y-3">
                    @php
                        $destinations = $insights['top_destinations'] ?? [
                            ['name' => 'Kenya', 'visits' => 5, 'percentage' => 35],
                            ['name' => 'Tanzania', 'visits' => 4, 'percentage' => 28],
                            ['name' => 'Uganda', 'visits' => 3, 'percentage' => 21],
                            ['name' => 'Rwanda', 'visits' => 2, 'percentage' => 16]
                        ];
                    @endphp
                    
                    @foreach($destinations as $dest)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-3 h-3 rounded-full bg-primary-500"></div>
                            <span class="text-gray-700 dark:text-gray-300">{{ $dest['name'] }}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-500">{{ $dest['visits'] }} visits</span>
                            <span class="text-sm font-medium text-gray-800 dark:text-white">{{ $dest['percentage'] }}%</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>

        <!-- Action Items -->
        <div class="mt-[25px]">
            <div class="trezo-card border border-yellow-200 dark:border-yellow-800 p-[20px] md:p-[25px] rounded-xl bg-gradient-to-br from-yellow-50 to-yellow-100 dark:from-yellow-900 dark:to-yellow-800">
                <h6 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
                    <i class="ri-lightbulb-flash-line mr-2 text-yellow-500"></i>
                    Personalized Recommendations
                </h6>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg">
                        <h6 class="font-medium text-gray-800 dark:text-white mb-2">
                            <i class="ri-price-tag-3-line mr-2 text-green-500"></i>
                            Cost Optimization
                        </h6>
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            Book flights on Tuesdays to save an average of 12% on your East African routes.
                        </p>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg">
                        <h6 class="font-medium text-gray-800 dark:text-white mb-2">
                            <i class="ri-calendar-check-line mr-2 text-blue-500"></i>
                            Best Time to Travel
                        </h6>
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            Consider traveling in May or November for better prices and fewer crowds.
                        </p>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg">
                        <h6 class="font-medium text-gray-800 dark:text-white mb-2">
                            <i class="ri-star-line mr-2 text-purple-500"></i>
                            New Destinations
                        </h6>
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            Based on your preferences, you might enjoy exploring Ethiopia or Madagascar.
                        </p>
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
    console.log('Travel Insights page loaded');
    
    // Add any interactive functionality here
    // For example, you could load charts dynamically
});
</script>
@endsection
