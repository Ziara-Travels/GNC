<!-- filepath: c:\xampp\htdocs\GNC\resources\views\tours\confirmation.blade.php -->

@extends('layouts.main')

@section('title', 'Booking Confirmation')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Success Alert -->
            <div class="alert alert-success mb-5 shadow-sm">
                <div class="d-flex align-items-center">
                    <div class="me-4">
                        <i class="fas fa-check-circle fa-3x text-success"></i>
                    </div>
                    <div>
                        <h4 class="alert-heading mb-1">Thank You! Your booking is confirmed</h4>
                        <p class="mb-0">Booking Reference: <strong>{{ $booking->booking_reference }}</strong></p>
                    </div>
                </div>
            </div>
            
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Booking Details</h5>
                        <a href="javascript:window.print();" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-print me-1"></i> Print
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-4 mb-md-0">
                            @if(isset($booking->tour->main_image))
                                <img src="{{ $booking->tour->main_image }}" alt="{{ $booking->tour->name }}" class="img-fluid rounded">
                            @elseif(isset($booking->hotel_details) && is_string($booking->hotel_details))
                                @php
                                    $tourDetails = json_decode($booking->hotel_details, true);
                                    $image = $tourDetails['images'][0] ?? '/images/travel/default-tour.jpg';
                                @endphp
                                <img src="{{ $image }}" alt="Tour Image" class="img-fluid rounded">
                            @else
                                <img src="/images/travel/default-tour.jpg" alt="Tour" class="img-fluid rounded">
                            @endif
                        </div>
                        <div class="col-md-8">
                            <h4 class="mb-3">
                                @if(isset($booking->tour->name))
                                    {{ $booking->tour->name }}
                                @else
                                    {{ $booking->title }}
                                @endif
                            </h4>
                            
                            <div class="mb-4">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <h6 class="text-muted">Booking Reference</h6>
                                        <p class="mb-0"><strong>{{ $booking->booking_reference }}</strong></p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <h6 class="text-muted">Status</h6>
                                        <span class="badge bg-{{ $booking->status == 'confirmed' ? 'success' : ($booking->status == 'pending' ? 'warning' : 'secondary') }} px-3 py-2">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <h6 class="text-muted">Tour Date</h6>
                                        <p class="mb-0">{{ isset($booking->tour_date) ? $booking->tour_date->format('M d, Y') : 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <h6 class="text-muted">Tour Time</h6>
                                        <p class="mb-0">{{ isset($booking->tour_time) ? $booking->tour_time : 'As scheduled' }}</p>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <h6 class="text-muted">Number of Participants</h6>
                                        <p class="mb-0">{{ $booking->participants ?? $booking->adults ?? 1 }}</p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <h6 class="text-muted">Payment Status</h6>
                                        <span class="badge bg-{{ $booking->payment_status == 'paid' ? 'success' : 'warning' }} px-3 py-2">
                                            {{ ucfirst($booking->payment_status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row mt-4">
                        <div class="col-md-6 mb-4">
                            <h5 class="mb-3">Contact Information</h5>
                            <ul class="list-unstyled">
                                <li class="mb-2"><strong>Name:</strong> {{ $booking->contact_name ?? 'N/A' }}</li>
                                <li class="mb-2"><strong>Email:</strong> {{ $booking->contact_email ?? 'N/A' }}</li>
                                <li class="mb-2"><strong>Phone:</strong> {{ $booking->contact_phone ?? 'N/A' }}</li>
                            </ul>
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <h5 class="mb-3">Payment Information</h5>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <strong>Total Amount:</strong>
                                    @if(isset($booking->currency) && isset($booking->total_amount))
                                        {{ $booking->currency }} {{ number_format($booking->total_amount, 2) }}
                                        @if($booking->currency == 'USD')
                                            <span class="text-muted small">|</span>
                                            KES {{ number_format($booking->total_amount * 125, 0) }}
                                        @endif
                                    @else
                                        {{ $booking->currency ?? 'USD' }} {{ number_format($booking->amount ?? 0, 2) }}
                                    @endif
                                </li>
                                <li class="mb-2"><strong>Payment Method:</strong> {{ ucfirst($booking->payment_method ?? 'N/A') }}</li>
                                @if(isset($booking->payment_date))
                                <li class="mb-2"><strong>Payment Date:</strong> {{ $booking->payment_date->format('M d, Y') }}</li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    
                    @if(isset($booking->special_requirements) && !empty($booking->special_requirements))
                    <div class="mt-3">
                        <h5>Special Requirements</h5>
                        <p>{{ $booking->special_requirements }}</p>
                    </div>
                    @endif
                    
                    @if(isset($booking->pickup_location) && !empty($booking->pickup_location))
                    <div class="mt-3">
                        <h5>Pickup Information</h5>
                        <p>{{ $booking->pickup_location }}</p>
                    </div>
                    @endif
                </div>
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-between">
                        <div>
                            <a href="{{ route('tours.index') }}" class="btn btn-outline-primary">
                                <i class="fas fa-search me-1"></i> Browse More Tours
                            </a>
                        </div>
                        <div>
                            @if($booking->status !== 'cancelled')
                                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#cancelModal">
                                    <i class="fas fa-times-circle me-1"></i> Cancel Booking
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">What's Next?</h5>
                    <div class="row mt-4">
                        <div class="col-md-4 mb-3">
                            <div class="d-flex">
                                <div class="me-3">
                                    <span class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                </div>
                                <div>
                                    <h6>Confirmation Email</h6>
                                    <p class="text-muted small">A confirmation email has been sent to your provided email address.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="d-flex">
                                <div class="me-3">
                                    <span class="bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                        <i class="fas fa-map-marked-alt"></i>
                                    </span>
                                </div>
                                <div>
                                    <h6>Tour Details</h6>
                                    <p class="text-muted small">We'll send detailed instructions about your tour before the tour date.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="d-flex">
                                <div class="me-3">
                                    <span class="bg-warning text-dark rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                        <i class="fas fa-question-circle"></i>
                                    </span>
                                </div>
                                <div>
                                    <h6>Need Help?</h6>
                                    <p class="text-muted small">Contact our support team at support@example.com</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Booking Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelModalLabel">Cancel Booking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('tours.cancel', ['reference' => $booking->booking_reference]) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Are you sure you want to cancel this booking? This action cannot be undone.
                    </div>
                    
                    <div class="mb-3">
                        <label for="cancellationReason" class="form-label">Cancellation Reason</label>
                        <textarea class="form-control" id="cancellationReason" name="cancellation_reason" rows="3" required></textarea>
                    </div>
                    
                    @if(isset($booking->tour) && isset($booking->tour->cancellation_policy))
                    <div class="mt-3">
                        <h6>Cancellation Policy</h6>
                        <p class="small text-muted">{{ $booking->tour->cancellation_policy }}</p>
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Confirm Cancellation</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection