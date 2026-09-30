@extends('layouts.main')

@section('content')
@include('partials.ziara-page-hero', [
    'kicker' => 'Tours & Experiences',
    'title'  => 'Complete Your <em>Tour</em> Booking',
    'intro'  => 'Lock in your package, start date and traveller list with our local guides.',
    'crumbs' => [
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Tour Deals', 'url' => route('deal.tour.results')],
        ['label' => 'Traveler Details'],
    ],
])

    <!-- Include required CSS and JS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

    <style>
        .ztb-booking {
            background:
                radial-gradient(900px 420px at 12% -8%, rgba(201,162,39,.14), transparent 62%),
                linear-gradient(180deg, var(--gold-pale) 0%, #fffdf8 100%);
            padding: 56px 0 88px;
        }
        .booking-container {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 18px;
            box-shadow: var(--shadow-md);
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .section-title {
            position: relative;
            padding-bottom: 10px;
            margin-bottom: 20px;
            font-weight: 700;
            font-family: var(--font-display);
            font-size: 26px;
            color: var(--maroon-deep);
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background: var(--gold);
            border-radius: 3px;
        }

        .tour-info-card {
            background-color: #fff;
            border: 1px solid var(--gold-border);
            border-radius: 16px;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
        }

        .tour-info-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }

        .tour-info-card h4,
        .tour-info-card h5,
        .tour-info-card h6 {
            font-family: var(--font-display);
            font-weight: 700;
            color: var(--maroon-deep);
        }

        .tour-info-card h5 { font-size: 19px; }
        .tour-info-card h6 { font-size: 16px; }
        .tour-info-card .text-warning { color: var(--gold) !important; }
        .tour-info-card .img-fluid { border: 1px solid var(--line); }

        .price-summary {
            background-color: #fff;
            border: 1px solid var(--line);
            border-radius: 14px;
            border-left: 4px solid var(--gold);
            box-shadow: var(--shadow-sm);
        }

        .price-summary span { color: var(--muted); }
        .price-summary .fw-bold { color: var(--maroon-deep) !important; }
        .price-summary .fw-bold.fs-4 {
            font-family: var(--font-display);
            color: var(--maroon) !important;
        }
        .price-summary hr { border-top-color: var(--gold-border); opacity: 1; }
        .price-summary .text-success { color: var(--gold-dim) !important; }

        .guest-form label {
            font-weight: 600;
            font-size: 12.5px;
            letter-spacing: .4px;
            text-transform: uppercase;
            color: var(--muted);
        }

        .ztb-booking .form-control,
        .ztb-booking .form-select {
            border-color: var(--gold-border);
            border-radius: 10px;
            padding: 12px 14px;
            font-size: 14.5px;
            color: var(--ink);
            background-color: #fff;
        }

        .ztb-booking .form-control:focus,
        .ztb-booking .form-select:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 3px rgba(201,162,39,.18);
        }

        .ztb-booking .form-check {
            background: #fff;
            border-color: var(--gold-border) !important;
        }

        .ztb-booking .form-check:hover { background: var(--gold-pale); }
        .ztb-booking .form-check-input { border-color: var(--gold-border); }
        .ztb-booking .form-check-input:checked {
            background-color: var(--maroon);
            border-color: var(--maroon);
        }
        .ztb-booking .form-check-input:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 3px var(--gold-border);
        }
        .ztb-booking .form-check-label .fw-bold {
            font-family: var(--font-display);
            color: var(--maroon-deep);
        }

        .ztb-booking .additional-traveler {
            background: var(--gold-pale);
            border-color: var(--gold-border) !important;
        }

        .ztb-booking .additional-traveler h6 {
            font-family: var(--font-display);
            color: var(--maroon-deep);
        }

        .ztb-booking .card {
            border: 1px solid var(--line) !important;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--shadow-sm) !important;
        }

        .ztb-booking .card-header {
            background: var(--maroon-deep) !important;
            border-bottom: 0;
            padding: 14px 22px;
        }

        .ztb-booking .card-header h5 {
            font-family: var(--font-display);
            font-weight: 700;
            font-size: 18px;
            color: var(--gold-bright);
        }

        .ztb-booking .card-header .btn-sm {
            background: var(--gold) !important;
            color: var(--maroon-deep) !important;
            border-radius: 50px;
            padding: 7px 16px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .ztb-booking .card-body { padding: 22px; }
        .ztb-booking .card-title {
            font-family: var(--font-display);
            font-weight: 700;
            color: var(--maroon-deep);
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
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: #fff;
            border: 1.5px solid var(--gold-border);
            color: var(--muted);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .step.active .step-number {
            background-color: var(--maroon);
            border-color: var(--maroon);
            color: var(--gold-bright);
            box-shadow: 0 0 0 4px rgba(201,162,39,.2);
        }

        .step-title {
            font-size: 13px;
            color: var(--muted);
            text-align: center;
            max-width: 120px;
        }

        .step.active .step-title {
            color: var(--maroon-deep);
            font-weight: 700;
        }

        .step-line {
            position: absolute;
            top: 17px;
            height: 2px;
            width: 100%;
            background-color: var(--gold-border);
            z-index: -1;
        }

        .ztb-booking .text-muted { color: var(--muted) !important; }

        .feature-icon {
            display: inline-flex;
            align-items: center;
            margin-right: 20px;
            margin-bottom: 10px;
            color: var(--muted);
        }

        .feature-icon i {
            color: var(--gold);
            margin-right: 5px;
        }

        .thumbnail-gallery {
            display: flex;
            gap: 10px;
            margin-top: 10px;
            flex-wrap: wrap;
        }

        .thumbnail {
            width: 80px;
            height: 60px;
            border-radius: 8px;
            object-fit: cover;
            cursor: pointer;
            opacity: 0.6;
            transition: opacity 0.3s ease, box-shadow 0.3s ease;
        }

        .thumbnail:hover, .thumbnail.active {
            opacity: 1;
            box-shadow: 0 0 0 2px var(--gold);
        }

        .currency-divider {
            font-weight: 500;
            color: var(--gold-border);
            margin: 0 5px;
        }

        .itinerary-day {
            position: relative;
            padding-left: 30px;
            margin-bottom: 20px;
        }

        .itinerary-day::before {
            content: '';
            position: absolute;
            left: 10px;
            top: 5px;
            width: 12px;
            height: 12px;
            background-color: var(--gold);
            border-radius: 50%;
        }

        .itinerary-day::after {
            content: '';
            position: absolute;
            left: 15px;
            top: 20px;
            width: 2px;
            height: calc(100% - 5px);
            background-color: var(--gold-border);
        }

        .itinerary-day:last-child::after {
            display: none;
        }

        .ztb-booking .btn-outline-secondary {
            background: #fff;
            color: var(--maroon);
            border: 1.5px solid var(--gold-border);
            border-radius: 50px;
            padding: 12px 26px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .ztb-booking .btn-outline-secondary:hover {
            background: var(--gold-pale);
            border-color: var(--gold);
            color: var(--maroon-deep);
        }
    </style>

    <div class="ztb-booking">
    <div class="container py-5">
        <!-- Booking Progress Steps -->
        <div class="booking-steps position-relative">
            <div class="step-line"></div>
            <div class="step active">
                <div class="step-number">1</div>
                <div class="step-title">Tour Details</div>
            </div>
            <div class="step">
                <div class="step-number">2</div>
                <div class="step-title">Traveler Information</div>
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
            <h1 class="h2 fw-bold">Tour Booking</h1>
            <p class="text-muted">Please review your tour details and proceed to traveler information</p>
        </div>

        <div class="row g-4">
            <!-- Tour Information Section -->
            <div class="col-md-8">
                <div class="booking-container">
                    <h3 class="section-title">Tour Details</h3>

                    <div class="tour-info-card p-4 mb-4">
                        <!-- Tour Header -->
                        <div class="d-flex flex-wrap justify-content-between align-items-start mb-4">
                            <div>
                                <h4 class="mb-1">{{ $tour->tourName ?? 'Maasai Mara Safari Adventure' }}</h4>
                                <div class="text-muted mb-2">{{ $tour->location ?? 'Narok County' }}, {{ $tour->country ?? 'Kenya' }}</div>
                                <div class="mb-2">
                                    @for($i = 0; $i < ($tour->rating ?? 5); $i++)
                                    <i class="fas fa-star text-warning"></i>
                                    @endfor
                                </div>
                                <span class="badge text-dark" style="background-color: var(--gold-pale); border: 1px solid var(--gold-border);">
                                    <i class="fas fa-award me-1"></i> {{ $tour->highlight ?? 'Best Seller' }}
                                </span>
                                <span class="badge text-white ms-2" style="background-color: var(--maroon);">{{ $tour->tourType ?? 'Wildlife Safari' }}</span>
                            </div>
                            <div class="text-end">
                                <div>
                                    <span class="fs-5 fw-bold" style="color: var(--maroon); font-family: var(--font-display);">USD {{ number_format($tour->priceUsd ?? 45, 0) }}</span>
                                    <span class="currency-divider">|</span>
                                    <span class="fs-5 fw-bold" style="color: var(--maroon); font-family: var(--font-display);">KES {{ number_format($tour->priceKes ?? 5814, 0) }}</span>
                                </div>
                                <span class="text-muted text-decoration-line-through d-block">
                                    USD {{ number_format(($tour->originalPriceUsd ?? 60), 0) }} |
                                    KES {{ number_format(($tour->originalPriceKes ?? 7752), 0) }}
                                </span>
                                <span class="badge rounded-pill px-3 py-2 mt-2" style="background-color: var(--gold); color: var(--maroon-deep);">{{ $tour->discountPercent ?? 25 }}% OFF</span>
                            </div>
                        </div>

                        <!-- Tour Image -->
                        <div class="position-relative mb-3">
                            <img
                                id="mainTourImage"
                                src="{{ asset('images/tours/' . strtolower(str_replace(' ', '-', $tour->tourName ?? 'maasai-mara-safari')) . '.jpg') }}"
                                class="img-fluid rounded w-100"
                                style="height: 300px; object-fit: cover;"
                                alt="{{ $tour->tourName ?? 'Tour' }}"
                                onerror="this.src='{{ asset('images/tours/default-tour.jpg') }}'">

                            <div class="thumbnail-gallery">
                                <img src="{{ asset('images/tours/' . strtolower(str_replace(' ', '-', $tour->tourName ?? 'maasai-mara-safari')) . '.jpg') }}"
                                    class="thumbnail active"
                                    alt="Main View"
                                    onclick="changeImage(this, '{{ asset('images/tours/' . strtolower(str_replace(' ', '-', $tour->tourName ?? 'maasai-mara-safari')) . '.jpg') }}')">

                                <img src="{{ asset('images/tours/wildlife-1.jpg') }}"
                                    class="thumbnail"
                                    alt="Wildlife"
                                    onclick="changeImage(this, '{{ asset('images/tours/wildlife-1.jpg') }}')">

                                <img src="{{ asset('images/tours/accommodation.jpg') }}"
                                    class="thumbnail"
                                    alt="Accommodation"
                                    onclick="changeImage(this, '{{ asset('images/tours/accommodation.jpg') }}')">

                                <img src="{{ asset('images/tours/safari-vehicle.jpg') }}"
                                    class="thumbnail"
                                    alt="Safari Vehicle"
                                    onclick="changeImage(this, '{{ asset('images/tours/safari-vehicle.jpg') }}')">
                            </div>
                        </div>

                        <!-- Tour Description -->
                        <div class="mb-4">
                            <h5 class="mb-3">Description</h5>
                            <p class="text-muted">{{ $tour->description ?? 'Experience the world-famous Maasai Mara National Reserve in Kenya on this unforgettable safari adventure. Witness the spectacular wildlife including lions, elephants, rhinos, and if you\'re lucky, the Great Migration. Enjoy comfortable accommodations, expert guides, and authentic cultural experiences with local Maasai communities.' }}</p>
                        </div>

                        <!-- Tour Duration & Group Size -->
                        <div class="mb-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="mb-3">Tour Information</h5>
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-clock me-2" style="color: var(--gold);"></i>
                                        <span>Duration: {{ $tour->duration ?? '3 Days / 2 Nights' }}</span>
                                    </div>
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-users me-2" style="color: var(--gold);"></i>
                                        <span>Group Size: {{ $tour->groupSize ?? 'Max 6 people' }}</span>
                                    </div>
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-language me-2" style="color: var(--gold);"></i>
                                        <span>Languages: {{ $tour->languages ?? 'English, Swahili' }}</span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-map-marker-alt me-2" style="color: var(--gold);"></i>
                                        <span>Meeting Point: {{ $tour->meetingPoint ?? 'Nairobi Hotel Pickup' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="mb-3">Tour Features</h5>
                                    <div class="d-flex flex-wrap">
                                        <span class="feature-icon">
                                            <i class="fas fa-utensils me-1"></i> All Meals Included
                                        </span>
                                        <span class="feature-icon">
                                            <i class="fas fa-car me-1"></i> Transportation
                                        </span>
                                        <span class="feature-icon">
                                            <i class="fas fa-bed me-1"></i> Accommodation
                                        </span>
                                        <span class="feature-icon">
                                            <i class="fas fa-binoculars me-1"></i> Game Drives
                                        </span>
                                        <span class="feature-icon">
                                            <i class="fas fa-user-tie me-1"></i> Professional Guide
                                        </span>
                                        <span class="feature-icon">
                                            <i class="fas fa-camera me-1"></i> Photo Opportunities
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tour Itinerary -->
                        <div class="mb-4">
                            <h5 class="mb-3">Itinerary</h5>

                            <div class="itinerary-day">
                                <h6 class="fw-bold">Day 1: Nairobi to Maasai Mara</h6>
                                <p class="small text-muted">Morning departure from Nairobi to the Maasai Mara National Reserve. After lunch at the camp, enjoy an afternoon game drive. Return for dinner and overnight stay at the camp.</p>
                            </div>

                            <div class="itinerary-day">
                                <h6 class="fw-bold">Day 2: Full Day at Maasai Mara</h6>
                                <p class="small text-muted">Full day of game viewing in the Maasai Mara with packed lunch. Optional visit to a Maasai village (extra cost). Dinner and overnight at the camp.</p>
                            </div>

                            <div class="itinerary-day">
                                <h6 class="fw-bold">Day 3: Maasai Mara to Nairobi</h6>
                                <p class="small text-muted">Early morning game drive, breakfast at the camp, and return to Nairobi by approximately 5:00 PM.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Traveler Information Form -->
                    <h3 class="section-title mt-5">Traveler Information</h3>

                    <form method="POST" action="{{ route('deal.tour.book') }}" class="guest-form">
                        @csrf
                        <input type="hidden" name="tour_data" value="{{ json_encode($tour ?? []) }}">

                        <!-- Primary Traveler -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Lead Traveler</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="traveler_title" class="form-label">Title</label>
                                        <select class="form-select" id="traveler_title" name="traveler[title]" required>
                                            <option value="">Select title</option>
                                            <option value="Mr">Mr</option>
                                            <option value="Mrs">Mrs</option>
                                            <option value="Ms">Ms</option>
                                            <option value="Dr">Dr</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6"></div>
                                    <div class="col-md-6">
                                        <label for="traveler_first_name" class="form-label">First Name</label>
                                        <input type="text" class="form-control" id="traveler_first_name" name="traveler[first_name]" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="traveler_last_name" class="form-label">Last Name</label>
                                        <input type="text" class="form-control" id="traveler_last_name" name="traveler[last_name]" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Travelers -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Additional Travelers</h5>
                                <button type="button" class="btn btn-sm" style="background-color: var(--gold); color: var(--maroon-deep);" id="add-traveler">
                                    <i class="fas fa-plus me-1"></i> Add Traveler
                                </button>
                            </div>
                            <div class="card-body" id="additional-travelers">
                                <p class="text-muted" id="no-travelers-msg">No additional travelers added yet.</p>
                                <!-- Additional travelers will be added dynamically here -->
                            </div>
                        </div>

                        <!-- Tour Options -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Tour Options</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-check mb-3 p-3 border rounded">
                                    <input class="form-check-input" type="radio" name="tour_package" id="standard_package" value="standard" checked>
                                    <label class="form-check-label d-flex justify-content-between w-100" for="standard_package">
                                        <div>
                                            <div class="fw-bold">Standard Package</div>
                                            <div class="text-muted small">Tented camp accommodation, shared safari vehicle</div>
                                        </div>
                                        <div class="text-end">
                                            <div style="color: var(--maroon); font-weight: 700;">USD {{ $tour->priceUsd ?? 45 }} | KES {{ $tour->priceKes ?? 5814 }}</div>
                                            <div class="text-muted small">per person</div>
                                        </div>
                                    </label>
                                </div>

                                <div class="form-check mb-3 p-3 border rounded">
                                    <input class="form-check-input" type="radio" name="tour_package" id="comfort_package" value="comfort">
                                    <label class="form-check-label d-flex justify-content-between w-100" for="comfort_package">
                                        <div>
                                            <div class="fw-bold">Comfort Package</div>
                                            <div class="text-muted small">Lodge accommodation, shared safari vehicle</div>
                                        </div>
                                        <div class="text-end">
                                            <div style="color: var(--maroon); font-weight: 700;">USD {{ number_format(($tour->priceUsd ?? 45) * 1.3, 0) }} | KES {{ number_format(($tour->priceKes ?? 5814) * 1.3, 0) }}</div>
                                            <div class="text-muted small">per person</div>
                                        </div>
                                    </label>
                                </div>

                                <div class="form-check mb-3 p-3 border rounded">
                                    <input class="form-check-input" type="radio" name="tour_package" id="luxury_package" value="luxury">
                                    <label class="form-check-label d-flex justify-content-between w-100" for="luxury_package">
                                        <div>
                                            <div class="fw-bold">Luxury Package</div>
                                            <div class="text-muted small">Luxury lodge, private safari vehicle</div>
                                        </div>
                                        <div class="text-end">
                                            <div style="color: var(--maroon); font-weight: 700;">USD {{ number_format(($tour->priceUsd ?? 45) * 1.8, 0) }} | KES {{ number_format(($tour->priceKes ?? 5814) * 1.8, 0) }}</div>
                                            <div class="text-muted small">per person</div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Tour Date -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Tour Date</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="tour_date" class="form-label">Preferred Start Date</label>
                                        <input type="date" class="form-control" id="tour_date" name="tour[start_date]"
                                            value="{{ \Carbon\Carbon::now()->addDays(7)->format('Y-m-d') }}" required>
                                        <small class="text-muted">Tour starts at 7:00 AM on the selected date</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="num_travelers" class="form-label">Number of Travelers</label>
                                        <select class="form-select" id="num_travelers" name="tour[travelers]" required>
                                            <option value="1" selected>1 Traveler</option>
                                            <option value="2">2 Travelers</option>
                                            <option value="3">3 Travelers</option>
                                            <option value="4">4 Travelers</option>
                                            <option value="5">5 Travelers</option>
                                            <option value="6">6 Travelers</option>
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
                                        <small class="text-muted">For urgent communications only</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Optional Add-ons -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Optional Add-ons</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="addon_balloon" name="addons[balloon]" value="1">
                                    <label class="form-check-label d-flex justify-content-between w-100" for="addon_balloon">
                                        <div>Hot Air Balloon Safari</div>
                                        <div class="text-end">
                                            <span style="color: var(--maroon); font-weight: 700;">+USD 450 | KES 58,140</span>
                                            <span class="text-muted small d-block">per person</span>
                                        </div>
                                    </label>
                                </div>

                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="addon_maasai" name="addons[maasai]" value="1">
                                    <label class="form-check-label d-flex justify-content-between w-100" for="addon_maasai">
                                        <div>Maasai Village Visit</div>
                                        <div class="text-end">
                                            <span style="color: var(--maroon); font-weight: 700;">+USD 30 | KES 3,876</span>
                                            <span class="text-muted small d-block">per person</span>
                                        </div>
                                    </label>
                                </div>

                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="addon_photography" name="addons[photography]" value="1">
                                    <label class="form-check-label d-flex justify-content-between w-100" for="addon_photography">
                                        <div>Professional Photography Service</div>
                                        <div class="text-end">
                                            <span style="color: var(--maroon); font-weight: 700;">+USD 100 | KES 12,920</span>
                                            <span class="text-muted small d-block">per booking</span>
                                        </div>
                                    </label>
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
                                    <textarea class="form-control" id="special_requests" name="special_requests" rows="3" placeholder="Dietary requirements, medical needs, or other special requests"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Form Buttons -->
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('deal.tour.results') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Results
                            </a>
                            <button type="submit" class="btn px-5" style="background-color: var(--maroon); color: var(--gold-bright); border: 1px solid var(--maroon); border-radius: 50px; padding: 13px 34px; font-size: 12px; font-weight: 700; letter-spacing: 1.2px; text-transform: uppercase;">
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
                            <span>Base Price (per person)</span>
                            <span id="base-price">USD {{ $tour->priceUsd ?? 45 }} | KES {{ $tour->priceKes ?? 5814 }}</span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span>Number of Travelers</span>
                            <span id="num-travelers-display">1</span>
                        </div>

                        <div id="addons-section" class="d-none">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span>Add-ons</span>
                                <span id="addons-price">USD 0 | KES 0</span>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span>Taxes & Fees</span>
                            <span id="taxes-price">USD {{ number_format(($tour->priceUsd ?? 45) * 0.1, 0) }} | KES {{ number_format(($tour->priceKes ?? 5814) * 0.1, 0) }}</span>
                        </div>

                        @if(isset($tour->discountPercent) && $tour->discountPercent > 0)
                        <div class="d-flex justify-content-between align-items-center mb-3 text-success">
                            <span>Discount ({{ $tour->discountPercent }}%)</span>
                            <span id="discount-price">-USD {{ number_format(($tour->originalPriceUsd ?? 60) - ($tour->priceUsd ?? 45), 0) }} | -KES {{ number_format(($tour->originalPriceKes ?? 7752) - ($tour->priceKes ?? 5814), 0) }}</span>
                        </div>
                        @endif

                        <hr class="my-3">

                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold">Total Price</span>
                            <div>
                                <div class="fw-bold fs-4" id="total-price-usd">USD {{ number_format(($tour->priceUsd ?? 45) + ($tour->priceUsd ?? 45) * 0.1, 0) }}</div>
                                <div class="fw-bold" id="total-price-kes">KES {{ number_format(($tour->priceKes ?? 5814) + ($tour->priceKes ?? 5814) * 0.1, 0) }}</div>
                            </div>
                        </div>

                        @if(isset($tour->originalPriceUsd))
                        <div class="d-flex justify-content-end align-items-center">
                            <div class="text-end">
                                <small class="text-muted text-decoration-line-through" id="original-price-usd">USD {{ number_format(($tour->originalPriceUsd ?? 60) + ($tour->originalPriceUsd ?? 60) * 0.1, 0) }}</small>
                                <small class="text-muted text-decoration-line-through d-block" id="original-price-kes">KES {{ number_format(($tour->originalPriceKes ?? 7752) + ($tour->originalPriceKes ?? 7752) * 0.1, 0) }}</small>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Tour Details -->
                    <div class="mb-4">
                        <h5 class="mb-3">Tour Summary</h5>
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-tag me-2" style="color: var(--gold);"></i>
                            <span>{{ $tour->highlight ?? 'Best Seller' }}</span>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-map-marker-alt me-2" style="color: var(--gold);"></i>
                            <span>{{ $tour->location ?? 'Maasai Mara' }}, {{ $tour->country ?? 'Kenya' }}</span>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-clock me-2" style="color: var(--gold);"></i>
                            <span>{{ $tour->duration ?? '3 Days / 2 Nights' }}</span>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-calendar-alt me-2" style="color: var(--gold);"></i>
                            <span id="tour-date-display">{{ date('M d, Y', strtotime('+7 days')) }}</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-suitcase me-2" style="color: var(--gold);"></i>
                            <span id="package-type-display">Standard Package</span>
                        </div>
                    </div>

                    <!-- Tour Policies -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Tour Policies</h5>
                            <p class="card-text small mb-2"><i class="fas fa-ban me-1 text-danger"></i> <strong>Cancellation Policy:</strong> Free cancellation up to 7 days before tour start. 50% refund between 7-3 days. No refund within 72 hours.</p>
                            <p class="card-text small mb-2"><i class="fas fa-child me-1" style="color: var(--gold);"></i> <strong>Child Policy:</strong> Children under 3 are free. Children 3-12 pay 50% of adult rate.</p>
                            <p class="card-text small mb-0"><i class="fas fa-info-circle me-1" style="color: var(--gold);"></i> See our <a href="#" style="color: var(--gold-dim);">terms and conditions</a> for more details.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        let travelerCount = 0;
        const maxTravelers = 6;
        
        // Package selection
        const packageRadios = document.querySelectorAll('input[name="tour_package"]');
        const basePriceElement = document.getElementById('base-price');
        const packageTypeDisplay = document.getElementById('package-type-display');
        
        const basePriceText = basePriceElement.textContent;
        const usdBasePrice = parseInt(basePriceText.split('USD')[1].split('|')[0].replace(/[^0-9]/g, ''));
        const kesBasePrice = parseInt(basePriceText.split('KES')[1].replace(/[^0-9]/g, ''));
        
        packageRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                let priceMultiplier = 1;
                let packageTypeText = 'Standard Package';
                
                if (this.value === 'comfort') {
                    priceMultiplier = 1.3;
                    packageTypeText = 'Comfort Package';
                } else if (this.value === 'luxury') {
                    priceMultiplier = 1.8;
                    packageTypeText = 'Luxury Package';
                }
                
                const newUsdPrice = Math.round(usdBasePrice * priceMultiplier);
                const newKesPrice = Math.round(kesBasePrice * priceMultiplier);
                basePriceElement.textContent = `USD ${newUsdPrice} | KES ${newKesPrice.toLocaleString()}`;
                packageTypeDisplay.textContent = packageTypeText;
                
                // Update total
                updateTotalPrice();
            });
        });
        
        // Tour date update
        const tourDate = document.getElementById('tour_date');
        const tourDateDisplay = document.getElementById('tour-date-display');
        
        tourDate.addEventListener('change', function() {
            const selectedDate = new Date(this.value);
            const formattedDate = selectedDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
            tourDateDisplay.textContent = formattedDate;
        });
        
        // Number of travelers update
        const numTravelers = document.getElementById('num_travelers');
        const numTravelersDisplay = document.getElementById('num-travelers-display');
        
        numTravelers.addEventListener('change', function() {
            numTravelersDisplay.textContent = this.value;
            updateTotalPrice();
        });
        
        // Add-ons update
        const addons = document.querySelectorAll('input[name^="addons"]');
        const addonsSection = document.getElementById('addons-section');
        const addonsPrice = document.getElementById('addons-price');
        
        addons.forEach(addon => {
            addon.addEventListener('change', function() {
                updateAddonsPrice();
            });
        });
        
        function updateAddonsPrice() {
            let totalUsdAddons = 0;
            let totalKesAddons = 0;
            let hasAddons = false;
            
            if (document.getElementById('addon_balloon').checked) {
                totalUsdAddons += 450;
                totalKesAddons += 58140;
                hasAddons = true;
            }
            
            if (document.getElementById('addon_maasai').checked) {
                totalUsdAddons += 30 * parseInt(numTravelers.value);
                totalKesAddons += 3876 * parseInt(numTravelers.value);
                hasAddons = true;
            }
            
            if (document.getElementById('addon_photography').checked) {
                totalUsdAddons += 100;
                totalKesAddons += 12920;
                hasAddons = true;
            }
            
            if (hasAddons) {
                addonsSection.classList.remove('d-none');
                addonsPrice.textContent = `USD ${totalUsdAddons} | KES ${totalKesAddons.toLocaleString()}`;
            } else {
                addonsSection.classList.add('d-none');
            }
            
            updateTotalPrice();
        }
        
        // Update total price based on package, travelers, and addons
        function updateTotalPrice() {
            const currentBasePriceText = basePriceElement.textContent;
            const currentUsdBasePrice = parseInt(currentBasePriceText.split('USD')[1].split('|')[0].replace(/[^0-9]/g, ''));
            const currentKesBasePrice = parseInt(currentBasePriceText.split('KES')[1].replace(/[^0-9]/g, ''));
            
            const travelers = parseInt(numTravelers.value);
            
            // Calculate base costs
            let usdBaseCost = currentUsdBasePrice * travelers;
            let kesBaseCost = currentKesBasePrice * travelers;
            
            // Add add-ons
            let usdAddons = 0;
            let kesAddons = 0;
            
            if (document.getElementById('addon_balloon').checked) {
                usdAddons += 450;
                kesAddons += 58140;
            }
            
            if (document.getElementById('addon_maasai').checked) {
                usdAddons += 30 * travelers;
                kesAddons += 3876 * travelers;
            }
            
            if (document.getElementById('addon_photography').checked) {
                usdAddons += 100;
                kesAddons += 12920;
            }
            
            // Calculate taxes
            const usdTaxes = Math.round((usdBaseCost + usdAddons) * 0.1);
            const kesTaxes = Math.round((kesBaseCost + kesAddons) * 0.1);
            
            // Update taxes display
            document.getElementById('taxes-price').textContent = `USD ${usdTaxes} | KES ${kesTaxes.toLocaleString()}`;
            
            // Calculate grand totals
            const usdTotal = usdBaseCost + usdAddons + usdTaxes;
            const kesTotal = kesBaseCost + kesAddons + kesTaxes;
            
            // Update total price display
            document.getElementById('total-price-usd').textContent = `USD ${usdTotal}`;
            document.getElementById('total-price-kes').textContent = `KES ${kesTotal.toLocaleString()}`;
            
            // Update original price if exists
            const originalPriceUsd = document.getElementById('original-price-usd');
            const originalPriceKes = document.getElementById('original-price-kes');
            
            if (originalPriceUsd && originalPriceKes) {
                // Assuming 25% discount
                const originalUsdTotal = Math.round(usdTotal / 0.75);
                const originalKesTotal = Math.round(kesTotal / 0.75);
                
                originalPriceUsd.textContent = `USD ${originalUsdTotal}`;
                originalPriceKes.textContent = `KES ${originalKesTotal.toLocaleString()}`;
            }
        }
        
        // Add traveler functionality
        document.getElementById('add-traveler').addEventListener('click', function() {
            if (travelerCount < maxTravelers - 1) { // -1 because lead traveler counts as 1
                travelerCount++;
                
                const travelerHTML = `
                    <div class="additional-traveler border rounded p-3 mb-3">
                        <div class="d-flex justify-content-between mb-3">
                            <h6 class="mb-0">Traveler #${travelerCount + 1}</h6>
                            <button type="button" class="btn-close remove-traveler" aria-label="Close"></button>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">First Name</label>
                                <input type="text" class="form-control" name="additional_travelers[${travelerCount}][first_name]" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Last Name</label>
                                <input type="text" class="form-control" name="additional_travelers[${travelerCount}][last_name]" required>
                            </div>
                        </div>
                    </div>
                `;
                
                document.getElementById('no-travelers-msg').classList.add('d-none');
                document.getElementById('additional-travelers').insertAdjacentHTML('beforeend', travelerHTML);
                
                // Add event listeners to remove buttons
                document.querySelectorAll('.remove-traveler').forEach(btn => {
                    btn.addEventListener('click', function() {
                        this.closest('.additional-traveler').remove();
                        travelerCount--;
                        
                        if (travelerCount === 0) {
                            document.getElementById('no-travelers-msg').classList.remove('d-none');
                        }
                    });
                });
            } else {
                alert(`Maximum of ${maxTravelers} travelers allowed per booking.`);
            }
        });
        
        // Initialize values
        updateTotalPrice();
    });
    
    function changeImage(thumbnail, newSrc) {
        // Update main image
        document.getElementById('mainTourImage').src = newSrc;
        
        // Update active thumbnail
        document.querySelectorAll('.thumbnail').forEach(thumb => {
            thumb.classList.remove('active');
        });
        thumbnail.classList.add('active');
    }
</script>
@endsection
