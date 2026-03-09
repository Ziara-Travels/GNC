@extends('layouts.dashboard')

@section('title', 'Hotel Booking - Admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">Book Hotel</h1>
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="hotelBookingForm" action="{{ route('admin.hotels.book') }}" method="POST" class="space-y-6">
                @csrf
                
                <!-- Booking Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Booking Type</label>
                    <select name="booking_type" id="bookingType" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                        <option value="self">Book for Myself</option>
                        <option value="other">Book for Other User</option>
                        <option value="bulk">Bulk Booking</option>
                    </select>
                </div>

                <!-- User Selection (for other) -->
                <div id="userSelection" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select User</label>
                    <select name="user_id" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Select a user...</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Bulk Users Selection -->
                <div id="bulkUserSelection" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Multiple Users</label>
                    <div class="max-h-32 overflow-y-auto border border-gray-300 rounded-lg p-2">
                        @foreach($users as $user)
                            <label class="flex items-center space-x-2 p-1">
                                <input type="checkbox" name="bulk_user_ids[]" value="{{ $user->id }}" class="rounded">
                                <span class="text-sm">{{ $user->name }} ({{ $user->email }})</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Hotel Search -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">City/Hotel Name</label>
                        <input type="text" name="location" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Enter city or hotel name" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Number of Adults</label>
                        <select name="adults" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                            @for($i = 1; $i <= 10; $i++)
                                <option value="{{ $i }}" {{ $i == 2 ? 'selected' : '' }}>{{ $i }} Adult{{ $i > 1 ? 's' : '' }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Check-in Date</label>
                        <input type="date" name="check_in" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Check-out Date</label>
                        <input type="date" name="check_out" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Number of Rooms</label>
                        <select name="rooms" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                            @for($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}">{{ $i }} Room{{ $i > 1 ? 's' : '' }}</option>
                            @endfor
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Currency</label>
                        <select name="currency" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                            <option value="USD">USD</option>
                            <option value="EUR">EUR</option>
                            <option value="GBP">GBP</option>
                            <option value="NGN">NGN</option>
                        </select>
                    </div>
                </div>

                <!-- Hotel Selection (populated via AJAX) -->
                <div id="hotelSelection" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Hotel</label>
                    <div id="hotelResults" class="space-y-2">
                        <!-- Hotel options will be populated here -->
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex space-x-4">
                    <button type="button" id="searchHotels" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition duration-200">
                        Search Hotels
                    </button>
                    
                    <button type="submit" id="bookHotel" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition duration-200 hidden">
                        Book Selected Hotel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const bookingType = document.getElementById('bookingType');
    const userSelection = document.getElementById('userSelection');
    const bulkUserSelection = document.getElementById('bulkUserSelection');
    const searchButton = document.getElementById('searchHotels');
    const bookButton = document.getElementById('bookHotel');
    const hotelSelection = document.getElementById('hotelSelection');
    const hotelResults = document.getElementById('hotelResults');

    // Handle booking type change
    bookingType.addEventListener('change', function() {
        userSelection.classList.add('hidden');
        bulkUserSelection.classList.add('hidden');
        
        if (this.value === 'other') {
            userSelection.classList.remove('hidden');
            userSelection.querySelector('select').required = true;
        } else if (this.value === 'bulk') {
            bulkUserSelection.classList.remove('hidden');
        }
        
        if (this.value !== 'other') {
            userSelection.querySelector('select').required = false;
        }
    });

    // Set minimum dates
    const today = new Date().toISOString().split('T')[0];
    document.querySelector('input[name="check_in"]').min = today;
    document.querySelector('input[name="check_out"]').min = today;

    // Update checkout min date when checkin changes
    document.querySelector('input[name="check_in"]').addEventListener('change', function() {
        const checkoutInput = document.querySelector('input[name="check_out"]');
        checkoutInput.min = this.value;
        if (checkoutInput.value && checkoutInput.value <= this.value) {
            const nextDay = new Date(this.value);
            nextDay.setDate(nextDay.getDate() + 1);
            checkoutInput.value = nextDay.toISOString().split('T')[0];
        }
    });

    // Search hotels
    searchButton.addEventListener('click', function() {
        const formData = new FormData(document.getElementById('hotelBookingForm'));
        
        // Validate required fields for search
        const location = formData.get('location');
        const checkIn = formData.get('check_in');
        const checkOut = formData.get('check_out');
        
        if (!location || !checkIn || !checkOut) {
            alert('Please fill in location, check-in, and check-out dates');
            return;
        }

        this.disabled = true;
        this.textContent = 'Searching...';

        fetch('{{ route("hotels.search.results") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.hotels) {
                displayHotels(data.hotels);
                hotelSelection.classList.remove('hidden');
                bookButton.classList.remove('hidden');
            } else {
                alert(data.message || 'No hotels found');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while searching for hotels');
        })
        .finally(() => {
            this.disabled = false;
            this.textContent = 'Search Hotels';
        });
    });

    function displayHotels(hotels) {
        hotelResults.innerHTML = '';
        
        hotels.forEach((hotel, index) => {
            const hotelDiv = document.createElement('div');
            hotelDiv.className = 'border border-gray-300 rounded-lg p-4 hover:border-blue-500 cursor-pointer';
            hotelDiv.innerHTML = `
                <label class="flex items-start space-x-3 cursor-pointer">
                    <input type="radio" name="selected_hotel" value="${index}" class="mt-1" required>
                    <div class="flex-1">
                        <h3 class="font-semibold text-lg">${hotel.name}</h3>
                        <p class="text-gray-600">${hotel.address || 'Address not available'}</p>
                        <div class="mt-2 flex justify-between items-center">
                            <span class="text-lg font-bold text-green-600">$${hotel.price || 'Price on request'}</span>
                            <span class="text-sm text-gray-500">per night</span>
                        </div>
                        ${hotel.rating ? `<div class="mt-1"><span class="text-yellow-500">★</span> ${hotel.rating}/5</div>` : ''}
                    </div>
                </label>
            `;
            
            hotelResults.appendChild(hotelDiv);
        });

        // Store hotel data for submission
        window.hotelData = hotels;
    }

    // Form submission
    document.getElementById('hotelBookingForm').addEventListener('submit', function(e) {
        const selectedHotelIndex = document.querySelector('input[name="selected_hotel"]:checked');
        
        if (!selectedHotelIndex) {
            e.preventDefault();
            alert('Please select a hotel');
            return;
        }

        // Add selected hotel data to form
        if (window.hotelData) {
            const selectedHotel = window.hotelData[selectedHotelIndex.value];
            const hotelDataInput = document.createElement('input');
            hotelDataInput.type = 'hidden';
            hotelDataInput.name = 'hotel_data';
            hotelDataInput.value = JSON.stringify(selectedHotel);
            this.appendChild(hotelDataInput);
        }

        // Validate bulk booking
        if (bookingType.value === 'bulk') {
            const checkedUsers = document.querySelectorAll('input[name="bulk_user_ids[]"]:checked');
            if (checkedUsers.length === 0) {
                e.preventDefault();
                alert('Please select at least one user for bulk booking');
                return;
            }
        }
    });
});
</script>
@endsection
