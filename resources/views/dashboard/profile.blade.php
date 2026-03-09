@extends('layouts.user-dashboard')

@section('title', 'Profile & Settings')

@section('content')
<div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">
    
    <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
        
        <!-- Header -->
        <div class="trezo-card-header mb-[20px] md:mb-[25px] flex items-center justify-between">
            <div class="trezo-card-title">
                <h5 class="mb-0 md:text-xl font-medium text-gray-800 dark:text-white">
                    <i class="ri-user-settings-line mr-2"></i>
                    Profile & Settings
                </h5>
                <p class="text-gray-600 dark:text-gray-300 text-sm mt-1">
                    Manage your account preferences and travel settings
                </p>
            </div>
            <a href="{{ route('user.dashboard') }}" class="btn btn-outline-primary">
                <i class="ri-arrow-left-line mr-2"></i>
                Back to Dashboard
            </a>
        </div>

        <!-- Profile Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-[25px]">
            
            <!-- Personal Information -->
            <div class="lg:col-span-2">
                <div class="trezo-card border border-gray-100 dark:border-[#172036] p-[20px] md:p-[25px] rounded-md mb-[25px]">
                    <h6 class="text-lg font-medium mb-4 text-gray-800 dark:text-white">
                        <i class="ri-user-line mr-2"></i>
                        Personal Information
                    </h6>
                    
                    <form class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Full Name</label>
                                <input type="text" value="{{ $user->name }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-white">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email Address</label>
                                <input type="email" value="{{ $user->email }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-white">
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Phone Number</label>
                                <input type="tel" value="{{ $user->phone ?? '' }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-white">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date of Birth</label>
                                <input type="date" value="{{ $user->date_of_birth ?? '' }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-white">
                            </div>
                        </div>

                        <button type="submit" class="bg-primary-500 text-white px-6 py-2 rounded-md hover:bg-primary-600 transition-colors">
                            <i class="ri-save-line mr-2"></i>
                            Update Information
                        </button>
                    </form>
                </div>

                <!-- Travel Preferences -->
                <div class="trezo-card border border-gray-100 dark:border-[#172036] p-[20px] md:p-[25px] rounded-md mb-[25px]">
                    <h6 class="text-lg font-medium mb-4 text-gray-800 dark:text-white">
                        <i class="ri-flight-takeoff-line mr-2"></i>
                        Travel Preferences
                    </h6>
                    
                    <form class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Preferred Class</label>
                                <select class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-white">
                                    <option value="economy" {{ ($travel_preferences['preferred_class'] ?? '') == 'economy' ? 'selected' : '' }}>Economy</option>
                                    <option value="premium" {{ ($travel_preferences['preferred_class'] ?? '') == 'premium' ? 'selected' : '' }}>Premium Economy</option>
                                    <option value="business" {{ ($travel_preferences['preferred_class'] ?? '') == 'business' ? 'selected' : '' }}>Business</option>
                                    <option value="first" {{ ($travel_preferences['preferred_class'] ?? '') == 'first' ? 'selected' : '' }}>First Class</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Seat Preference</label>
                                <select class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-white">
                                    <option value="window" {{ ($travel_preferences['seat_preference'] ?? '') == 'window' ? 'selected' : '' }}>Window</option>
                                    <option value="aisle" {{ ($travel_preferences['seat_preference'] ?? '') == 'aisle' ? 'selected' : '' }}>Aisle</option>
                                    <option value="middle" {{ ($travel_preferences['seat_preference'] ?? '') == 'middle' ? 'selected' : '' }}>Middle</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="bg-primary-500 text-white px-6 py-2 rounded-md hover:bg-primary-600 transition-colors">
                            <i class="ri-save-line mr-2"></i>
                            Save Preferences
                        </button>
                    </form>
                </div>
            </div>

            <!-- Sidebar Stats -->
            <div class="space-y-[25px]">
                <!-- Account Summary -->
                <div class="trezo-card border border-gray-100 dark:border-[#172036] p-[20px] md:p-[25px] rounded-md">
                    <h6 class="text-lg font-medium mb-4 text-gray-800 dark:text-white">
                        <i class="ri-bar-chart-line mr-2"></i>
                        Account Summary
                    </h6>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 dark:text-gray-300">Total Bookings</span>
                            <span class="font-medium text-gray-800 dark:text-white">{{ $stats['total_bookings'] }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 dark:text-gray-300">Total Spent</span>
                            <span class="font-medium text-gray-800 dark:text-white">${{ number_format($stats['total_spent'], 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 dark:text-gray-300">Countries Visited</span>
                            <span class="font-medium text-gray-800 dark:text-white">{{ $stats['countries_visited'] }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 dark:text-gray-300">Member Since</span>
                            <span class="font-medium text-gray-800 dark:text-white">{{ $user->created_at->format('M Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Notification Settings -->
                <div class="trezo-card border border-gray-100 dark:border-[#172036] p-[20px] md:p-[25px] rounded-md">
                    <h6 class="text-lg font-medium mb-4 text-gray-800 dark:text-white">
                        <i class="ri-notification-line mr-2"></i>
                        Notifications
                    </h6>
                    
                    <div class="space-y-3">
                        <label class="flex items-center justify-between cursor-pointer">
                            <span class="text-gray-700 dark:text-gray-300">Email Notifications</span>
                            <input type="checkbox" {{ ($notification_preferences['email_notifications'] ?? true) ? 'checked' : '' }} class="toggle-switch">
                        </label>
                        <label class="flex items-center justify-between cursor-pointer">
                            <span class="text-gray-700 dark:text-gray-300">SMS Notifications</span>
                            <input type="checkbox" {{ ($notification_preferences['sms_notifications'] ?? false) ? 'checked' : '' }} class="toggle-switch">
                        </label>
                        <label class="flex items-center justify-between cursor-pointer">
                            <span class="text-gray-700 dark:text-gray-300">Price Alerts</span>
                            <input type="checkbox" {{ ($notification_preferences['price_alerts'] ?? true) ? 'checked' : '' }} class="toggle-switch">
                        </label>
                    </div>
                </div>

                <!-- Security Settings -->
                <div class="trezo-card border border-gray-100 dark:border-[#172036] p-[20px] md:p-[25px] rounded-md">
                    <h6 class="text-lg font-medium mb-4 text-gray-800 dark:text-white">
                        <i class="ri-shield-check-line mr-2"></i>
                        Security
                    </h6>
                    
                    <div class="space-y-3">
                        <label class="flex items-center justify-between cursor-pointer">
                            <span class="text-gray-700 dark:text-gray-300">Two-Factor Auth</span>
                            <input type="checkbox" {{ ($security_settings['two_factor_enabled'] ?? false) ? 'checked' : '' }} class="toggle-switch">
                        </label>
                        <button class="w-full text-left px-3 py-2 text-sm text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900 rounded-md transition-colors">
                            <i class="ri-key-line mr-2"></i>
                            Change Password
                        </button>
                        <button class="w-full text-left px-3 py-2 text-sm text-green-600 hover:bg-green-50 dark:hover:bg-green-900 rounded-md transition-colors">
                            <i class="ri-download-line mr-2"></i>
                            Download Data
                        </button>
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
    // Handle toggle switches
    const toggleSwitches = document.querySelectorAll('.toggle-switch');
    toggleSwitches.forEach(toggle => {
        toggle.addEventListener('change', function() {
            // Add your toggle functionality here
            console.log('Toggle changed:', this.checked);
        });
    });
});
</script>

<style>
.toggle-switch {
    appearance: none;
    width: 44px;
    height: 24px;
    background-color: #e5e7eb;
    border-radius: 12px;
    position: relative;
    cursor: pointer;
    transition: all 0.3s ease;
}

.toggle-switch:checked {
    background-color: #3b82f6;
}

.toggle-switch::before {
    content: '';
    position: absolute;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background-color: white;
    top: 2px;
    left: 2px;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.toggle-switch:checked::before {
    transform: translateX(20px);
}
</style>
@endsection
