@extends('layouts.main')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/ziara-booking.css') }}?v={{ (file_exists(public_path('css/ziara-booking.css')) ? filemtime(public_path('css/ziara-booking.css')) : 0) }}">
@endpush

@section('content')
<div class="ziara-bk">
@include('partials.ziara-page-hero', [
    'kicker' => 'Flight Booking',
    'title'  => 'Passenger <em>Details</em>',
    'intro'  => 'Tell us who is travelling so we can issue your e-ticket exactly as it should be.',
])

<div class="ziara-bk-body container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm ziara-bk-card">
                <div class="card-header bg-primary text-white ziara-bk-cta">
                    <h4 class="mb-0">Passenger Details</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('booking.save-passengers') }}">
                        @csrf
                        <input type="hidden" name="booking_type" value="{{ $type }}">
                        
                        <div class="mb-4">
                            <h5>Booking Summary</h5>
                            @if($type == 'flight')
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <p class="mb-1">
                                            <strong>{{ $bookingData['originCity'] }} to {{ $bookingData['destinationCity'] }}</strong>
                                        </p>
                                        <p class="text-muted">{{ $bookingData['departureDate'] }}, {{ $bookingData['cabin'] }}</p>
                                    </div>
                                    <div class="text-end">
                                        <p class="mb-1 fw-bold">{{ $bookingData['currency'] }} {{ number_format($bookingData['price'], 2) }}</p>
                                    </div>
                                </div>
                            @else
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <p class="mb-1">
                                            <strong>{{ $bookingData['hotelName'] }}</strong>
                                        </p>
                                        <p class="text-muted">{{ $bookingData['city'] }}, {{ $bookingData['country'] }}</p>
                                    </div>
                                    <div class="text-end">
                                        <p class="mb-1 fw-bold">{{ $bookingData['currency'] }} {{ number_format($bookingData['price'], 2) }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="mb-4">
                            <h5>Contact Information</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="contact_email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control @error('contact_email') is-invalid @enderror" 
                                        name="contact_email" id="contact_email" value="{{ old('contact_email', Auth::user()->email ?? '') }}" required>
                                    @error('contact_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="contact_phone" class="form-label">Phone Number</label>
                                    <input type="text" class="form-control @error('contact_phone') is-invalid @enderror" 
                                        name="contact_phone" id="contact_phone" value="{{ old('contact_phone', Auth::user()->phone ?? '') }}" required>
                                    @error('contact_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <h5 class="mb-3">Passenger Details</h5>
                        
                        <div id="passengers-container">
                            <div class="passenger-form mb-4 p-3 border rounded">
                                <h6>Passenger 1</h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">First Name</label>
                                        <input type="text" class="form-control" name="passengers[0][first_name]" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Last Name</label>
                                        <input type="text" class="form-control" name="passengers[0][last_name]" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Date of Birth</label>
                                        <input type="date" class="form-control" name="passengers[0][dob]" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nationality</label>
                                        <input type="text" class="form-control" name="passengers[0][nationality]" required>
                                    </div>
                                    @if($type == 'flight')
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Passport Number</label>
                                            <input type="text" class="form-control" name="passengers[0][passport_number]" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Passport Expiry Date</label>
                                            <input type="date" class="form-control" name="passengers[0][passport_expiry]" required>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <button type="button" id="add-passenger" class="btn btn-outline-primary ziara-btn ziara-btn--outline">
                                <i class="fas fa-plus-circle"></i> Add Another Passenger
                            </button>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg ziara-btn ziara-btn--maroon">Continue to Payment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let passengerCount = 1;
        const maxPassengers = 9;
        
        document.getElementById('add-passenger').addEventListener('click', function() {
            if (passengerCount >= maxPassengers) {
                alert('Maximum ' + maxPassengers + ' passengers allowed per booking');
                return;
            }
            
            passengerCount++;
            
            const template = `
                <div class="passenger-form mb-4 p-3 border rounded">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6>Passenger ${passengerCount}</h6>
                        <button type="button" class="btn btn-sm btn-outline-danger remove-passenger">
                            <i class="fas fa-times"></i> Remove
                        </button>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">First Name</label>
                            <input type="text" class="form-control" name="passengers[${passengerCount-1}][first_name]" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Last Name</label>
                            <input type="text" class="form-control" name="passengers[${passengerCount-1}][last_name]" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" name="passengers[${passengerCount-1}][dob]" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nationality</label>
                            <input type="text" class="form-control" name="passengers[${passengerCount-1}][nationality]" required>
                        </div>
                        ${document.querySelector('input[name="booking_type"]').value === 'flight' ? `
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Passport Number</label>
                                <input type="text" class="form-control" name="passengers[${passengerCount-1}][passport_number]" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Passport Expiry Date</label>
                                <input type="date" class="form-control" name="passengers[${passengerCount-1}][passport_expiry]" required>
                            </div>
                        ` : ''}
                    </div>
                </div>
            `;
            
            const container = document.getElementById('passengers-container');
            const div = document.createElement('div');
            div.innerHTML = template;
            container.appendChild(div.firstElementChild);
            
            // Add event listener to remove button
            container.lastElementChild.querySelector('.remove-passenger').addEventListener('click', function() {
                this.closest('.passenger-form').remove();
                passengerCount--;
                
                // Renumber the passenger forms
                document.querySelectorAll('.passenger-form h6').forEach((el, index) => {
                    el.textContent = `Passenger ${index + 1}`;
                });
                
                // Update the form field names
                renumberPassengerFields();
            });
        });
        
        function renumberPassengerFields() {
            const forms = document.querySelectorAll('.passenger-form');
            forms.forEach((form, index) => {
                const inputs = form.querySelectorAll('input');
                inputs.forEach(input => {
                    const name = input.getAttribute('name');
                    if (name) {
                        input.setAttribute('name', name.replace(/passengers\[\d+\]/, `passengers[${index}]`));
                    }
                });
            });
        }
    });
</script>
@endsection