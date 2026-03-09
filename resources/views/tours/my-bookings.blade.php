<!-- filepath: c:\xampp\htdocs\GNC\resources\views\tours\my-bookings.blade.php -->

@extends('layouts.main')

@section('title', 'My Tour Bookings')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col">
            <h1 class="fw-bold">My Tour Bookings</h1>
            <p class="text-muted">Manage and view your tour bookings</p>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-3">
            <!-- Sidebar -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="mb-3">Booking Management</h5>
                    <div class="nav flex-column nav-pills">
                        <a class="nav-link active" href="{{ route('tours.my-bookings') }}">
                            <i class="fas fa-compass me-2"></i>Tour Bookings
                        </a>
                        <a class="nav-link" href="{{ route('booking.history') }}">
                            <i class="fas fa-plane me-2"></i>Flight Bookings
                        </a>
                        <a class="nav-link" href="{{ route('hotel.bookings') }}">
                            <i class="fas fa-hotel me-2"></i>Hotel Bookings
                        </a>
                    </div>
                    
                    <hr>
                    
                    <h5 class="mb-3">Quick Links</h5>
                    <div class="nav flex-column nav-pills">
                        <a class="nav-link" href="{{ route('tours.index') }}">
                            <i class="fas fa-search me-2"></i>Browse Tours
                        </a>
                        <a class="nav-link" href="{{ route('profile') }}">
                            <i class="fas fa-user me-2"></i>My Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-9">
            <!-- Tour Bookings -->
            @if(count($tourBookings ?? []) > 0 || count($generalBookings ?? []) > 0)
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Your Tour Bookings</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">Booking Ref</th>
                                    <th scope="col">Tour</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- TourBooking model bookings -->
                                @foreach($tourBookings ?? [] as $booking)
                                    <tr>
                                        <td>
                                            <span class="fw-medium">{{ $booking->booking_reference }}</span><br>
                                            <small class="text-muted">{{ $booking->created_at->format('M d, Y') }}</small>
                                        </td>
                                        <td>
                                            {{ $booking->tour->name ?? 'Tour' }}<br>
                                            <small class="text-muted">{{ $booking->tour->city ?? 'Location' }}</small>
                                        </td>
                                        <td>
                                            {{ $booking->tour_date->format('M d, Y') }}<br>
                                            <small class="text-muted">{{ $booking->participants }} {{ $booking->participants > 1 ? 'participants' : 'participant' }}</small>
                                        </td>
                                        <td>
                                            <span class="text-success fw-bold">{{ $booking->currency }} {{ number_format($booking->total_amount, 2) }}</span>
                                        </td>
                                        <td>
                                            @if($booking->status == 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($booking->status == 'confirmed')
                                                <span class="badge bg-success">Confirmed</span>
                                            @elseif($booking->status == 'cancelled')
                                                <span class="badge bg-danger">Cancelled</span>
                                            @elseif($booking->status == 'completed')
                                                <span class="badge bg-info">Completed</span>
                                            @else
                                                <span class="badge bg-secondary">{{ ucfirst($booking->status) }}</span>
                                            @endif
                                            
                                            @if($booking->payment_status != 'paid')
                                                <br>
                                                <small class="text-danger">Payment: {{ ucfirst($booking->payment_status) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Actions
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('tour.confirmation', ['reference' => $booking->booking_reference]) }}">
                                                            <i class="fas fa-file-alt me-2"></i>View Details
                                                        </a>
                                                    </li>
                                                    
                                                    @if($booking->payment_status == 'pending')
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('tour.payment', ['reference' => $booking->booking_reference]) }}">
                                                                <i class="fas fa-credit-card me-2"></i>Complete Payment
                                                            </a>
                                                        </li>
                                                    @endif
                                                    
                                                    @if($booking->status != 'cancelled' && $booking->status != 'completed')
                                                        <li>
                                                            <button class="dropdown-item text-danger cancel-booking-btn" 
                                                                   data-bs-toggle="modal" 
                                                                   data-bs-target="#cancelBookingModal" 
                                                                   data-reference="{{ $booking->booking_reference }}">
                                                                <i class="fas fa-times me-2"></i>Cancel Booking
                                                            </button>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                
                                <!-- General Booking model bookings -->
                                @foreach($generalBookings ?? [] as $booking)
                                    @php
                                        $tourDetails = json_decode($booking->hotel_details ?? '{}', true);
                                        $travelerDetails = json_decode($booking->traveler_details ?? '{}', true);
                                    @endphp
                                    <tr>
                                        <td>
                                            <span class="fw-medium">{{ $booking->booking_reference }}</span><br>
                                            <small class="text-muted">{{ $booking->created_at->format('M d, Y') }}</small>
                                        </td>
                                        <td>
                                            {{ $booking->title }}<br>
                                            <small class="text-muted">{{ $tourDetails['city'] ?? 'Location' }}</small>
                                        </td>
                                        <td>
                                            {{ $travelerDetails['tour_date'] ?? 'Date not specified' }}<br>
                                            <small class="text-muted">{{ $booking->adults ?? 1 }} {{ $booking->adults > 1 ? 'participants' : 'participant' }}</small>
                                        </td>
                                        <td>
                                            <span class="text-success fw-bold">{{ $booking->currency }} {{ number_format($booking->total_amount, 2) }}</span>
                                        </td>
                                        <td>
                                            @if($booking->status == 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($booking->status == 'confirmed')
                                                <span class="badge bg-success">Confirmed</span>
                                            @elseif($booking->status == 'cancelled')
                                                <span class="badge bg-danger">Cancelled</span>
                                            @elseif($booking->status == 'completed')
                                                <span class="badge bg-info">Completed</span>
                                            @else
                                                <span class="badge bg-secondary">{{ ucfirst($booking->status) }}</span>
                                            @endif
                                            
                                            @if($booking->payment_status != 'paid')
                                                <br>
                                                <small class="text-danger">Payment: {{ ucfirst($booking->payment_status) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Actions
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('booking.confirmation', ['reference' => $booking->booking_reference]) }}">
                                                            <i class="fas fa-file-alt me-2"></i>View Details
                                                        </a>
                                                    </li>
                                                    
                                                    @if($booking->payment_status == 'pending')
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('booking.payment', ['reference' => $booking->booking_reference]) }}">
                                                                <i class="fas fa-credit-card me-2"></i>Complete Payment
                                                            </a>
                                                        </li>
                                                    @endif
                                                    
                                                    @if($booking->status != 'cancelled' && $booking->status != 'completed')
                                                        <li>
                                                            <button class="dropdown-item text-danger cancel-booking-btn" 
                                                                   data-bs-toggle="modal" 
                                                                   data-bs-target="#cancelBookingModal" 
                                                                   data-reference="{{ $booking->booking_reference }}">
                                                                <i class="fas fa-times me-2"></i>Cancel Booking
                                                            </button>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="card shadow-sm">
                    <div class="card-body p-5 text-center">
                        <div class="mb-4">
                            <i class="fas fa-compass fa-3x text-muted"></i>
                        </div>
                        <h3>No Tour Bookings Yet</h3>
                        <p class="text-muted">You haven't booked any tours yet. Start exploring our amazing tours in East Africa!</p>
                        <a href="{{ route('tours.index') }}" class="btn btn-primary">
                            <i class="fas fa-search me-2"></i>Browse Tours
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Cancel Booking Modal -->
<div class="modal fade" id="cancelBookingModal" tabindex="-1" aria-labelledby="cancelBookingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelBookingModalLabel">Cancel Booking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="cancelBookingForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Warning:</strong> Cancellation may be subject to fees depending on the tour's cancellation policy.
                    </div>
                    
                    <div class="mb-3">
                        <label for="cancellation_reason" class="form-label">Please provide a reason for cancellation:</label>
                        <textarea class="form-control" id="cancellation_reason" name="cancellation_reason" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times me-1"></i>Confirm Cancellation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set up cancel booking modal
    const cancelBtns = document.querySelectorAll('.cancel-booking-btn');
    const cancelForm = document.getElementById('cancelBookingForm');
    
    cancelBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const reference = this.dataset.reference;
            cancelForm.action = `/tours/cancel/${reference}`;
        });
    });
});
</script>
@endsection