@extends('layouts.main')

@section('content')
<div class="bg-gray-50">
    <!-- Hotel Header with Cover Image -->
    <div class="relative h-72 md:h-96 overflow-hidden">
        <img 
            src="{{ isset($hotel->images) && count($hotel->images) > 0 ? $hotel->images[0]->uri : asset('images/hotel-placeholder.jpg') }}" 
            alt="{{ $hotel->name ?? 'Hotel Image' }}"
            class="w-full h-full object-cover"
        >
        <div class="absolute inset-0 bg-gradient-to-b from-transparent to-black opacity-60"></div>
        <div class="absolute bottom-0 left-0 w-full p-6 text-white">
            <div class="max-w-7xl mx-auto">
                <h1 class="text-2xl md:text-4xl font-bold">{{ $hotel->name ?? 'Hotel Details' }}</h1>
                @if(isset($hotel->address))
                <div class="flex items-center mt-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-300" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                    </svg>
                    <span class="ml-2">
                        {{ $hotel->address->cityName ?? '' }}{{ isset($hotel->address->countryCode) ? ', ' . $hotel->address->countryCode : '' }}
                    </span>
                </div>
                @endif
                @if(isset($hotel->rating))
                <div class="flex items-center mt-1">
                    @for($i = 0; $i < $hotel->rating; $i++)
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    @endfor
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Left Column: Hotel Information -->
            <div class="lg:w-2/3">
                <!-- Photo Gallery -->
                @if(isset($hotel->images) && count($hotel->images) > 0)
                <div class="bg-white rounded-lg shadow-md p-4 mb-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Photo Gallery</h2>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                        @foreach($hotel->images as $index => $image)
                            @if($index < 8) <!-- Limit to first 8 images -->
                            <div class="aspect-w-1 aspect-h-1">
                                <img 
                                    src="{{ $image->uri ?? asset('images/hotel-placeholder.jpg') }}" 
                                    alt="Hotel Image {{ $index + 1 }}"
                                    class="w-full h-full object-cover rounded-md"
                                >
                            </div>
                            @endif
                        @endforeach
                    </div>
                    @if(count($hotel->images) > 8)
                    <div class="text-center mt-3">
                        <button type="button" class="text-indigo-600 hover:text-indigo-800 font-medium">
                            View All {{ count($hotel->images) }} Photos
                        </button>
                    </div>
                    @endif
                </div>
                @endif

                <!-- Description -->
                <div class="bg-white rounded-lg shadow-md p-4 mb-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-2">About This Hotel</h2>
                    <p class="text-gray-600">
                        {{ $hotel->description ?? 'No description available for this hotel.' }}
                    </p>
                    
                    <!-- Additional Information -->
                    @if(isset($hotel->amenities) && count($hotel->amenities) > 0)
                    <div class="mt-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Amenities</h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                            @foreach($hotel->amenities as $amenity)
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span class="ml-2 text-sm text-gray-600">{{ $amenity }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Location Information -->
                @if(isset($hotel->address))
                <div class="bg-white rounded-lg shadow-md p-4 mb-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Location</h2>
                    <div class="aspect-w-16 aspect-h-9">
                        <!-- Placeholder for map, in a real app you would use Google Maps or similar -->
                        <div class="w-full h-64 bg-gray-200 rounded-md flex items-center justify-center">
                            <div class="text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <p class="mt-2 text-gray-600">
                                    {{ $hotel->address->lines[0] ?? '' }} <br>
                                    {{ $hotel->address->cityName ?? '' }}{{ isset($hotel->address->postalCode) ? ', ' . $hotel->address->postalCode : '' }}<br>
                                    {{ $hotel->address->countryCode ?? '' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Reviews -->
                <div class="bg-white rounded-lg shadow-md p-4 mb-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Guest Reviews</h2>
                    
                    <!-- Overall Rating -->
                    <div class="flex items-center mb-4">
                        <div class="bg-green-500 text-white font-bold rounded-lg p-2 text-center w-16">
                            {{ isset($hotel->rating) ? number_format($hotel->rating, 1) : '4.5' }}
                        </div>
                        <div class="ml-4">
                            <div class="text-lg font-bold text-gray-900">
                                {{ isset($hotel->rating) ? 'Very Good' : 'Excellent' }}
                            </div>
                            <div class="text-sm text-gray-500">
                                Based on {{ rand(50, 500) }} reviews
                            </div>
                        </div>
                    </div>
                    
                    <!-- Review categories -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                        <div>
                            <div class="flex items-center mb-2">
                                <span class="w-24 text-sm text-gray-600">Cleanliness</span>
                                <div class="w-full bg-gray-200 rounded-full h-2 ml-2">
                                    <div class="bg-green-500 h-2 rounded-full" style="width: 90%"></div>
                                </div>
                                <span class="w-8 text-sm font-medium text-gray-900 ml-2">4.5</span>
                            </div>
                            <div class="flex items-center mb-2">
                                <span class="w-24 text-sm text-gray-600">Comfort</span>
                                <div class="w-full bg-gray-200 rounded-full h-2 ml-2">
                                    <div class="bg-green-500 h-2 rounded-full" style="width: 85%"></div>
                                </div>
                                <span class="w-8 text-sm font-medium text-gray-900 ml-2">4.3</span>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center mb-2">
                                <span class="w-24 text-sm text-gray-600">Location</span>
                                <div class="w-full bg-gray-200 rounded-full h-2 ml-2">
                                    <div class="bg-green-500 h-2 rounded-full" style="width: 95%"></div>
                                </div>
                                <span class="w-8 text-sm font-medium text-gray-900 ml-2">4.8</span>
                            </div>
                            <div class="flex items-center mb-2">
                                <span class="w-24 text-sm text-gray-600">Value</span>
                                <div class="w-full bg-gray-200 rounded-full h-2 ml-2">
                                    <div class="bg-green-500 h-2 rounded-full" style="width: 80%"></div>
                                </div>
                                <span class="w-8 text-sm font-medium text-gray-900 ml-2">4.0</span>
                            </div>
                        </div>
                    </div>

                    <!-- Sample Reviews -->
                    <div class="space-y-4">
                        <div class="border-b border-gray-200 pb-4">
                            <div class="flex items-center mb-2">
                                <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center">
                                    <span class="text-gray-700 font-medium">JD</span>
                                </div>
                                <div class="ml-3">
                                    <h4 class="font-medium text-gray-900">John Doe</h4>
                                    <div class="flex items-center">
                                        <div class="flex">
                                            @for($i = 0; $i < 5; $i++)
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 {{ $i < 4 ? 'text-yellow-400' : 'text-gray-300' }}" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            @endfor
                                        </div>
                                        <span class="ml-2 text-sm text-gray-500">2 months ago</span>
                                    </div>
                                </div>
                            </div>
                            <p class="text-gray-600 text-sm">
                                Great location, friendly staff, and very clean rooms. Would definitely stay here again on my next visit.
                            </p>
                        </div>
                        <div class="border-b border-gray-200 pb-4">
                            <div class="flex items-center mb-2">
                                <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center">
                                    <span class="text-gray-700 font-medium">JS</span>
                                </div>
                                <div class="ml-3">
                                    <h4 class="font-medium text-gray-900">Jane Smith</h4>
                                    <div class="flex items-center">
                                        <div class="flex">
                                            @for($i = 0; $i < 5; $i++)
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 {{ $i < 5 ? 'text-yellow-400' : 'text-gray-300' }}" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            @endfor
                                        </div>
                                        <span class="ml-2 text-sm text-gray-500">1 month ago</span>
                                    </div>
                                </div>
                            </div>
                            <p class="text-gray-600 text-sm">
                                Absolutely perfect stay! The room was spacious and comfortable, the breakfast was delicious with plenty of options, and the staff went above and beyond to make our stay special. The hotel is also in a perfect location for exploring the city.
                            </p>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <button class="text-indigo-600 hover:text-indigo-800 font-medium">
                            See all reviews
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Right Column: Booking Widget -->
            <div class="lg:w-1/3">
                <div class="bg-white rounded-lg shadow-md p-4 sticky top-24">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Book This Hotel</h2>
                    
                    <!-- Price Display -->
                    @if(isset($hotel->offers) && count($hotel->offers) > 0)
                    <div class="mb-4 p-3 bg-green-50 rounded-lg border border-green-200">
                        <div class="flex items-center justify-between">
                            <span class="text-green-800 font-medium">
                                Special Offer
                            </span>
                            <span class="text-xl font-bold text-green-800">
                                {{ $hotel->offers[0]->price->currency ?? 'USD' }} {{ number_format($hotel->offers[0]->price->total ?? rand(100, 500), 2) }}
                            </span>
                        </div>
                        <div class="text-sm text-green-700 mt-1">
                            Per night · Includes taxes & fees
                        </div>
                    </div>
                    @else
                    <div class="mb-4 p-3 bg-green-50 rounded-lg border border-green-200">
                        <div class="flex items-center justify-between">
                            <span class="text-green-800 font-medium">
                                Standard Rate
                            </span>
                            <span class="text-xl font-bold text-green-800">
                                USD {{ number_format(rand(100, 500), 2) }}
                            </span>
                        </div>
                        <div class="text-sm text-green-700 mt-1">
                            Per night · Includes taxes & fees
                        </div>
                    </div>
                    @endif
                    
                    <!-- Room Selection Form -->
                    <form action="{{ route('hotels.booking', ['hotelId' => $hotel->id ?? 'DUMMY']) }}" method="GET" class="space-y-4">
                        <!-- Dates -->
                        <div>
                            <label for="check-in" class="block text-sm font-medium text-gray-700 mb-1">Check-in Date</label>
                            <input 
                                type="date" 
                                id="check-in" 
                                name="check_in" 
                                class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" 
                                required
                            >
                        </div>
                        <div>
                            <label for="check-out" class="block text-sm font-medium text-gray-700 mb-1">Check-out Date</label>
                            <input 
                                type="date" 
                                id="check-out" 
                                name="check_out" 
                                class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" 
                                required
                            >
                        </div>
                        
                        <!-- Guests -->
                        <div>
                            <label for="adults" class="block text-sm font-medium text-gray-700 mb-1">Adults</label>
                            <select 
                                id="adults" 
                                name="adults" 
                                class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                            >
                                @for($i = 1; $i <= 4; $i++)
                                    <option value="{{ $i }}">{{ $i }} {{ $i === 1 ? 'Adult' : 'Adults' }}</option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label for="children" class="block text-sm font-medium text-gray-700 mb-1">Children</label>
                            <select 
                                id="children" 
                                name="children" 
                                class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                            >
                                @for($i = 0; $i <= 3; $i++)
                                    <option value="{{ $i }}">{{ $i }} {{ $i === 1 ? 'Child' : 'Children' }}</option>
                                @endfor
                            </select>
                        </div>
                        
                        <!-- Room Type (if available in the API response) -->
                        @if(isset($hotel->offers) && count($hotel->offers) > 0)
                            <div>
                                <label for="room-type" class="block text-sm font-medium text-gray-700 mb-1">Room Type</label>
                                <select 
                                    id="room-type" 
                                    name="room_type" 
                                    class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                >
                                    @foreach($hotel->offers as $index => $offer)
                                        <option value="{{ $offer->id ?? 'ROOM'.$index }}">
                                            {{ $offer->room->typeEstimated->category ?? 'Standard Room' }} 
                                            ({{ $offer->price->currency ?? 'USD' }} {{ number_format($offer->price->total ?? rand(100, 500), 2) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <div>
                                <label for="room-type" class="block text-sm font-medium text-gray-700 mb-1">Room Type</label>
                                <select 
                                    id="room-type" 
                                    name="room_type" 
                                    class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                >
                                    <option value="standard">Standard Room (USD {{ number_format(rand(100, 200), 2) }})</option>
                                    <option value="deluxe">Deluxe Room (USD {{ number_format(rand(150, 250), 2) }})</option>
                                    <option value="suite">Suite (USD {{ number_format(rand(200, 350), 2) }})</option>
                                </select>
                            </div>
                        @endif
                        
                        <!-- Book Button -->
                        <div>
                            <button 
                                type="submit" 
                                class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md font-medium hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 flex justify-center items-center"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" />
                                    <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd" />
                                </svg>
                                Book Now
                            </button>
                        </div>
                    </form>
                    
                    <!-- Policies -->
                    <div class="mt-4 text-sm text-gray-600">
                        <h3 class="font-medium text-gray-900 mb-2">Hotel Policies:</h3>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Check-in time: 2:00 PM</li>
                            <li>Check-out time: 12:00 PM</li>
                            <li>Free cancellation before 24 hours</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toastify Container for notifications -->
<div id="toastContainer" class="fixed bottom-4 right-4 z-50"></div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set minimum dates for check-in and check-out
        const today = new Date();
        const tomorrow = new Date(today);
        tomorrow.setDate(tomorrow.getDate() + 1);
        
        const checkInDateInput = document.getElementById('check-in');
        const checkOutDateInput = document.getElementById('check-out');
        
        // Format dates as YYYY-MM-DD for input value
        const formatDate = (date) => {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        };
        
        checkInDateInput.min = formatDate(today);
        checkInDateInput.value = formatDate(today);
        
        checkOutDateInput.min = formatDate(tomorrow);
        checkOutDateInput.value = formatDate(tomorrow);
        
        // Update check-out min date when check-in changes
        checkInDateInput.addEventListener('change', function() {
            const newCheckInDate = new Date(this.value);
            const newMinCheckOutDate = new Date(newCheckInDate);
            newMinCheckOutDate.setDate(newMinCheckOutDate.getDate() + 1);
            
            checkOutDateInput.min = formatDate(newMinCheckOutDate);
            
            // If current check-out date is before new min date, update it
            const currentCheckOutDate = new Date(checkOutDateInput.value);
            if (currentCheckOutDate <= newCheckInDate) {
                checkOutDateInput.value = formatDate(newMinCheckOutDate);
            }
        });
        
        // Toast notification functionality
        window.showToast = function(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `px-6 py-3 rounded-lg shadow-lg flex items-center ${
                type === 'success' ? 'bg-green-500' : 'bg-red-500'
            } text-white transform transition-all duration-300 translate-y-20 opacity-0 mb-4`;
            
            toast.innerHTML = `
                <span class="mr-2">
                    ${type === 'success' 
                        ? '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>'
                        : '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>'
                    }
                </span>
                <span>${message}</span>
            `;
            
            document.getElementById('toastContainer').appendChild(toast);
            
            // Show toast with animation
            setTimeout(() => {
                toast.classList.remove('translate-y-20', 'opacity-0');
            }, 100);
            
            // Hide toast after 5 seconds
            setTimeout(() => {
                toast.classList.add('translate-y-20', 'opacity-0');
                
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 5000);
        };
        
        // Show success toast if redirected with success message
        @if(session('success'))
            window.showToast('{{ session('success') }}', 'success');
        @endif
        
        // Show error toast if redirected with error message
        @if(session('error'))
            window.showToast('{{ session('error') }}', 'error');
        @endif
    });
</script>
@endsection
