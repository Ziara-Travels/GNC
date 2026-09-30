@extends('layouts.main')

@section('content')
    <!-- Hero Section -->
    <div class="hero-section" style="background-color: #2b2e3d;">
        <div class="container">
            <div class="row align-items-center py-5">
                <div class="col-12 text-center text-white">
                    <h1 class="display-4">Complete Your Booking</h1>
                    <p class="lead">You're just a few steps away from your journey</p>
                </div>
            </div>
        </div>
    </div>


    <div class="main-content" style="background-color: #ffffff;">
    <div class="container py-5" style="background-color: #f8f9fa;">
        <div class="row">
            <!-- Flight Details Card -->
            <div class="col-lg-7 mb-4">
                <div class="card shadow-lg h-100">
                    <div class="card-header" style="background-color: #2b2e3d;">
                        <h3 class="mb-0 text-white">
                            <i class="fas fa-plane-departure me-2" style="color: #e3a300;"></i>
                            Flight Details
                        </h3>
                    </div>
                    <div class="card-body">
                        @if(isset($data->flightOffers[0]))
                            <div class="flight-info">
                                <!-- Flight Route -->
                                <div class="route-info p-4 mb-4 rounded" style="background-color: #f8f9fa;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <!-- Departure -->
                                        <div class="text-center">
                                            <div class="fs-2 fw-bold text-primary">
                                                {{ $data->flightOffers[0]->itineraries[0]->segments[0]->departure->iataCode }}
                                            </div>
                                            <div class="fs-5">
                                                {{ Carbon\Carbon::parse($data->flightOffers[0]->itineraries[0]->segments[0]->departure->at)->format('h:i A') }}
                                            </div>
                                        </div>

                                        <!-- Flight Path -->
                                        <div class="flight-path flex-grow-1 px-4">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <div class="flight-line"></div>
                                                <i class="fas fa-plane" style="color: #e3a300; transform: rotate(90deg);"></i>
                                            </div>
                                            <div class="text-center mt-2">
                                                <span class="badge" style="background-color: #e3a300; color: white; font-size: 0.9rem; padding: 0.5rem 1rem;">
                                                    <i class="fas fa-clock me-1"></i>
                                                    {{ $duration ?? 'Duration not available' }}
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Arrival -->
                                        <div class="text-center">
                                            <div class="fs-2 fw-bold text-primary">
                                                {{ $data->flightOffers[0]->itineraries[0]->segments[0]->arrival->iataCode }}
                                            </div>
                                            <div class="fs-5">
                                                {{ Carbon\Carbon::parse($data->flightOffers[0]->itineraries[0]->segments[0]->arrival->at)->format('h:i A') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Flight Details -->
                                <div class="details-grid p-4 rounded" style="background-color: #f8f9fa;">
                                <div class="row g-4">
                                    <!-- Flight Information -->
                                    <div class="col-md-6">
                                        <div class="detail-item">
                                            <h5 class="text-muted mb-3">
                                                <i class="fas fa-info-circle me-2" style="color: #e3a300;"></i>
                                                Flight Information
                                            </h5>
                                            <div class="ps-4">
                                                <p class="mb-2">
                                                    <strong>Airline:</strong>
                                                    <span class="ms-2">{{ $data->flightOffers[0]->validatingAirlineCodes[0] }}</span>
                                                </p>
                                                <p class="mb-2">
                                                    <strong>Flight Number:</strong>
                                                    <span class="ms-2">{{ $data->flightOffers[0]->itineraries[0]->segments[0]->number }}</span>
                                                </p>
                                                <p class="mb-2">
                                                    <strong>Aircraft:</strong>
                                                    <span class="ms-2">{{ $data->flightOffers[0]->itineraries[0]->segments[0]->aircraft->code }}</span>
                                                </p>
                                                <p class="mb-0">
                                                    <strong>Class:</strong>
                                                    <span class="ms-2">{{ $data->flightOffers[0]->travelerPricings[0]->fareDetailsBySegment[0]->cabin }}</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Travel Details -->
                                    <div class="col-md-6">
                                        <div class="detail-item">
                                            <h5 class="text-muted mb-3">
                                                <i class="fas fa-calendar-alt me-2" style="color: #e3a300;"></i>
                                                Travel Details
                                            </h5>
                                            <div class="ps-4">
                                                <p class="mb-2">
                                                    <strong>Date:</strong>
                                                    <span class="ms-2">{{ Carbon\Carbon::parse($data->flightOffers[0]->itineraries[0]->segments[0]->departure->at)->format('D, M d, Y') }}</span>
                                                </p>
                                                <p class="mb-2">
                                                    <strong>Duration:</strong>
                                                    <span class="ms-2">
                                                    {{ $duration ?? 'Duration not available' }}

                                                    </span>
                                                </p>
                                                <p class="mb-0">
                                                    <strong>Stops:</strong>
                                                    <span class="ms-2">{{ count($data->flightOffers[0]->itineraries[0]->segments) - 1 }} stops</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </div>

                                <!-- Proceed to Checkout Button -->
                                @auth
                                    <form action="{{ route('booking.process') }}" method="POST" class="mt-4">
                                        @csrf
                                        <input type="hidden" name="flight_data" value="{{ json_encode($data) }}">
                                        <button type="submit" class="btn btn-lg w-100" 
                                                style="background-color: #e3a300; color: white;">
                                            <i class="fas fa-shopping-cart me-2"></i>
                                            Proceed to Checkout
                                        </button>
                                    </form>
                                @else
                                    <div class="mt-4">
                                        <a href="{{ route('register') }}" class="btn btn-lg w-100" 
                                        style="background-color: #e3a300; color: white;">
                                            <i class="fas fa-user-plus me-2"></i>
                                            Register to Continue
                                        </a>
                                        <div class="text-center mt-3">
                                            <span class="text-muted">Already have an account?</span>
                                            <a href="{{ route('login') }}" class="ms-2" style="color: #e3a300;">
                                                Login here
                                            </a>
                                        </div>
                                    </div>
                                @endauth
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Flight details are not available
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Price Summary Card -->
            <div class="col-lg-5">
                <div class="card shadow-lg">
                    <div class="card-header" style="background-color: #2b2e3d;">
                        <h3 class="mb-0 text-white">
                            <i class="fas fa-receipt me-2" style="color: #e3a300;"></i>
                            Price Summary
                        </h3>
                    </div>
                    <div class="card-body">
            <div class="price-details">
            <!-- Base Fare -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted">Base Fare</span>
                <span class="fw-bold">${{ number_format($data->flightOffers[0]->price->base, 2) }}</span>
            </div>

           <!-- Taxes and Fees -->
           <div class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-muted">Taxes & Fees</span>
            <span class="fw-bold">${{ number_format($data->flightOffers[0]->price->total - $data->flightOffers[0]->price->base, 2) }}</span>
          </div>

           <!-- Divider -->
           <hr class="my-3">

         <!-- Total Price -->
           <div class="d-flex justify-content-between align-items-center mb-3">
            <span class="h5 mb-0">Total Price</span>
            <div class="text-end">
                <h4 class="mb-0" style="color: #e3a300;">${{ number_format($data->flightOffers[0]->price->total, 2) }}</h4>
                @php
                    $exchange_rate = 130.50;
                    $price_kes = $data->flightOffers[0]->price->total * $exchange_rate;
                @endphp
                <small class="text-muted">KES {{ number_format($price_kes, 2) }}</small>
            </div>
         </div>

         <!-- Price Notes -->
         <div class="price-notes mt-4">
            <small class="text-muted">
                <i class="fas fa-info-circle me-1"></i>
                Prices include all applicable taxes and fees
            </small>
        </div>
    </div>
    </div>

    

    <style>
        .hero-section {
            position: relative;
            padding: 3rem 0;
            margin-bottom: 0;
        }

        .main-content {
            min-height: calc(100vh - 300px);
            width: 100%;
        }

        .flight-line {
            height: 2px;
            background: #e3a300;
            flex-grow: 1;
            position: relative;
            margin: 0 15px;
        }

        .route-info {
            border: 1px solid rgba(0,0,0,0.1);
        }

        .detail-item {
            background-color: #f8f9fa;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .detail-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        .card {
            border: none;
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(227, 163, 0, 0.3);
        }

        /* Add responsive padding */
        @media (max-width: 768px) {
            .hero-section {
                padding: 2rem 0;
            }
            .main-content {
                padding: 1rem;
            }
        }
    </style>
@endsection