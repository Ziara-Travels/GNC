@extends('layouts.main')

@section('content')
@include('partials.ziara-page-hero', [
    'kicker' => 'Secure Booking',
    'title'  => 'Complete Your <em>Booking</em>',
    'intro'  => 'Review your travel package, choose a departure date, and confirm your traveler details to continue to payment.',
])

<style>
    /* Custom animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes fadeOutDown {
        from {
            opacity: 1;
            transform: translateY(0);
        }
        to {
            opacity: 0;
            transform: translateY(20px);
        }
    }
    
    .animate-fade-in-up {
        animation: fadeInUp 0.3s ease-out forwards;
    }
    
    .animate-fade-out-down {
        animation: fadeOutDown 0.3s ease-out forwards;
    }
    
    .modal-overlay {
        background-color: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(5px);
    }
    
    /* Timeline styles */
    .timeline-step.active .timeline-circle {
        background-color: #4f46e5;
        border-color: #4f46e5;
    }
    
    .timeline-step.active .timeline-text {
        color: #4f46e5;
        font-weight: 600;
    }
    
    .timeline-step.completed .timeline-circle {
        background-color: #22c55e;
        border-color: #22c55e;
    }
    
    .timeline-line {
        top: 24px;
        height: 3px;
        transform: translateY(-50%);
    }

    .zbook-shell { background: var(--gold-pale); }
    .zbook-card {
        background: #fff;
        border: 1px solid var(--line);
        border-radius: 16px;
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }
    .zbook-card__body { padding: 30px 28px; }
    .zbook-title {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: 30px;
        font-weight: 700;
        color: var(--maroon-deep);
        margin: 0 0 6px;
    }
    .zbook-heading {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: 23px;
        font-weight: 700;
        color: var(--maroon-deep);
        margin: 0 0 14px;
    }
    .zbook-subheading {
        font-size: 18px;
        font-weight: 700;
        color: var(--ink);
        margin: 0 0 14px;
    }
    .zbook-meta {
        font-size: 13.5px;
        color: var(--muted);
        display: flex;
        align-items: center;
        gap: 7px;
        margin: 0;
    }
    .zbook-meta i { color: var(--maroon); font-size: 15px; }
    .zbook-chip {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 7px 14px;
        border-radius: 999px;
        background: var(--gold-pale);
        border: 1px solid var(--gold-border);
        color: var(--maroon);
        font-size: 13px;
        font-weight: 600;
    }
    .zbook-chip i { color: var(--maroon); font-size: 14px; }
    .zbook-rating {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 13px;
        border-radius: 999px;
        background: var(--gold-pale);
        color: var(--maroon);
        font-size: 13.5px;
        font-weight: 700;
    }
    .zbook-rating i { color: var(--gold-dim); fill: currentColor; }
    .zbook-body { font-size: 15.5px; line-height: 1.75; color: #3a322c; margin: 0 0 14px; }
    .zbook-list { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px 22px; }
    .zbook-list--single { grid-template-columns: 1fr; }
    .zbook-list__item { display: flex; align-items: flex-start; gap: 11px; font-size: 15px; line-height: 1.6; color: var(--ink); }
    .zbook-list__item i { color: var(--gold-dim); font-size: 17px; flex-shrink: 0; margin-top: 1px; }
    .zbook-list__item--no i { color: #b3261e; }
    .zbook-day {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--maroon);
        color: #fff;
        font-size: 14px;
        font-weight: 700;
    }
    .zbook-dayline { width: 1px; background: var(--line); margin: 10px auto; }
    .zbook-daytitle { font-size: 16px; font-weight: 700; color: var(--ink); margin: 0 0 4px; }
    .zbook-daysum { font-size: 14px; color: var(--muted); margin: 0; line-height: 1.7; }
    .zbook-note {
        background: var(--gold-pale);
        border: 1px solid var(--gold-border);
        border-left: 4px solid var(--gold);
        border-radius: 10px;
        padding: 18px 20px;
        font-size: 14.5px;
        line-height: 1.7;
        color: var(--ink);
        margin: 0;
    }
    .zbook-dates { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; }
    .departureDateBtn {
        padding: 14px 12px;
        border: 1.5px solid var(--line);
        border-radius: 12px;
        background: #fff;
        font-family: var(--font-body);
        text-align: center;
        cursor: pointer;
        transition: border-color .25s, background .25s, transform .25s;
    }
    .departureDateBtn:hover { transform: translateY(-2px); border-color: var(--gold); }
    .departureDateBtn.hover\:border-indigo-500:hover { border-color: var(--gold); }
    .departureDateBtn.border-indigo-600 { border-color: var(--maroon); }
    .departureDateBtn.bg-indigo-50 { background: var(--gold-pale); }
    .departureDateBtn.text-indigo-700 { color: var(--maroon-deep); }
    .departureDateBtn.border-gray-300 { border-color: var(--line); }
    .departureDateBtn.border-yellow-400 { border-color: var(--gold); }
    .departureDateBtn.border-red-300 { border-color: #b3261e; }
    .zbook-date__main { font-size: 15px; font-weight: 600; color: var(--ink); }
    .zbook-date__sub { font-size: 12px; margin-top: 4px; color: var(--muted); }
    .zbook-hint { font-size: 13.5px; font-style: italic; color: var(--muted); margin: 18px 0 0; }
    .zbook-label {
        display: block;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .8px;
        text-transform: uppercase;
        color: var(--muted);
        margin-bottom: 8px;
    }
    .zbook-field, .zbook-select, .zbook-textarea {
        width: 100%;
        padding: 13px 15px;
        border: 1.5px solid var(--line);
        border-radius: 10px;
        font-size: 15px;
        font-family: var(--font-body);
        color: var(--ink);
        background: #fff;
    }
    .zbook-field:focus, .zbook-select:focus, .zbook-textarea:focus {
        outline: none;
        border-color: var(--gold);
        box-shadow: 0 0 0 3px var(--gold-border);
    }
    .zbook-stepper { display: inline-flex; align-items: stretch; }
    .zbook-stepper button {
        width: 46px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1.5px solid var(--line);
        background: #fff;
        color: var(--maroon);
        cursor: pointer;
        transition: background .25s, border-color .25s;
    }
    .zbook-stepper button:hover { background: var(--gold-pale); border-color: var(--gold); }
    .zbook-stepper .zbook-stepper__minus { border-radius: 10px 0 0 10px; }
    .zbook-stepper .zbook-stepper__plus { border-radius: 0 10px 10px 0; }
    .zbook-stepper input {
        width: 76px;
        text-align: center;
        padding: 13px 8px;
        border-top: 1.5px solid var(--line);
        border-bottom: 1.5px solid var(--line);
        font-size: 15px;
        font-family: var(--font-body);
        color: var(--ink);
        font-weight: 700;
    }
    .zbook-stepper input:focus { outline: none; border-color: var(--gold); }
    .zbook-check { display: flex; align-items: flex-start; gap: 12px; }
    .zbook-check input { width: 18px; height: 18px; accent-color: var(--maroon); margin-top: 1px; flex-shrink: 0; }
    .zbook-check label { font-size: 14.5px; line-height: 1.7; color: var(--ink); }
    .zbook-check a { color: var(--maroon); font-weight: 600; }
    .zbook-check a:hover { color: var(--gold-dim); }
    .zbook-hintline { font-size: 12.5px; color: var(--muted); margin: 7px 0 0; }
    .zbook-card input:not([type="hidden"]):not([type="checkbox"]):not([type="radio"]):not([type="submit"]),
    .zbook-card select,
    .zbook-card textarea,
    .zbook-modal input:not([type="hidden"]):not([type="checkbox"]),
    .zbook-modal select {
        border-color: var(--line);
        border-radius: 10px;
        padding: 13px 15px;
        font-family: var(--font-body);
        font-size: 15px;
        color: var(--ink);
        background: #fff;
    }
    .zbook-card input:focus, .zbook-card select:focus, .zbook-card textarea:focus,
    .zbook-modal input:focus, .zbook-modal select:focus {
        outline: none;
        border-color: var(--gold);
        box-shadow: 0 0 0 3px var(--gold-border);
    }
    .zbook-card input[type="checkbox"], .zbook-modal input[type="checkbox"] { accent-color: var(--maroon); }
    .zbook-stepper { display: inline-flex; align-items: stretch; }
    .zbook-stepper > button {
        width: 46px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1.5px solid var(--line);
        background: #fff;
        color: var(--maroon);
        cursor: pointer;
        border-radius: 0;
        transition: background .25s, border-color .25s;
    }
    .zbook-stepper > button:hover { background: var(--gold-pale); border-color: var(--gold); }
    .zbook-stepper > #decreaseTravelers { border-radius: 10px 0 0 10px; }
    .zbook-stepper > #increaseTravelers { border-radius: 0 10px 10px 0; }
    .zbook-stepper > input {
        width: 78px;
        text-align: center;
        padding: 13px 8px;
        border-top: 1.5px solid var(--line);
        border-bottom: 1.5px solid var(--line);
        font-size: 15px;
        font-weight: 700;
        color: var(--ink);
    }
    .zbook-modal__title {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: 25px;
        font-weight: 700;
        color: var(--maroon-deep);
        margin: 0 0 12px;
    }
    .zbook-modal__text { font-size: 14.5px; line-height: 1.7; color: var(--muted); margin: 0 0 20px; }
    .zbook-modal label {
        display: block;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .8px;
        text-transform: uppercase;
        color: var(--muted);
        margin-bottom: 8px;
    }
    .zbook-modal .zbook-check label { font-size: 14px; letter-spacing: 0; text-transform: none; font-weight: 500; margin-bottom: 0; }
    .zbook-cta {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 15px 34px;
        border: 0;
        border-radius: 50px;
        background: var(--maroon);
        color: #fff;
        font-family: var(--font-body);
        font-size: 12.5px;
        font-weight: 700;
        letter-spacing: .9px;
        text-transform: uppercase;
        cursor: pointer;
        transition: background .35s, color .35s, transform .35s, box-shadow .35s;
    }
    .zbook-cta:hover { background: var(--maroon-deep); transform: translateY(-2px); box-shadow: 0 10px 24px rgba(107,44,31,.28); }
    .zbook-cta:disabled, .zbook-cta.disabled { background: var(--line); color: var(--muted); cursor: not-allowed; transform: none; box-shadow: none; }
    .zbook-thumb { width: 96px; height: 96px; border-radius: 12px; overflow: hidden; flex-shrink: 0; }
    .zbook-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .zbook-sumtitle { font-family: 'Cormorant Garamond', Georgia, serif; font-size: 24px; font-weight: 700; color: var(--maroon-deep); margin: 0 0 20px; }
    .zbook-sumrow {
        display: flex;
        align-items: flex-start;
        gap: 13px;
        font-size: 14.5px;
        color: var(--ink);
    }
    .zbook-sumrow + .zbook-sumrow { margin-top: 16px; }
    .zbook-sumrow i { color: var(--maroon); font-size: 17px; flex-shrink: 0; margin-top: 1px; }
    .zbook-sumrow p { margin: 0; font-weight: 600; }
    .zbook-sumrow span { font-size: 14px; color: var(--muted); }
    .zbook-pricerow { display: flex; justify-content: space-between; gap: 14px; font-size: 14.5px; color: var(--muted); }
    .zbook-pricerow + .zbook-pricerow { margin-top: 10px; }
    .zbook-pricerow--total {
        border-top: 2px solid var(--gold);
        margin-top: 16px;
        padding-top: 14px;
        font-size: 17px;
        font-weight: 700;
        color: var(--maroon-deep);
    }
    .zbook-policy { display: flex; align-items: flex-start; gap: 11px; font-size: 14px; line-height: 1.65; color: var(--ink); }
    .zbook-policy + .zbook-policy { margin-top: 12px; }
    .zbook-policy i { color: var(--maroon); font-size: 16px; flex-shrink: 0; margin-top: 1px; }
    .zbook-empty { text-align: center; padding: 40px 20px; }
    .zbook-empty i { color: var(--line); font-size: 40px; }
    .zbook-empty p { font-size: 14.5px; color: var(--muted); margin: 12px 0 0; }
    .zbook-modal {
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 30px 70px rgba(31,20,16,.34);
        overflow: hidden;
    }
    .zbook-modal__body { padding: 30px 28px 20px; text-align: left; }
    .zbook-modal__foot { background: var(--gold-pale); padding: 16px 28px; display: flex; gap: 12px; }
    .zbook-modal__foot .zbook-cta { flex: 1; }
    .zbook-modal__foot .zbook-ghost {
        flex: 1;
        padding: 15px 24px;
        border: 1.5px solid var(--maroon);
        border-radius: 50px;
        background: transparent;
        color: var(--maroon);
        font-family: var(--font-body);
        font-size: 12.5px;
        font-weight: 700;
        letter-spacing: .9px;
        text-transform: uppercase;
        cursor: pointer;
        transition: background .35s, color .35s, transform .35s;
    }
    .zbook-modal__foot .zbook-ghost:hover { background: var(--maroon); color: #fff; transform: translateY(-2px); }
    .zbook-loginlink { font-size: 14px; font-weight: 600; color: var(--maroon); }
    .zbook-loginlink:hover { color: var(--gold-dim); }
    @media (max-width: 768px) {
        .zbook-list, .zbook-dates { grid-template-columns: 1fr; }
    }
</style>

<div class="zbook-shell min-h-screen pb-16">
    <!-- Booking Progress Timeline -->
    <div class="bg-white shadow-sm border-b" style="border-color:var(--line)!important;position:sticky;top:0;z-index:30;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="relative flex items-center justify-between">
                <!-- Timeline Line -->
                <div class="absolute timeline-line left-0 w-full" style="background:var(--line);"></div>
                
                <!-- Step 1: Search -->
                <div class="timeline-step completed relative z-10 flex flex-col items-center">
                    <div class="timeline-circle w-12 h-12 rounded-full border-4 flex items-center justify-center text-white" style="background:#167a3a;border-color:#fff;">
                        <i data-lucide="check" class="w-6 h-6"></i>
                    </div>
                    <span class="timeline-text mt-2 text-sm font-medium" style="color:var(--ink);">Search</span>
                </div>
                
                <!-- Step 2: Select -->
                <div class="timeline-step completed relative z-10 flex flex-col items-center">
                    <div class="timeline-circle w-12 h-12 rounded-full border-4 flex items-center justify-center text-white" style="background:#167a3a;border-color:#fff;">
                        <i data-lucide="check" class="w-6 h-6"></i>
                    </div>
                    <span class="timeline-text mt-2 text-sm font-medium" style="color:var(--ink);">Select</span>
                </div>
                
                <!-- Step 3: Book -->
                <div class="timeline-step active relative z-10 flex flex-col items-center">
                    <div class="timeline-circle w-12 h-12 rounded-full border-4 flex items-center justify-center" style="background:#fff;border-color:var(--line);">
                        <i data-lucide="clipboard" class="w-6 h-6" style="color:var(--maroon);"></i>
                    </div>
                    <span class="timeline-text mt-2 text-sm font-medium" style="color:var(--maroon-deep);">Book</span>
                </div>
                
                <!-- Step 4: Pay -->
                <div class="timeline-step relative z-10 flex flex-col items-center">
                    <div class="timeline-circle w-12 h-12 rounded-full border-4 flex items-center justify-center" style="background:#fff;border-color:var(--line);">
                        <i data-lucide="credit-card" class="w-6 h-6" style="color:var(--muted);"></i>
                    </div>
                    <span class="timeline-text mt-2 text-sm font-medium" style="color:var(--muted);">Pay</span>
                </div>
                
                <!-- Step 5: Confirm -->
                <div class="timeline-step relative z-10 flex flex-col items-center">
                    <div class="timeline-circle w-12 h-12 rounded-full border-4 flex items-center justify-center" style="background:#fff;border-color:var(--line);">
                        <i data-lucide="check-circle" class="w-6 h-6" style="color:var(--muted);"></i>
                    </div>
                    <span class="timeline-text mt-2 text-sm font-medium" style="color:var(--muted);">Confirm</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Left Column: Package Information -->
            <div class="lg:w-2/3">
                <div class="zbook-card mb-8">
                    <!-- Package Images Gallery -->
                    <div class="relative h-80">
                        <div class="swiper-container h-full">
                            <div class="absolute inset-0">
                                <img src="{{ asset('images/packages/' . ($package['image'] ?? 'default-package.jpg')) }}" 
                                     alt="{{ $package['name'] ?? 'Package Image' }}" 
                                     class="w-full h-full object-cover">
                            </div>
                            
                            <!-- Package Gallery Navigation -->
                            <div class="absolute bottom-4 right-4 flex space-x-2">
                                <button class="bg-black/60 hover:bg-black/80 text-white p-2 rounded-full backdrop-blur-sm">
                                    <i data-lucide="chevron-left" class="w-5 h-5"></i>
                                </button>
                                <button class="bg-black/60 hover:bg-black/80 text-white p-2 rounded-full backdrop-blur-sm">
                                    <i data-lucide="chevron-right" class="w-5 h-5"></i>
                                </button>
                            </div>
                            
                            <!-- View All Photos Button -->
                            <button class="absolute top-4 right-4 bg-black/60 hover:bg-black/80 text-white px-4 py-2 rounded-full text-sm font-medium backdrop-blur-sm flex items-center">
                                <i data-lucide="image" class="w-4 h-4 mr-2"></i>
                                View All Photos
                            </button>
                        </div>
                    </div>
                    
                    <div class="zbook-card__body">
                        <!-- Package Name and Rating -->
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h1 class="zbook-title">{{ $package['name'] ?? 'East African Adventure' }}</h1>
                                <p class="zbook-meta">
                                    <i data-lucide="map-pin"></i>
                                    {{ $package['destination'] ?? 'Destination' }}, {{ $package['country'] ?? 'Country' }}
                                </p>
                            </div>
                            
                            <div class="flex flex-col items-end">
                                <!-- Rating -->
                                <div class="zbook-rating">
                                    <i data-lucide="star"></i>
                                    <span>{{ $package['rating'] ?? '4.8' }}</span>
                                </div>
                                
                                <!-- Reviews -->
                                <span class="text-sm mt-1" style="color:var(--muted);">
                                    {{ $package['reviewCount'] ?? '124' }} reviews
                                </span>
                            </div>
                        </div>
                        
                        <!-- Package Type & Duration -->
                        <div class="flex flex-wrap gap-3 mb-6">
                            <span class="zbook-chip">
                                <i data-lucide="tag"></i>
                                {{ $package['type'] ?? 'Adventure Package' }}
                            </span>
                            
                            <span class="zbook-chip">
                                <i data-lucide="calendar"></i>
                                {{ $package['duration'] ?? '7 days' }}
                            </span>
                            
                            <span class="zbook-chip">
                                <i data-lucide="users"></i>
                                Max {{ $package['groupSize'] ?? '12' }} people
                            </span>
                            
                            <span class="zbook-chip">
                                <i data-lucide="languages"></i>
                                {{ $package['languages'] ?? 'English' }}
                            </span>
                        </div>
                        
                        <!-- Package Description -->
                        <div class="mb-8">
                            <h2 class="zbook-heading">About This Package</h2>
                            <p class="zbook-body">
                                {{ $package['description'] ?? 'Experience the best of East Africa with this comprehensive package that includes accommodation, transportation, activities, and more. You\'ll explore vibrant cities, discover breathtaking landscapes, encounter incredible wildlife, and immerse yourself in local cultures. Our expert guides will ensure you have an unforgettable journey through this amazing region.' }}
                            </p>
                            
                            <!-- Read More Button -->
                            <button class="zbook-loginlink flex items-center" style="background:none;border:0;padding:0;cursor:pointer;">
                                Read more
                                <i data-lucide="chevron-down" class="w-4 h-4 ml-1"></i>
                            </button>
                        </div>
                        
                        <!-- Package Highlights -->
                        <div class="mb-8">
                            <h2 class="zbook-heading">Package Highlights</h2>
                            <div class="zbook-list">
                                @foreach($package['highlights'] ?? ['Premium accommodation in central locations', 'All transportation including airport transfers', 'Daily breakfast and selected meals', 'Guided tours of major attractions', 'Authentic cultural experiences', 'Entrance fees to all attractions'] as $highlight)
                                    <div class="zbook-list__item">
                                        <i data-lucide="check-circle"></i>
                                        <span>{{ $highlight }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- What's Included -->
                        <div class="mb-8">
                            <h2 class="zbook-heading">What's Included</h2>
                            <div class="zbook-list">
                                @foreach($package['included'] ?? ['Hotel accommodation (6 nights)', 'All transportation in destination', 'Airport pickup and drop-off', 'Daily breakfast', '3 lunches and 2 dinners', 'Guided sightseeing tours', 'Entrance fees', '24/7 customer support'] as $included)
                                    <div class="zbook-list__item">
                                        <i data-lucide="check"></i>
                                        <span>{{ $included }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- What's Not Included -->
                        <div class="mb-8">
                            <h2 class="zbook-heading">Not Included</h2>
                            <div class="zbook-list">
                                @foreach($package['notIncluded'] ?? ['International flights', 'Travel insurance', 'Some meals (as specified in itinerary)', 'Personal expenses', 'Optional activities', 'Visa fees if applicable'] as $notIncluded)
                                    <div class="zbook-list__item zbook-list__item--no">
                                        <i data-lucide="x"></i>
                                        <span>{{ $notIncluded }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Itinerary Overview -->
                        <div class="mb-8">
                            <h2 class="zbook-heading">Itinerary Overview</h2>
                            
                            <div class="space-y-6">
                                @foreach($package['itinerary'] ?? [
                                    ['day' => 'Day 1', 'title' => 'Arrival & Welcome Dinner', 'description' => 'Airport pickup and transfer to your hotel. Evening welcome dinner with your guide and group.'],
                                    ['day' => 'Day 2', 'title' => 'City Tour', 'description' => 'Full day exploring the city\'s highlights including museums, markets, and historical sites.'],
                                    ['day' => 'Day 3', 'title' => 'Cultural Experience', 'description' => 'Visit local villages and participate in traditional activities. Authentic lunch with local families.'],
                                    ['day' => 'Day 4', 'title' => 'Nature & Wildlife', 'description' => 'Full day excursion to nearby national park or nature reserve for wildlife viewing.'],
                                    ['day' => 'Day 5', 'title' => 'Free Day', 'description' => 'Free day to relax or explore on your own. Optional activities available.'],
                                    ['day' => 'Day 6', 'title' => 'Adventure Activities', 'description' => 'Choose between hiking, water activities, or other adventures. Evening farewell dinner.'],
                                    ['day' => 'Day 7', 'title' => 'Departure', 'description' => 'Free morning before airport transfer for your departure flight.']
                                ] as $day)
                                    <div class="flex">
                                        <!-- Day indicator -->
                                        <div class="mr-4">
                                            <div class="zbook-day">
                                                {{ substr($day['day'], 4) }}
                                            </div>
                                            @if(!$loop->last)
                                                <div class="zbook-dayline"></div>
                                            @endif
                                        </div>
                                        
                                        <!-- Day content -->
                                        <div class="flex-1">
                                            <h3 class="zbook-daytitle">{{ $day['day'] }}: {{ $day['title'] }}</h3>
                                            <p class="zbook-daysum">{{ $day['description'] }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Cancellation Policy -->
                        <div>
                            <h2 class="zbook-heading">Cancellation Policy</h2>
                            <p class="zbook-note">
                                {{ $package['cancellationPolicy'] ?? 'Free cancellation up to 30 days before departure for a full refund. 15-29 days before departure: 50% refund. Less than 15 days before departure: no refund. We recommend purchasing travel insurance for unexpected circumstances.' }}
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Available Dates -->
                <div class="zbook-card mb-8">
                    <div class="zbook-card__body">
                        <h2 class="zbook-sumtitle">Available Departure Dates</h2>
                        
                        <!-- Date Selector -->
                        <div class="zbook-dates mb-6">
                            @foreach($package['dates'] ?? [
                                ['date' => '2023-11-15', 'availability' => 'available', 'spotsLeft' => 8],
                                ['date' => '2023-12-05', 'availability' => 'available', 'spotsLeft' => 4],
                                ['date' => '2023-12-20', 'availability' => 'limited', 'spotsLeft' => 2],
                                ['date' => '2024-01-10', 'availability' => 'available', 'spotsLeft' => 12],
                                ['date' => '2024-01-25', 'availability' => 'available', 'spotsLeft' => 6],
                                ['date' => '2024-02-08', 'availability' => 'limited', 'spotsLeft' => 3],
                                ['date' => '2024-02-22', 'availability' => 'available', 'spotsLeft' => 10],
                                ['date' => '2024-03-15', 'availability' => 'available', 'spotsLeft' => 8]
                            ] as $availableDate)
                                @php
                                    $date = \Carbon\Carbon::parse($availableDate['date']);
                                    $colorClass = $availableDate['availability'] === 'available' 
                                        ? 'border-gray-300 hover:border-indigo-500' 
                                        : ($availableDate['availability'] === 'limited' 
                                            ? 'border-yellow-400 bg-yellow-50' 
                                            : 'border-red-300 bg-red-50 opacity-50');
                                    $isDisabled = $availableDate['availability'] === 'soldout';
                                @endphp
                                
                                <button type="button" class="departureDateBtn text-center focus:outline-none {{ $colorClass }}" data-date="{{ $availableDate['date'] }}" {{ $isDisabled ? 'disabled' : '' }}>
                                    <div class="zbook-date__main">{{ $date->format('M d, Y') }}</div>
                                    <div class="zbook-date__sub {{ $availableDate['availability'] === 'limited' ? 'text-yellow-700' : '' }}">
                                        @if($availableDate['availability'] === 'soldout')
                                            Sold Out
                                        @else
                                            {{ $availableDate['spotsLeft'] }} spots left
                                        @endif
                                    </div>
                                </button>
                            @endforeach
                        </div>
                        
                        <p class="zbook-hint">
                            * More dates may be available for custom group bookings. Contact us for details.
                        </p>
                    </div>
                </div>
                
                <!-- Traveler Information -->
                <div class="zbook-card mb-8">
                    <div class="zbook-card__body">
                        <h2 class="zbook-sumtitle">Traveler Information</h2>
                        
                        <form id="packageBookingForm" action="{{ route('popular.destination.process') }}" method="POST">
                            @csrf
                            <input type="hidden" name="booking_type" value="package">
                            <input type="hidden" name="package_id" value="{{ $package['id'] ?? '' }}">
                            <input type="hidden" name="package_data" value="{{ json_encode($package ?? []) }}">
                            <input type="hidden" name="departure_date" id="departureDateInput" value="">
                            
                            <!-- Number of Travelers -->
                            <div class="mb-6">
                                <label for="travelers" class="zbook-label">Number of Travelers</label>
                                <div class="zbook-stepper">
                                    <button type="button" id="decreaseTravelers">
                                        <i data-lucide="minus" class="w-5 h-5"></i>
                                    </button>
                                    <input type="number" id="travelers" name="travelers" min="1" max="{{ $package['maxGroupSize'] ?? 12 }}" value="2" class="text-center focus:outline-none">
                                    <button type="button" id="increaseTravelers">
                                        <i data-lucide="plus" class="w-5 h-5"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Lead Traveler -->
                            <div class="mb-8">
                                <h3 class="zbook-subheading">Lead Traveler</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- First Name -->
                                    <div>
                                        <label for="firstName" class="zbook-label">First Name*</label>
                                        <input type="text" id="firstName" name="first_name" class="w-full" required>
                                    </div>
                                    
                                    <!-- Last Name -->
                                    <div>
                                        <label for="lastName" class="zbook-label">Last Name*</label>
                                        <input type="text" id="lastName" name="last_name" class="w-full" required>
                                    </div>
                                    
                                    <!-- Email -->
                                    <div>
                                        <label for="email" class="zbook-label">Email Address*</label>
                                        <input type="email" id="email" name="email" class="w-full" required>
                                    </div>
                                    
                                    <!-- Phone -->
                                    <div>
                                        <label for="phone" class="zbook-label">Phone Number*</label>
                                        <input type="tel" id="phone" name="phone" class="w-full" required>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Additional Information -->
                            <div class="mb-8">
                                <h3 class="zbook-subheading">Additional Information</h3>
                                
                                <div class="mb-4">
                                    <label for="specialRequests" class="zbook-label">Special Requests (optional)</label>
                                    <textarea id="specialRequests" name="special_requests" rows="3" class="w-full" placeholder="Dietary requirements, accessibility needs, or other special requests..."></textarea>
                                    <p class="zbook-hintline">Special requests are subject to availability and cannot be guaranteed.</p>
                                </div>
                                
                                <div>
                                    <label for="hearAboutUs" class="zbook-label">How did you hear about us?</label>
                                    <select id="hearAboutUs" name="hear_about_us" class="w-full">
                                        <option value="">Please select</option>
                                        <option value="search">Search Engine</option>
                                        <option value="social">Social Media</option>
                                        <option value="friend">Friend Recommendation</option>
                                        <option value="advertisement">Advertisement</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Terms and Conditions -->
                            <div class="mb-6">
                                <div class="zbook-check">
                                    <input id="terms" name="terms" type="checkbox" class="h-4 w-4 rounded" required>
                                    <label for="terms">I agree to the <a href="#">Terms and Conditions</a> and <a href="#">Privacy Policy</a>.</label>
                                </div>
                            </div>
                            
                            <!-- Continue to Payment -->
                            <div class="flex justify-end">
                                <button type="submit" id="continueToPayment" class="zbook-cta opacity-50 cursor-not-allowed" disabled>
                                    Continue to Payment
                                    <i data-lucide="chevron-right" class="w-5 h-5"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Right Column: Price Summary -->
            <div class="lg:w-1/3">
                <div class="zbook-card sticky top-24">
                    <div class="zbook-card__body">
                        <h2 class="zbook-sumtitle">Booking Summary</h2>
                        
                        <!-- Package Info Summary -->
                        <div class="flex items-center mb-6">
                            <div class="zbook-thumb">
                                <img src="{{ asset('images/packages/' . ($package['image'] ?? 'default-package.jpg')) }}" alt="{{ $package['name'] ?? 'Package' }}" class="w-full h-full object-cover">
                            </div>
                            <div class="ml-4">
                                <h3 class="zbook-subheading" style="margin-bottom:4px;">{{ $package['name'] ?? 'Package Name' }}</h3>
                                <p class="zbook-meta">
                                    <i data-lucide="map-pin"></i>
                                    {{ $package['destination'] ?? 'Destination' }}
                                </p>
                                <div class="flex items-center mt-1">
                                    <i data-lucide="star" style="width:16px;height:16px;color:var(--gold-dim);fill:currentColor;"></i>
                                    <span class="ml-1 text-sm" style="color:var(--muted);">{{ $package['rating'] ?? '4.8' }} ({{ $package['reviewCount'] ?? '124' }} reviews)</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Date & Duration Info -->
                        <div class="py-4 mb-4" style="border-top:1px solid var(--line);border-bottom:1px solid var(--line);">
                            <div id="dateTimeInfo">
                                <div class="zbook-sumrow">
                                    <i data-lucide="calendar"></i>
                                    <div>
                                        <p>Departure Date</p>
                                        <span id="selectedDate">Please select a date</span>
                                    </div>
                                </div>
                                
                                <div class="zbook-sumrow">
                                    <i data-lucide="clock"></i>
                                    <div>
                                        <p>Duration</p>
                                        <span>{{ $package['duration'] ?? '7 days' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Price Breakdown -->
                        <div class="mb-6">
                            <h3 class="zbook-subheading">Price Details</h3>
                            
                            <div>
                                <div class="zbook-pricerow">
                                    <span>Package Price (per person)</span>
                                    <span>{{ $package['currency'] ?? 'USD' }} {{ number_format($package['price'] ?? 999) }}</span>
                                </div>
                                
                                <div class="zbook-pricerow">
                                    <span>Number of travelers</span>
                                    <span id="travelerCount">2</span>
                                </div>
                                
                                <div class="zbook-pricerow">
                                    <span>Subtotal</span>
                                    <span id="subtotal">
                                        {{ $package['currency'] ?? 'USD' }} {{ number_format(($package['price'] ?? 999) * 2) }}
                                    </span>
                                </div>
                                
                                <div class="zbook-pricerow">
                                    <span>Taxes &amp; Fees (12%)</span>
                                    <span id="taxesFees">
                                        @php
                                            $taxes = ($package['price'] ?? 999) * 2 * 0.12;
                                        @endphp
                                        {{ $package['currency'] ?? 'USD' }} {{ number_format($taxes) }}
                                    </span>
                                </div>
                                
                                <div class="zbook-pricerow zbook-pricerow--total">
                                    <span>Total</span>
                                    <span id="totalPrice">
                                        @php
                                            $total = ($package['price'] ?? 999) * 2 + $taxes;
                                        @endphp
                                        {{ $package['currency'] ?? 'USD' }} {{ number_format($total) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Package Policies -->
                        <div class="mb-6">
                            <h3 class="zbook-subheading">Policies</h3>
                            
                            <div>
                                <div class="zbook-policy">
                                    <i data-lucide="shield-check"></i>
                                    <span>{{ $package['cancellationPolicySummary'] ?? 'Free cancellation up to 30 days before' }}</span>
                                </div>
                                
                                <div class="zbook-policy">
                                    <i data-lucide="credit-card"></i>
                                    <span>Reserve now, pay later</span>
                                </div>
                                
                                <div class="zbook-policy">
                                    <i data-lucide="check-circle"></i>
                                    <span>24/7 customer support</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="zbook-empty" id="selectDateMessage">
                            <i data-lucide="info"></i>
                            <p>Please select a departure date to continue</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Registration Modal -->
    <div id="registrationModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity modal-overlay" aria-hidden="true"></div>

            <!-- Modal panel -->
            <div class="inline-block align-bottom zbook-modal text-left transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full animate-fade-in-up">
                <div class="zbook-modal__body">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="zbook-modal__title" id="modal-title">
                                Create an Account to Continue
                            </h3>
                            
                            <div class="mb-4">
                                <p class="zbook-modal__text">
                                    Please create an account to continue with your booking. It only takes a minute!
                                </p>
                            </div>
                            
                            <!-- Registration Form -->
                            <form method="POST" action="{{ route('register') }}" id="quickRegisterForm">
                                @csrf
                                <input type="hidden" name="redirect_to" value="booking">
                                
                                <!-- Name -->
                                <div class="mb-4">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" required class="mt-1 block w-full shadow-sm sm:text-sm">
                                </div>
                                
                                <!-- Email Address -->
                                <div class="mb-4">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" id="email" required class="mt-1 block w-full shadow-sm sm:text-sm">
                                </div>
                                
                                <!-- Phone Number -->
                                <div class="mb-4">
                                    <label for="phone">Phone Number</label>
                                    <input type="tel" name="phone" id="phone" required class="mt-1 block w-full shadow-sm sm:text-sm">
                                </div>
                                
                                <!-- Password -->
                                <div class="mb-4">
                                    <label for="password">Password</label>
                                    <input type="password" name="password" id="password" required class="mt-1 block w-full shadow-sm sm:text-sm">
                                </div>
                                
                                <!-- Confirm Password -->
                                <div class="mb-4">
                                    <label for="password_confirmation">Confirm Password</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" required class="mt-1 block w-full shadow-sm sm:text-sm">
                                </div>
                                
                                <!-- Terms Checkbox -->
                                <div class="mb-4">
                                    <div class="zbook-check">
                                        <input id="terms" name="terms" type="checkbox" required class="h-4 w-4 rounded">
                                        <label for="terms">I agree to the <a href="#">Terms and Conditions</a>.</label>
                                    </div>
                                </div>
                                
                                <div class="flex items-center justify-between mt-6">
                                    <div class="text-sm">
                                        <a href="{{ route('login') }}" class="zbook-loginlink">
                                            Already have an account? Login
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="zbook-modal__foot sm:flex-row-reverse">
                    <button type="button" id="registerSubmitBtn" class="zbook-cta">
                        Register &amp; Continue
                    </button>
                    <button type="button" id="modalCloseBtn" class="zbook-ghost">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Initialize Lucide icons
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
        
        // Date selection functionality
        const departureDateBtns = document.querySelectorAll('.departureDateBtn');
        const selectedDate = document.getElementById('selectedDate');
        const departureDateInput = document.getElementById('departureDateInput');
        const selectDateMessage = document.getElementById('selectDateMessage');
        const continueToPayment = document.getElementById('continueToPayment');
        
        departureDateBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                // Remove active class from all buttons
                departureDateBtns.forEach(b => {
                    b.classList.remove('border-indigo-600', 'bg-indigo-50', 'text-indigo-700');
                    
                    if (!b.classList.contains('border-yellow-400') && !b.classList.contains('border-red-300')) {
                        b.classList.add('border-gray-300');
                    }
                });
                
                // Add active class to selected button (if not already styled for limited availability)
                if (!this.classList.contains('border-yellow-400') && !this.classList.contains('border-red-300')) {
                    this.classList.remove('border-gray-300');
                    this.classList.add('border-indigo-600', 'bg-indigo-50', 'text-indigo-700');
                }
                
                // Update selected date
                const date = this.dataset.date;
                const formattedDate = new Date(date).toLocaleDateString('en-US', { 
                    weekday: 'long',
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric' 
                });
                
                selectedDate.textContent = formattedDate;
                departureDateInput.value = date;
                
                // Hide message and enable continue button
                selectDateMessage.style.display = 'none';
                continueToPayment.classList.remove('opacity-50', 'cursor-not-allowed');
                continueToPayment.disabled = false;
            });
        });
        
        // Traveler count functionality
        const decreaseTravelersBtn = document.getElementById('decreaseTravelers');
        const increaseTravelersBtn = document.getElementById('increaseTravelers');
        const travelersInput = document.getElementById('travelers');
        const travelerCount = document.getElementById('travelerCount');
        const subtotal = document.getElementById('subtotal');
        const taxesFees = document.getElementById('taxesFees');
        const totalPrice = document.getElementById('totalPrice');
        
        // Update price calculations based on traveler count
        function updatePrices() {
            const price = {{ $package['price'] ?? 999 }};
            const travelers = parseInt(travelersInput.value);
            const currency = '{{ $package['currency'] ?? 'USD' }}';
            
            travelerCount.textContent = travelers;
            
            const subtotalValue = price * travelers;
            subtotal.textContent = `${currency} ${subtotalValue.toLocaleString()}`;
            
            const taxesValue = subtotalValue * 0.12;
            taxesFees.textContent = `${currency} ${taxesValue.toLocaleString()}`;
            
            const totalValue = subtotalValue + taxesValue;
            totalPrice.textContent = `${currency} ${totalValue.toLocaleString()}`;
        }
        
        decreaseTravelersBtn.addEventListener('click', function() {
            const currentValue = parseInt(travelersInput.value);
            if (currentValue > 1) {
                travelersInput.value = currentValue - 1;
                updatePrices();
            }
        });
        
        increaseTravelersBtn.addEventListener('click', function() {
            const currentValue = parseInt(travelersInput.value);
            const maxValue = {{ $package['maxGroupSize'] ?? 12 }};
            if (currentValue < maxValue) {
                travelersInput.value = currentValue + 1;
                updatePrices();
            }
        });
        
        // Update prices if traveler input changes directly
        travelersInput.addEventListener('change', function() {
            const min = parseInt(this.min);
            const max = parseInt(this.max);
            let value = parseInt(this.value);
            
            if (isNaN(value) || value < min) {
                value = min;
            } else if (value > max) {
                value = max;
            }
            
            this.value = value;
            updatePrices();
        });
        
        // Handle package booking form submission
        const packageBookingForm = document.getElementById('packageBookingForm');
        const registrationModal = document.getElementById('registrationModal');
        
        packageBookingForm.addEventListener('submit', function(event) {
            // Check if a departure date has been selected
            if (!departureDateInput.value) {
                event.preventDefault();
                alert('Please select a departure date.');
                return;
            }
            
            // Check if user is logged in
            const isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};
            
            if (!isLoggedIn) {
                event.preventDefault();
                registrationModal.classList.remove('hidden');
            }
            // If user is logged in, form submits normally
        });
        
        // Modal functionality
        const modalCloseBtn = document.getElementById('modalCloseBtn');
        modalCloseBtn.addEventListener('click', function() {
            registrationModal.classList.add('hidden');
        });
        
        // Registration form submission
        const quickRegisterForm = document.getElementById('quickRegisterForm');
        const registerSubmitBtn = document.getElementById('registerSubmitBtn');
        
        registerSubmitBtn.addEventListener('click', function() {
            // Trigger form submission
            quickRegisterForm.submit();
        });
    });
    
    // Toast notification functionality
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `fixed bottom-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 flex items-center ${
            type === 'success' ? 'bg-green-500' : 'bg-red-500'
        } text-white transform transition-all duration-300 translate-y-20 opacity-0`;
        
        toast.innerHTML = `
            <i data-lucide="${type === 'success' ? 'check-circle' : 'alert-circle'}" class="w-5 h-5 mr-2"></i>
            <span>${message}</span>
        `;
        
        document.body.appendChild(toast);
        
        // Initialize the Lucide icon in the toast
        lucide.createIcons();
        
        // Show toast with animation
        setTimeout(() => {
            toast.classList.remove('translate-y-20', 'opacity-0');
        }, 100);
        
        // Hide toast after 5 seconds
        setTimeout(() => {
            toast.classList.add('translate-y-20', 'opacity-0');
            
            // Remove from DOM after animation completes
            setTimeout(() => {
                document.body.removeChild(toast);
            }, 300);
        }, 5000);
    }
    
    // Show success toast if redirected with success message
    @if(session('success'))
        document.addEventListener('DOMContentLoaded', function() {
            showToast('{{ session('success') }}', 'success');
        });
    @endif
    
    // Show error toast if redirected with error message
    @if(session('error'))
        document.addEventListener('DOMContentLoaded', function() {
            showToast('{{ session('error') }}', 'error');
        });
    @endif
</script>
@endsection
