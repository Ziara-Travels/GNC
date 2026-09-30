@extends('layouts.main')

@section('content')
@include('partials.ziara-page-hero', [
    'kicker' => 'Secure Checkout',
    'title'  => 'Complete Your <em>Payment</em>',
    'intro'  => 'Encrypted end-to-end. Choose how you would like to settle your fare.',
    'crumbs' => [
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Flight Deals', 'url' => route('deal.flight.results')],
        ['label' => 'Payment'],
    ],
])

    <!-- Include required CSS and JS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

    <style>
        .zfc-checkout {
            background:
                radial-gradient(900px 420px at 88% -8%, rgba(201,162,39,.14), transparent 62%),
                linear-gradient(180deg, var(--gold-pale) 0%, #fffdf8 100%);
            padding: 56px 0 88px;
        }
        .checkout-container {
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

        .booking-summary {
            background-color: #fff;
            border: 1px solid var(--gold-border);
            border-radius: 16px;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
        }

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

        .checkout-form label {
            font-weight: 600;
            font-size: 12.5px;
            letter-spacing: .4px;
            text-transform: uppercase;
            color: var(--muted);
        }

        .zfc-checkout .form-control,
        .zfc-checkout .form-select {
            border-color: var(--gold-border);
            border-radius: 10px;
            padding: 12px 14px;
            font-size: 14.5px;
            color: var(--ink);
            background-color: #fff;
        }

        .zfc-checkout .form-control:focus,
        .zfc-checkout .form-select:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 3px rgba(201,162,39,.18);
        }

        .zfc-checkout .input-group-text {
            background: var(--gold-pale);
            border-color: var(--gold-border);
            color: var(--maroon);
        }

        .zfc-checkout .form-check-input { border-color: var(--gold-border); }
        .zfc-checkout .form-check-input:checked {
            background-color: var(--maroon);
            border-color: var(--maroon);
        }
        .zfc-checkout .form-check-input:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 3px var(--gold-border);
        }
        .zfc-checkout .form-check-label { color: var(--ink); }

        .zfc-checkout .card {
            border: 1px solid var(--line) !important;
            border-radius: 16px;
            box-shadow: var(--shadow-sm) !important;
        }

        .zfc-checkout .card-body { padding: 22px; }
        .zfc-checkout .card-title {
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

        .payment-method {
            border: 1.5px solid var(--gold-border);
            border-radius: 14px;
            padding: 15px 18px;
            margin-bottom: 20px;
            cursor: pointer;
            background: #fff;
            transition: all .3s ease;
        }

        .payment-method:hover {
            border-color: var(--gold);
            background-color: var(--gold-pale);
        }

        .payment-method.active {
            border-color: var(--maroon);
            background-color: var(--gold-pale);
            box-shadow: 0 0 0 1px var(--maroon) inset;
        }

        .payment-method img {
            height: 30px;
            object-fit: contain;
        }

        .card-element {
            padding: 10px;
            border: 1px solid var(--gold-border);
            border-radius: 10px;
            background-color: #fff;
        }

        .secure-badge {
            display: flex;
            align-items: center;
            font-size: 13px;
            color: var(--muted);
        }

        .secure-badge i {
            color: var(--gold-dim);
            margin-right: 5px;
        }

        .zfc-checkout .text-muted { color: var(--muted) !important; }
        .zfc-checkout .text-danger { color: #b3261e !important; }
        .zfc-checkout h4.h5 {
            font-family: var(--font-display);
            font-weight: 700;
            color: var(--maroon-deep);
        }

        .zfc-checkout .btn-outline-secondary {
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

        .zfc-checkout .btn-outline-secondary:hover {
            background: var(--gold-pale);
            border-color: var(--gold);
            color: var(--maroon-deep);
        }
    </style>

    <div class="zfc-checkout">
    <div class="container py-5">
        <!-- Booking Progress Steps -->
        <div class="booking-steps position-relative">
            <div class="step-line"></div>
            <div class="step">
                <div class="step-number">1</div>
                <div class="step-title">Flight Details</div>
            </div>
            <div class="step">
                <div class="step-number">2</div>
                <div class="step-title">Passenger Information</div>
            </div>
            <div class="step active">
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
            <h1 class="h2 fw-bold">Secure Checkout</h1>
            <p class="text-muted">Complete your flight booking by providing payment details</p>
        </div>

        <div class="row g-4">
            <!-- Payment Information Section -->
            <div class="col-md-8">
                <div class="checkout-container">
                    <h3 class="section-title">Payment Method</h3>

                    <form method="POST" action="{{ route('deal.flight.payment', $booking->booking_reference ?? '') }}" class="checkout-form">
                        @csrf
                        <input type="hidden" name="booking_data" value="{{ json_encode($booking ?? []) }}">

                        <!-- Payment Method Selection -->
                        <div class="mb-4">
                            <div class="payment-method active" onclick="selectPaymentMethod('card')">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="payment_card" value="card" checked>
                                    <label class="form-check-label d-flex justify-content-between w-100" for="payment_card">
                                        <div class="d-flex align-items-center">
                                            <span class="me-3">Credit/Debit Card</span>
                                            <span class="secure-badge"><i class="fas fa-lock"></i> Secure</span>
                                        </div>
                                        <div>
                                            <img src="{{ asset('images/payment/visa.png') }}" alt="Visa" class="me-2" onerror="this.src='https://via.placeholder.com/40x20?text=Visa'">
                                            <img src="{{ asset('images/payment/mastercard.png') }}" alt="Mastercard" class="me-2" onerror="this.src='https://via.placeholder.com/40x20?text=Mastercard'">
                                            <img src="{{ asset('images/payment/amex.png') }}" alt="American Express" onerror="this.src='https://via.placeholder.com/40x20?text=Amex'">
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div class="payment-method" onclick="selectPaymentMethod('mpesa')">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="payment_mpesa" value="mpesa">
                                    <label class="form-check-label d-flex justify-content-between w-100" for="payment_mpesa">
                                        <div class="d-flex align-items-center">
                                            <span class="me-3">M-PESA</span>
                                            <span class="secure-badge"><i class="fas fa-lock"></i> Secure</span>
                                        </div>
                                        <div>
                                            <img src="{{ asset('images/payment/mpesa.png') }}" alt="M-PESA" onerror="this.src='https://via.placeholder.com/60x20?text=MPESA'">
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div class="payment-method" onclick="selectPaymentMethod('paypal')">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="payment_paypal" value="paypal">
                                    <label class="form-check-label d-flex justify-content-between w-100" for="payment_paypal">
                                        <div class="d-flex align-items-center">
                                            <span class="me-3">PayPal</span>
                                            <span class="secure-badge"><i class="fas fa-lock"></i> Secure</span>
                                        </div>
                                        <div>
                                            <img src="{{ asset('images/payment/paypal.png') }}" alt="PayPal" onerror="this.src='https://via.placeholder.com/60x20?text=PayPal'">
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Credit Card Form -->
                        <div id="card-payment-form">
                            <h4 class="h5 mb-3">Card Details</h4>

                            <div class="row g-3 mb-4">
                                <div class="col-12">
                                    <label for="card_name" class="form-label">Name on Card</label>
                                    <input type="text" class="form-control" id="card_name" name="card_name" required>
                                </div>
                                <div class="col-12">
                                    <label for="card_number" class="form-label">Card Number</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="card_number" name="card_number" placeholder="1234 5678 9012 3456" required>
                                        <span class="input-group-text">
                                            <i class="fas fa-credit-card"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="expiry_date" class="form-label">Expiration Date</label>
                                    <input type="text" class="form-control" id="expiry_date" name="expiry_date" placeholder="MM/YY" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="cvv" class="form-label">CVV</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="cvv" name="cvv" placeholder="123" required>
                                        <span class="input-group-text" data-bs-toggle="tooltip" data-bs-placement="right" title="3 or 4 digit security code on your card">
                                            <i class="fas fa-question-circle"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- M-PESA Form -->
                        <div id="mpesa-payment-form" class="d-none">
                            <h4 class="h5 mb-3">M-PESA Details</h4>

                            <div class="mb-4">
                                <label for="mpesa_phone" class="form-label">M-PESA Phone Number</label>
                                <div class="input-group">
                                    <span class="input-group-text">+254</span>
                                    <input type="text" class="form-control" id="mpesa_phone" name="mpesa_phone" placeholder="712345678">
                                </div>
                                <small class="text-muted">You will receive an M-PESA payment prompt on this number</small>
                            </div>
                        </div>

                        <!-- PayPal Form -->
                        <div id="paypal-payment-form" class="d-none">
                            <p class="mb-4">You will be redirected to PayPal to complete your payment securely.</p>
                        </div>

                        <!-- Billing Address -->
                        <h4 class="h5 mb-3 mt-4">Billing Address</h4>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="billing_country" class="form-label">Country</label>
                                <select class="form-select" id="billing_country" name="billing[country]" required>
                                    <option value="">Select Country</option>
                                    <option value="KE" selected>Kenya</option>
                                    <option value="US">United States</option>
                                    <option value="GB">United Kingdom</option>
                                    <option value="CA">Canada</option>
                                    <option value="AU">Australia</option>
                                    <option value="DE">Germany</option>
                                </select>
                            </div>
                            <div class="col-md-6"></div>
                            <div class="col-md-6">
                                <label for="billing_first_name" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="billing_first_name" name="billing[first_name]" required>
                            </div>
                            <div class="col-md-6">
                                <label for="billing_last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="billing_last_name" name="billing[last_name]" required>
                            </div>
                            <div class="col-12">
                                <label for="billing_address" class="form-label">Street Address</label>
                                <input type="text" class="form-control" id="billing_address" name="billing[address]" required>
                            </div>
                            <div class="col-md-6">
                                <label for="billing_city" class="form-label">City</label>
                                <input type="text" class="form-control" id="billing_city" name="billing[city]" required>
                            </div>
                            <div class="col-md-6">
                                <label for="billing_postal_code" class="form-label">Postal Code</label>
                                <input type="text" class="form-control" id="billing_postal_code" name="billing[postal_code]" required>
                            </div>
                        </div>

                        <!-- Terms and Privacy -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="terms_agree" name="terms_agree" required>
                                <label class="form-check-label" for="terms_agree">
                                    I agree to the <a href="#" style="color: var(--gold-dim);">Terms and Conditions</a> and <a href="#" style="color: var(--gold-dim);">Privacy Policy</a>
                                </label>
                            </div>
                        </div>

                        <!-- Form Buttons -->
                        <div class="d-flex justify-content-between mt-4">
                            <a href="javascript:history.back()" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Passenger Details
                            </a>
                            <button type="submit" class="btn px-5" style="background-color: var(--maroon); color: var(--gold-bright); border: 1px solid var(--maroon); border-radius: 50px; padding: 13px 34px; font-size: 12px; font-weight: 700; letter-spacing: 1.2px; text-transform: uppercase;">
                                Complete Payment<i class="fas fa-check ms-2"></i>
                            </button>
                        </div>
                    </form>

                    <!-- Security Badges -->
                    <div class="d-flex flex-wrap justify-content-center mt-4">
                        <img src="{{ asset('images/payment/security-badge-1.png') }}" alt="Secure Payment" class="m-2" style="height: 40px;" onerror="this.src='https://via.placeholder.com/80x40?text=Secure+SSL'">
                        <img src="{{ asset('images/payment/security-badge-2.png') }}" alt="PCI Compliant" class="m-2" style="height: 40px;" onerror="this.src='https://via.placeholder.com/80x40?text=PCI+Compliant'">
                        <img src="{{ asset('images/payment/security-badge-3.png') }}" alt="Verified" class="m-2" style="height: 40px;" onerror="this.src='https://via.placeholder.com/80x40?text=Verified'">
                    </div>
                </div>
            </div>

            <!-- Booking Summary Section -->
            <div class="col-md-4">
                <div class="checkout-container sticky-top" style="top: 100px;">
                    <h3 class="section-title">Booking Summary</h3>

                    <!-- Flight Summary -->
                    <div class="booking-summary p-4 mb-4">
                        <h5 class="mb-3">Flight Details</h5>

                        <!-- Outbound Flight -->
                        <div class="border-bottom pb-3 mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">{{ date('D, M j', strtotime($booking->departure_date ?? 'now')) }}</span>
                                <span class="badge" style="background-color: var(--gold-pale); color: var(--maroon); border: 1px solid var(--gold-border);">Outbound</span>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <div class="fw-bold">{{ $booking->departure_time ?? '08:00' }}</div>
                                    <div class="text-muted">{{ $booking->origin_code ?? 'NBO' }}</div>
                                </div>

                                <div class="text-center px-2">
                                    <div class="text-muted small">{{ $booking->flight_duration ?? '1h 30m' }}</div>
                                    <div style="height: 1px; background-color: var(--gold); position: relative; min-width: 100px;">
                                        <i class="fas fa-plane" style="color: var(--gold-dim); position: absolute; top: -10px; left: 50%; transform: translateX(-50%);"></i>
                                    </div>
                                    <div class="text-muted small">Direct</div>
                                </div>

                                <div class="text-end">
                                    <div class="fw-bold">{{ $booking->arrival_time ?? '09:30' }}</div>
                                    <div class="text-muted">{{ $booking->destination_code ?? 'MBA' }}</div>
                                </div>
                            </div>

                            <div class="text-muted small">
                                {{ $booking->airline ?? 'Kenya Airways' }} • {{ $booking->flight_number ?? 'KQ 400' }}
                            </div>
                        </div>

                        <!-- Return Flight if round trip -->
                        @if(isset($booking->return_date))
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">{{ date('D, M j', strtotime($booking->return_date ?? 'now +7 days')) }}</span>
                                <span class="badge" style="background-color: var(--gold-pale); color: var(--maroon); border: 1px solid var(--gold-border);">Return</span>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <div class="fw-bold">{{ $booking->return_departure_time ?? '18:00' }}</div>
                                    <div class="text-muted">{{ $booking->destination_code ?? 'MBA' }}</div>
                                </div>

                                <div class="text-center px-2">
                                    <div class="text-muted small">{{ $booking->return_flight_duration ?? '1h 30m' }}</div>
                                    <div style="height: 1px; background-color: var(--gold); position: relative; min-width: 100px;">
                                        <i class="fas fa-plane" style="color: var(--gold-dim); position: absolute; top: -10px; left: 50%; transform: translateX(-50%) rotate(180deg);"></i>
                                    </div>
                                    <div class="text-muted small">Direct</div>
                                </div>

                                <div class="text-end">
                                    <div class="fw-bold">{{ $booking->return_arrival_time ?? '19:30' }}</div>
                                    <div class="text-muted">{{ $booking->origin_code ?? 'NBO' }}</div>
                                </div>
                            </div>

                            <div class="text-muted small">
                                {{ $booking->return_airline ?? 'Kenya Airways' }} • {{ $booking->return_flight_number ?? 'KQ 401' }}
                            </div>
                        </div>
                        @endif

                        <!-- Passengers -->
                        <div class="d-flex justify-content-between mb-2">
                            <span>Passengers</span>
                            <span>{{ $booking->total_passengers ?? 1 }} {{ ($booking->total_passengers ?? 1) > 1 ? 'Passengers' : 'Passenger' }}</span>
                        </div>

                        <!-- Cabin Class -->
                        <div class="d-flex justify-content-between">
                            <span>Class</span>
                            <span>{{ $booking->cabin_class ?? 'Economy' }}</span>
                        </div>
                    </div>

                    <!-- Price Summary -->
                    <div class="price-summary p-4 mb-4">
                        <h5 class="mb-3">Price Details</h5>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span>Fare ({{ $booking->total_passengers ?? 1 }} {{ ($booking->total_passengers ?? 1) > 1 ? 'passengers' : 'passenger' }})</span>
                            <span>{{ $booking->currency ?? 'KES' }} {{ number_format($booking->base_fare ?? 25000) }}</span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span>Taxes & Fees</span>
                            <span>{{ $booking->currency ?? 'KES' }} {{ number_format($booking->taxes ?? 5000) }}</span>
                        </div>

                        @if(isset($booking->discount) && $booking->discount > 0)
                        <div class="d-flex justify-content-between align-items-center mb-3 text-success">
                            <span>Deal Discount</span>
                            <span>-{{ $booking->currency ?? 'KES' }} {{ number_format($booking->discount ?? 2000) }}</span>
                        </div>
                        @endif

                        @if(isset($booking->baggage_fee) && $booking->baggage_fee > 0)
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span>Extra Baggage Fee</span>
                            <span>{{ $booking->currency ?? 'KES' }} {{ number_format($booking->baggage_fee ?? 1000) }}</span>
                        </div>
                        @endif

                        @if(isset($booking->seat_selection_fee) && $booking->seat_selection_fee > 0)
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span>Seat Selection Fee</span>
                            <span>{{ $booking->currency ?? 'KES' }} {{ number_format($booking->seat_selection_fee ?? 500) }}</span>
                        </div>
                        @endif

                        <hr class="my-3">

                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold">Total Price</span>
                            <span class="fw-bold fs-4">{{ $booking->currency ?? 'KES' }} {{ number_format($booking->total_amount ?? 29500) }}</span>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Contact Information</h5>
                            <p class="card-text small mb-1"><i class="fas fa-envelope me-2" style="color: var(--gold);"></i>{{ $booking->contact_email ?? 'john.doe@example.com' }}</p>
                            <p class="card-text small mb-0"><i class="fas fa-phone me-2" style="color: var(--gold);"></i>{{ $booking->contact_phone ?? '+254 712 345 678' }}</p>
                        </div>
                    </div>

                    <!-- Need Help? -->
                    <div class="mt-4 text-center">
                        <h5 class="mb-3">Need Help?</h5>
                        <p class="mb-2"><i class="fas fa-headset me-2" style="color: var(--gold);"></i>Customer Support</p>
                        <p class="mb-0"><a href="tel:+254712345678" style="color: var(--gold-dim); text-decoration: none;">+254 712 345 678</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
        
        // Format card inputs
        document.getElementById('card_number').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 0) {
                value = value.match(new RegExp('.{1,4}', 'g')).join(' ');
            }
            e.target.value = value.substring(0, 19); // Limit to 16 digits + 3 spaces
        });
        
        document.getElementById('expiry_date').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            e.target.value = value.substring(0, 5); // MM/YY format
        });
        
        document.getElementById('cvv').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            e.target.value = value.substring(0, 4); // CVV is usually 3-4 digits
        });
        
        document.getElementById('mpesa_phone').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            e.target.value = value.substring(0, 9); // 9 digits for Kenyan mobile numbers after the +254
        });
    });
    
    function selectPaymentMethod(method) {
        // Update radio button
        document.getElementById('payment_' + method).checked = true;
        
        // Update active class
        document.querySelectorAll('.payment-method').forEach(el => {
            el.classList.remove('active');
        });
        document.getElementById('payment_' + method).closest('.payment-method').classList.add('active');
        
        // Show/hide relevant form
        document.getElementById('card-payment-form').classList.add('d-none');
        document.getElementById('mpesa-payment-form').classList.add('d-none');
        document.getElementById('paypal-payment-form').classList.add('d-none');
        
        document.getElementById(method + '-payment-form').classList.remove('d-none');
    }
</script>
@endsection
