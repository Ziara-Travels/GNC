@extends('layouts.main')

@section('content')
@include('partials.ziara-page-hero', [
    'kicker' => 'Secure Checkout',
    'title'  => 'Complete Your <em>Payment</em>',
    'intro'  => 'Review your trip details and choose a secure payment method to confirm your destination booking.',
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
    
    /* Pulse animation for secured badge */
    .animate-pulse-slow {
        animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    
    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.8;
        }
    }

    .zchk-shell { background: var(--gold-pale); }
    .zchk-card {
        background: #fff;
        border: 1px solid var(--line);
        border-radius: 16px;
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }
    .zchk-card__body { padding: 30px 28px; }
    .zchk-title {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: 26px;
        font-weight: 700;
        color: var(--maroon-deep);
        margin: 0 0 20px;
    }
    .zchk-heading {
        font-size: 18px;
        font-weight: 700;
        color: var(--ink);
        margin: 0 0 14px;
    }
    .zchk-media { border-radius: 14px; overflow: hidden; height: 100%; min-height: 200px; background: var(--gold-pale); }
    .zchk-media img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .zchk-destname {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: 24px;
        font-weight: 700;
        color: var(--maroon-deep);
        margin: 0;
    }
    .zchk-meta { display: flex; align-items: center; flex-wrap: wrap; gap: 8px; font-size: 14px; color: var(--muted); }
    .zchk-meta i { color: var(--maroon); font-size: 15px; }
    .zchk-dot { color: var(--gold-dim); }
    .zchk-tagbox {
        margin-top: 18px;
        padding: 15px 17px;
        background: var(--gold-pale);
        border: 1px solid var(--gold-border);
        border-left: 4px solid var(--gold);
        border-radius: 10px;
    }
    .zchk-tagbox__title { font-size: 15px; font-weight: 700; color: var(--maroon-deep); margin: 0 0 5px; }
    .zchk-tagbox__desc { font-size: 14px; line-height: 1.65; color: #3a322c; margin: 0; }
    .zchk-facts { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; margin-top: 20px; }
    .zchk-fact__label { font-size: 11px; font-weight: 700; letter-spacing: .8px; text-transform: uppercase; color: var(--muted); margin-bottom: 4px; }
    .zchk-fact__value { font-size: 15px; font-weight: 600; color: var(--ink); }
    .zchk-rule { border-top: 1px solid var(--line); }
    .zchk-row { display: flex; align-items: flex-start; gap: 12px; }
    .zchk-row + .zchk-row { margin-top: 13px; }
    .zchk-row i { color: var(--maroon); font-size: 18px; flex-shrink: 0; margin-top: 1px; }
    .zchk-row i.zchk-row__icon--ok { color: #167a3a; }
    .zchk-row__text { font-size: 14.5px; line-height: 1.65; color: var(--ink); }
    .zchk-row__text strong { color: #167a3a; }
    .zchk-includes { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px 22px; }
    .zchk-include { display: flex; align-items: center; gap: 11px; font-size: 14.5px; color: var(--ink); }
    .zchk-include i { color: #167a3a; font-size: 17px; flex-shrink: 0; }
    .zchk-secure {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        border-radius: 999px;
        background: #eef7ef;
        border: 1px solid rgba(22,122,58,.3);
        color: #167a3a;
        font-size: 12.5px;
        font-weight: 600;
    }
    .zchk-method {
        display: flex;
        align-items: center;
        gap: 13px;
        padding: 15px 17px;
        border: 1.5px solid var(--line);
        border-radius: 12px;
        background: #fff;
        cursor: pointer;
        transition: border-color .25s, background .25s;
    }
    .zchk-method + .zchk-method { margin-top: 13px; }
    .zchk-method:hover { border-color: var(--gold); background: var(--gold-pale); }
    .zchk-method:has(input:checked) { border-color: var(--maroon); background: var(--gold-pale); }
    .zchk-method input { width: 18px; height: 18px; accent-color: var(--maroon); flex-shrink: 0; cursor: pointer; }
    .zchk-method label { font-size: 15px; font-weight: 600; color: var(--ink); cursor: pointer; }
    .zchk-logos { margin-left: auto; display: flex; gap: 9px; align-items: center; }
    .zchk-logos img { height: 24px; }
    .zchk-label {
        display: block;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .8px;
        text-transform: uppercase;
        color: var(--muted);
        margin-bottom: 8px;
    }
    .zchk-required {
        display: inline-block;
        padding: 4px 11px;
        border-radius: 999px;
        background: var(--gold-pale);
        border: 1px solid var(--gold-border);
        color: var(--maroon);
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .6px;
        text-transform: uppercase;
    }
    .zchk-hint { font-size: 12.5px; color: var(--muted); margin: 7px 0 0; }
    .zchk-check { display: flex; align-items: flex-start; gap: 12px; }
    .zchk-check input { width: 18px; height: 18px; accent-color: var(--maroon); margin-top: 1px; flex-shrink: 0; }
    .zchk-check label { font-size: 14px; line-height: 1.7; color: var(--ink); }
    .zchk-check a { color: var(--maroon); font-weight: 600; }
    .zchk-check a:hover { color: var(--gold-dim); }
    .zchk-pay {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        width: 100%;
        padding: 16px 36px;
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
    .zchk-pay:hover { background: var(--maroon-deep); transform: translateY(-2px); box-shadow: 0 10px 24px rgba(107,44,31,.28); }
    .zchk-trust { display: flex; flex-wrap: wrap; align-items: center; justify-content: center; gap: 28px; }
    .zchk-trust__item { display: flex; align-items: center; gap: 9px; font-size: 14px; color: var(--ink); }
    .zchk-trust__item i { color: #167a3a; font-size: 18px; }
    .zchk-sumtitle { font-family: 'Cormorant Garamond', Georgia, serif; font-size: 26px; font-weight: 700; color: var(--maroon-deep); margin: 0 0 20px; }
    .zchk-pricerow { display: flex; justify-content: space-between; gap: 14px; font-size: 14.5px; color: var(--muted); }
    .zchk-pricerow + .zchk-pricerow { margin-top: 11px; }
    .zchk-pricerow--discount { color: #167a3a; }
    .zchk-pricerow--total {
        border-top: 2px solid var(--gold);
        margin-top: 16px;
        padding-top: 14px;
        font-size: 18px;
        font-weight: 700;
        color: var(--maroon-deep);
    }
    .zchk-note { font-size: 12.5px; color: var(--muted); display: flex; align-items: center; justify-content: flex-end; gap: 6px; margin-top: 8px; }
    .zchk-policy {
        margin-top: 24px;
        padding: 18px 20px;
        background: var(--gold-pale);
        border: 1px solid var(--gold-border);
        border-left: 4px solid var(--gold);
        border-radius: 10px;
    }
    .zchk-policy p { font-size: 14px; line-height: 1.7; color: var(--ink); margin: 0; }
    .zchk-support { margin-top: 24px; display: flex; align-items: center; justify-content: center; gap: 9px; font-size: 14px; color: var(--ink); }
    .zchk-support i { color: var(--maroon); font-size: 18px; }
    .zchk-support a { color: var(--maroon); font-weight: 600; }
    .zchk-support a:hover { color: var(--gold-dim); }
    .zchk-card input:not([type="hidden"]):not([type="checkbox"]):not([type="radio"]):not([type="submit"]),
    .zchk-card select,
    .zchk-card textarea,
    .zchk-modal input:not([type="hidden"]):not([type="checkbox"]),
    .zchk-modal select {
        border-color: var(--line);
        border-radius: 10px;
        padding: 13px 15px;
        font-family: var(--font-body);
        font-size: 15px;
        color: var(--ink);
        background: #fff;
    }
    .zchk-card input:focus, .zchk-card select:focus, .zchk-card textarea:focus,
    .zchk-modal input:focus, .zchk-modal select:focus {
        outline: none;
        border-color: var(--gold);
        box-shadow: 0 0 0 3px var(--gold-border);
    }
    .zchk-card input[type="checkbox"], .zchk-modal input[type="checkbox"] { accent-color: var(--maroon); }
    .zchk-card input[type="radio"], .zchk-method input[type="radio"] { accent-color: var(--maroon); }
    .zchk-modal { background: #fff; border-radius: 18px; box-shadow: 0 30px 70px rgba(31,20,16,.34); overflow: hidden; }
    .zchk-modal__body { padding: 30px 28px 20px; text-align: left; }
    .zchk-modal__title { font-family: 'Cormorant Garamond', Georgia, serif; font-size: 25px; font-weight: 700; color: var(--maroon-deep); margin: 0 0 12px; }
    .zchk-modal__text { font-size: 14.5px; line-height: 1.7; color: var(--muted); margin: 0 0 20px; }
    .zchk-modal label { display: block; font-size: 11px; font-weight: 700; letter-spacing: .8px; text-transform: uppercase; color: var(--muted); margin-bottom: 8px; }
    .zchk-modal .zchk-check label { font-size: 14px; letter-spacing: 0; text-transform: none; font-weight: 500; margin-bottom: 0; }
    .zchk-modal__foot { background: var(--gold-pale); padding: 16px 28px; display: flex; gap: 12px; }
    .zchk-modal__foot .zchk-pay { flex: 1; width: auto; }
    .zchk-modal__foot .zchk-ghost {
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
    .zchk-modal__foot .zchk-ghost:hover { background: var(--maroon); color: #fff; transform: translateY(-2px); }
    .zchk-loginlink { font-size: 14px; font-weight: 600; color: var(--maroon); }
    .zchk-loginlink:hover { color: var(--gold-dim); }
    @media (max-width: 768px) {
        .zchk-facts, .zchk-includes { grid-template-columns: 1fr; }
    }
</style>

<div class="zchk-shell min-h-screen pb-16">
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
                <div class="timeline-step completed relative z-10 flex flex-col items-center">
                    <div class="timeline-circle w-12 h-12 rounded-full border-4 flex items-center justify-center text-white" style="background:#167a3a;border-color:#fff;">
                        <i data-lucide="check" class="w-6 h-6"></i>
                    </div>
                    <span class="timeline-text mt-2 text-sm font-medium" style="color:var(--ink);">Book</span>
                </div>
                
                <!-- Step 4: Pay -->
                <div class="timeline-step active relative z-10 flex flex-col items-center">
                    <div class="timeline-circle w-12 h-12 rounded-full border-4 flex items-center justify-center" style="background:#fff;border-color:var(--line);">
                        <i data-lucide="credit-card" class="w-6 h-6" style="color:var(--maroon);"></i>
                    </div>
                    <span class="timeline-text mt-2 text-sm font-medium" style="color:var(--maroon-deep);">Pay</span>
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
            <!-- Left Column: Payment Form -->
            <div class="lg:w-2/3">
                <!-- Destination Summary -->
                <div class="zchk-card mb-8">
                    <div class="zchk-card__body">
                        <h2 class="zchk-title">Destination Summary</h2>
                        
                        <div class="flex flex-col md:flex-row gap-6 mb-6">
                            <!-- Destination Image -->
                            <div class="md:w-1/3">
                                <div class="zchk-media">
                                    <img src="{{ asset('images/destinations/' . ($destination['image'] ?? 'destination-placeholder.jpg')) }}" alt="{{ $destination['name'] ?? 'Destination' }}" class="w-full h-full object-cover">
                                </div>
                            </div>
                            
                            <!-- Destination Details -->
                            <div class="md:w-2/3">
                                <h3 class="zchk-destname">{{ $destination['name'] ?? 'Zanzibar Beach Getaway' }}</h3>
                                
                                <div class="zchk-meta mt-2">
                                    <i data-lucide="map-pin"></i>
                                    <span>{{ $destination['location'] ?? 'Zanzibar, Tanzania' }}</span>
                                    
                                    <span class="zchk-dot">•</span>
                                    
                                    <i data-lucide="star" style="color:var(--gold-dim);fill:currentColor;"></i>
                                    <span>{{ $destination['rating'] ?? '4.8' }} ({{ $destination['review_count'] ?? '156' }} reviews)</span>
                                </div>
                            
                            <!-- Destination Type -->
                            <div class="zchk-tagbox">
                                <div class="zchk-tagbox__title">{{ $destination['type'] ?? 'Beach Destination' }}</div>
                                <div class="zchk-tagbox__desc">{{ $destination['highlights'] ?? 'White sand beaches, crystal clear water, water sports and local cuisine' }}</div>
                            </div>
                            
                            <!-- Destination Details -->
                            <div class="zchk-facts">
                                <div>
                                    <div class="zchk-fact__label">Check-in</div>
                                    <div class="zchk-fact__value">{{ $booking['check_in'] ?? date('D, M d, Y') }}</div>
                                </div>
                                
                                <div>
                                    <div class="zchk-fact__label">Check-out</div>
                                    <div class="zchk-fact__value">{{ $booking['check_out'] ?? date('D, M d, Y', strtotime('+5 days')) }}</div>
                                </div>
                                
                                <div>
                                    <div class="zchk-fact__label">Duration</div>
                                    <div class="zchk-fact__value">{{ $destination['duration'] ?? '5 days, 4 nights' }}</div>
                                </div>
                                
                                <div>
                                    <div class="zchk-fact__label">Travelers</div>
                                    <div class="zchk-fact__value">{{ $booking['adults'] ?? '2' }} adults{{ ($booking['children'] ?? 0) > 0 ? ', ' . $booking['children'] . ' children' : '' }}</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="zchk-rule pt-4 mt-4">
                            <h3 class="zchk-heading">Travel Details</h3>
                            
                            <div>
                                <div class="zchk-row">
                                    <i data-lucide="plane"></i>
                                    <div class="zchk-row__text">
                                        {{ $destination['flight_details'] ?? 'Flight to Zanzibar International Airport (ZNZ) included' }}
                                    </div>
                                </div>
                                
                                <div class="zchk-row">
                                    <i data-lucide="home"></i>
                                    <div class="zchk-row__text">
                                        {{ $destination['accommodation'] ?? 'Beach front resort accommodation (4-star)' }}
                                    </div>
                                </div>
                                
                                <div class="zchk-row">
                                    <i data-lucide="shield-check" class="zchk-row__icon--ok"></i>
                                    <div class="zchk-row__text">
                                        <strong>{{ $destination['cancellation'] ?? 'Free cancellation' }}</strong> up to 7 days before the trip starts
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Package Includes -->
                        <div class="mt-6">
                            <h3 class="zchk-heading">Package Includes</h3>
                            
                            <div class="zchk-includes">
                                @foreach($destination['included'] ?? ['Return flights', 'Airport transfers', 'Resort accommodation', 'Breakfast and dinner daily', 'Island tour', 'Snorkeling experience'] as $included)
                                    <div class="zchk-include">
                                        <i data-lucide="check"></i>
                                        <span>{{ $included }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Payment Method -->
                <div class="zchk-card">
                    <div class="zchk-card__body">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="zchk-title" style="margin:0;">Payment Method</h2>
                            
                            <!-- Secured Payment Badge -->
                            <div class="zchk-secure">
                                <i data-lucide="shield" class="animate-pulse-slow"></i>
                                <span>Secure Payment</span>
                            </div>
                        </div>
                        
                        <!-- Payment Method Selection -->
                        <div class="mb-6">
                            <div class="zchk-method">
                                <input id="card" name="paymentMethod" type="radio" checked>
                                <label for="card">
                                    Credit/Debit Card
                                </label>
                                
                                <!-- Card Icons -->
                                <div class="zchk-logos">
                                    <img src="{{ asset('images/payment/visa.svg') }}" alt="Visa" class="h-6">
                                    <img src="{{ asset('images/payment/mastercard.svg') }}" alt="Mastercard" class="h-6">
                                    <img src="{{ asset('images/payment/amex.svg') }}" alt="American Express" class="h-6">
                                </div>
                            </div>
                            
                            <div class="zchk-method">
                                <input id="mpesa" name="paymentMethod" type="radio">
                                <label for="mpesa">
                                    M-Pesa
                                </label>
                                
                                <!-- MPesa Icon -->
                                <div class="zchk-logos">
                                    <img src="{{ asset('images/payment/mpesa.svg') }}" alt="MPesa" class="h-6">
                                </div>
                            </div>
                            
                            <div class="zchk-method">
                                <input id="paypal" name="paymentMethod" type="radio">
                                <label for="paypal">
                                    PayPal
                                </label>
                                
                                <!-- PayPal Icon -->
                                <div class="zchk-logos">
                                    <img src="{{ asset('images/payment/paypal.svg') }}" alt="PayPal" class="h-6">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Credit Card Form (Default active) -->
                        <div id="cardPaymentForm" class="zchk-rule pt-6">
                            <form action="{{ route('popular.destination.process') }}" method="POST">
                                @csrf
                                <input type="hidden" name="booking_id" value="{{ $booking['id'] ?? '' }}">
                                <input type="hidden" name="booking_type" value="destination">
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Card Holder Name -->
                                    <div class="col-span-2">
                                        <label for="cardName" class="zchk-label">Cardholder Name</label>
                                        <input type="text" name="cardName" id="cardName" class="w-full" placeholder="As appears on card">
                                    </div>
                                    
                                    <!-- Card Number -->
                                    <div class="col-span-2">
                                        <label for="cardNumber" class="zchk-label">Card Number</label>
                                        <div class="relative">
                                            <input type="text" name="cardNumber" id="cardNumber" class="w-full pr-10" placeholder="1234 5678 9012 3456" maxlength="19">
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                                <i data-lucide="credit-card" style="height:20px;width:20px;color:var(--muted);"></i>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Expiry Date -->
                                    <div>
                                        <label for="cardExpiry" class="zchk-label">Expiry Date</label>
                                        <input type="text" name="cardExpiry" id="cardExpiry" class="w-full" placeholder="MM/YY" maxlength="5">
                                    </div>
                                    
                                    <!-- CVV -->
                                    <div>
                                        <label for="cardCvv" class="zchk-label">CVV</label>
                                        <div class="relative">
                                            <input type="text" name="cardCvv" id="cardCvv" class="w-full pr-10" placeholder="123" maxlength="4">
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                                <i data-lucide="help-circle" style="height:20px;width:20px;color:var(--muted);cursor:help;" title="3 or 4 digit security code usually found on the back of your card"></i>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Billing Address -->
                                    <div class="col-span-2">
                                        <div class="flex items-center mb-4">
                                            <h3 class="zchk-heading" style="margin:0;">Billing Address</h3>
                                            <span class="ml-2 zchk-required">Required</span>
                                        </div>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <!-- Country -->
                                            <div class="col-span-2">
                                                <label for="country" class="zchk-label">Country</label>
                                                <select name="country" id="country" class="w-full">
                                                    <option value="">Select Country</option>
                                                    <option value="KE">Kenya</option>
                                                    <option value="UG">Uganda</option>
                                                    <option value="TZ">Tanzania</option>
                                                    <option value="RW">Rwanda</option>
                                                    <option value="US">United States</option>
                                                    <option value="GB">United Kingdom</option>
                                                    <!-- Add more countries as needed -->
                                                </select>
                                            </div>
                                            
                                            <!-- Address -->
                                            <div class="col-span-2">
                                                <label for="address" class="zchk-label">Street Address</label>
                                                <input type="text" name="address" id="address" class="w-full">
                                            </div>
                                            
                                            <!-- City -->
                                            <div>
                                                <label for="city" class="zchk-label">City</label>
                                                <input type="text" name="city" id="city" class="w-full">
                                            </div>
                                            
                                            <!-- Postal Code -->
                                            <div>
                                                <label for="postalCode" class="zchk-label">Postal Code</label>
                                                <input type="text" name="postalCode" id="postalCode" class="w-full">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Save Card Info -->
                                    <div class="col-span-2 mt-4">
                                        <div class="zchk-check">
                                            <input id="saveCardInfo" name="saveCardInfo" type="checkbox" class="h-4 w-4 rounded">
                                            <label for="saveCardInfo">
                                                Save my card information securely for future bookings
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Terms and Conditions -->
                                <div class="mt-6">
                                    <div class="zchk-check">
                                        <input id="terms" name="terms" type="checkbox" required class="h-4 w-4 rounded">
                                        <label for="terms">
                                            I agree to the <a href="#">Terms and Conditions</a>, <a href="#">Privacy Policy</a>, and <a href="#">Cancellation Policy</a>
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- Submit Button -->
                                <div class="mt-8">
                                    <button type="submit" class="zchk-pay">
                                        Pay &amp; Confirm Booking
                                        <i data-lucide="lock" class="w-5 h-5"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Mobile Money Form (Initially hidden) -->
                        <div id="mpesaPaymentForm" class="hidden zchk-rule pt-6">
                            <form action="{{ route('popular.destination.process') }}" method="POST">
                                @csrf
                                <input type="hidden" name="payment_method" value="mpesa">
                                <input type="hidden" name="booking_id" value="{{ $booking['id'] ?? '' }}">
                                <input type="hidden" name="booking_type" value="destination">
                                
                                <!-- Phone Number -->
                                <div class="mb-6">
                                    <label for="mpesaPhone" class="zchk-label">M-Pesa Phone Number</label>
                                    <div class="relative">
                                        <input type="tel" name="mpesaPhone" id="mpesaPhone" class="w-full" placeholder="e.g. 07XXXXXXXX">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                            <i data-lucide="smartphone" style="height:20px;width:20px;color:var(--muted);"></i>
                                        </div>
                                    </div>
                                    <p class="zchk-hint">You will receive a payment prompt on this phone number</p>
                                </div>
                                
                                <!-- Terms and Conditions -->
                                <div class="mt-6">
                                    <div class="zchk-check">
                                        <input id="mpesaTerms" name="terms" type="checkbox" required class="h-4 w-4 rounded">
                                        <label for="mpesaTerms">
                                            I agree to the <a href="#">Terms and Conditions</a>, <a href="#">Privacy Policy</a>, and <a href="#">Cancellation Policy</a>
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- Submit Button -->
                                <div class="mt-8">
                                    <button type="submit" class="zchk-pay">
                                        Pay with M-Pesa
                                        <i data-lucide="smartphone" class="w-5 h-5"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <!-- PayPal Form (Initially hidden) -->
                        <div id="paypalPaymentForm" class="hidden zchk-rule pt-6">
                            <form action="{{ route('popular.destination.process') }}" method="POST">
                                @csrf
                                <input type="hidden" name="payment_method" value="paypal">
                                <input type="hidden" name="booking_id" value="{{ $booking['id'] ?? '' }}">
                                <input type="hidden" name="booking_type" value="destination">
                                
                                <div class="text-center py-6">
                                    <p class="mb-6" style="color:var(--ink);">Click the button below to proceed to PayPal's secure payment page.</p>
                                    
                                    <button type="submit" class="zchk-pay" style="width:auto;background:#0070ba;margin:0 auto;">
                                        <img src="{{ asset('images/payment/paypal-white.svg') }}" alt="PayPal" class="h-5 mr-2">
                                        Pay with PayPal
                                    </button>
                                </div>
                                
                                <!-- Terms and Conditions -->
                                <div class="mt-6">
                                    <div class="zchk-check">
                                        <input id="paypalTerms" name="terms" type="checkbox" required class="h-4 w-4 rounded">
                                        <label for="paypalTerms">
                                            I agree to the <a href="#">Terms and Conditions</a>, <a href="#">Privacy Policy</a>, and <a href="#">Cancellation Policy</a>
                                        </label>
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Payment Security Information -->
                        <div class="mt-8 zchk-rule pt-6">
                            <div class="zchk-trust">
                                <div class="zchk-trust__item">
                                    <i data-lucide="shield"></i>
                                    <span>Secure Payment</span>
                                </div>
                                
                                <div class="zchk-trust__item">
                                    <i data-lucide="lock"></i>
                                    <span>Encrypted Data</span>
                                </div>
                                
                                <div class="zchk-trust__item">
                                    <i data-lucide="eye-off"></i>
                                    <span>Privacy Protected</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Column: Booking Summary -->
            <div class="lg:w-1/3">
                <div class="zchk-card sticky top-24">
                    <div class="zchk-card__body">
                        <h2 class="zchk-sumtitle">Trip Details</h2>
                        
                        <!-- Destination Summary -->
                        <div class="mb-6">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <h3 class="zchk-heading" style="margin-bottom:4px;">{{ $destination['name'] ?? 'Zanzibar Beach Getaway' }}</h3>
                                    <p class="zchk-meta" style="font-size:13.5px;">{{ $destination['duration'] ?? '5 days, 4 nights' }}</p>
                                </div>
                            </div>
                            
                            <div class="zchk-meta" style="font-size:13.5px;display:block;margin-top:10px;">
                                <div class="zchk-row" style="margin:0 0 8px;">
                                    <i data-lucide="calendar" style="font-size:16px;"></i>
                                    <span>{{ $booking['check_in'] ?? date('M d, Y') }} - {{ $booking['check_out'] ?? date('M d, Y', strtotime('+5 days')) }}</span>
                                </div>
                                
                                <div class="zchk-row" style="margin:0;">
                                    <i data-lucide="users" style="font-size:16px;"></i>
                                    <span>{{ $booking['adults'] ?? '2' }} adults{{ ($booking['children'] ?? 0) > 0 ? ', ' . $booking['children'] . ' children' : '' }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Price Breakdown -->
                        <div class="zchk-rule" style="padding:16px 0;">
                            <div>
                                <!-- Package Price -->
                                <div class="zchk-pricerow">
                                    <span>Package Price ({{ $booking['adults'] ?? '2' }} × {{ $destination['currency'] ?? 'USD' }} {{ $destination['adult_price'] ?? '899' }})</span>
                                    <span>
                                        @php
                                            $adultTotal = ($booking['adults'] ?? 2) * ($destination['adult_price'] ?? 899);
                                        @endphp
                                        {{ $destination['currency'] ?? 'USD' }} {{ $adultTotal }}
                                    </span>
                                </div>
                                
                                @if(($booking['children'] ?? 0) > 0)
                                    <!-- Child Price -->
                                    <div class="zchk-pricerow">
                                        <span>Child Price ({{ $booking['children'] }} × {{ $destination['currency'] ?? 'USD' }} {{ $destination['child_price'] ?? '499' }})</span>
                                        <span>
                                            @php
                                                $childTotal = ($booking['children'] ?? 0) * ($destination['child_price'] ?? 499);
                                            @endphp
                                            {{ $destination['currency'] ?? 'USD' }} {{ $childTotal }}
                                        </span>
                                    </div>
                                @endif
                                
                                <!-- Airport Transfer -->
                                <div class="zchk-pricerow">
                                    <span>Airport transfers</span>
                                    <span>
                                        @php
                                            $transferFee = 50;
                                        @endphp
                                        {{ $destination['currency'] ?? 'USD' }} {{ $transferFee }}
                                    </span>
                                </div>
                                
                                <!-- Booking Fee -->
                                <div class="zchk-pricerow">
                                    <span>Booking fee</span>
                                    <span>
                                        @php
                                            $bookingFee = 45;
                                        @endphp
                                        {{ $destination['currency'] ?? 'USD' }} {{ $bookingFee }}
                                    </span>
                                </div>
                                
                                <!-- Taxes -->
                                <div class="zchk-pricerow">
                                    <span>Taxes (10%)</span>
                                    <span>
                                        @php
                                            $subtotal = $adultTotal + ($childTotal ?? 0) + $transferFee + $bookingFee;
                                            $taxes = $subtotal * 0.10;
                                            $taxes = round($taxes, 2);
                                        @endphp
                                        {{ $destination['currency'] ?? 'USD' }} {{ $taxes }}
                                    </span>
                                </div>
                                
                                @if(($destination['discount'] ?? 0) > 0)
                                    <!-- Discount -->
                                    <div class="zchk-pricerow zchk-pricerow--discount">
                                        <span>{{ $destination['discount'] }}% Last-minute discount</span>
                                        <span>
                                            @php
                                                $discount = $subtotal * ($destination['discount'] ?? 0) / 100;
                                                $discount = round($discount, 2);
                                            @endphp
                                            -{{ $destination['currency'] ?? 'USD' }} {{ $discount }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Total -->
                        <div class="pt-4">
                            <div class="zchk-pricerow zchk-pricerow--total" style="margin:0;border:0;padding:0;">
                                <span>Total</span>
                                <span>
                                    @php
                                        $total = $subtotal + $taxes;
                                        if(($destination['discount'] ?? 0) > 0) {
                                            $total -= $discount;
                                        }
                                        $total = round($total, 2);
                                    @endphp
                                    {{ $destination['currency'] ?? 'USD' }} {{ $total }}
                                </span>
                            </div>
                            
                            <div class="zchk-note">
                                <i data-lucide="info" style="height:13px;width:13px;"></i>
                                Includes taxes and fees
                            </div>
                        </div>
                        
                        <!-- Cancellation Policy -->
                        <div class="zchk-policy">
                            <h3 class="zchk-heading">Cancellation Policy</h3>
                            <p>
                                {{ $destination['cancellation_policy'] ?? 'Free cancellation up to 7 days before arrival. If you cancel less than 7 days before arrival, 50% of the total amount will be charged. No-show: 100% of the first night will be charged.' }}
                            </p>
                        </div>
                        
                        <!-- Contact Support -->
                        <div class="zchk-support">
                            <i data-lucide="headphones"></i>
                            <span>Need help? <a href="#">Contact support</a></span>
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
            <div class="inline-block align-bottom zchk-modal text-left transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full animate-fade-in-up">
                <div class="zchk-modal__body">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="zchk-modal__title" id="modal-title">
                                Create an Account to Continue
                            </h3>
                            
                            <div class="mb-4">
                                <p class="zchk-modal__text">
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
                                    <div class="zchk-check">
                                        <input id="terms" name="terms" type="checkbox" required class="h-4 w-4 rounded">
                                        <label for="terms">I agree to the <a href="#">Terms and Conditions</a>.</label>
                                    </div>
                                </div>
                                
                                <div class="flex items-center justify-between mt-6">
                                    <div class="text-sm">
                                        <a href="{{ route('login') }}" class="zchk-loginlink">
                                            Already have an account? Login
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="zchk-modal__foot sm:flex-row-reverse">
                    <button type="button" id="registerSubmitBtn" class="zchk-pay">
                        Register &amp; Continue
                    </button>
                    <button type="button" id="modalCloseBtn" class="zchk-ghost">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Lucide icons
        lucide.createIcons();
        
        // Payment method selection
        const paymentOptions = document.querySelectorAll('input[name="paymentMethod"]');
        const cardPaymentForm = document.getElementById('cardPaymentForm');
        const mpesaPaymentForm = document.getElementById('mpesaPaymentForm');
        const paypalPaymentForm = document.getElementById('paypalPaymentForm');
        
        paymentOptions.forEach(option => {
            option.addEventListener('change', function() {
                // Hide all payment forms
                cardPaymentForm.classList.add('hidden');
                mpesaPaymentForm.classList.add('hidden');
                paypalPaymentForm.classList.add('hidden');
                
                // Show the selected payment form
                if (this.id === 'card') {
                    cardPaymentForm.classList.remove('hidden');
                } else if (this.id === 'mpesa') {
                    mpesaPaymentForm.classList.remove('hidden');
                } else if (this.id === 'paypal') {
                    paypalPaymentForm.classList.remove('hidden');
                }
            });
        });
        
        // Credit card input formatting
        const cardNumberInput = document.getElementById('cardNumber');
        const cardExpiryInput = document.getElementById('cardExpiry');
        const cardCvvInput = document.getElementById('cardCvv');
        
        // Format card number with spaces
        if (cardNumberInput) {
            cardNumberInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\s+/g, '');
                if (value.length > 0) {
                    value = value.match(new RegExp('.{1,4}', 'g')).join(' ');
                }
                e.target.value = value;
            });
        }
        
        // Format expiry date with slash
        if (cardExpiryInput) {
            cardExpiryInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 0) {
                    if (value.length > 2) {
                        value = value.slice(0, 2) + '/' + value.slice(2, 4);
                    }
                }
                e.target.value = value;
            });
        }
        
        // Limit CVV to numbers
        if (cardCvvInput) {
            cardCvvInput.addEventListener('input', function(e) {
                e.target.value = e.target.value.replace(/\D/g, '');
            });
        }
        
        // Modal functionality
        const registrationModal = document.getElementById('registrationModal');
        const modalCloseBtn = document.getElementById('modalCloseBtn');
        const quickRegisterForm = document.getElementById('quickRegisterForm');
        const registerSubmitBtn = document.getElementById('registerSubmitBtn');
        
        if (modalCloseBtn) {
            modalCloseBtn.addEventListener('click', function() {
                registrationModal.classList.add('hidden');
            });
        }
        
        if (registerSubmitBtn) {
            registerSubmitBtn.addEventListener('click', function() {
                quickRegisterForm.submit();
            });
        }
        
        // Show registration modal for guest users (if needed)
        @if(!Auth::check() && isset($showRegistrationModal) && $showRegistrationModal)
            setTimeout(() => {
                registrationModal.classList.remove('hidden');
            }, 500);
        @endif
        
        // Fill stars for star ratings
        document.querySelectorAll('[data-lucide="star"]').forEach(function(star) {
            if (star.classList.contains('fill-current')) {
                star.setAttribute('fill', 'currentColor');
            }
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
            showToast('{{ session('success') }}', 'success');
        @endif
        
        // Show error toast if redirected with error message
        @if(session('error'))
            showToast('{{ session('error') }}', 'error');
        @endif
    });
</script>
@endsection
