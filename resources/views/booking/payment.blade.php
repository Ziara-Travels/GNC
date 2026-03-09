@extends('layouts.main')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Payment</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('booking.process-payment') }}">
                        @csrf
                        <input type="hidden" name="booking_type" value="{{ $type }}">
                        <input type="hidden" name="amount" value="{{ $finalPrice }}">
                        <input type="hidden" name="currency" value="{{ $bookingData['currency'] }}">
                        
                        <div class="mb-4">
                            <h5>Booking Summary</h5>
                            @if($type == 'flight')
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <p class="mb-1">
                                            <strong>{{ $bookingData['originCity'] }} to {{ $bookingData['destinationCity'] }}</strong>
                                        </p>
                                        <p class="text-muted">{{ $bookingData['departureDate'] }}, {{ $bookingData['cabin'] }}</p>
                                    </div>
                                    <div class="text-end">
                                        <p class="mb-1 fw-bold">{{ $bookingData['currency'] }} {{ number_format($bookingData['price'], 2) }}</p>
                                    </div>
                                </div>
                            @else
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <p class="mb-1">
                                            <strong>{{ $bookingData['hotelName'] }}</strong>
                                        </p>
                                        <p class="text-muted">{{ $bookingData['city'] }}, {{ $bookingData['country'] }}</p>
                                    </div>
                                    <div class="text-end">
                                        <p class="mb-1 fw-bold">{{ $bookingData['currency'] }} {{ number_format($bookingData['price'], 2) }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        <div class="mb-4">
                            <p><strong>Passengers:</strong> {{ count($passengerDetails) }}</p>
                            <p><strong>Contact:</strong> {{ $contactDetails['email'] }}, {{ $contactDetails['phone'] }}</p>
                        </div>

                        @if($discountInfo)
                        <div class="mb-4">
                            <div class="alert alert-success">
                                <h6 class="mb-1">Discount Applied</h6>
                                <p class="mb-0">{{ $discountInfo['description'] }}: {{ $bookingData['currency'] }} {{ number_format($discountInfo['amount'], 2) }} off</p>
                                
                                @if($discountInfo['type'] === 'Loyalty')
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" name="apply_loyalty_discount" id="apply_loyalty_discount" value="1" checked>
                                        <label class="form-check-label" for="apply_loyalty_discount">
                                            Use {{ $discountInfo['points_required'] }} points for this discount
                                        </label>
                                        <input type="hidden" name="points_to_redeem" value="{{ $discountInfo['points_required'] }}">
                                        <input type="hidden" name="discount_type" value="{{ $discountInfo['type'] }}">
                                    </div>
                                @else
                                    <input type="hidden" name="discount_type" value="{{ $discountInfo['type'] }}">
                                @endif
                            </div>
                        </div>
                        @endif

                        <div class="mb-4">
                            <div class="card">
                                <div class="card-body bg-light">
                                    <div class="d-flex justify-content-between mb-2">
                                        <div>Subtotal:</div>
                                        <div>{{ $bookingData['currency'] }} {{ number_format($originalPrice, 2) }}</div>
                                    </div>
                                    @if($discountInfo)
                                    <div class="d-flex justify-content-between mb-2">
                                        <div>Discount:</div>
                                        <div>-{{ $bookingData['currency'] }} {{ number_format($discountInfo['amount'], 2) }}</div>
                                    </div>
                                    @endif
                                    <div class="d-flex justify-content-between fw-bold">
                                        <div>Total:</div>
                                        <div>{{ $bookingData['currency'] }} {{ number_format($finalPrice, 2) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5>Payment Method</h5>
                            <div class="mb-3">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="payment_method" id="method_mpesa" value="mpesa" checked>
                                    <label class="form-check-label" for="method_mpesa">M-Pesa</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="payment_method" id="method_card" value="card">
                                    <label class="form-check-label" for="method_card">Credit/Debit Card</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="payment_method" id="method_bank" value="bank">
                                    <label class="form-check-label" for="method_bank">Bank Transfer</label>
                                </div>
                            </div>
                            
                            <div id="mpesa_fields" class="payment-fields">
                                <div class="mb-3">
                                    <label for="mpesa_phone" class="form-label">M-Pesa Phone Number</label>
                                    <input type="text" class="form-control" name="mpesa_phone" id="mpesa_phone" placeholder="e.g. 254712345678">
                                    <div class="form-text">Enter your M-Pesa registered phone number</div>
                                </div>
                            </div>
                            
                            <div id="card_fields" class="payment-fields d-none">
                                <div class="mb-3">
                                    <label for="card_number" class="form-label">Card Number</label>
                                    <input type="text" class="form-control" name="card_number" id="card_number" placeholder="1234 5678 9012 3456">
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="card_expiry" class="form-label">Expiry Date</label>
                                        <input type="text" class="form-control" name="card_expiry" id="card_expiry" placeholder="MM/YY">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="card_cvv" class="form-label">CVV</label>
                                        <input type="text" class="form-control" name="card_cvv" id="card_cvv" placeholder="123">
                                    </div>
                                </div>
                            </div>
                            
                            <div id="bank_fields" class="payment-fields d-none">
                                <div class="mb-3">
                                    <label for="bank_name" class="form-label">Bank Name</label>
                                    <input type="text" class="form-control" name="bank_name" id="bank_name">
                                </div>
                                <div class="mb-3">
                                    <label for="bank_account" class="form-label">Account Number</label>
                                    <input type="text" class="form-control" name="bank_account" id="bank_account">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="accept_terms" required>
                            <label class="form-check-label" for="accept_terms">
                                I agree to the <a href="/terms" target="_blank">Terms and Conditions</a> and <a href="/privacy" target="_blank">Privacy Policy</a>
                            </label>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">Complete Payment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
        const paymentFields = document.querySelectorAll('.payment-fields');
        
        paymentMethods.forEach(method => {
            method.addEventListener('change', function() {
                paymentFields.forEach(field => {
                    field.classList.add('d-none');
                });
                
                document.getElementById(this.value + '_fields').classList.remove('d-none');
            });
        });
    });
</script>
@endsection