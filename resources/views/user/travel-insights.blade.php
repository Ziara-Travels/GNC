@extends('layouts.user-dashboard')

@section('title', 'Travel Insights')

@section('content')
<div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

    <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">

        <!-- Header -->
        <div class="trezo-card-header mb-[20px] md:mb-[25px] flex items-center justify-between flex-wrap gap-3">
            <div class="trezo-card-title">
                <h5 class="mb-0 md:text-xl font-medium text-gray-800 dark:text-white flex items-center">
                    <i class="ri-compass-3-line mr-2 text-[var(--maroon)]"></i>
                    Your Travel Insights
                    <span class="ml-3 bg-[var(--gold-pale)] text-[var(--maroon)] text-xs font-medium px-2 py-1 rounded-full">PERSONALIZED</span>
                </h5>
                <p class="text-gray-600 dark:text-gray-300 text-sm mt-1">
                    Discover your travel patterns and get personalized recommendations based on your booking history
                </p>
            </div>
            <div class="flex space-x-2">
                <button class="text-white px-4 py-2 rounded-md transition-colors flex items-center gap-1" style="background:linear-gradient(135deg,var(--maroon),var(--maroon-deep));">
                    <i class="ri-download-line mr-2"></i>
                    Export Report
                </button>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-primary text-[var(--maroon)] border-[var(--maroon)]">
                    <i class="ri-arrow-left-line mr-2"></i>
                    Back to Dashboard
                </a>
            </div>
        </div>

        <!-- Travel Overview Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-[25px] mb-[25px]">

            <!-- Travel Frequency -->
            <div class="trezo-card border border-[var(--gold-border)] p-[20px] md:p-[25px] rounded-xl" style="background:linear-gradient(135deg,var(--maroon-deep),var(--maroon));">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-[var(--gold)] text-[var(--maroon-deep)] rounded-full p-3">
                        <i class="ri-flight-takeoff-line text-xl"></i>
                    </div>
                    <span class="text-[var(--gold-bright)] text-xs font-medium bg-white/15 px-2 py-1 rounded-full">FREQUENCY</span>
                </div>
                <h6 class="text-lg font-semibold text-white mb-2">Travel Frequency</h6>
                <p class="text-2xl font-bold text-[var(--gold-bright)] mb-1">{{ $travelInsights['travel_frequency'] ?? 'New Traveler' }}</p>
                <p class="text-sm text-white/80">Based on your booking history</p>
            </div>

            <!-- Average Trip Cost -->
            <div class="trezo-card border border-[var(--gold-border)] p-[20px] md:p-[25px] rounded-xl" style="background:linear-gradient(135deg,#8f4b34,#6b1d15);">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-white text-[var(--maroon)] rounded-full p-3">
                        <i class="ri-money-dollar-circle-line text-xl"></i>
                    </div>
                    <span class="text-[var(--zt-gold-bright,#f3d24a)] text-xs font-medium bg-white/15 px-2 py-1 rounded-full">BUDGET</span>
                </div>
                <h6 class="text-lg font-semibold text-white mb-2">Average Trip Cost</h6>
                <p class="text-2xl font-bold text-[var(--gold-bright)] mb-1">${{ number_format($travelInsights['average_trip_cost'] ?? 0, 0) }}</p>
                <p class="text-sm text-white/80">Average spend per booking</p>
            </div>

            <!-- Countries Visited -->
            <div class="trezo-card border border-[var(--gold-border)] p-[20px] md:p-[25px] rounded-xl" style="background:linear-gradient(135deg,#a3552d,#7b2a1b);">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-[var(--gold)] text-[var(--maroon-deep)] rounded-full p-3">
                        <i class="ri-earth-line text-xl"></i>
                    </div>
                    <span class="text-[var(--gold-bright)] text-xs font-medium bg-white/15 px-2 py-1 rounded-full">FOOTPRINT</span>
                </div>
                <h6 class="text-lg font-semibold text-white mb-2">Countries Visited</h6>
                <p class="text-2xl font-bold text-[var(--gold-bright)] mb-1">{{ $travelInsights['total_countries_visited'] ?? 0 }}</p>
                <p class="text-sm text-white/80">Unique countries explored</p>
            </div>

            <!-- Favorite Month -->
            <div class="trezo-card border border-[var(--gold-border)] p-[20px] md:p-[25px] rounded-xl" style="background:linear-gradient(135deg,#7d3a2a,#5a1810);">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-white text-[var(--maroon)] rounded-full p-3">
                        <i class="ri-calendar-2-line text-xl"></i>
                    </div>
                    <span class="text-[var(--zt-gold-bright,#f3d24a)] text-xs font-medium bg-white/15 px-2 py-1 rounded-full">SEASON</span>
                </div>
                <h6 class="text-lg font-semibold text-white mb-2">Favorite Month</h6>
                <p class="text-2xl font-bold text-[var(--gold-bright)] mb-1">{{ $travelInsights['favorite_month'] ?? 'N/A' }}</p>
                <p class="text-sm text-white/80">{{ $travelInsights['total_cities_visited'] ?? 0 }} cities visited in total</p>
            </div>

        </div>

        <!-- Travel Patterns Analysis -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-[25px] mb-[25px]">

            <!-- Booking Patterns -->
            <div class="trezo-card border border-gray-100 dark:border-[#172036] p-[20px] md:p-[25px] rounded-md">
                <div class="trezo-card-header mb-[20px]">
                    <h5 class="mb-0 flex items-center">
                        <i class="ri-line-chart-line mr-2 text-[var(--maroon)]"></i>
                        Spending &amp; Impact
                    </h5>
                </div>
                <div class="trezo-card-content">
                    <div class="space-y-4">
                        <div class="p-4 rounded-lg" style="background:var(--maroon-pale,#f7ece7);">
                            <h6 class="font-semibold text-gray-800 dark:text-white mb-2">Travel Style</h6>
                            <p class="text-lg font-bold text-[var(--maroon)]">{{ $travelInsights['travel_style'] ?? 'Balanced Traveler' }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-300">Computed from your average spend and booking habits</p>
                        </div>

                        <div class="p-4 rounded-lg" style="background:var(--gold-pale);">
                            <h6 class="font-semibold text-gray-800 dark:text-white mb-2">Money Saved</h6>
                            <p class="text-lg font-bold text-[var(--maroon)]">${{ number_format($travelInsights['money_saved'] ?? 0, 2) }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-300">Estimated savings from booking through Ziara Travel</p>
                        </div>

                        <div class="p-4 rounded-lg" style="background:var(--maroon-pale,#f7ece7);">
                            <h6 class="font-semibold text-gray-800 dark:text-white mb-2">Carbon Footprint</h6>
                            <p class="text-lg font-bold text-[var(--maroon)]">{{ $travelInsights['carbon_footprint'] ?? '0 tons CO2' }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-300">Estimated emissions from your flight bookings</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Destination Insights -->
            <div class="trezo-card border border-gray-100 dark:border-[#172036] p-[20px] md:p-[25px] rounded-md">
                <div class="trezo-card-header mb-[20px]">
                    <h5 class="mb-0 flex items-center">
                        <i class="ri-map-pin-line mr-2 text-[var(--maroon)]"></i>
                        Destination Insights
                    </h5>
                </div>
                <div class="trezo-card-content">
                    <div class="space-y-3">
                        @if(empty($travelInsights['favorite_destinations']))
                            <div class="p-4 rounded-lg" style="background:var(--gold-pale);text-align:center;">
                                <p class="mb-1 font-semibold text-gray-800 dark:text-white">No confirmed destinations yet</p>
                                <p class="text-sm text-gray-600 dark:text-gray-300">Your confirmed destinations will appear here once you start booking</p>
                            </div>
                        @else
                            @foreach(array_slice($travelInsights['favorite_destinations'], 0, 3) as $index => $destination)
                            <div class="flex items-center justify-between p-3 rounded-lg" style="background:{{ $index % 2 === 0 ? 'var(--gold-pale)' : 'var(--maroon-pale,#f7ece7)' }};">
                                <span class="flex items-center gap-3">
                                    <span class="w-8 h-8 rounded-full inline-flex items-center justify-center font-semibold text-white text-sm" style="background:linear-gradient(135deg,var(--maroon),var(--maroon-deep));">#{{ $index + 1 }}</span>
                                    <span class="font-medium text-gray-800 dark:text-white">{{ $destination }}</span>
                                </span>
                                <i class="ri-map-pin-2-line text-[var(--maroon)]"></i>
                            </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

        </div>

        <!-- Personalized Recommendations -->
        <div class="trezo-card border border-gray-100 dark:border-[#172036] p-[20px] md:p-[25px] rounded-md mb-[25px]">
            <div class="trezo-card-header mb-[20px]">
                <h5 class="mb-0 flex items-center">
                    <i class="ri-lightbulb-line mr-2 text-[var(--gold-dim)]"></i>
                    Personalized Recommendations
                </h5>
            </div>
            <div class="trezo-card-content">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    <!-- Based on Your Patterns -->
                    <div class="p-6 rounded-lg recommendation-card" style="background:linear-gradient(135deg,var(--maroon-deep),var(--maroon));">
                        <i class="ri-user-heart-line text-3xl text-[var(--gold-bright)] mb-4"></i>
                        <h6 class="font-semibold text-white mb-3">Your Travel Style</h6>
                        <ul class="space-y-2 text-sm list-none pl-0">
                            <li class="flex items-start">
                                <i class="ri-check-line text-[var(--gold-bright)] mr-2 mt-0.5"></i>
                                <span class="text-white/90">You travel like a <strong>{{ $travelInsights['travel_style'] ?? 'Balanced Traveler' }}</strong></span>
                            </li>
                            <li class="flex items-start">
                                <i class="ri-check-line text-[var(--gold-bright)] mr-2 mt-0.5"></i>
                                <span class="text-white/90">Your most active booking month is <strong>{{ $travelInsights['favorite_month'] ?? 'N/A' }}</strong></span>
                            </li>
                            <li class="flex items-start">
                                <i class="ri-check-line text-[var(--gold-bright)] mr-2 mt-0.5"></i>
                                <span class="text-white/90">You have visited <strong>{{ $travelInsights['total_countries_visited'] ?? 0 }}</strong> countries so far</span>
                            </li>
                        </ul>
                    </div>

                    <!-- You Might Love -->
                    <div class="p-6 rounded-lg recommendation-card" style="background:linear-gradient(135deg,#8f4b34,#6b1d15);">
                        <i class="ri-map-2-line text-3xl text-[var(--gold-bright)] mb-4"></i>
                        <h6 class="font-semibold text-white mb-3">Top Destinations</h6>
                        <ul class="space-y-2 text-sm list-none pl-0">
                            @forelse(array_slice($travelInsights['favorite_destinations'] ?? [], 0, 3) as $destination)
                            <li class="flex items-start">
                                <i class="ri-map-pin-line text-[var(--gold-bright)] mr-2 mt-0.5"></i>
                                <span class="text-white/90">{{ $destination }}</span>
                            </li>
                            @empty
                            <li class="flex items-start">
                                <i class="ri-map-pin-line text-[var(--gold-bright)] mr-2 mt-0.5"></i>
                                <span class="text-white/90">Start exploring new places once you make your first confirmed booking</span>
                            </li>
                            @endforelse
                        </ul>
                    </div>

                    <!-- Save Money -->
                    <div class="p-6 rounded-lg recommendation-card" style="background:linear-gradient(135deg,#7d3a2a,#5a1810);">
                        <i class="ri-money-dollar-circle-line text-3xl text-[var(--gold-bright)] mb-4"></i>
                        <h6 class="font-semibold text-white mb-3">Your Savings</h6>
                        <ul class="space-y-2 text-sm list-none pl-0">
                            <li class="flex items-start">
                                <i class="ri-coins-line text-[var(--gold-bright)] mr-2 mt-0.5"></i>
                                <span class="text-white/90">You have saved approximately <strong>${{ number_format($travelInsights['money_saved'] ?? 0, 2) }}</strong> with Ziara</span>
                            </li>
                            <li class="flex items-start">
                                <i class="ri-coins-line text-[var(--gold-bright)] mr-2 mt-0.5"></i>
                                <span class="text-white/90">Your estimated carbon footprint is <strong>{{ $travelInsights['carbon_footprint'] ?? '0 tons CO2' }}</strong></span>
                            </li>
                            <li class="flex items-start">
                                <i class="ri-coins-line text-[var(--gold-bright)] mr-2 mt-0.5"></i>
                                <span class="text-white/90">Book bundles to unlock <strong>extra discounts</strong> on future trips</span>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>

        <!-- Travel Timeline -->
        <div class="trezo-card border border-gray-100 dark:border-[#172036] p-[20px] md:p-[25px] rounded-md">
            <div class="trezo-card-header mb-[20px]">
                <h5 class="mb-0 flex items-center">
                    <i class="ri-timeline-line mr-2 text-[var(--maroon)]"></i>
                    Your Travel Journey
                </h5>
            </div>
            <div class="trezo-card-content">
                <div class="relative">
                    <!-- Timeline -->
                    <div class="absolute left-4 top-0 bottom-0 w-0.5" style="background:linear-gradient(180deg,var(--gold),var(--line));"></div>

                    <div class="space-y-6">
                        @php
                            $travelMilestones = [
                                ['date' => 'First Steps', 'event' => 'Created your Ziara Travel account', 'type' => 'account', 'color' => 'maroon'],
                                ['date' => 'Booking', 'event' => 'Made your first travel booking', 'type' => 'boat', 'color' => 'maroon'],
                                ['date' => 'Frequency', 'event' => 'Your current travel frequency is ' . ($travelInsights['travel_frequency'] ?? 'New Traveler'), 'type' => 'milestone', 'color' => 'maroon'],
                                ['date' => 'Your Style', 'event' => 'Detected as a ' . ($travelInsights['travel_style'] ?? 'Balanced Traveler'), 'type' => 'star', 'color' => 'maroon']
                            ];
                        @endphp

                        @foreach($travelMilestones as $milestone)
                        <div class="relative flex items-start space-x-4 timeline-item">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center relative z-10" style="background:linear-gradient(135deg,var(--maroon),var(--maroon-deep));">
                                @if($milestone['type'] === 'boat')
                                    <i class="ri-sailboat-line text-white text-sm"></i>
                                @elseif($milestone['type'] === 'milestone')
                                    <i class="ri-award-line text-white text-sm"></i>
                                @elseif($milestone['type'] === 'star')
                                    <i class="ri-star-line text-white text-sm"></i>
                                @else
                                    <i class="ri-user-smile-line text-white text-sm"></i>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between flex-wrap gap-2">
                                    <p class="text-sm font-medium text-gray-800 dark:text-white mb-0">{{ $milestone['event'] }}</p>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize travel insights features
    console.log('Travel insights page loaded');

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
@endsection