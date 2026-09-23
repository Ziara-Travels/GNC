
@extends('layouts.main')

@section('title', 'Payment - ' . $booking->tour->name)

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Booking Summary -->
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Booking Summary</h5>
                </div>
                <div class="card-body">
                    <h6>{{ $booking->tour->name }}</h6>
                    <p class="text-muted small">
                        <i class="fas fa-map-marker-alt me-1"></i>
                        {{ $booking->tour->city }}, {{ $booking->tour->country }}
                    </p>
                    
                    <hr>
                    
                    <div class="booking-details">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Booking Reference:</span>
                            <strong>{{ $booking->booking_reference }}</strong>
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
                            <strong class="text-success">{{ $booking->currency }} {{ number_format($booking->total_amount, 2) }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Form -->
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="card-title mb-4">Complete Your Payment</h3>
                    
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
                                            <i class="fab fa-cc-visa text-primary me-1" style="font-size: 1.5rem;"></i>
                                            <i class="fab fa-cc-mastercard text-warning me-1" style="font-size: 1.5rem;"></i>
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
                                        <i class="fas fa-university text-primary" style="font-size: 1.5rem;"></i>
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
                            <div class="alert alert-info">
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
                            <button type="submit" class="btn btn-success btn-lg" id="paymentButton" disabled>
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
.payment-option {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 15px;
    transition: all 0.3s ease;
}

.payment-option:hover {
    border-color: #28a745;
    background-color: #f8f9fa;
}

.payment-option input[type="radio"]:checked + label {
    border-color: #28a745;
    background-color: #e6ffed;
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