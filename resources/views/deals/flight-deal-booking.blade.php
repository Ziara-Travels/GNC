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
            background: #ffc107;
            border-radius: 3px;
        }
        
        .flight-info-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }
        
        .flight-info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .price-summary {
            background-color: #fffdf5;
            border-radius: 10px;
            border-left: 4px solid #ffc107;
        }
        
        .passenger-form label {
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
            background-color: #ffc107;
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
    </style>

    <div class="container py-5">
        <!-- Booking Progress Steps -->
        <div class="booking-steps position-relative">
            <div class="step-line"></div>
            <div class="step active">
                <div class="step-number">1</div>
                <div class="step-title">Flight Details</div>
            </div>
            <div class="step">
                <div class="step-number">2</div>
                <div class="step-title">Passenger Information</div>
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
            <h1 class="h2 fw-bold">Flight Deal Booking</h1>
            <p class="text-muted">Please review your flight details and proceed to passenger information</p>
        </div>
        
        <div class="row g-4">
            <!-- Flight Information Section -->
            <div class="col-md-8">
                <div class="booking-container">
                    <h3 class="section-title">Flight Details</h3>
                    
                    <div class="flight-info-card p-4 mb-4">
                        <!-- Flight Header -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <span class="badge bg-warning px-3 py-2">{{ $flight->airline ?? 'KQ' }}</span>
                                <span class="ms-2 text-muted">Flight {{ $flight->flightNumber ?? 'KQ101' }}</span>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-light text-dark px-3 py-2">{{ $flight->cabin ?? 'Economy' }}</span>
                            </div>
                        </div>
                        
                        <!-- Flight Route -->
                        <div class="row align-items-center mb-4">
                            <div class="col-4 text-center">
                                <h5 class="mb-1">{{ $flight->originCity ?? 'Nairobi' }}</h5>
                                <div class="badge bg-light text-dark">{{ $flight->origin ?? 'NBO' }}</div>
                                <p class="small text-muted mb-0">{{ $flight->departureTime ?? '08:30' }}</p>
                                <p class="small text-muted">{{ $flight->departureDate ?? 'Aug 15, 2023' }}</p>
                            </div>
                            
                            <div class="col-4 text-center">
                                <div class="d-flex align-items-center justify-content-center">
                                    <div class="me-2" style="width: 10px; height: 10px; border-radius: 50%; background-color: #ffc107;"></div>
                                    <div style="flex-grow: 1; height: 2px; background-color: #ffc107;"></div>
                                    <i class="fas fa-plane mx-2 text-warning"></i>
                                    <div style="flex-grow: 1; height: 2px; background-color: #ffc107;"></div>
                                    <div class="ms-2" style="width: 10px; height: 10px; border-radius: 50%; background-color: #ffc107;"></div>
                                </div>
                                <p class="small text-muted my-2">{{ $flight->duration ?? '1h 5m' }}</p>
                                <p class="small text-muted">{{ isset($flight->stops) && $flight->stops == 0 ? 'Direct Flight' : $flight->stops . ' Stop(s)' }}</p>
                            </div>
                            
                            <div class="col-4 text-center">
                                <h5 class="mb-1">{{ $flight->destinationCity ?? 'Mombasa' }}</h5>
                                <div class="badge bg-light text-dark">{{ $flight->destination ?? 'MBA' }}</div>
                                <p class="small text-muted mb-0">{{ $flight->arrivalTime ?? '09:35' }}</p>
                                <p class="small text-muted">{{ $flight->departureDate ?? 'Aug 15, 2023' }}</p>
                            </div>
                        </div>
                        
                        @if(isset($flight->returnDate))
                        <!-- Return Flight (if applicable) -->
                        <hr class="my-4">
                        
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <span class="badge bg-warning px-3 py-2">{{ $flight->airline ?? 'KQ' }}</span>
                                <span class="ms-2 text-muted">Flight {{ $flight->returnFlightNumber ?? 'KQ102' }}</span>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-light text-dark px-3 py-2">{{ $flight->cabin ?? 'Economy' }}</span>
                            </div>
                        </div>
                        
                        <div class="row align-items-center">
                            <div class="col-4 text-center">
                                <h5 class="mb-1">{{ $flight->destinationCity ?? 'Mombasa' }}</h5>
                                <div class="badge bg-light text-dark">{{ $flight->destination ?? 'MBA' }}</div>
                                <p class="small text-muted mb-0">{{ $flight->returnDepartureTime ?? '10:30' }}</p>
                                <p class="small text-muted">{{ $flight->returnDate ?? 'Aug 22, 2023' }}</p>
                            </div>
                            
                            <div class="col-4 text-center">
                                <div class="d-flex align-items-center justify-content-center">
                                    <div class="me-2" style="width: 10px; height: 10px; border-radius: 50%; background-color: #ffc107;"></div>
                                    <div style="flex-grow: 1; height: 2px; background-color: #ffc107;"></div>
                                    <i class="fas fa-plane mx-2 text-warning" style="transform: scaleX(-1);"></i>
                                    <div style="flex-grow: 1; height: 2px; background-color: #ffc107;"></div>
                                    <div class="ms-2" style="width: 10px; height: 10px; border-radius: 50%; background-color: #ffc107;"></div>
                                </div>
                                <p class="small text-muted my-2">{{ $flight->returnDuration ?? '1h 5m' }}</p>
                                <p class="small text-muted">{{ isset($flight->returnStops) && $flight->returnStops == 0 ? 'Direct Flight' : $flight->returnStops . ' Stop(s)' }}</p>
                            </div>
                            
                            <div class="col-4 text-center">
                                <h5 class="mb-1">{{ $flight->originCity ?? 'Nairobi' }}</h5>
                                <div class="badge bg-light text-dark">{{ $flight->origin ?? 'NBO' }}</div>
                                <p class="small text-muted mb-0">{{ $flight->returnArrivalTime ?? '11:35' }}</p>
                                <p class="small text-muted">{{ $flight->returnDate ?? 'Aug 22, 2023' }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Passenger Information Form -->
                    <h3 class="section-title mt-5">Passenger Information</h3>
                    
                    <form method="POST" action="{{ route('save-passenger-details') }}" class="passenger-form">
                        @csrf
                        <input type="hidden" name="flight_data" value="{{ json_encode($flight ?? []) }}">
                        
                        <!-- Adult Passenger 1 -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Adult 1 (Primary Contact)</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="adult1_title" class="form-label">Title</label>
                                        <select class="form-select" id="adult1_title" name="passengers[0][title]" required>
                                            <option value="">Select title</option>
                                            <option value="Mr">Mr</option>
                                            <option value="Mrs">Mrs</option>
                                            <option value="Ms">Ms</option>
                                            <option value="Dr">Dr</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6"></div>
                                    <div class="col-md-6">
                                        <label for="adult1_first_name" class="form-label">First Name</label>
                                        <input type="text" class="form-control" id="adult1_first_name" name="passengers[0][first_name]" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="adult1_last_name" class="form-label">Last Name</label>
                                        <input type="text" class="form-control" id="adult1_last_name" name="passengers[0][last_name]" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="adult1_dob" class="form-label">Date of Birth</label>
                                        <input type="date" class="form-control" id="adult1_dob" name="passengers[0][dob]" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="adult1_nationality" class="form-label">Nationality</label>
                                        <select class="form-select" id="adult1_nationality" name="passengers[0][nationality]" required>
                                            <option value="">Select country</option>
                                            <option value="KE">Kenya</option>
                                            <option value="TZ">Tanzania</option>
                                            <option value="UG">Uganda</option>
                                            <option value="RW">Rwanda</option>
                                            <option value="ET">Ethiopia</option>
                                            <!-- Add more countries as needed -->
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="adult1_passport" class="form-label">Passport Number</label>
                                        <input type="text" class="form-control" id="adult1_passport" name="passengers[0][passport]" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="adult1_passport_expiry" class="form-label">Passport Expiry Date</label>
                                        <input type="date" class="form-control" id="adult1_passport_expiry" name="passengers[0][passport_expiry]" required>
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
                                        <small class="text-muted">Your booking confirmation and e-ticket will be sent here</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="contact_phone" class="form-label">Phone Number</label>
                                        <input type="tel" class="form-control" id="contact_phone" name="contact[phone]" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Add Passenger Button -->
                        <div class="d-flex justify-content-end mb-4">
                            <button type="button" class="btn btn-outline-secondary" id="addPassengerBtn">
                                <i class="fas fa-plus me-2"></i>Add Another Passenger
                            </button>
                        </div>
                        
                        <!-- Special Requests -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Special Requests (Optional)</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="special_requests" class="form-label">Special Requests or Requirements</label>
                                    <textarea class="form-control" id="special_requests" name="special_requests" rows="3" placeholder="Meal preferences, wheelchair assistance, etc."></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Form Buttons -->
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('deals.flight-results') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Results
                            </a>
                            <button type="submit" class="btn btn-warning px-5">
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
                            <span>1 x Adult</span>
                            <span>{{ $flight->currency ?? 'KES' }} {{ number_format($flight->price ?? 0) }}</span>
                        </div>
                        
                        @if(isset($flight->returnDate))
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span>Return Flight</span>
                            <span>Included</span>
                        </div>
                        @endif
                        
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span>Taxes & Fees</span>
                            <span>Included</span>
                        </div>
                        
                        @if(isset($flight->discountPercent) && $flight->discountPercent > 0)
                        <div class="d-flex justify-content-between align-items-center mb-3 text-success">
                            <span>Discount ({{ $flight->discountPercent }}%)</span>
                            <span>-{{ $flight->currency ?? 'KES' }} {{ number_format(($flight->originalPrice ?? 0) - ($flight->price ?? 0)) }}</span>
                        </div>
                        @endif
                        
                        <hr class="my-3">
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold">Total Price</span>
                            <span class="fw-bold fs-4">{{ $flight->currency ?? 'KES' }} {{ number_format($flight->price ?? 0) }}</span>
                        </div>
                        
                        @if(isset($flight->originalPrice))
                        <div class="d-flex justify-content-end align-items-center">
                            <small class="text-muted text-decoration-line-through">{{ $flight->currency ?? 'KES' }} {{ number_format($flight->originalPrice ?? 0) }}</small>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Reservation Details -->
                    <div class="mb-4">
                        <h5 class="mb-3">Deal Highlight</h5>
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-tag text-warning me-2"></i>
                            <span>{{ $flight->highlight ?? 'Special Offer' }}</span>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-calendar-alt text-warning me-2"></i>
                            <span>Limited time offer</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle text-warning me-2"></i>
                            <span>Instant confirmation</span>
                        </div>
                    </div>
                    
                    <!-- Cancellation Policy -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Cancellation Policy</h5>
                            <p class="card-text small text-muted">Free cancellation up to 24 hours before departure. See our <a href="#" class="text-warning">terms and conditions</a> for more details.</p>
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
        let passengerCount = 1;
        
        // Add passenger functionality
        document.getElementById('addPassengerBtn').addEventListener('click', function() {
            if (passengerCount >= 9) {
                alert('Maximum 9 passengers allowed per booking');
                return;
            }
            
            passengerCount++;
            
            // Create new passenger form section
            const passengerForm = document.querySelector('.passenger-form');
            const passengerDiv = document.createElement('div');
            passengerDiv.className = 'card border-0 shadow-sm mb-4';
            passengerDiv.innerHTML = `
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Adult ${passengerCount}</h5>
                    <button type="button" class="btn-close remove-passenger" data-passenger-id="${passengerCount}"></button>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="adult${passengerCount}_title" class="form-label">Title</label>
                            <select class="form-select" id="adult${passengerCount}_title" name="passengers[${passengerCount - 1}][title]" required>
                                <option value="">Select title</option>
                                <option value="Mr">Mr</option>
                                <option value="Mrs">Mrs</option>
                                <option value="Ms">Ms</option>
                                <option value="Dr">Dr</option>
                            </select>
                        </div>
                        <div class="col-md-6"></div>
                        <div class="col-md-6">
                            <label for="adult${passengerCount}_first_name" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="adult${passengerCount}_first_name" name="passengers[${passengerCount - 1}][first_name]" required>
                        </div>
                        <div class="col-md-6">
                            <label for="adult${passengerCount}_last_name" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="adult${passengerCount}_last_name" name="passengers[${passengerCount - 1}][last_name]" required>
                        </div>
                        <div class="col-md-6">
                            <label for="adult${passengerCount}_dob" class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" id="adult${passengerCount}_dob" name="passengers[${passengerCount - 1}][dob]" required>
                        </div>
                        <div class="col-md-6">
                            <label for="adult${passengerCount}_nationality" class="form-label">Nationality</label>
                            <select class="form-select" id="adult${passengerCount}_nationality" name="passengers[${passengerCount - 1}][nationality]" required>
                                <option value="">Select country</option>
                                <option value="KE">Kenya</option>
                                <option value="TZ">Tanzania</option>
                                <option value="UG">Uganda</option>
                                <option value="RW">Rwanda</option>
                                <option value="ET">Ethiopia</option>
                                <!-- Add more countries as needed -->
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="adult${passengerCount}_passport" class="form-label">Passport Number</label>
                            <input type="text" class="form-control" id="adult${passengerCount}_passport" name="passengers[${passengerCount - 1}][passport]" required>
                        </div>
                        <div class="col-md-6">
                            <label for="adult${passengerCount}_passport_expiry" class="form-label">Passport Expiry Date</label>
                            <input type="date" class="form-control" id="adult${passengerCount}_passport_expiry" name="passengers[${passengerCount - 1}][passport_expiry]" required>
                        </div>
                    </div>
                </div>
            `;
            
            // Insert before the "Add Passenger" button
            document.getElementById('addPassengerBtn').parentNode.before(passengerDiv);
            
            // Update price summary
            updatePriceSummary();
            
            // Add event listener for remove button
            passengerDiv.querySelector('.remove-passenger').addEventListener('click', function() {
                this.closest('.card').remove();
                passengerCount--;
                updatePriceSummary();
            });
        });
        
        function updatePriceSummary() {
            // Get the base price from the page
            const basePriceElement = document.querySelector('.price-summary .d-flex:first-child span:last-child');
            const basePrice = parseInt(basePriceElement.textContent.replace(/[^0-9]/g, ''));
            const currency = basePriceElement.textContent.replace(/[\d,]/g, '').trim();
            
            // Update the adult count and price
            const adultsText = document.querySelector('.price-summary .d-flex:first-child span:first-child');
            adultsText.textContent = `${passengerCount} x Adult`;
            basePriceElement.textContent = `${currency} ${(basePrice * passengerCount).toLocaleString()}`;
            
            // Update total price
            const totalPrice = document.querySelector('.price-summary .fw-bold.fs-4');
            const oldTotalPrice = parseInt(totalPrice.textContent.replace(/[^0-9]/g, '')) / (passengerCount - 1);
            totalPrice.textContent = `${currency} ${(oldTotalPrice * passengerCount).toLocaleString()}`;
        }
    });
</script>
@endsection
