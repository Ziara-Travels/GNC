<?php
@extends('layouts.main')

@section('title', 'Flight Booking Confirmation')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-check-circle me-2"></i>
                        Flight Details Confirmation
                    </h4>
                </div>
                <div class="card-body">
                    @if(isset($flight))
                        @php
                            $firstSegment = $flight->itineraries[0]->segments[0];
                            $lastSegment = end($flight->itineraries[0]->segments);
                            $carrierCode = $firstSegment->carrierCode;
                            $airlineName = isset($flight->dictionaries->carriers->{$carrierCode}) 
                                ? $flight->dictionaries->carriers->{$carrierCode} 
                                : $carrierCode;
                            $numberOfStops = count($flight->itineraries[0]->segments) - 1;
                            $cabin = $flight->travelerPricings[0]->fareDetailsBySegment[0]->cabin ?? 'N/A';
                        @endphp
                        
                        <div class="mb-4 border-bottom pb-4">
                            <h5 class="fw-bold mb-3">Flight Summary</h5>
                            
                            <div class="d-flex justify-content-between mb-3">
                                <div>
                                    <div class="small text-muted">Airline</div>
                                    <div class="fw-bold">{{ $airlineName }}</div>
                                </div>
                                <div>
                                    <div class="small text-muted">Flight Number</div>
                                    <div class="fw-bold">{{ $carrierCode }}-{{ $firstSegment->number }}</div>
                                </div>
                                <div>
                                    <div class="small text-muted">Class</div>
                                    <div class="fw-bold">{{ ucwords(str_replace('_', ' ', strtolower($cabin))) }}</div>
                                </div>
                            </div>
                            
                            <div class="row align-items-center g-3">
                                <div class="col-md-5">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light rounded-circle p-3 me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                            <i class="fas fa-plane-departure text-primary"></i>
                                        </div>
                                        <div>
                                            <div class="small text-muted">Departure</div>
                                            <div class="fw-bold fs-5">{{ date('H:i', strtotime($firstSegment->departure->at)) }}</div>
                                            <div class="small">{{ date('D, M d', strtotime($firstSegment->departure->at)) }}</div>
                                            <div class="small fw-bold mt-1">{{ $firstSegment->departure->iataCode }}</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-2 text-center">
                                    <div class="d-none d-md-block">
                                        <i class="fas fa-long-arrow-alt-right text-muted"></i>
                                    </div>
                                    <div class="d-md-none py-2">
                                        <i class="fas fa-long-arrow-alt-down text-muted"></i>
                                    </div>
                                </div>
                                
                                <div class="col-md-5">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light rounded-circle p-3 me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                            <i class="fas fa-plane-arrival text-primary"></i>
                                        </div>
                                        <div>
                                            <div class="small text-muted">Arrival</div>
                                            <div class="fw-bold fs-5">{{ date('H:i', strtotime($lastSegment->arrival->at)) }}</div>
                                            <div class="small">{{ date('D, M d', strtotime($lastSegment->arrival->at)) }}</div>
                                            <div class="small fw-bold mt-1">{{ $lastSegment->arrival->iataCode }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between mt-3">
                                <div>
                                    <span class="badge bg-{{ $numberOfStops === 0 ? 'success' : 'warning' }}">
                                        {{ $numberOfStops === 0 ? 'Non-stop' : $numberOfStops.' '.($numberOfStops === 1 ? 'Stop' : 'Stops') }}
                                    </span>
                                </div>
                                <div>
                                    <span class="badge bg-info">
                                        <i class="fas fa-clock me-1"></i> {{ $flight->itineraries[0]->duration }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4 border-bottom pb-4">
                            <h5 class="fw-bold mb-3">Price Details</h5>
                            
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                        @foreach($flight->travelerPricings as $index => $traveler)
                                            <tr>
                                                <td>{{ ucfirst(strtolower($traveler->travelerType)) }} {{ $index + 1 }}</td>
                                                <td>Base Price</td>
                                                <td class="text-end">{{ $flight->price->currency }} {{ $traveler->price->base }}</td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td>Taxes & Fees</td>
                                                <td class="text-end">{{ $flight->price->currency }} {{ number_format((float)$traveler->price->total - (float)$traveler->price->base, 2) }}</td>
                                            </tr>
                                        @endforeach
                                        <tr class="table-light">
                                            <th colspan="2">Total Price</th>
                                            <th class="text-end">{{ $flight->price->currency }} {{ $flight->price->total }}</th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        @guest
                            <div class="alert alert-info">
                                <div class="d-flex">
                                    <div class="me-3">
                                        <i class="fas fa-info-circle fa-2x"></i>
                                    </div>
                                    <div>
                                        <h5 class="alert-heading">Login Required</h5>
                                        <p class="mb-0">Please login or create an account to complete your booking.</p>
                                    </div>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-end">
                                    <a href="{{ route('login') }}" class="btn btn-primary me-2">
                                        <i class="fas fa-sign-in-alt me-1"></i> Login
                                    </a>
                                    <a href="{{ route('register') }}" class="btn btn-outline-primary">
                                        <i class="fas fa-user-plus me-1"></i> Create Account
                                    </a>
                                </div>
                            </div>
                        @else
                            <form action="{{ route('booking.create') }}" method="POST">
                                @csrf
                                <input type="hidden" name="flight_data" value="{{ json_encode($flight) }}">
                                
                                <div class="mb-4 border-bottom pb-4">
                                    <h5 class="fw-bold mb-3">Passenger Information</h5>
                                    
                                    @for($i = 0; $i < count($flight->travelerPricings); $i++)
                                        <div class="card mb-3">
                                            <div class="card-header bg-light">
                                                <h6 class="mb-0">Passenger {{ $i + 1 }} ({{ ucfirst(strtolower($flight->travelerPricings[$i]->travelerType)) }})</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label">First Name</label>
                                                        <input type="text" class="form-control" name="passengers[{{ $i }}][firstName]" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Last Name</label>
                                                        <input type="text" class="form-control" name="passengers[{{ $i }}][lastName]" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Date of Birth</label>
                                                        <input type="date" class="form-control" name="passengers[{{ $i }}][dob]" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Gender</label>
                                                        <select class="form-select" name="passengers[{{ $i }}][gender]" required>
                                                            <option value="">Select Gender</option>
                                                            <option value="MALE">Male</option>
                                                            <option value="FEMALE">Female</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endfor
                                </div>
                                
                                <div class="mb-4">
                                    <h5 class="fw-bold mb-3">Contact Information</h5>
                                    
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Email</label>
                                            <input type="email" class="form-control" name="contact[email]" value="{{ Auth::user()->email }}" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Phone Number</label>
                                            <input type="tel" class="form-control" name="contact[phone]" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('flights.results') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left me-1"></i> Back to Results
                                    </a>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-check-circle me-1"></i> Confirm & Book
                                    </button>
                                </div>
                            </form>
                        @endguest
                    @else
                        <div class="alert alert-danger">
                            <h5 class="alert-heading">Error</h5>
                            <p>No flight data found. Please return to the search page and try again.</p>
                            <hr>
                            <a href="/" class="btn btn-primary">
                                <i class="fas fa-search me-1"></i> New Search
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection