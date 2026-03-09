@extends('layouts.admin-dashboard')

@section('content')
<div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">
    <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
        <div class="trezo-card-header mb-[20px] md:mb-[25px]">
            <h3 class="text-[20px] md:text-[24px] font-bold text-[#181818] dark:text-white mb-2">
                Admin Flight Booking
            </h3>
            <p class="text-gray-600 dark:text-gray-400">
                Book flights for yourself, other admins, or users from your dashboard
            </p>
        </div>

        <div class="trezo-card-content">
            <!-- Booking Type Selection -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                    Who are you booking for?
                </label>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="booking-type-card" data-type="self">
                        <input type="radio" name="booking_for" value="self" id="booking_self" class="sr-only" checked>
                        <label for="booking_self" class="flex flex-col items-center p-4 border-2 border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                            <i class="material-symbols-outlined text-2xl text-blue-600 dark:text-blue-400 mb-2">person</i>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">Book for Myself</span>
                        </label>
                    </div>
                    <div class="booking-type-card" data-type="single">
                        <input type="radio" name="booking_for" value="single" id="booking_single" class="sr-only">
                        <label for="booking_single" class="flex flex-col items-center p-4 border-2 border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                            <i class="material-symbols-outlined text-2xl text-green-600 dark:text-green-400 mb-2">person_add</i>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">Book for Someone</span>
                        </label>
                    </div>
                    <div class="booking-type-card" data-type="bulk">
                        <input type="radio" name="booking_for" value="bulk" id="booking_bulk" class="sr-only">
                        <label for="booking_bulk" class="flex flex-col items-center p-4 border-2 border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                            <i class="material-symbols-outlined text-2xl text-purple-600 dark:text-purple-400 mb-2">groups</i>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">Bulk Booking</span>
                        </label>
                    </div>
                </div>
            </div>

            <form id="admin-flight-booking-form" action="{{ route('admin.flights.book') }}" method="POST" class="space-y-6">
                @csrf

                <!-- User Selection (hidden for self booking) -->
                <div id="user-selection" class="hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Single User Selection -->
                        <div id="single-user-selection" class="hidden">
                            <label for="book_for_user_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Select User
                            </label>
                            <select name="book_for_user_id" id="book_for_user_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white">
                                <option value="">Select a user...</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Bulk User Selection -->
                        <div id="bulk-user-selection" class="hidden md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Select Users (Multiple)
                            </label>
                            <div class="max-h-40 overflow-y-auto border border-gray-300 dark:border-gray-600 rounded-md p-3 bg-gray-50 dark:bg-gray-800">
                                @foreach($users as $user)
                                    <label class="flex items-center mb-2">
                                        <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" 
                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <span class="ml-2 text-sm text-gray-900 dark:text-white">
                                            {{ $user->name }} ({{ $user->email }})
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Flight Search Form -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label for="origin" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            From
                        </label>
                        <input type="text" name="origin" id="origin" placeholder="Airport code (e.g., NBO)" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white" required>
                    </div>
                    <div>
                        <label for="destination" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            To
                        </label>
                        <input type="text" name="destination" id="destination" placeholder="Airport code (e.g., LHR)" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white" required>
                    </div>
                    <div>
                        <label for="departure_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Departure
                        </label>
                        <input type="date" name="departure_date" id="departure_date" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white" required>
                    </div>
                    <div>
                        <label for="return_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Return (Optional)
                        </label>
                        <input type="date" name="return_date" id="return_date" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="adults" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Adults
                        </label>
                        <select name="adults" id="adults" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white" required>
                            @for($i = 1; $i <= 9; $i++)
                                <option value="{{ $i }}" {{ $i == 1 ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label for="children" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Children
                        </label>
                        <select name="children" id="children" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white">
                            @for($i = 0; $i <= 9; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label for="infants" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Infants
                        </label>
                        <select name="infants" id="infants" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white">
                            @for($i = 0; $i <= 9; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label for="travel_class" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Class
                        </label>
                        <select name="travel_class" id="travel_class" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white" required>
                            <option value="ECONOMY">Economy</option>
                            <option value="PREMIUM_ECONOMY">Premium Economy</option>
                            <option value="BUSINESS">Business</option>
                            <option value="FIRST">First Class</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="px-6 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <i class="material-symbols-outlined text-sm mr-2">search</i>
                        Search Flights
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const bookingTypeInputs = document.querySelectorAll('input[name="booking_for"]');
    const userSelection = document.getElementById('user-selection');
    const singleUserSelection = document.getElementById('single-user-selection');
    const bulkUserSelection = document.getElementById('bulk-user-selection');

    // Set minimum date to today
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('departure_date').min = today;
    document.getElementById('return_date').min = today;

    // Handle booking type changes
    bookingTypeInputs.forEach(input => {
        input.addEventListener('change', function() {
            // Update card styles
            document.querySelectorAll('.booking-type-card label').forEach(label => {
                label.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/30');
                label.classList.add('border-gray-200', 'dark:border-gray-700');
            });

            if (this.checked) {
                const label = document.querySelector(`label[for="${this.id}"]`);
                label.classList.remove('border-gray-200', 'dark:border-gray-700');
                label.classList.add('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/30');
            }

            // Show/hide user selection based on type
            if (this.value === 'self') {
                userSelection.classList.add('hidden');
                singleUserSelection.classList.add('hidden');
                bulkUserSelection.classList.add('hidden');
            } else if (this.value === 'single') {
                userSelection.classList.remove('hidden');
                singleUserSelection.classList.remove('hidden');
                bulkUserSelection.classList.add('hidden');
            } else if (this.value === 'bulk') {
                userSelection.classList.remove('hidden');
                singleUserSelection.classList.add('hidden');
                bulkUserSelection.classList.remove('hidden');
            }
        });
    });

    // Handle return date validation
    document.getElementById('departure_date').addEventListener('change', function() {
        const returnDate = document.getElementById('return_date');
        returnDate.min = this.value;
        if (returnDate.value && returnDate.value < this.value) {
            returnDate.value = '';
        }
    });

    // Initialize with default selection
    document.getElementById('booking_self').click();
});
</script>
@endsection
