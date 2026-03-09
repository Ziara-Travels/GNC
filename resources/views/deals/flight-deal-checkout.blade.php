@extends('layouts.main')

@section('content')
    <!-- Include required CSS and JS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

    <style>
        .checkout-container {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
        }
        
        .section-title {
            position: relative;
            padding-bottom: 10px;
            margin-bottom: 20px;
            font-weight: 600;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background: #8f95d3;
            border-radius: 3px;
        }
        
        .booking-summary {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }
        
        .price-summary {
            background-color: #f8f9ff;
            border-radius: 10px;
            border-left: 4px solid #8f95d3;
        }
        
        .checkout-form label {
            font-weight: 500;
            color: #495057;
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
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #e9ecef;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-bottom: 8px;
        }
        
        .step.active .step-number {
            background-color: #8f95d3;
            color: white;
        }
        
        .step-title {
            font-size: 14px;
            color: #6c757d;
            text-align: center;
            max-width: 120px;
        }
        
        .step.active .step-title {
            color: #495057;
            font-weight: 600;
        }
        
        .step-line {
            position: absolute;
            top: 15px;
            height: 2px;
            width: 100%;
            background-color: #e9ecef;
            z-index: -1;
        }
        
        .payment-method {
            border: 2px solid #dee2e6;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .payment-method:hover {
            border-color: #8f95d3;
            background-color: #f8f9ff;
        }
        
        .payment-method.active {
            border-color: #8f95d3;
            background-color: #f8f9ff;
        }
        
        .payment-method img {
            height: 30px;
            object-fit: contain;
        }
        
        .card-element {
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            background-color: white;
        }
        
        .secure-badge {
            display: flex;
            align-items: center;
            font-size: 14px;
            color: #6c757d;
        }
        
        .secure-badge i {
            color: #28a745;
            margin-right: 5px;
        }
    </style>

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
                    
                    <form method="POST" action="{{ route('process-flight-payment') }}" class="checkout-form">
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
                                    I agree to the <a href="#" style="color: #8f95d3;">Terms and Conditions</a> and <a href="#" style="color: #8f95d3;">Privacy Policy</a>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Form Buttons -->
                        <div class="d-flex justify-content-between mt-4">
                            <a href="javascript:history.back()" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Passenger Details
                            </a>
                            <button type="submit" class="btn px-5" style="background-color: #8f95d3; color: white;">
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
                                <span class="badge" style="background-color: rgba(143, 149, 211, 0.2); color: #8f95d3;">Outbound</span>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <div class="fw-bold">{{ $booking->departure_time ?? '08:00' }}</div>
                                    <div class="text-muted">{{ $booking->origin_code ?? 'NBO' }}</div>
                                </div>
                                
                                <div class="text-center px-2">
                                    <div class="text-muted small">{{ $booking->flight_duration ?? '1h 30m' }}</div>
                                    <div style="height: 1px; background-color: #8f95d3; position: relative; min-width: 100px;">
                                        <i class="fas fa-plane" style="color: #8f95d3; position: absolute; top: -10px; left: 50%; transform: translateX(-50%);"></i>
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
                                <span class="badge" style="background-color: rgba(143, 149, 211, 0.2); color: #8f95d3;">Return</span>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <div class="fw-bold">{{ $booking->return_departure_time ?? '18:00' }}</div>
                                    <div class="text-muted">{{ $booking->destination_code ?? 'MBA' }}</div>
                                </div>
                                
                                <div class="text-center px-2">
                                    <div class="text-muted small">{{ $booking->return_flight_duration ?? '1h 30m' }}</div>
                                    <div style="height: 1px; background-color: #8f95d3; position: relative; min-width: 100px;">
                                        <i class="fas fa-plane" style="color: #8f95d3; position: absolute; top: -10px; left: 50%; transform: translateX(-50%) rotate(180deg);"></i>
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
                            <p class="card-text small mb-1"><i class="fas fa-envelope me-2" style="color: #8f95d3;"></i>{{ $booking->contact_email ?? 'john.doe@example.com' }}</p>
                            <p class="card-text small mb-0"><i class="fas fa-phone me-2" style="color: #8f95d3;"></i>{{ $booking->contact_phone ?? '+254 712 345 678' }}</p>
                        </div>
                    </div>
                    
                    <!-- Need Help? -->
                    <div class="mt-4 text-center">
                        <h5 class="mb-3">Need Help?</h5>
                        <p class="mb-2"><i class="fas fa-headset me-2" style="color: #8f95d3;"></i>Customer Support</p>
                        <p class="mb-0"><a href="tel:+254712345678" style="color: #8f95d3; text-decoration: none;">+254 712 345 678</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
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
