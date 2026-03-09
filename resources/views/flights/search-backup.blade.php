
@extends('layouts.main')

@section('content')
<!-- Flight Search Header -->
<div class="bg-gradient-to-br from-blue-900 via-blue-800 to-indigo-900 min-h-screen">
    <!-- Navigation Header -->
    <div class="bg-white/10 backdrop-blur-md border-b border-white/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-8">
                    <h2 class="text-xl font-bold text-white">Flight Search</h2>
                    <div class="hidden md:flex space-x-6">
                        <button class="text-white/90 hover:text-white transition-colors duration-200 flex items-center space-x-2">
                            <i class="fas fa-plane text-sm"></i>
                            <span>Flights</span>
                        </button>
                       
                       
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <button class="text-white/90 hover:text-white transition-colors duration-200">
                        <i class="fas fa-user-circle text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Hero Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-12">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-6xl font-bold text-white mb-4">
                Find Your Perfect
                <span class="bg-gradient-to-r from-yellow-400 to-orange-500 bg-clip-text text-transparent">Flight</span>
            </h1>
            <p class="text-xl text-white/80 max-w-2xl mx-auto">
                Search millions of flights from hundreds of airlines and travel sites
            </p>
        </div>

        <!-- Search Form -->
        <div class="max-w-5xl mx-auto">
            <div class="bg-white rounded-2xl shadow-2xl p-6 md:p-8">
                <!-- Trip Type Tabs -->
                <div class="flex flex-wrap gap-2 mb-6">
                    <button class="trip-type-btn active px-6 py-3 rounded-full font-semibold transition-all duration-200 bg-blue-600 text-white" data-type="round-trip">
                        <i class="fas fa-exchange-alt mr-2"></i>Round Trip
                    </button>
                    <button class="trip-type-btn px-6 py-3 rounded-full font-semibold transition-all duration-200 bg-gray-100 text-gray-700 hover:bg-gray-200" data-type="one-way">
                        <i class="fas fa-long-arrow-alt-right mr-2"></i>One Way
                    </button>
                    <button class="trip-type-btn px-6 py-3 rounded-full font-semibold transition-all duration-200 bg-gray-100 text-gray-700 hover:bg-gray-200" data-type="multi-city">
                        <i class="fas fa-route mr-2"></i>Multi-City
                    </button>
                </div>

                    <!-- Trip Type Content -->
                    <div class="tab-content" id="tripTypeContent">
                        <!-- Round Trip Form -->
                        <div class="tab-pane fade show active" id="round-trip" role="tabpanel">
                            <form action="/api/search" method="POST">
                                @csrf
                                <input type="hidden" name="trip_type" value="round_trip">
                                <div class="row g-2 align-items-end mb-3">
                                    <!-- From -->
                                    <div class="col-lg-2 col-md-4">
                                        <label class="form-label fw-medium text-muted mb-1 small" style="font-family: 'Jost', sans-serif;">From</label>
                                        <div class="position-relative">
                                            <i class="fas fa-plane-departure position-absolute text-muted" style="left: 10px; top: 50%; transform: translateY(-50%); z-index: 5; font-size: 0.8rem;"></i>
                                            <input type="text" 
                                                class="form-control border-2 ps-4 airport-autocomplete" 
                                                id="rt-origin" 
                                                name="from" 
                                                placeholder="Country, city or airport"
                                                style="border-radius: 8px; padding: 0.5rem 0.5rem 0.5rem 2rem; border-color: #e5e7eb; font-size: 0.9rem; font-family: 'Jost', sans-serif; color: #000000;"
                                                required autocomplete="off">
                                            <input type="hidden" name="from_iata" id="from_iata">
                                        </div>
                                        <div class="airport-suggestions position-absolute w-100 mt-1 bg-white rounded shadow-lg" id="fromSuggestions" style="z-index: 1000;"></div>
                                    </div>

                                    <!-- To -->
                                    <div class="col-lg-2 col-md-4">
                                        <label class="form-label fw-medium text-muted mb-1 small" style="font-family: 'Jost', sans-serif;">To</label>
                                        <div class="position-relative">
                                            <i class="fas fa-plane-arrival position-absolute text-muted" style="left: 10px; top: 50%; transform: translateY(-50%); z-index: 5; font-size: 0.8rem;"></i>
                                            <input type="text" 
                                                class="form-control border-2 ps-4 airport-autocomplete" 
                                                id="rt-destination" 
                                                name="to" 
                                                placeholder="Country, city or airport"
                                                style="border-radius: 8px; padding: 0.5rem 0.5rem 0.5rem 2rem; border-color: #e5e7eb; font-size: 0.9rem; font-family: 'Jost', sans-serif; color: #000000;"
                                                required autocomplete="off">
                                            <input type="hidden" name="to_iata" id="to_iata">
                                        </div>
                                        <div class="airport-suggestions position-absolute w-100 mt-1 bg-white rounded shadow-lg" id="toSuggestions" style="z-index: 1000;"></div>
                                    </div>

                                    <!-- Depart -->
                                    <div class="col-lg-1 col-md-3">
                                        <label class="form-label fw-medium text-muted mb-1 small" style="font-family: 'Jost', sans-serif;">Depart</label>
                                        <div class="position-relative">
                                            <i class="fas fa-calendar position-absolute text-muted" style="left: 10px; top: 50%; transform: translateY(-50%); z-index: 5; font-size: 0.8rem;"></i>
                                            <input type="date" 
                                                class="form-control border-2 ps-4" 
                                                id="rt-departure" 
                                                name="date"
                                                style="border-radius: 8px; padding: 0.5rem 0.5rem 0.5rem 2rem; border-color: #e5e7eb; font-size: 0.9rem; font-family: 'Jost', sans-serif; color: #000000;"
                                                required>
                                        </div>
                                    </div>

                                    <!-- Return -->
                                    <div class="col-lg-1 col-md-3">
                                        <label class="form-label fw-medium text-muted mb-1 small" style="font-family: 'Jost', sans-serif;">Return</label>
                                        <div class="position-relative">
                                            <i class="fas fa-calendar position-absolute text-muted" style="left: 10px; top: 50%; transform: translateY(-50%); z-index: 5; font-size: 0.8rem;"></i>
                                            <input type="date" 
                                                class="form-control border-2 ps-4" 
                                                id="rt-return" 
                                                name="return_date"
                                                style="border-radius: 8px; padding: 0.5rem 0.5rem 0.5rem 2rem; border-color: #e5e7eb; font-size: 0.9rem; font-family: 'Jost', sans-serif; color: #000000;">
                                        </div>
                                    </div>

                                    <!-- Adults -->
                                    <div class="col-lg-1 col-md-2">
                                        <label class="form-label fw-medium text-muted mb-1 small" style="font-family: 'Jost', sans-serif;">Adults</label>
                                        <div class="position-relative">
                                            <i class="fas fa-user position-absolute text-muted" style="left: 10px; top: 50%; transform: translateY(-50%); z-index: 5; font-size: 0.8rem;"></i>
                                            <input type="number" 
                                                class="form-control border-2 ps-4 text-center" 
                                                name="adults" 
                                                value="1" 
                                                min="0" 
                                                max="10"
                                                style="border-radius: 8px; padding: 0.5rem 0.5rem 0.5rem 2rem; border-color: #e5e7eb; font-size: 0.9rem; font-family: 'Jost', sans-serif; color: #000000;"
                                                required>
                                        </div>
                                    </div>

                                    <!-- Children -->
                                    <div class="col-lg-1 col-md-2">
                                        <label class="form-label fw-medium text-muted mb-1 small" style="font-family: 'Jost', sans-serif;">Children</label>
                                        <div class="position-relative">
                                            <i class="fas fa-child position-absolute text-muted" style="left: 10px; top: 50%; transform: translateY(-50%); z-index: 5; font-size: 0.8rem;"></i>
                                            <input type="number" 
                                                class="form-control border-2 ps-4 text-center" 
                                                name="children" 
                                                value="0" 
                                                min="0" 
                                                max="10"
                                                style="border-radius: 8px; padding: 0.5rem 0.5rem 0.5rem 2rem; border-color: #e5e7eb; font-size: 0.9rem; font-family: 'Jost', sans-serif; color: #000000;">
                                        </div>
                                    </div>

                                    <!-- Infants -->
                                    <div class="col-lg-1 col-md-2">
                                        <label class="form-label fw-medium text-muted mb-1 small" style="font-family: 'Jost', sans-serif;">Infants</label>
                                        <div class="position-relative">
                                            <i class="fas fa-baby position-absolute text-muted" style="left: 10px; top: 50%; transform: translateY(-50%); z-index: 5; font-size: 0.8rem;"></i>
                                            <input type="number" 
                                                class="form-control border-2 ps-4 text-center" 
                                                name="infants" 
                                                value="0" 
                                                min="0" 
                                                max="10"
                                                style="border-radius: 8px; padding: 0.5rem 0.5rem 0.5rem 2rem; border-color: #e5e7eb; font-size: 0.9rem; font-family: 'Jost', sans-serif; color: #000000;">
                                        </div>
                                    </div>

                                    <!-- Travel Class -->
                                    <div class="col-lg-2 col-md-3">
                                        <label class="form-label fw-medium text-muted mb-1 small" style="font-family: 'Jost', sans-serif;">Travel Class</label>
                                        <div class="position-relative">
                                            <i class="fas fa-star position-absolute text-muted" style="left: 10px; top: 50%; transform: translateY(-50%); z-index: 5; font-size: 0.8rem;"></i>
                                            <select class="form-select border-2 ps-4" 
                                                    name="travel_class"
                                                    style="border-radius: 8px; padding: 0.5rem 0.5rem 0.5rem 2rem; border-color: #e5e7eb; font-size: 0.9rem; font-family: 'Jost', sans-serif; color: #000000;">
                                                <option value="ECONOMY">Economy</option>
                                                <option value="PREMIUM_ECONOMY">Premium Economy</option>
                                                <option value="BUSINESS">Business</option>
                                                <option value="FIRST">First Class</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Search button -->
                                    <div class="col-lg-1 col-md-2">
                                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold d-flex align-items-center justify-content-center" style="border-radius: 8px; font-family: 'Jost', sans-serif; font-weight: 700;">
                                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                            <i class="fas fa-search me-1"></i>
                                            <span>Search</span>
                                        </button>
                                    </div>
                                </div>

                                <!-- Search options -->
                                <div class="d-flex flex-wrap gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="rt-nearbyAirports" name="nearby_airports">
                                        <label class="form-check-label text-muted small" for="rt-nearbyAirports" style="font-family: 'Jost', sans-serif;">
                                            Add nearby airports
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="rt-directFlights" name="non_stop">
                                        <label class="form-check-label text-muted small" for="rt-directFlights" style="font-family: 'Jost', sans-serif;">
                                            Non-stop flights only
                                        </label>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- One Way Trip Form -->
                        <div class="tab-pane fade" id="one-way" role="tabpanel">
                            <form action="/api/search" method="POST">
                                @csrf
                                <input type="hidden" name="trip_type" value="one_way">
                                <div class="row g-2 align-items-end mb-3">
                                    <!-- From -->
                                    <div class="col-lg-2 col-md-4">
                                        <label class="form-label fw-medium text-muted mb-1 small" style="font-family: 'Jost', sans-serif;">From</label>
                                        <div class="position-relative">
                                            <i class="fas fa-plane-departure position-absolute text-muted" style="left: 10px; top: 50%; transform: translateY(-50%); z-index: 5; font-size: 0.8rem;"></i>
                                            <input type="text" 
                                                class="form-control border-2 ps-4 airport-autocomplete" 
                                                id="ow-origin" 
                                                name="from" 
                                                placeholder="Country, city or airport"
                                                style="border-radius: 8px; padding: 0.5rem 0.5rem 0.5rem 2rem; border-color: #e5e7eb; font-size: 0.9rem; font-family: 'Jost', sans-serif; color: #000000;"
                                                required autocomplete="off">
                                            <input type="hidden" name="from_iata" id="ow-from-iata">
                                        </div>
                                        <div class="airport-suggestions position-absolute w-100 mt-1 bg-white rounded shadow-lg" id="owFromSuggestions" style="z-index: 1000;"></div>
                                    </div>

                                    <!-- To -->
                                    <div class="col-lg-2 col-md-4">
                                        <label class="form-label fw-medium text-muted mb-1 small" style="font-family: 'Jost', sans-serif;">To</label>
                                        <div class="position-relative">
                                            <i class="fas fa-plane-arrival position-absolute text-muted" style="left: 10px; top: 50%; transform: translateY(-50%); z-index: 5; font-size: 0.8rem;"></i>
                                            <input type="text" 
                                                class="form-control border-2 ps-4 airport-autocomplete" 
                                                id="ow-destination" 
                                                name="to" 
                                                placeholder="Country, city or airport"
                                                style="border-radius: 8px; padding: 0.5rem 0.5rem 0.5rem 2rem; border-color: #e5e7eb; font-size: 0.9rem; font-family: 'Jost', sans-serif; color: #000000;"
                                                required autocomplete="off">
                                            <input type="hidden" name="to_iata" id="ow-to-iata">
                                        </div>
                                        <div class="airport-suggestions position-absolute w-100 mt-1 bg-white rounded shadow-lg" id="owToSuggestions" style="z-index: 1000;"></div>
                                    </div>

                                    <!-- Depart -->
                                    <div class="col-lg-2 col-md-4">
                                        <label class="form-label fw-medium text-muted mb-1 small" style="font-family: 'Jost', sans-serif;">Depart</label>
                                        <div class="position-relative">
                                            <i class="fas fa-calendar position-absolute text-muted" style="left: 10px; top: 50%; transform: translateY(-50%); z-index: 5; font-size: 0.8rem;"></i>
                                            <input type="date" 
                                                class="form-control border-2 ps-4" 
                                                id="ow-departure" 
                                                name="date"
                                                style="border-radius: 8px; padding: 0.5rem 0.5rem 0.5rem 2rem; border-color: #e5e7eb; font-size: 0.9rem; font-family: 'Jost', sans-serif; color: #000000;"
                                                required>
                                        </div>
                                    </div>

                                    <!-- Adults -->
                                    <div class="col-lg-1 col-md-2">
                                        <label class="form-label fw-medium text-muted mb-1 small" style="font-family: 'Jost', sans-serif;">Adults</label>
                                        <div class="position-relative">
                                            <i class="fas fa-user position-absolute text-muted" style="left: 10px; top: 50%; transform: translateY(-50%); z-index: 5; font-size: 0.8rem;"></i>
                                            <input type="number" 
                                                class="form-control border-2 ps-4 text-center" 
                                                name="adults" 
                                                value="1" 
                                                min="0" 
                                                max="10"
                                                style="border-radius: 8px; padding: 0.5rem 0.5rem 0.5rem 2rem; border-color: #e5e7eb; font-size: 0.9rem; font-family: 'Jost', sans-serif; color: #000000;"
                                                required>
                                        </div>
                                    </div>

                                    <!-- Children -->
                                    <div class="col-lg-1 col-md-2">
                                        <label class="form-label fw-medium text-muted mb-1 small" style="font-family: 'Jost', sans-serif;">Children</label>
                                        <div class="position-relative">
                                            <i class="fas fa-child position-absolute text-muted" style="left: 10px; top: 50%; transform: translateY(-50%); z-index: 5; font-size: 0.8rem;"></i>
                                            <input type="number" 
                                                class="form-control border-2 ps-4 text-center" 
                                                name="children" 
                                                value="0" 
                                                min="0" 
                                                max="10"
                                                style="border-radius: 8px; padding: 0.5rem 0.5rem 0.5rem 2rem; border-color: #e5e7eb; font-size: 0.9rem; font-family: 'Jost', sans-serif; color: #000000;">
                                        </div>
                                    </div>

                                    <!-- Infants -->
                                    <div class="col-lg-1 col-md-2">
                                        <label class="form-label fw-medium text-muted mb-1 small" style="font-family: 'Jost', sans-serif;">Infants</label>
                                        <div class="position-relative">
                                            <i class="fas fa-baby position-absolute text-muted" style="left: 10px; top: 50%; transform: translateY(-50%); z-index: 5; font-size: 0.8rem;"></i>
                                            <input type="number" 
                                                class="form-control border-2 ps-4 text-center" 
                                                name="infants" 
                                                value="0" 
                                                min="0" 
                                                max="10"
                                                style="border-radius: 8px; padding: 0.5rem 0.5rem 0.5rem 2rem; border-color: #e5e7eb; font-size: 0.9rem; font-family: 'Jost', sans-serif; color: #000000;">
                                        </div>
                                    </div>

                                    <!-- Travel Class -->
                                    <div class="col-lg-2 col-md-3">
                                        <label class="form-label fw-medium text-muted mb-1 small" style="font-family: 'Jost', sans-serif;">Travel Class</label>
                                        <div class="position-relative">
                                            <i class="fas fa-star position-absolute text-muted" style="left: 10px; top: 50%; transform: translateY(-50%); z-index: 5; font-size: 0.8rem;"></i>
                                            <select class="form-select border-2 ps-4" 
                                                    name="travel_class"
                                                    style="border-radius: 8px; padding: 0.5rem 0.5rem 0.5rem 2rem; border-color: #e5e7eb; font-size: 0.9rem; font-family: 'Jost', sans-serif; color: #000000;">
                                                <option value="ECONOMY">Economy</option>
                                                <option value="PREMIUM_ECONOMY">Premium Economy</option>
                                                <option value="BUSINESS">Business</option>
                                                <option value="FIRST">First Class</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Search button -->
                                    <div class="col-lg-1 col-md-3">
                                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold d-flex align-items-center justify-content-center" style="border-radius: 8px; font-family: 'Jost', sans-serif; font-weight: 700;">
                                            <i class="fas fa-search me-1"></i>
                                            <span>Search</span>
                                        </button>
                                    </div>
                                </div>

                                <!-- Search options -->
                                <div class="d-flex flex-wrap gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="ow-nearbyAirports" name="nearby_airports">
                                        <label class="form-check-label text-muted small" for="ow-nearbyAirports" style="font-family: 'Jost', sans-serif;">
                                            Add nearby airports
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="ow-directFlights" name="non_stop" value="true">
                                        <label class="form-check-label text-muted small" for="ow-directFlights" style="font-family: 'Jost', sans-serif;">
                                            Non-stop flights only
                                        </label>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Multi-City Trip Form -->
                        <div class="tab-pane fade" id="multi-city" role="tabpanel">
                            <form action="/api/search" method="POST">
                                @csrf
                                <input type="hidden" name="trip_type" value="multi_city">
                                <div id="multi-city-flights">
                                    <!-- Flight Segment 1 -->
                                    <div class="flight-segment mb-3 p-3 border rounded" style="background-color: #f8f9fa;">
                                        <div class="row g-2 align-items-end">
                                            <div class="col-md-4">
                                                <label class="form-label fw-medium text-muted mb-1 small" style="font-family: 'Jost', sans-serif;">From</label>
                                                <div class="position-relative">
                                                    <i class="fas fa-plane-departure position-absolute text-muted" style="left: 10px; top: 50%; transform: translateY(-50%); z-index: 5; font-size: 0.8rem;"></i>
                                                    <input type="text" 
                                                        class="form-control border-2 ps-4 airport-autocomplete" 
                                                        name="from[]" 
                                                        placeholder="Country, city or airport"
                                                        style="border-radius: 8px; padding: 0.5rem 0.5rem 0.5rem 2rem; border-color: #e5e7eb; font-size: 0.9rem; font-family: 'Jost', sans-serif; color: #000000;"
                                                        required>
                                                    <input type="hidden" name="from_iata[]">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-medium text-muted mb-1 small" style="font-family: 'Jost', sans-serif;">To</label>
                                                <div class="position-relative">
                                                    <i class="fas fa-plane-arrival position-absolute text-muted" style="left: 10px; top: 50%; transform: translateY(-50%); z-index: 5; font-size: 0.8rem;"></i>
                                                    <input type="text" 
                                                        class="form-control border-2 ps-4 airport-autocomplete" 
                                                        name="to[]" 
                                                        placeholder="Country, city or airport"
                                                        style="border-radius: 8px; padding: 0.5rem 0.5rem 0.5rem 2rem; border-color: #e5e7eb; font-size: 0.9rem; font-family: 'Jost', sans-serif; color: #000000;"
                                                        required>
                                                    <input type="hidden" name="to_iata[]">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-medium text-muted mb-1 small" style="font-family: 'Jost', sans-serif;">Date</label>
                                                <div class="position-relative">
                                                    <i class="fas fa-calendar position-absolute text-muted" style="left: 10px; top: 50%; transform: translateY(-50%); z-index: 5; font-size: 0.8rem;"></i>
                                                    <input type="date" 
                                                        class="form-control border-2 ps-4" 
                                                        name="date[]"
                                                        style="border-radius: 8px; padding: 0.5rem 0.5rem 0.5rem 2rem; border-color: #e5e7eb; font-size: 0.9rem; font-family: 'Jost', sans-serif; color: #000000;"
                                                        required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Add Another Flight Button -->
                                <div class="text-center mb-3">
                                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="addFlightSegment()" style="font-family: 'Jost', sans-serif;">
                                        <i class="fas fa-plus me-2"></i>Add Another Flight
                                    </button>
                                </div>

                                <!-- Passengers & Class Row -->
                                <div class="row g-2 align-items-end mb-3">
                                    <!-- Adults -->
                                    <div class="col-md-2">
                                        <label class="form-label fw-medium text-muted mb-1 small" style="font-family: 'Jost', sans-serif;">Adults</label>
                                        <div class="position-relative">
                                            <i class="fas fa-user position-absolute text-muted" style="left: 10px; top: 50%; transform: translateY(-50%); z-index: 5; font-size: 0.8rem;"></i>
                                            <input type="number" 
                                                class="form-control border-2 ps-4 text-center" 
                                                name="adults" 
                                                value="1" 
                                                min="0" 
                                                max="10"
                                                style="border-radius: 8px; padding: 0.5rem 0.5rem 0.5rem 2rem; border-color: #e5e7eb; font-size: 0.9rem; font-family: 'Jost', sans-serif; color: #000000;"
                                                required>
                                        </div>
                                    </div>

                                    <!-- Children -->
                                    <div class="col-md-2">
                                        <label class="form-label fw-medium text-muted mb-1 small" style="font-family: 'Jost', sans-serif;">Children</label>
                                        <div class="position-relative">
                                            <i class="fas fa-child position-absolute text-muted" style="left: 10px; top: 50%; transform: translateY(-50%); z-index: 5; font-size: 0.8rem;"></i>
                                            <input type="number" 
                                                class="form-control border-2 ps-4 text-center" 
                                                name="children" 
                                                value="0" 
                                                min="0" 
                                                max="10"
                                                style="border-radius: 8px; padding: 0.5rem 0.5rem 0.5rem 2rem; border-color: #e5e7eb; font-size: 0.9rem; font-family: 'Jost', sans-serif; color: #000000;">
                                        </div>
                                    </div>

                                    <!-- Infants -->
                                    <div class="col-md-2">
                                        <label class="form-label fw-medium text-muted mb-1 small" style="font-family: 'Jost', sans-serif;">Infants</label>
                                        <div class="position-relative">
                                            <i class="fas fa-baby position-absolute text-muted" style="left: 10px; top: 50%; transform: translateY(-50%); z-index: 5; font-size: 0.8rem;"></i>
                                            <input type="number" 
                                                class="form-control border-2 ps-4 text-center" 
                                                name="infants" 
                                                value="0" 
                                                min="0" 
                                                max="10"
                                                style="border-radius: 8px; padding: 0.5rem 0.5rem 0.5rem 2rem; border-color: #e5e7eb; font-size: 0.9rem; font-family: 'Jost', sans-serif; color: #000000;">
                                        </div>
                                    </div>

                                    <!-- Travel Class -->
                                    <div class="col-md-3">
                                        <label class="form-label fw-medium text-muted mb-1 small" style="font-family: 'Jost', sans-serif;">Travel Class</label>
                                        <div class="position-relative">
                                            <i class="fas fa-star position-absolute text-muted" style="left: 10px; top: 50%; transform: translateY(-50%); z-index: 5; font-size: 0.8rem;"></i>
                                            <select class="form-select border-2 ps-4" 
                                                    name="travel_class"
                                                    style="border-radius: 8px; padding: 0.5rem 0.5rem 0.5rem 2rem; border-color: #e5e7eb; font-size: 0.9rem; font-family: 'Jost', sans-serif; color: #000000;">
                                                <option value="ECONOMY">Economy</option>
                                                <option value="PREMIUM_ECONOMY">Premium Economy</option>
                                                <option value="BUSINESS">Business</option>
                                                <option value="FIRST">First Class</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Search button -->
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold d-flex align-items-center justify-content-center" style="border-radius: 8px; font-family: 'Jost', sans-serif; font-weight: 700;">
                                            <i class="fas fa-search me-1"></i>
                                            <span>Search Flights</span>
                                        </button>
                                    </div>
                                </div>

                                <!-- Search options -->
                                <div class="d-flex flex-wrap gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="mc-nearbyAirports" name="nearby_airports">
                                        <label class="form-check-label text-muted small" for="mc-nearbyAirports" style="font-family: 'Jost', sans-serif;">
                                            Add nearby airports
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="mc-directFlights" name="non_stop" value="true">
                                        <label class="form-check-label text-muted small" for="mc-directFlights" style="font-family: 'Jost', sans-serif;">
                                            Non-stop flights only
                                        </label>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Enhanced Flight Results Table -->
                    @if(isset($flights) && count($flights) > 0)
                        <div class="mt-4">
                            <h3 class="text-center text-dark mb-3" style="font-family: 'Jost', sans-serif; font-weight: 700;">
                                <i class="fas fa-list-alt text-warning me-2"></i>
                                {{ count($flights) }} Results Found
                            </h3>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th scope="col"><i class="fas fa-hashtag me-1"></i>Flight #</th>
                                            <th scope="col"><i class="fas fa-plane me-1"></i>Airline</th>
                                            <th scope="col"><i class="fas fa-route me-1"></i>Route</th>
                                            <th scope="col"><i class="fas fa-clock me-1"></i>Duration</th>
                                            <th scope="col"><i class="fas fa-layer-group me-1"></i>Stops</th>
                                            <th scope="col"><i class="fas fa-chair me-1"></i>Class</th>
                                            <th scope="col"><i class="fas fa-euro-sign me-1"></i>Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($flights as $flight)
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
                                            <tr onclick="document.getElementById('{{ 'form'.$flight->id }}').submit()" style="cursor: pointer;">
                                                <th scope="row">{{ $flight->id }}</th>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="airline-logo me-2 d-flex align-items-center justify-content-center" 
                                                            style="width: 30px; height: 30px; background: linear-gradient(45deg, #667eea, #764ba2); border-radius: 50%; color: white; font-weight: bold; font-size: 0.8rem;">
                                                            {{ $carrierCode }}
                                                        </div>
                                                        <div>
                                                            <div class="fw-bold" style="font-size: 0.9rem;">{{ $airlineName }}</div>
                                                            <small class="text-muted">{{ $carrierCode }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="route-info">
                                                        <div class="fw-bold">{{ $firstSegment->departure->iataCode }} → {{ $lastSegment->arrival->iataCode }}</div>
                                                        <small class="text-muted">
                                                            {{ date('H:i', strtotime($firstSegment->departure->at)) }} - 
                                                            {{ date('H:i', strtotime($lastSegment->arrival->at)) }}
                                                        </small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-info">{{ $flight->itineraries[0]->duration }}</span>
                                                </td>
                                                <td>
                                                    @if($numberOfStops == 0)
                                                        <span class="badge bg-success">Direct</span>
                                                    @else
                                                        <span class="badge bg-warning">{{ $numberOfStops }} Stop{{ $numberOfStops > 1 ? 's' : '' }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge bg-secondary">{{ ucwords(str_replace('_', ' ', strtolower($cabin))) }}</span>
                                                </td>
                                                <td>
                                                    <div class="price-info">
                                                        <div class="fw-bold text-success fs-5">{{ $flight->price->currency }} {{ $flight->price->total }}</div>
                                                        <small class="text-muted">{{ $flight->price->currency }} {{ $flight->price->base }} + taxes</small>
                                                    </div>
                                                </td>
                                            </tr>

                                            <form action="/api/price" hidden id="{{ 'form' . $flight->id }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="flight" value="{{ json_encode($flight) }}">
                                            </form>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>





 <!-- Hero Section -->
    <div class="relative bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 min-h-[60vh] flex items-center justify-center overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%239C92AC" fill-opacity="0.1"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-20"></div>
        
        <!-- Hero Content -->
        <div class="relative z-10 text-center px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                <h1 class="text-4xl md:text-6xl font-bold text-white mb-6 leading-tight">
                    Customer <span class="text-gradient bg-gradient-to-r from-amber-400 to-orange-500 bg-clip-text text-transparent">Support</span>
                </h1>
                <p class="text-xl md:text-2xl text-slate-300 mb-8 max-w-2xl mx-auto">
                    Get help with your bookings and travel needs - We're here 24/7
                </p>
                <div class="flex justify-center">
                    <div class="inline-flex items-center px-6 py-3 bg-white/10 backdrop-blur-sm rounded-full border border-white/20">
                        <i data-lucide="headphones" class="w-5 h-5 text-amber-400 mr-2"></i>
                        <span class="text-white font-medium">Available 24/7</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Help Section -->
    <section class="py-20 bg-gradient-to-br from-slate-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <div class="inline-flex items-center px-4 py-2 bg-blue-100 rounded-full mb-6">
                    <i data-lucide="zap" class="w-4 h-4 text-blue-600 mr-2"></i>
                    <span class="text-blue-700 font-medium text-sm">Quick Help</span>
                </div>
                <h2 class="text-3xl md:text-4xl font-bold text-slate-800 mb-4">
                    Get Instant Support
                </h2>
                <p class="text-lg text-slate-600 max-w-2xl mx-auto">
                    Choose the fastest way to get help with your travel needs
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Live Chat -->
                <div class="group hover:scale-105 transition-transform duration-300">
                    <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-100 hover:shadow-xl transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                            <i data-lucide="message-circle" class="w-8 h-8 text-white"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-2">Live Chat</h3>
                        <p class="text-slate-600 text-sm mb-4">Chat with our support team in real-time</p>
                        <button class="w-full bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                            Start Chat
                        </button>
                    </div>
                </div>

                <!-- Email Support -->
                <div class="group hover:scale-105 transition-transform duration-300">
                    <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-100 hover:shadow-xl transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                            <i data-lucide="mail" class="w-8 h-8 text-white"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-2">Email Support</h3>
                        <p class="text-slate-600 text-sm mb-4">Send us a detailed message</p>
                        <a href="#contact-form" class="block w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200 text-center">
                            Send Email
                        </a>
                    </div>
                </div>

                <!-- FAQ -->
                <div class="group hover:scale-105 transition-transform duration-300">
                    <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-100 hover:shadow-xl transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                            <i data-lucide="help-circle" class="w-8 h-8 text-white"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-2">FAQ</h3>
                        <p class="text-slate-600 text-sm mb-4">Find answers to common questions</p>
                        <a href="{{ route('help') }}" class="block w-full bg-purple-500 hover:bg-purple-600 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200 text-center">
                            Browse FAQ
                        </a>
                    </div>
                </div>

                <!-- Phone Support -->
                <div class="group hover:scale-105 transition-transform duration-300">
                    <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-100 hover:shadow-xl transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                            <i data-lucide="phone" class="w-8 h-8 text-white"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-2">Phone Support</h3>
                        <p class="text-slate-600 text-sm mb-4">Speak directly with our team</p>
                        <a href="tel:+254700123456" class="block w-full bg-amber-500 hover:bg-amber-600 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200 text-center">
                            Call Now
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Form Section -->
    <section id="contact-form" class="py-20 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <div class="inline-flex items-center px-4 py-2 bg-amber-100 rounded-full mb-6">
                    <i data-lucide="send" class="w-4 h-4 text-amber-600 mr-2"></i>
                    <span class="text-amber-700 font-medium text-sm">Send Message</span>
                </div>
                <h2 class="text-3xl md:text-4xl font-bold text-slate-800 mb-4">
                    Get in Touch
                </h2>
                <p class="text-lg text-slate-600 max-w-2xl mx-auto">
                    Send us a message and we'll get back to you as soon as possible
                </p>
            </div>

            <div class="bg-white rounded-2xl shadow-2xl border border-slate-100 p-8">
                <!-- Success Message -->
                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex items-center">
                            <i data-lucide="check-circle" class="w-5 h-5 text-green-600 mr-2"></i>
                            <span class="text-green-700 font-medium">{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                <!-- Error Messages -->
                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-center mb-2">
                            <i data-lucide="alert-circle" class="w-5 h-5 text-red-600 mr-2"></i>
                            <span class="text-red-700 font-medium">Please correct the following errors:</span>
                        </div>
                        <ul class="text-red-600 text-sm space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>

                @endif

                <form action="{{ route('contactus.submit') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                <i data-lucide="user" class="w-4 h-4 inline mr-1"></i>
                                Full Name
                            </label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                   class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors duration-200">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                <i data-lucide="mail" class="w-4 h-4 inline mr-1"></i>
                                Email Address
                            </label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                   class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors duration-200">
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                <i data-lucide="phone" class="w-4 h-4 inline mr-1"></i>
                                Phone Number (Optional)
                            </label>
                            <input type="tel" name="phone" value="{{ old('phone') }}"
                                   class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors duration-200">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                <i data-lucide="tag" class="w-4 h-4 inline mr-1"></i>
                                Subject
                            </label>
                            <select name="subject" required
                                    class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors duration-200">
                                <option value="">Select a subject</option>
                                <option value="booking-inquiry" {{ old('subject') == 'booking-inquiry' ? 'selected' : '' }}>Booking Inquiry</option>
                                <option value="technical-support" {{ old('subject') == 'technical-support' ? 'selected' : '' }}>Technical Support</option>
                                <option value="payment-issue" {{ old('subject') == 'payment-issue' ? 'selected' : '' }}>Payment Issue</option>
                                <option value="flight-change" {{ old('subject') == 'flight-change' ? 'selected' : '' }}>Flight Change</option>
                                <option value="refund-request" {{ old('subject') == 'refund-request' ? 'selected' : '' }}>Refund Request</option>
                                <option value="general-inquiry" {{ old('subject') == 'general-inquiry' ? 'selected' : '' }}>General Inquiry</option>
                                <option value="other" {{ old('subject') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            <i data-lucide="message-square" class="w-4 h-4 inline mr-1"></i>
                            Message
                        </label>
                        <textarea name="message" rows="6" required
                                  class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors duration-200"
                                  placeholder="Please provide as much detail as possible about your inquiry...">{{ old('message') }}</textarea>
                    </div>

                    <div class="flex justify-center">
                        <button type="submit"
                                class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-amber-500 to-orange-600 text-white font-semibold rounded-lg hover:from-amber-600 hover:to-orange-700 focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 transition-all duration-200 shadow-lg hover:shadow-xl">
                            <i data-lucide="send" class="w-5 h-5 mr-2"></i>
                            Send Message
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Contact Information -->
    <section class="py-20 bg-gradient-to-br from-slate-800 to-slate-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                    Other Ways to Reach Us
                </h2>
                <p class="text-slate-300 text-lg">
                    Choose the method that works best for you
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Phone -->
                <div class="text-center">
                    <div class="w-20 h-20 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i data-lucide="phone" class="w-10 h-10 text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Phone Support</h3>
                    <p class="text-slate-300 mb-4">Available 24/7 for urgent matters</p>
                    <a href="tel:+254700123456" class="text-green-400 hover:text-green-300 font-medium">
                        +254 700 123 456
                    </a>
                </div>

                <!-- Email -->
                <div class="text-center">
                    <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i data-lucide="mail" class="w-10 h-10 text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Email Support</h3>
                    <p class="text-slate-300 mb-4">We'll respond within 24 hours</p>
                    <a href="mailto:support@ziara.com" class="text-blue-400 hover:text-blue-300 font-medium">
                        support@ziara.com
                    </a>
                </div>

                <!-- WhatsApp -->
                <div class="text-center">
                    <div class="w-20 h-20 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i data-lucide="message-circle" class="w-10 h-10 text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">WhatsApp</h3>
                    <p class="text-slate-300 mb-4">Quick responses via WhatsApp</p>
                    <a href="https://wa.me/254700123456" class="text-green-400 hover:text-green-300 font-medium">
                        +254 700 123 456
                    </a>
                </div>
            </div>
        </div>
    </section>

@endsection 

