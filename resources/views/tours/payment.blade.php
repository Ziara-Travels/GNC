
@extends('layouts.main')

@section('title', 'Payment - ' . $booking->tour->name)

@section('content')
<div class="container ziara-pay-shell">
    <div class="row">
        <!-- Booking Summary -->
        <div class="col-lg-4">
            <div class="card ziara-pay-card">
                <div class="card-header">
                    <h5 class="mb-0">Booking Summary</h5>
                </div>
                <div class="card-body">
                    <h6 class="ziara-pay-tour">{{ $booking->tour->name }}</h6>
                    <p class="text-muted small">
                        <i class="fas fa-map-marker-alt me-1"></i>
                        {{ $booking->tour->city }}, {{ $booking->tour->country }}
                    </p>
                    
                    <hr>
                    
                    <div class="booking-details">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Booking Reference:</span>
                            <strong class="ziara-pay-ref">{{ $booking->booking_reference }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tour Date:</span>
                            <span>{{ $booking->activity_date->format('M d, Y') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Participants:</span>
                            <span>{{ $booking->traveler_count }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Price per person:</span>
                            <span>{{ $booking->currency }} {{ number_format($booking->amount, 2) }}</span>
                        </div>
                        
                        <hr>
                        
                        <div class="d-flex justify-content-between">
                            <strong>Total Amount:</strong>
                            <strong class="ziara-pay-total">{{ $booking->currency }} {{ number_format($booking->total_amount, 2) }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Form -->
        <div class="col-lg-8">
            <div class="card ziara-pay-card">
                <div class="card-body">
                    <h3 class="card-title ziara-pay-title mb-4">Complete Your Payment</h3>
                    
                    <form action="{{ route('tour.payment.process') }}" method="POST">
                        @csrf
                        <input type="hidden" name="booking_reference" value="{{ $booking->booking_reference }}">
                        
                        <!-- Payment Method Selection -->
                        <div class="mb-4">
                            <label class="form-label">Select Payment Method</label>
                            
                            <!-- M-Pesa -->
                            <div class="form-check payment-option mb-3">
                                <input class="form-check-input" type="radio" name="payment_method" value="mpesa" id="mpesa">
                                <label class="form-check-label w-100" for="mpesa">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>M-Pesa</strong>
                                            <p class="text-muted small mb-0">Pay with your M-Pesa mobile money</p>
                                        </div>
                                        <img src="/images/mpesa-logo.png" alt="M-Pesa" style="height: 30px;">
                                    </div>
                                </label>
                            </div>
                            
                            <!-- Credit Card -->
                            <div class="form-check payment-option mb-3">
                                <input class="form-check-input" type="radio" name="payment_method" value="card" id="card">
                                <label class="form-check-label w-100" for="card">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>Credit/Debit Card</strong>
                                            <p class="text-muted small mb-0">Visa, Mastercard, American Express</p>
                                        </div>
                                        <div>
                                            <i class="fab fa-cc-visa ziara-pay-brand me-1" style="font-size: 1.5rem;"></i>
                                            <i class="fab fa-cc-mastercard ziara-pay-card-brand me-1" style="font-size: 1.5rem;"></i>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            
                            <!-- Bank Transfer -->
                            <div class="form-check payment-option">
                                <input class="form-check-input" type="radio" name="payment_method" value="bank_transfer" id="bank_transfer">
                                <label class="form-check-label w-100" for="bank_transfer">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>Bank Transfer</strong>
                                            <p class="text-muted small mb-0">Direct bank transfer</p>
                                        </div>
                                        <i class="fas fa-university ziara-pay-brand" style="font-size: 1.5rem;"></i>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- M-Pesa Details -->
                        <div class="payment-details" id="mpesaDetails" style="display: none;">
                            <div class="mb-3">
                                <label class="form-label">M-Pesa Phone Number</label>
                                <input type="tel" class="form-control" name="phone_number" placeholder="e.g., 254712345678">
                                <small class="text-muted">You will receive an M-Pesa prompt to complete payment</small>
                            </div>
                        </div>

                        <!-- Card Details -->
                        <div class="payment-details" id="cardDetails" style="display: none;">
                            <div class="row mb-3">
                                <div class="col-md-8">
                                    <label class="form-label">Card Number</label>
                                    <input type="text" class="form-control" name="card_number" placeholder="1234 5678 9012 3456">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">CVV</label>
                                    <input type="text" class="form-control" name="cvv" placeholder="123">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Expiry Month</label>
                                    <select class="form-select" name="expiry_month">
                                        @for($i = 1; $i <= 12; $i++)
                                            <option value="{{ sprintf('%02d', $i) }}">{{ sprintf('%02d', $i) }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Expiry Year</label>
                                    <select class="form-select" name="expiry_year">
                                        @for($i = date('Y'); $i <= date('Y') + 10; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Bank Transfer Details -->
                        <div class="payment-details" id="bankDetails" style="display: none;">
                            <div class="alert ziara-pay-alert">
                                <h6>Bank Transfer Instructions</h6>
                                <p class="mb-2"><strong>Bank:</strong> Example Bank</p>
                                <p class="mb-2"><strong>Account Name:</strong> Ziara Travels</p>
                                <p class="mb-2"><strong>Account Number:</strong> 1234567890</p>
                                <p class="mb-0"><strong>Reference:</strong> {{ $booking->booking_reference }}</p>
                            </div>
                            <p class="text-muted">Please use the booking reference when making the transfer and send proof of payment to our support team.</p>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn ziara-pay-cta btn-lg" id="paymentButton" disabled>
                                <i class="fas fa-lock me-2"></i>
                                Complete Payment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .ziara-pay-shell { padding-top: 40px; padding-bottom: 56px; }
    .ziara-pay-card {
        background: #fff;
        border: 1px solid #eadfca;
        border-radius: 16px;
        box-shadow: 0 10px 34px rgba(74,29,19,.10);
    }
    .ziara-pay-card .card-header {
        background: linear-gradient(110deg, #4a1d13 0%, #6b2c1f 100%);
        border-bottom: 3px solid #c9a227;
        border-radius: 15px 15px 0 0;
        padding: 16px 24px;
    }
    .ziara-pay-card .card-header h5 {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-weight: 700;
        color: #fff;
        font-size: 1.25rem;
    }
    .ziara-pay-card .card-body { padding: 24px; }
    .ziara-pay-title,
    .ziara-pay-tour {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-weight: 700;
        color: #4a1d13;
    }
    .ziara-pay-tour { font-size: 1.2rem; }
    .ziara-pay-card .text-muted { color: #7a6a60; }
    .ziara-pay-card hr { border-color: #eadfca; opacity: 1; }
    .ziara-pay-total { color: #6b2d15; font-weight: 700; }
    .ziara-pay-ref { color: #4a1d13; font-weight: 700; }
    .ziara-pay-brand { color: #7b2a1b; }
    .ziara-pay-card-brand { color: #c9a227; }
    .ziara-pay-cta {
        background: #6b2c1f;
        border: 1px solid #6b2c1f;
        color: #fff;
        font-weight: 600;
        letter-spacing: .3px;
        border-radius: 10px;
    }
    .ziara-pay-cta:hover,
    .ziara-pay-cta:focus {
        background: #4a1d13;
        border-color: #4a1d13;
        color: #fff;
    }
    .ziara-pay-cta:disabled { background: #a8968a; border-color: #a8968a; color: #fff; }
    .ziara-pay-alert {
        background: #fdf6df;
        border: 1px solid rgba(201,162,39,.45);
        color: #3a322c;
        border-radius: 12px;
    }
    .ziara-pay-alert h6 { color: #6b1d15; font-weight: 700; }
    .payment-option {
        border: 2px solid #eadfca;
        border-radius: 10px;
        padding: 15px;
        transition: all 0.3s ease;
    }

    .payment-option:hover {
        border-color: #c9a227;
        background-color: #fdf8eb;
    }

    .payment-option input[type="radio"]:checked + label {
        border-color: #c9a227;
        background-color: #fdf6df;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
    const paymentButton = document.getElementById('paymentButton');
    const paymentDetails = document.querySelectorAll('.payment-details');
    
    paymentMethods.forEach(method => {
        method.addEventListener('change', function() {
            // Hide all payment details
            paymentDetails.forEach(detail => {
                detail.style.display = 'none';
            });
            
            // Show selected payment details
            const selectedDetails = document.getElementById(this.value + 'Details');
            if (selectedDetails) {
                selectedDetails.style.display = 'block';
            }
            
            // Enable payment button
            paymentButton.disabled = false;
            
            // Update button text based on payment method
            const buttonTexts = {
                'mpesa': 'Pay with M-Pesa',
                'card': 'Pay with Card', 
                'bank_transfer': 'Confirm Bank Transfer'
            };
            
            paymentButton.innerHTML = `<i class="fas fa-lock me-2"></i>${buttonTexts[this.value]}`;
        });
    });
});
</script>
@endsection