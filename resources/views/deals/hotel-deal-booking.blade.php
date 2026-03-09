@extends('layouts.main')

@section('content')
    <!-- Include required CSS and JS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

    <style>
        .booking-container {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
        }
        
        .section-title {
            position: relative;
            padding-bottom: 10px;
            margin-bottom: 20px;
            font-weight: 600;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background: #8f95d3;
            border-radius: 3px;
        }
        
        .hotel-info-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }
        
        .hotel-info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .price-summary {
            background-color: #f8f9ff;
            border-radius: 10px;
            border-left: 4px solid #8f95d3;
        }
        
        .guest-form label {
            font-weight: 500;
            color: #495057;
        }
        
        .booking-steps {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        
        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 1;
        }
        
        .step-number {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #e9ecef;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-bottom: 8px;
        }
        
        .step.active .step-number {
            background-color: #8f95d3;
            color: white;
        }
        
        .step-title {
            font-size: 14px;
            color: #6c757d;
            text-align: center;
            max-width: 120px;
        }
        
        .step.active .step-title {
            color: #495057;
            font-weight: 600;
        }
        
        .step-line {
            position: absolute;
            top: 15px;
            height: 2px;
            width: 100%;
            background-color: #e9ecef;
            z-index: -1;
        }
        
        .amenity-icon {
            display: inline-flex;
            align-items: center;
            margin-right: 20px;
            color: #6c757d;
        }
        
        .amenity-icon i {
            color: #8f95d3;
            margin-right: 5px;
        }
        
        .thumbnail-gallery {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }
        
        .thumbnail {
            width: 80px;
            height: 60px;
            border-radius: 5px;
            object-fit: cover;
            cursor: pointer;
            opacity: 0.7;
            transition: opacity 0.3s ease;
        }
        
        .thumbnail:hover, .thumbnail.active {
            opacity: 1;
            box-shadow: 0 0 0 2px #8f95d3;
        }
    </style>

    <div class="container py-5">
        <!-- Booking Progress Steps -->
        <div class="booking-steps position-relative">
            <div class="step-line"></div>
            <div class="step active">
                <div class="step-number">1</div>
                <div class="step-title">Hotel Details</div>
            </div>
            <div class="step">
                <div class="step-number">2</div>
                <div class="step-title">Guest Information</div>
            </div>
            <div class="step">
                <div class="step-number">3</div>
                <div class="step-title">Payment</div>
            </div>
            <div class="step">
                <div class="step-number">4</div>
                <div class="step-title">Confirmation</div>
            </div>
        </div>
        
        <!-- Page Title -->
        <div class="text-center mb-5">
            <h1 class="h2 fw-bold">Hotel Booking</h1>
            <p class="text-muted">Please review your hotel details and proceed to guest information</p>
        </div>
        
        <div class="row g-4">
            <!-- Hotel Information Section -->
            <div class="col-md-8">
                <div class="booking-container">
                    <h3 class="section-title">Hotel Details</h3>
                    
                    <div class="hotel-info-card p-4 mb-4">
                        <!-- Hotel Header -->
                        <div class="d-flex flex-wrap justify-content-between align-items-start mb-4">
                            <div>
                                <h4 class="mb-1">{{ $hotel->hotelName ?? 'Serena Mountain Lodge' }}</h4>
                                <div class="text-muted mb-2">{{ $hotel->city ?? 'Nairobi' }}, {{ $hotel->country ?? 'Kenya' }}</div>
                                <div class="mb-2">
                                    @for($i = 0; $i < ($hotel->stars ?? 5); $i++)
                                    <i class="fas fa-star text-warning"></i>
                                    @endfor
                                </div>
                                <span class="badge text-dark" style="background-color: rgba(143, 149, 211, 0.3);">
                                    <i class="fas fa-award me-1"></i> {{ $hotel->highlight ?? 'Mountain View' }}
                                </span>
                                <span class="badge text-white ms-2" style="background-color: #27ae60;">{{ $hotel->hotelType ?? 'Luxury' }}</span>
                            </div>
                            <div class="text-end">
                                <span class="fs-5 fw-bold" style="color: #8f95d3;">{{ $hotel->currency ?? 'KES' }} {{ number_format($hotel->price ?? 22000) }}</span>
                                <span class="text-muted text-decoration-line-through d-block">{{ $hotel->currency ?? 'KES' }} {{ number_format($hotel->originalPrice ?? 27500) }}</span>
                                <span class="badge rounded-pill px-3 py-2 mt-2" style="background-color: #e74c3c;">{{ $hotel->discountPercent ?? 20 }}% OFF</span>
                            </div>
                        </div>
                        
                        <!-- Hotel Image -->
                        <div class="position-relative mb-3">
                            <img 
                                id="mainHotelImage"
                                src="{{ asset('images/travel/' . strtolower(str_replace(' ', '-', $hotel->city ?? 'nairobi')) . '.jpg') }}" 
                                class="img-fluid rounded w-100" 
                                style="height: 300px; object-fit: cover;"
                                alt="{{ $hotel->hotelName ?? 'Hotel' }}"
                                onerror="this.src='{{ asset('images/travel/destination-default.jpg') }}'">
                            
                            <div class="thumbnail-gallery">
                                <img src="{{ asset('images/travel/' . strtolower(str_replace(' ', '-', $hotel->city ?? 'nairobi')) . '.jpg') }}" 
                                    class="thumbnail active" 
                                    alt="Main View" 
                                    onclick="changeImage(this, '{{ asset('images/travel/' . strtolower(str_replace(' ', '-', $hotel->city ?? 'nairobi')) . '.jpg') }}')">
                                    
                                <img src="{{ asset('images/travel/hotel-room.jpg') }}" 
                                    class="thumbnail" 
                                    alt="Room" 
                                    onclick="changeImage(this, '{{ asset('images/travel/hotel-room.jpg') }}')">
                                    
                                <img src="{{ asset('images/travel/hotel-restaurant.jpg') }}" 
                                    class="thumbnail" 
                                    alt="Restaurant" 
                                    onclick="changeImage(this, '{{ asset('images/travel/hotel-restaurant.jpg') }}')">
                                    
                                <img src="{{ asset('images/travel/hotel-pool.jpg') }}" 
                                    class="thumbnail" 
                                    alt="Pool" 
                                    onclick="changeImage(this, '{{ asset('images/travel/hotel-pool.jpg') }}')">
                            </div>
                        </div>
                        
                        <!-- Hotel Description -->
                        <div class="mb-4">
                            <h5 class="mb-3">Description</h5>
                            <p class="text-muted">{{ $hotel->description ?? 'Luxury hotel with stunning views of Mount Kenya. Located in the heart of Nairobi, this 5-star property offers world-class amenities, elegantly furnished rooms, and exceptional service. Perfect for both business and leisure travelers seeking comfort and convenience.' }}</p>
                        </div>
                        
                        <!-- Hotel Location -->
                        <div class="mb-4">
                            <h5 class="mb-3">Location</h5>
                            <p class="text-muted">{{ $hotel->location ?? 'City Center' }} - {{ $hotel->city ?? 'Nairobi' }}, {{ $hotel->country ?? 'Kenya' }}</p>
                        </div>
                        
                        <!-- Hotel Amenities -->
                        <div class="mb-4">
                            <h5 class="mb-3">Amenities</h5>
                            <div class="d-flex flex-wrap mb-3">
                                <span class="amenity-icon">
                                    <i class="fas fa-utensils me-1"></i> {{ $hotel->meals ?? 'Breakfast Included' }}
                                </span>
                                <span class="amenity-icon">
                                    <i class="fas fa-wifi me-1"></i> {{ $hotel->wifi ?? 'Free WiFi' }}
                                </span>
                                <span class="amenity-icon">
                                    <i class="fas fa-swimming-pool me-1"></i> Swimming Pool
                                </span>
                                <span class="amenity-icon">
                                    <i class="fas fa-parking me-1"></i> Free Parking
                                </span>
                            </div>
                            <div class="d-flex flex-wrap">
                                <span class="amenity-icon">
                                    <i class="fas fa-concierge-bell me-1"></i> 24/7 Room Service
                                </span>
                                <span class="amenity-icon">
                                    <i class="fas fa-spa me-1"></i> Spa & Wellness
                                </span>
                                <span class="amenity-icon">
                                    <i class="fas fa-dumbbell me-1"></i> Fitness Center
                                </span>
                                <span class="amenity-icon">
                                    <i class="fas fa-business-time me-1"></i> Business Center
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Guest Information Form -->
                    <h3 class="section-title mt-5">Guest Information</h3>
                    
                    <form method="POST" action="{{ route('save-hotel-guest-details') }}" class="guest-form">
                        @csrf
                        <input type="hidden" name="hotel_data" value="{{ json_encode($hotel ?? []) }}">
                        
                        <!-- Primary Guest -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Primary Guest</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="guest_title" class="form-label">Title</label>
                                        <select class="form-select" id="guest_title" name="guest[title]" required>
                                            <option value="">Select title</option>
                                            <option value="Mr">Mr</option>
                                            <option value="Mrs">Mrs</option>
                                            <option value="Ms">Ms</option>
                                            <option value="Dr">Dr</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6"></div>
                                    <div class="col-md-6">
                                        <label for="guest_first_name" class="form-label">First Name</label>
                                        <input type="text" class="form-control" id="guest_first_name" name="guest[first_name]" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="guest_last_name" class="form-label">Last Name</label>
                                        <input type="text" class="form-control" id="guest_last_name" name="guest[last_name]" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Room Options -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Room Selection</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-check mb-3 p-3 border rounded">
                                    <input class="form-check-input" type="radio" name="room_type" id="standard_room" value="standard" checked>
                                    <label class="form-check-label d-flex justify-content-between w-100" for="standard_room">
                                        <div>
                                            <div class="fw-bold">Standard Room</div>
                                            <div class="text-muted small">1 king bed or 2 twin beds, City view</div>
                                        </div>
                                        <div class="text-end">
                                            <div style="color: #8f95d3;">{{ $hotel->currency ?? 'KES' }} {{ number_format($hotel->price ?? 22000) }}</div>
                                            <div class="text-muted small">per night</div>
                                        </div>
                                    </label>
                                </div>
                                
                                <div class="form-check mb-3 p-3 border rounded">
                                    <input class="form-check-input" type="radio" name="room_type" id="deluxe_room" value="deluxe">
                                    <label class="form-check-label d-flex justify-content-between w-100" for="deluxe_room">
                                        <div>
                                            <div class="fw-bold">Deluxe Room</div>
                                            <div class="text-muted small">1 king bed, Mountain view, Balcony</div>
                                        </div>
                                        <div class="text-end">
                                            <div style="color: #8f95d3;">{{ $hotel->currency ?? 'KES' }} {{ number_format(($hotel->price ?? 22000) * 1.25) }}</div>
                                            <div class="text-muted small">per night</div>
                                        </div>
                                    </label>
                                </div>
                                
                                <div class="form-check mb-3 p-3 border rounded">
                                    <input class="form-check-input" type="radio" name="room_type" id="suite_room" value="suite">
                                    <label class="form-check-label d-flex justify-content-between w-100" for="suite_room">
                                        <div>
                                            <div class="fw-bold">Executive Suite</div>
                                            <div class="text-muted small">1 king bed, Separate living area, Premium view</div>
                                        </div>
                                        <div class="text-end">
                                            <div style="color: #8f95d3;">{{ $hotel->currency ?? 'KES' }} {{ number_format(($hotel->price ?? 22000) * 1.5) }}</div>
                                            <div class="text-muted small">per night</div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Stay Details -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Stay Details</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="check_in_date" class="form-label">Check-in Date</label>
                                        <input type="date" class="form-control" id="check_in_date" name="stay[check_in_date]" 
                                            value="{{ \Carbon\Carbon::now()->addDays(1)->format('Y-m-d') }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="check_out_date" class="form-label">Check-out Date</label>
                                        <input type="date" class="form-control" id="check_out_date" name="stay[check_out_date]"
                                            value="{{ \Carbon\Carbon::now()->addDays(3)->format('Y-m-d') }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="num_adults" class="form-label">Number of Adults</label>
                                        <select class="form-select" id="num_adults" name="stay[adults]" required>
                                            <option value="1">1 Adult</option>
                                            <option value="2" selected>2 Adults</option>
                                            <option value="3">3 Adults</option>
                                            <option value="4">4 Adults</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="num_children" class="form-label">Number of Children</label>
                                        <select class="form-select" id="num_children" name="stay[children]">
                                            <option value="0" selected>No Children</option>
                                            <option value="1">1 Child</option>
                                            <option value="2">2 Children</option>
                                            <option value="3">3 Children</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Contact Information -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Contact Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="contact_email" class="form-label">Email Address</label>
                                        <input type="email" class="form-control" id="contact_email" name="contact[email]" required>
                                        <small class="text-muted">Your booking confirmation will be sent here</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="contact_phone" class="form-label">Phone Number</label>
                                        <input type="tel" class="form-control" id="contact_phone" name="contact[phone]" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Special Requests -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Special Requests (Optional)</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="special_requests" class="form-label">Special Requests or Requirements</label>
                                    <textarea class="form-control" id="special_requests" name="special_requests" rows="3" placeholder="Early check-in, late check-out, specific room preferences, etc."></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Form Buttons -->
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('deals.hotel-results') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Results
                            </a>
                            <button type="submit" class="btn px-5" style="background-color: #8f95d3; color: white;">
                                Proceed to Payment<i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Price Summary Section -->
            <div class="col-md-4">
                <div class="booking-container sticky-top" style="top: 100px;">
                    <h3 class="section-title">Price Summary</h3>
                    
                    <div class="price-summary p-4 mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span>Room Rate (per night)</span>
                            <span id="room-rate">{{ $hotel->currency ?? 'KES' }} {{ number_format($hotel->price ?? 22000) }}</span>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span>Number of Nights</span>
                            <span id="num-nights">2</span>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span>Taxes & Fees</span>
                            <span>{{ $hotel->currency ?? 'KES' }} {{ number_format(($hotel->price ?? 22000) * 0.1) }}</span>
                        </div>
                        
                        @if(isset($hotel->discountPercent) && $hotel->discountPercent > 0)
                        <div class="d-flex justify-content-between align-items-center mb-3 text-success">
                            <span>Discount ({{ $hotel->discountPercent }}%)</span>
                            <span>-{{ $hotel->currency ?? 'KES' }} {{ number_format(($hotel->originalPrice ?? 27500) - ($hotel->price ?? 22000)) }}</span>
                        </div>
                        @endif
                        
                        <hr class="my-3">
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold">Total Price</span>
                            <span class="fw-bold fs-4" id="total-price">{{ $hotel->currency ?? 'KES' }} {{ number_format(($hotel->price ?? 22000) * 2 + ($hotel->price ?? 22000) * 0.1) }}</span>
                        </div>
                        
                        @if(isset($hotel->originalPrice))
                        <div class="d-flex justify-content-end align-items-center">
                            <small class="text-muted text-decoration-line-through">{{ $hotel->currency ?? 'KES' }} {{ number_format(($hotel->originalPrice ?? 27500) * 2 + ($hotel->originalPrice ?? 27500) * 0.1) }}</small>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Reservation Details -->
                    <div class="mb-4">
                        <h5 class="mb-3">Reservation Details</h5>
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-tag me-2" style="color: #8f95d3;"></i>
                            <span>{{ $hotel->highlight ?? 'Mountain View' }}</span>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-bed me-2" style="color: #8f95d3;"></i>
                            <span id="room-type-display">Standard Room</span>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-calendar-alt me-2" style="color: #8f95d3;"></i>
                            <span id="stay-dates">{{ date('M d, Y', strtotime('+1 day')) }} - {{ date('M d, Y', strtotime('+3 days')) }}</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-user-friends me-2" style="color: #8f95d3;"></i>
                            <span id="guests-count">2 Adults, 0 Children</span>
                        </div>
                    </div>
                    
                    <!-- Cancellation Policy -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Hotel Policies</h5>
                            <p class="card-text small mb-2"><i class="fas fa-ban me-1 text-danger"></i> <strong>Cancellation Policy:</strong> Free cancellation up to 24 hours before check-in.</p>
                            <p class="card-text small mb-2"><i class="fas fa-clock me-1" style="color: #8f95d3;"></i> <strong>Check-in/Check-out:</strong> 2:00 PM / 11:00 AM</p>
                            <p class="card-text small mb-0"><i class="fas fa-info-circle me-1" style="color: #8f95d3;"></i> See our <a href="#" style="color: #8f95d3;">terms and conditions</a> for more details.</p>
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
        // Room type selection
        const roomTypeRadios = document.querySelectorAll('input[name="room_type"]');
        const roomRateElement = document.getElementById('room-rate');
        const roomTypeDisplay = document.getElementById('room-type-display');
        const totalPriceElement = document.getElementById('total-price');
        const basePriceText = roomRateElement.textContent;
        const basePrice = parseInt(basePriceText.replace(/[^0-9]/g, ''));
        const currency = basePriceText.replace(/[\d,]/g, '').trim();
        
        roomTypeRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                let priceMultiplier = 1;
                let roomTypeText = 'Standard Room';
                
                if (this.value === 'deluxe') {
                    priceMultiplier = 1.25;
                    roomTypeText = 'Deluxe Room';
                } else if (this.value === 'suite') {
                    priceMultiplier = 1.5;
                    roomTypeText = 'Executive Suite';
                }
                
                const newRoomRate = basePrice * priceMultiplier;
                roomRateElement.textContent = `${currency} ${newRoomRate.toLocaleString()}`;
                roomTypeDisplay.textContent = roomTypeText;
                
                // Update total
                updateTotalPrice();
            });
        });
        
        // Date selection for check-in and check-out
        const checkInDate = document.getElementById('check_in_date');
        const checkOutDate = document.getElementById('check_out_date');
        const numNightsElement = document.getElementById('num-nights');
        const stayDatesElement = document.getElementById('stay-dates');
        
        function updateNights() {
            const checkIn = new Date(checkInDate.value);
            const checkOut = new Date(checkOutDate.value);
            
            // Calculate nights difference
            const timeDiff = checkOut - checkIn;
            const nightsDiff = Math.ceil(timeDiff / (1000 * 60 * 60 * 24));
            
            if (nightsDiff > 0) {
                numNightsElement.textContent = nightsDiff;
                
                // Format dates for display
                const checkInFormatted = checkIn.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
                const checkOutFormatted = checkOut.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
                stayDatesElement.textContent = `${checkInFormatted} - ${checkOutFormatted}`;
                
                // Update total price
                updateTotalPrice();
            } else {
                alert('Check-out date must be after check-in date');
                checkOutDate.value = new Date(checkIn.getTime() + (24 * 60 * 60 * 1000)).toISOString().split('T')[0];
                updateNights();
            }
        }
        
        checkInDate.addEventListener('change', updateNights);
        checkOutDate.addEventListener('change', updateNights);
        
        // Guest count updates
        const numAdults = document.getElementById('num_adults');
        const numChildren = document.getElementById('num_children');
        const guestsCountElement = document.getElementById('guests-count');
        
        function updateGuestsCount() {
            const adults = numAdults.value;
            const children = numChildren.value;
            guestsCountElement.textContent = `${adults} Adult${adults > 1 ? 's' : ''}, ${children} Children`;
        }
        
        numAdults.addEventListener('change', updateGuestsCount);
        numChildren.addEventListener('change', updateGuestsCount);
        
        // Update total price based on room type and nights
        function updateTotalPrice() {
            const currentRoomRate = parseInt(roomRateElement.textContent.replace(/[^0-9]/g, ''));
            const nights = parseInt(numNightsElement.textContent);
            const taxRate = 0.1; // 10% tax
            
            const roomTotal = currentRoomRate * nights;
            const taxTotal = roomTotal * taxRate;
            const grandTotal = roomTotal + taxTotal;
            
            totalPriceElement.textContent = `${currency} ${grandTotal.toLocaleString()}`;
        }
        
        // Initialize values
        updateNights();
        updateGuestsCount();
    });
    
    function changeImage(thumbnail, newSrc) {
        // Update main image
        document.getElementById('mainHotelImage').src = newSrc;
        
        // Update active thumbnail
        document.querySelectorAll('.thumbnail').forEach(thumb => {
            thumb.classList.remove('active');
        });
        thumbnail.classList.add('active');
    }
</script>
@endsection
