@extends('layouts.user-dashboard')

@section('title', 'Travel Insights')

@section('content')
<div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">
    
    <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
        
        <!-- Header -->
        <div class="trezo-card-header mb-[20px] md:mb-[25px] flex items-center justify-between">
            <div class="trezo-card-title">
                <h5 class="mb-0 md:text-xl font-medium text-gray-800 dark:text-white flex items-center">
                    <i class="ri-compass-3-line mr-2 text-blue-500"></i>
                    Your Travel Insights
                    <span class="ml-3 bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded-full">PERSONALIZED</span>
                </h5>
                <p class="text-gray-600 dark:text-gray-300 text-sm mt-1">
                    Discover your travel patterns and get personalized recommendations based on your booking history
                </p>
            </div>
            <div class="flex space-x-2">
                <button class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition-colors">
                    <i class="ri-download-line mr-2"></i>
                    Export Report
                </button>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-primary">
                    <i class="ri-arrow-left-line mr-2"></i>
                    Back to Dashboard
                </a>
            </div>
        </div>

        <!-- Travel Overview Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-[25px] mb-[25px]">
            
            <!-- Travel Frequency -->
            <div class="trezo-card border border-blue-200 dark:border-blue-800 p-[20px] md:p-[25px] rounded-xl bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900 dark:to-blue-800">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-blue-500 text-white rounded-full p-3">
                        <i class="ri-flight-takeoff-line text-xl"></i>
                    </div>
                    <span class="text-blue-600 dark:text-blue-300 text-xs font-medium bg-blue-200 dark:bg-blue-700 px-2 py-1 rounded-full">FREQUENCY</span>
                </div>
                <h6 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Travel Style</h6>
                <p class="text-2xl font-bold text-blue-600 mb-1">{{ $travelInsights['travel_frequency'] ?? 'Regular Traveler' }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-300">{{ $travelInsights['trips_per_year'] ?? 8 }} trips per year</p>
            </div>

            <!-- Average Trip Cost -->
            <div class="trezo-card border border-green-200 dark:border-green-800 p-[20px] md:p-[25px] rounded-xl bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900 dark:to-green-800">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-green-500 text-white rounded-full p-3">
                        <i class="ri-money-dollar-circle-line text-xl"></i>
                    </div>
                    <span class="text-green-600 dark:text-green-300 text-xs font-medium bg-green-200 dark:bg-green-700 px-2 py-1 rounded-full">BUDGET</span>
                </div>
                <h6 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Avg Trip Cost</h6>
                <p class="text-2xl font-bold text-green-600 mb-1">${{ number_format($travelInsights['avg_trip_cost'] ?? 650, 0) }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-300">{{ $travelInsights['budget_category'] ?? 'Mid-range traveler' }}</p>
            </div>

            <!-- Favorite Season -->
            <div class="trezo-card border border-orange-200 dark:border-orange-800 p-[20px] md:p-[25px] rounded-xl bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900 dark:to-orange-800">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-orange-500 text-white rounded-full p-3">
                        <i class="ri-sun-line text-xl"></i>
                    </div>
                    <span class="text-orange-600 dark:text-orange-300 text-xs font-medium bg-orange-200 dark:bg-orange-700 px-2 py-1 rounded-full">SEASON</span>
                </div>
                <h6 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Favorite Season</h6>
                <p class="text-2xl font-bold text-orange-600 mb-1">{{ $travelInsights['favorite_season'] ?? 'Summer' }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-300">{{ $travelInsights['season_trips'] ?? 4 }} trips in this season</p>
            </div>

            <!-- Carbon Footprint -->
            <div class="trezo-card border border-purple-200 dark:border-purple-800 p-[20px] md:p-[25px] rounded-xl bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900 dark:to-purple-800">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-purple-500 text-white rounded-full p-3">
                        <i class="ri-leaf-line text-xl"></i>
                    </div>
                    <span class="text-purple-600 dark:text-purple-300 text-xs font-medium bg-purple-200 dark:bg-purple-700 px-2 py-1 rounded-full">ECO</span>
                </div>
                <h6 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Carbon Impact</h6>
                <p class="text-2xl font-bold text-purple-600 mb-1">{{ $travelInsights['carbon_footprint'] ?? '2.4' }} tons</p>
                <p class="text-sm text-gray-600 dark:text-gray-300">CO₂ from travel this year</p>
            </div>

        </div>

        <!-- Travel Patterns Analysis -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-[25px] mb-[25px]">
            
            <!-- Booking Patterns -->
            <div class="trezo-card border border-gray-100 dark:border-[#172036] p-[20px] md:p-[25px] rounded-md">
                <div class="trezo-card-header mb-[20px]">
                    <h5 class="mb-0 flex items-center">
                        <i class="ri-calendar-line mr-2 text-blue-500"></i>
                        Booking Patterns
                    </h5>
                </div>
                <div class="trezo-card-content">
                    <div class="space-y-4">
                        <div class="bg-blue-50 dark:bg-blue-900 p-4 rounded-lg">
                            <h6 class="font-semibold text-gray-800 dark:text-white mb-2">Advance Booking Habit</h6>
                            <p class="text-blue-600 text-lg font-bold">{{ $travelInsights['avg_booking_advance'] ?? '21 days' }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-300">You typically book {{ $travelInsights['avg_booking_advance'] ?? '21 days' }} in advance</p>
                        </div>
                        
                        <div class="bg-green-50 dark:bg-green-900 p-4 rounded-lg">
                            <h6 class="font-semibold text-gray-800 dark:text-white mb-2">Preferred Booking Day</h6>
                            <p class="text-green-600 text-lg font-bold">{{ $travelInsights['preferred_booking_day'] ?? 'Tuesday' }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-300">Most of your bookings are made on this day</p>
                        </div>
                        
                        <div class="bg-purple-50 dark:bg-purple-900 p-4 rounded-lg">
                            <h6 class="font-semibold text-gray-800 dark:text-white mb-2">Trip Duration Preference</h6>
                            <p class="text-purple-600 text-lg font-bold">{{ $travelInsights['avg_trip_duration'] ?? '5 days' }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-300">Your average trip length</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Destination Preferences -->
            <div class="trezo-card border border-gray-100 dark:border-[#172036] p-[20px] md:p-[25px] rounded-md">
                <div class="trezo-card-header mb-[20px]">
                    <h5 class="mb-0 flex items-center">
                        <i class="ri-map-pin-line mr-2 text-green-500"></i>
                        Destination Insights
                    </h5>
                </div>
                <div class="trezo-card-content">
                    <div class="space-y-4">
                        @php
                            $destinationPrefs = [
                                ['type' => 'Most Visited Region', 'value' => $travelInsights['favorite_region'] ?? 'East Africa', 'color' => 'blue'],
                                ['type' => 'Preferred Climate', 'value' => $travelInsights['preferred_climate'] ?? 'Tropical', 'color' => 'orange'],
                                ['type' => 'Destination Type', 'value' => $travelInsights['destination_type'] ?? 'Business & Leisure Mix', 'color' => 'purple'],
                                ['type' => 'Travel Distance', 'value' => $travelInsights['avg_travel_distance'] ?? '1,200 km average', 'color' => 'green']
                            ];
                        @endphp
                        
                        @foreach($destinationPrefs as $pref)
                        <div class="flex items-center justify-between p-3 bg-{{ $pref['color'] }}-50 dark:bg-{{ $pref['color'] }}-900 rounded-lg">
                            <span class="font-medium text-gray-800 dark:text-white">{{ $pref['type'] }}</span>
                            <span class="text-{{ $pref['color'] }}-600 font-semibold">{{ $pref['value'] }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>

        <!-- Personalized Recommendations -->
        <div class="trezo-card border border-gray-100 dark:border-[#172036] p-[20px] md:p-[25px] rounded-md mb-[25px]">
            <div class="trezo-card-header mb-[20px]">
                <h5 class="mb-0 flex items-center">
                    <i class="ri-lightbulb-line mr-2 text-yellow-500"></i>
                    Personalized Recommendations
                </h5>
            </div>
            <div class="trezo-card-content">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    
                    <!-- Based on Your Patterns -->
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900 dark:to-blue-800 p-6 rounded-lg">
                        <i class="ri-user-heart-line text-3xl text-blue-500 mb-4"></i>
                        <h6 class="font-semibold text-gray-800 dark:text-white mb-3">For You</h6>
                        <ul class="space-y-2 text-sm">
                            <li class="flex items-start">
                                <i class="ri-check-line text-blue-500 mr-2 mt-0.5"></i>
                                <span>{{ $travelInsights['recommendations']['personal'][0] ?? 'Consider booking flights on Tuesday for better deals' }}</span>
                            </li>
                            <li class="flex items-start">
                                <i class="ri-check-line text-blue-500 mr-2 mt-0.5"></i>
                                <span>{{ $travelInsights['recommendations']['personal'][1] ?? 'Try extending your trips to 7-10 days for better value' }}</span>
                            </li>
                            <li class="flex items-start">
                                <i class="ri-check-line text-blue-500 mr-2 mt-0.5"></i>
                                <span>{{ $travelInsights['recommendations']['personal'][2] ?? 'Book accommodation 3 weeks in advance for optimal prices' }}</span>
                            </li>
                        </ul>
                    </div>

                    <!-- New Destinations -->
                    <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900 dark:to-green-800 p-6 rounded-lg">
                        <i class="ri-map-2-line text-3xl text-green-500 mb-4"></i>
                        <h6 class="font-semibold text-gray-800 dark:text-white mb-3">Explore New</h6>
                        <ul class="space-y-2 text-sm">
                            @php
                                $newDestinations = $travelInsights['recommendations']['destinations'] ?? [
                                    'Zanzibar - Similar to your coastal preferences',
                                    'Rwanda - Great for eco-tourism experiences',
                                    'Seychelles - Perfect for luxury getaways'
                                ];
                            @endphp
                            @foreach($newDestinations as $destination)
                            <li class="flex items-start">
                                <i class="ri-map-pin-line text-green-500 mr-2 mt-0.5"></i>
                                <span>{{ $destination }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Cost Optimization -->
                    <div class="bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900 dark:to-orange-800 p-6 rounded-lg">
                        <i class="ri-money-dollar-circle-line text-3xl text-orange-500 mb-4"></i>
                        <h6 class="font-semibold text-gray-800 dark:text-white mb-3">Save Money</h6>
                        <ul class="space-y-2 text-sm">
                            @php
                                $savingTips = $travelInsights['recommendations']['savings'] ?? [
                                    'Travel in May-June for 30% cheaper flights',
                                    'Use our price alerts for your favorite routes',
                                    'Consider midweek departures for lower costs'
                                ];
                            @endphp
                            @foreach($savingTips as $tip)
                            <li class="flex items-start">
                                <i class="ri-coins-line text-orange-500 mr-2 mt-0.5"></i>
                                <span>{{ $tip }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                </div>
            </div>
        </div>

        <!-- Travel Timeline -->
        <div class="trezo-card border border-gray-100 dark:border-[#172036] p-[20px] md:p-[25px] rounded-md">
            <div class="trezo-card-header mb-[20px]">
                <h5 class="mb-0 flex items-center">
                    <i class="ri-timeline-line mr-2 text-indigo-500"></i>
                    Your Travel Journey
                </h5>
            </div>
            <div class="trezo-card-content">
                <div class="relative">
                    <!-- Timeline -->
                    <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-200 dark:bg-gray-700"></div>
                    
                    <div class="space-y-6">
                        @php
                            $travelMilestones = [
                                ['date' => 'Jan 2024', 'event' => 'First international trip - Dubai', 'type' => 'flight', 'color' => 'blue'],
                                ['date' => 'Mar 2024', 'event' => 'Discovered coastal destinations - Mombasa', 'type' => 'hotel', 'color' => 'green'],
                                ['date' => 'Jun 2024', 'event' => 'Started business travel pattern', 'type' => 'business', 'color' => 'purple'],
                                ['date' => 'Aug 2024', 'event' => 'Became a frequent traveler (10+ trips)', 'type' => 'milestone', 'color' => 'orange']
                            ];
                        @endphp
                        
                        @foreach($travelMilestones as $milestone)
                        <div class="relative flex items-start space-x-4">
                            <div class="flex-shrink-0 w-8 h-8 bg-{{ $milestone['color'] }}-500 rounded-full flex items-center justify-center relative z-10">
                                @if($milestone['type'] === 'flight')
                                    <i class="ri-plane-line text-white text-sm"></i>
                                @elseif($milestone['type'] === 'hotel')
                                    <i class="ri-hotel-line text-white text-sm"></i>
                                @elseif($milestone['type'] === 'business')
                                    <i class="ri-briefcase-line text-white text-sm"></i>
                                @else
                                    <i class="ri-trophy-line text-white text-sm"></i>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-gray-800 dark:text-white">{{ $milestone['event'] }}</p>
                                    <span class="text-xs text-gray-500">{{ $milestone['date'] }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
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
    // Initialize travel insights features
    console.log('Travel insights page loaded');
    
    // Add interactivity for recommendations
    const recommendationCards = document.querySelectorAll('.recommendation-card');
    recommendationCards.forEach(card => {
        card.addEventListener('click', function() {
            // Add click handling for recommendations
            console.log('Recommendation clicked');
        });
    });
    
    // Timeline animations
    const timelineItems = document.querySelectorAll('.timeline-item');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fade-in');
            }
        });
    });
    
    timelineItems.forEach(item => {
        observer.observe(item);
    });
});
</script>

<style>
.animate-fade-in {
    animation: fadeIn 0.6s ease-in-out forwards;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.timeline-item {
    opacity: 0;
    transform: translateY(20px);
    transition: all 0.3s ease;
}

.timeline-item.animate-fade-in {
    opacity: 1;
    transform: translateY(0);
}
</style>
@endsection
