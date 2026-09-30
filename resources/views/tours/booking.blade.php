
@extends('layouts.main')

@section('title', 'Book ' . $tour->name)

@section('content')
<style>
    .ziara-book-shell { padding-top: 40px; padding-bottom: 56px; }
    .ziara-book-card {
        background: #fff;
        border: 1px solid #eadfca;
        border-radius: 16px;
        box-shadow: 0 10px 34px rgba(74,29,19,.10);
        overflow: hidden;
    }
    .ziara-book-card img { border-radius: 0; }
    .ziara-book-card .card-body { padding: 24px; }
    .ziara-book-title,
    .ziara-book-h5 {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-weight: 700;
        color: #4a1d13;
    }
    .ziara-book-title { font-size: 1.35rem; }
    .ziara-book-h5 { font-size: 1.15rem; }
    .ziara-book-card .text-muted { color: #7a6a60; }
    .ziara-book-star { color: #c9a227; }
    .ziara-book-price { color: #6b1d15; font-weight: 700; }
    .ziara-book-feature { color: #7b2a1b; }
    .ziara-book-badge--muted { background: #fdf6df; color: #6b1d15; border: 1px solid rgba(201,162,39,.45); }
    .ziara-book-badge--deal { background: #6b2c1f; color: #fff; }
    .ziara-book-cta {
        background: #6b2c1f;
        border: 1px solid #6b2c1f;
        color: #fff;
        font-weight: 600;
        letter-spacing: .3px;
        border-radius: 10px;
    }
    .ziara-book-cta:hover,
    .ziara-book-cta:focus {
        background: #4a1d13;
        border-color: #4a1d13;
        color: #fff;
    }
    .ziara-book-btn-ghost {
        background: #fff;
        border: 1px solid #eadfca;
        color: #4a1d13;
        border-radius: 10px;
    }
    .ziara-book-btn-ghost:hover,
    .ziara-book-btn-ghost:focus {
        background: #fdf8eb;
        border-color: #c9a227;
        color: #4a1d13;
    }
    .ziara-book-modal {
        background: #fff;
        border: 1px solid #eadfca;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 30px 90px rgba(28,22,18,.4);
    }
    .ziara-book-modal-head {
        background: linear-gradient(110deg, #4a1d13 0%, #6b2c1f 100%);
        border-bottom: 3px solid #c9a227;
    }
    .ziara-book-modal-title {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-weight: 700;
        color: #fff;
    }
    .ziara-book-modal-head .btn-close { filter: invert(1) grayscale(1) brightness(2); }
    .ziara-book-modal .modal-body { background: #fff; color: #3a322c; }
    .ziara-book-modal .modal-footer { background: #fdf8eb; border-top: 1px solid #eadfca; }
</style>
<div class="container ziara-book-shell">
    <div class="row">
        <!-- Tour Summary -->
        <div class="col-lg-4">
            <div class="card ziara-book-card sticky-top" style="top: 20px;">
                <img src="{{ $tour->main_image }}" class="card-img-top" alt="{{ $tour->name }}" style="height: 200px; object-fit: cover;">
                <div class="card-body">
                    <h5 class="card-title ziara-book-title">{{ $tour->name }}</h5>
                    <p class="text-muted">
                        <i class="fas fa-map-marker-alt me-1"></i>{{ $tour->city }}, {{ $tour->country }}
                    </p>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Duration:</span>
                            <span>{{ $tour->duration }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Difficulty:</span>
                            <span class="badge ziara-book-badge--muted">{{ $tour->difficulty_level }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Rating:</span>
                            <span>
                                <i class="fas fa-star ziara-book-star"></i>
                                {{ $tour->rating }} ({{ $tour->review_count }} reviews)
                            </span>
                        </div>
                    </div>
                    
                    <div class="pricing mb-3">
                        @if($tour->has_discount)
                            <span class="text-muted text-decoration-line-through">
                                {{ $tour->currency }} {{ number_format($tour->original_price, 2) }}
                            </span>
                            <span class="badge ziara-book-badge--deal ms-2">{{ $tour->discount_percent }}% OFF</span>
                        @endif
                        <div class="h4 ziara-book-price mb-0">
                            {{ $tour->currency }} {{ number_format($tour->price, 2) }}
                            <small class="text-muted">per person</small>
                        </div>
                    </div>
                    
                    <div class="features">
                        @if($tour->free_cancellation)
                            <small class="text-success d-block">
                                <i class="fas fa-check me-1"></i>Free cancellation
                            </small>
                        @endif
                        @if($tour->instant_confirmation)
                            <small class="text-info d-block">
                                <i class="fas fa-bolt me-1"></i>Instant confirmation
                            </small>
                        @endif
                        @if($tour->hotel_pickup)
                            <small class="ziara-book-feature d-block">
                                <i class="fas fa-car me-1"></i>Hotel pickup available
                            </small>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Booking Form -->
        <div class="col-lg-8">
            <div class="card ziara-book-card">
                <div class="card-body">
                    <h3 class="card-title ziara-book-title mb-4">Book Your Tour</h3>
                    
                    <form action="{{ route('tours.book') }}" method="POST">
                        @csrf
                        <input type="hidden" name="tour_id" value="{{ $tour->id }}">
                        
                        <!-- Tour Date -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Tour Date *</label>
                                <input type="date" class="form-control" name="tour_date" 
                                       min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Number of Participants *</label>
                                <select class="form-select" name="participants" required>
                                    @for($i = 1; $i <= min($tour->max_participants, 10); $i++)
                                        <option value="{{ $i }}">{{ $i }} {{ $i == 1 ? 'Person' : 'People' }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <h5 class="ziara-book-h5 mb-3">Contact Information</h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Full Name *</label>
                                <input type="text" class="form-control" name="contact_name" 
                                       value="{{ Auth::user()->name ?? old('contact_name') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email Address *</label>
                                <input type="email" class="form-control" name="contact_email" 
                                       value="{{ Auth::user()->email ?? old('contact_email') }}" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Phone Number *</label>
                            <input type="tel" class="form-control" name="contact_phone" 
                                   value="{{ old('contact_phone') }}" required>
                        </div>

                        <!-- Pickup Options -->
                        @if($tour->hotel_pickup)
                            <h5 class="ziara-book-h5 mb-3">Pickup Options</h5>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="hotel_pickup" value="1" id="hotelPickup">
                                    <label class="form-check-label" for="hotelPickup">
                                        Request hotel pickup (if available in your area)
                                    </label>
                                </div>
                            </div>
                            
                            <div class="mb-3" id="pickupLocationDiv" style="display: none;">
                                <label class="form-label">Hotel/Pickup Location</label>
                                <input type="text" class="form-control" name="pickup_location" 
                                       placeholder="Enter your hotel name or pickup address">
                            </div>
                        @endif

                        <!-- Special Requirements -->
                        <div class="mb-3">
                            <label class="form-label">Special Requirements or Dietary Restrictions</label>
                            <textarea class="form-control" name="special_requirements" rows="3" 
                                      placeholder="Please let us know about any special dietary requirements, mobility needs, or other requests..."></textarea>
                        </div>

                        <!-- Participant Details -->
                        <div class="mb-4">
                            <h5 class="ziara-book-h5 mb-3">Participant Details (Optional)</h5>
                            <div id="participantDetails">
                                <!-- Will be populated by JavaScript -->
                            </div>
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="termsAccepted" required>
                                <label class="form-check-label" for="termsAccepted">
                                    I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms and Conditions</a> and <a href="#" data-bs-toggle="modal" data-bs-target="#cancellationModal">Cancellation Policy</a> *
                                </label>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid">
                            <button type="submit" class="btn ziara-book-cta btn-lg">
                                <i class="fas fa-lock me-2"></i>
                                Proceed to Payment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Terms Modal -->
<div class="modal fade" id="termsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content ziara-book-modal">
            <div class="modal-header ziara-book-modal-head">
                <h5 class="modal-title ziara-book-modal-title">Terms and Conditions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>By booking this tour, you agree to our terms and conditions...</p>
                <!-- Add your terms content here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn ziara-book-btn-ghost" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Cancellation Policy Modal -->
<div class="modal fade" id="cancellationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content ziara-book-modal">
            <div class="modal-header ziara-book-modal-head">
                <h5 class="modal-title ziara-book-modal-title">Cancellation Policy</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>{{ $tour->cancellation_policy ?? 'Free cancellation up to 24 hours before the tour starts.' }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn ziara-book-btn-ghost" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Hotel pickup toggle
    const hotelPickupCheck = document.getElementById('hotelPickup');
    const pickupLocationDiv = document.getElementById('pickupLocationDiv');
    
    if (hotelPickupCheck) {
        hotelPickupCheck.addEventListener('change', function() {
            pickupLocationDiv.style.display = this.checked ? 'block' : 'none';
        });
    }
    
    // Participant details
    const participantsSelect = document.querySelector('select[name="participants"]');
    const participantDetails = document.getElementById('participantDetails');
    
    participantsSelect.addEventListener('change', function() {
        const count = parseInt(this.value);
        updateParticipantDetails(count);
    });
    
    function updateParticipantDetails(count) {
        participantDetails.innerHTML = '';
        
        for (let i = 1; i <= count; i++) {
            const participantDiv = document.createElement('div');
            participantDiv.className = 'row mb-2';
            participantDiv.innerHTML = `
                <div class="col-md-6">
                    <input type="text" class="form-control" name="participant_details[${i-1}][name]" 
                           placeholder="Participant ${i} Name">
                </div>
                <div class="col-md-3">
                    <input type="number" class="form-control" name="participant_details[${i-1}][age]" 
                           placeholder="Age" min="1" max="120">
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control" name="participant_details[${i-1}][dietary_requirements]" 
                           placeholder="Dietary needs">
                </div>
            `;
            participantDetails.appendChild(participantDiv);
        }
    }
    
    // Initialize with 1 participant
    updateParticipantDetails(1);
});
</script>
@endsection