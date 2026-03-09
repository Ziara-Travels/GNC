@extends('layouts.main')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow border-0">
                <div class="card-body p-5 text-center">
                    <div class="mb-4">
                        <div class="avatar-lg bg-success rounded-circle mx-auto mb-4">
                            <i class="fas fa-check-circle fa-3x text-white" style="line-height: 1.5;"></i>
                        </div>
                        <h2 class="mb-2">Booking Confirmed!</h2>
                        <p class="text-muted">Your booking has been successfully processed.</p>
                    </div>
                    
                    <div class="mb-4">
                        <h5>Booking Reference</h5>
                        <div class="bg-light p-3 rounded mb-4">
                            <h3 class="mb-0">{{ $booking->booking_reference }}</h3>
                        </div>
                        <p class="text-muted">Please keep this reference number for your records.</p>
                    </div>
                    
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Booking Details</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <p class="text-muted mb-1">Booking Type</p>
                                    <p class="fw-bold mb-0">{{ ucfirst($booking->booking_type) }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <p class="text-muted mb-1">Status</p>
                                    <p class="fw-bold mb-0">
                                        <span class="badge bg-success">{{ ucfirst($booking->status) }}</span>
                                    </p>
                                </div>
                                
                                @if($booking->booking_type == 'flight')
                                <div class="col-md-6 mb-3">
                                    <p class="text-muted mb-1">Route</p>
                                    <p class="fw-bold mb-0">{{ $booking->departure_airport }} to {{ $booking->arrival_airport }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <p class="text-muted mb-1">Date</p>
                                    <p class="fw-bold mb-0">{{ \Carbon\Carbon::parse($booking->departure_time)->format('d M Y, H:i') }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <p class="text-muted mb-1">Flight Number</p>
                                    <p class="fw-bold mb-0">{{ $booking->flight_number ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <p class="text-muted mb-1">Class</p>
                                    <p class="fw-bold mb-0">{{ $booking->travel_class }}</p>
                                </div>
                                @else
                                <div class="col-md-6 mb-3">
                                    <p class="text-muted mb-1">Hotel</p>
                                    <p class="fw-bold mb-0">{{ json_decode($booking->hotel_details)->hotelName ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <p class="text-muted mb-1">Check-in</p>
                                    <p class="fw-bold mb-0">{{ $booking->check_in_date }}</p>
                                </div>
                                @endif
                                
                                <div class="col-md-6 mb-3">
                                    <p class="text-muted mb-1">Payment Method</p>
                                    <p class="fw-bold mb-0">{{ ucfirst($booking->payment_method) }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <p class="text-muted mb-1">Amount Paid</p>
                                    <p class="fw-bold mb-0">{{ $booking->currency }} {{ number_format($booking->amount, 2) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <p class="text-muted">A confirmation email has been sent to {{ $booking->contact_email }}</p>
                    </div>
                    
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('dashboard') }}" class="btn btn-primary">Go to My Bookings</a>
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary">Back to Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection