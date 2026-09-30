@extends('layouts.main')

@section('content')
@include('partials.ziara-page-hero', [
    'kicker' => 'Booking Confirmed',
    'title'  => 'Booking <em>Successful!</em>',
    'intro'  => 'Your destination package has been confirmed.',
])

<style>
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

    .animate-fade-in-up {
        animation: fadeInUp 0.5s ease-out forwards;
    }

    .animate-bounce-slow {
        animation: bounce 2s infinite;
    }

    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
    }

    .animate-pulse-slow {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }

    .success-animation {
        animation: scaleIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
    }

    @keyframes scaleIn {
        0% {
            transform: scale(0);
            opacity: 0;
        }
        100% {
            transform: scale(1);
            opacity: 1;
        }
    }

    .countdown-animation {
        transition: all 1s linear;
    }
</style>

<section class="zt-section zt-section--pale">
    <div class="container">
        <div class="ziara-panel animate-fade-in-up" style="padding:0;overflow:hidden;">
            <div class="ziara-panel--dark" style="text-align:center;padding:44px 28px;">
                <div class="success-animation" style="margin:0 auto 24px;background:#fff;border-radius:50%;height:96px;width:96px;display:flex;align-items:center;justify-content:center;">
                    <i data-lucide="check" style="height:48px;width:48px;color:var(--maroon);"></i>
                </div>
                <h1 class="ziara-page-hero__title" style="margin:0;">Booking Successful!</h1>
                <p class="ziara-cta-band__desc" style="margin:10px 0 0;">Your destination package has been confirmed.</p>
            </div>

            <div style="padding:30px 28px;">
                <ul class="ziara-summary" style="margin-bottom:34px;">
                    <li>
                        <span>Booking Reference</span>
                        <strong>{{ $booking['reference'] ?? 'DEST-' . strtoupper(substr(uniqid(), 0, 8)) }}</strong>
                    </li>
                </ul>

                <h2 class="ziara-panel__title">Trip Details</h2>
                <div class="ziara-split" style="margin-bottom:34px;">
                    <div class="ziara-deal__media">
                        <img src="{{ asset('images/destinations/' . ($destination['image'] ?? 'destination-placeholder.jpg')) }}" alt="{{ $destination['name'] ?? 'Destination' }}">
                    </div>
                    <div>
                        <h3 class="ziara-deal__title">{{ $destination['name'] ?? 'Zanzibar Beach Getaway' }}</h3>
                        <p class="ziara-deal__desc" style="display:flex;align-items:center;gap:8px;">
                            <i data-lucide="map-pin" style="height:16px;width:16px;color:var(--maroon);" aria-hidden="true"></i>
                            {{ $destination['location'] ?? 'Zanzibar, Tanzania' }}
                        </p>
                        <ul class="ziara-summary">
                            <li>
                                <span>Check-in</span>
                                <strong>{{ $booking['check_in'] ?? date('D, M d, Y') }}</strong>
                            </li>
                            <li>
                                <span>Check-out</span>
                                <strong>{{ $booking['check_out'] ?? date('D, M d, Y', strtotime('+5 days')) }}</strong>
                            </li>
                            <li>
                                <span>Duration</span>
                                <strong>{{ $destination['duration'] ?? '5 days, 4 nights' }}</strong>
                            </li>
                            <li>
                                <span>Travelers</span>
                                <strong>{{ $booking['adults'] ?? '2' }} adults{{ ($booking['children'] ?? 0) > 0 ? ', ' . $booking['children'] . ' children' : '' }}</strong>
                            </li>
                        </ul>
                    </div>
                </div>

                <h2 class="ziara-panel__title">Travel Information</h2>
                <div class="ziara-infocards" style="margin-bottom:34px;">
                    <div class="ziara-infocard">
                        <div class="ziara-infocard__icon"><i data-lucide="plane" aria-hidden="true"></i></div>
                        <h3 class="ziara-infocard__title">Flight Details</h3>
                        <div class="ziara-infocard__value">{{ $booking['flight_details'] ?? 'Flight to Zanzibar (ZNZ)' }}<br>{{ $booking['flight_number'] ?? 'KQ 410' }} - {{ $booking['flight_date'] ?? date('d M Y') }}</div>
                    </div>
                    <div class="ziara-infocard">
                        <div class="ziara-infocard__icon"><i data-lucide="hotel" aria-hidden="true"></i></div>
                        <h3 class="ziara-infocard__title">Accommodation</h3>
                        <div class="ziara-infocard__value">{{ $booking['accommodation'] ?? 'Beach Front Resort' }}<br>{{ $booking['room_type'] ?? 'Deluxe Suite' }}</div>
                    </div>
                    <div class="ziara-infocard">
                        <div class="ziara-infocard__icon"><i data-lucide="car" aria-hidden="true"></i></div>
                        <h3 class="ziara-infocard__title">Airport Transfer</h3>
                        <div class="ziara-infocard__value">{{ $booking['transfer'] ?? 'Included' }}</div>
                    </div>
                    <div class="ziara-infocard">
                        <div class="ziara-infocard__icon"><i data-lucide="utensils" aria-hidden="true"></i></div>
                        <h3 class="ziara-infocard__title">Meal Plan</h3>
                        <div class="ziara-infocard__value">{{ $booking['meal_plan'] ?? 'Half-board (Breakfast & Dinner)' }}</div>
                    </div>
                </div>

                <h2 class="ziara-panel__title">Payment Details</h2>
                <div class="ziara-infocards" style="margin-bottom:34px;">
                    <div class="ziara-infocard">
                        <div class="ziara-infocard__icon"><i data-lucide="credit-card" aria-hidden="true"></i></div>
                        <h3 class="ziara-infocard__title">Payment Method</h3>
                        <div class="ziara-infocard__value">{{ $booking['payment_method'] ?? 'Credit Card' }}</div>
                    </div>
                    <div class="ziara-infocard">
                        <div class="ziara-infocard__icon" style="background:#eef7ef;color:#167a3a;"><i data-lucide="check-circle" aria-hidden="true"></i></div>
                        <h3 class="ziara-infocard__title">Payment Status</h3>
                        <div class="ziara-infocard__value" style="color:#167a3a;font-weight:700;">Paid</div>
                    </div>
                    <div class="ziara-infocard">
                        <div class="ziara-infocard__icon"><i data-lucide="receipt" aria-hidden="true"></i></div>
                        <h3 class="ziara-infocard__title">Transaction ID</h3>
                        <div class="ziara-infocard__value">{{ $booking['transaction_id'] ?? 'TXN-' . rand(100000, 999999) }}</div>
                    </div>
                    <div class="ziara-infocard">
                        <div class="ziara-infocard__icon"><i data-lucide="banknote" aria-hidden="true"></i></div>
                        <h3 class="ziara-infocard__title">Amount Paid</h3>
                        <div class="ziara-infocard__value">{{ $booking['currency'] ?? 'USD' }} {{ $booking['total'] ?? '1,899.00' }}</div>
                    </div>
                </div>

                <h2 class="ziara-panel__title">Customer Details</h2>
                <div class="ziara-infocards" style="margin-bottom:34px;">
                    <div class="ziara-infocard">
                        <div class="ziara-infocard__icon"><i data-lucide="user" aria-hidden="true"></i></div>
                        <h3 class="ziara-infocard__title">Name</h3>
                        <div class="ziara-infocard__value">{{ Auth::user()->name ?? 'John Doe' }}</div>
                    </div>
                    <div class="ziara-infocard">
                        <div class="ziara-infocard__icon"><i data-lucide="mail" aria-hidden="true"></i></div>
                        <h3 class="ziara-infocard__title">Email</h3>
                        <div class="ziara-infocard__value">{{ Auth::user()->email ?? 'john.doe@example.com' }}</div>
                    </div>
                    <div class="ziara-infocard">
                        <div class="ziara-infocard__icon"><i data-lucide="phone" aria-hidden="true"></i></div>
                        <h3 class="ziara-infocard__title">Phone</h3>
                        <div class="ziara-infocard__value">{{ Auth::user()->phone ?? '+254 712 345 678' }}</div>
                    </div>
                </div>

                <div class="ziara-note" style="margin-bottom:34px;">
                    <h3 class="ziara-panel__title" style="margin-bottom:18px;">What's Next?</h3>
                    <ul class="ziara-checklist">
                        <li class="ziara-checklist__item">
                            <span class="ziara-checklist__icon"><i data-lucide="mail" aria-hidden="true"></i></span>
                            <span>A confirmation email with all booking details has been sent to your email address.</span>
                        </li>
                        <li class="ziara-checklist__item">
                            <span class="ziara-checklist__icon"><i data-lucide="file-text" aria-hidden="true"></i></span>
                            <span>Your e-ticket and travel vouchers are attached to the confirmation email.</span>
                        </li>
                        <li class="ziara-checklist__item">
                            <span class="ziara-checklist__icon"><i data-lucide="message-square" aria-hidden="true"></i></span>
                            <span>Our travel consultant will contact you within 24 hours to discuss final arrangements.</span>
                        </li>
                    </ul>
                </div>

                <div style="display:flex;flex-wrap:wrap;gap:16px;">
                    <a href="{{ route('user.bookings.all') }}" class="ziara-btn ziara-btn--maroon">
                        <i data-lucide="layout-dashboard" class="w-5 h-5" aria-hidden="true"></i>
                        View in Dashboard
                    </a>
                    <a href="{{ route('bookings.download', $booking['id'] ?? 1) }}" class="ziara-btn ziara-btn--gold">
                        <i data-lucide="download" class="w-5 h-5" aria-hidden="true"></i>
                        Download E-Ticket
                    </a>
                    <a href="{{ route('home') }}" class="ziara-btn ziara-btn--outline">
                        <i data-lucide="home" class="w-5 h-5" aria-hidden="true"></i>
                        Back to Home
                    </a>
                </div>
            </div>
        </div>

        <div class="animate-fade-in-up" style="text-align:center;animation-delay:0.3s;">
            <div class="ziara-note" style="display:inline-flex;align-items:center;gap:10px;">
                <i data-lucide="info" class="animate-pulse-slow" style="height:20px;width:20px;color:var(--maroon);" aria-hidden="true"></i>
                <span>You will be redirected to the homepage in <span id="countdown" class="countdown-animation" style="font-weight:700;">6</span> seconds</span>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Lucide icons
        lucide.createIcons();
        
        // Fill stars for star ratings
        document.querySelectorAll('[data-lucide="star"]').forEach(function(star) {
            if (star.classList.contains('fill-current')) {
                star.setAttribute('fill', 'currentColor');
            }
        });
        
        // Countdown timer for redirect
        let countdown = 6;
        const countdownElement = document.getElementById('countdown');
        
        const countdownInterval = setInterval(() => {
            countdown--;
            countdownElement.textContent = countdown;
            
            if (countdown <= 0) {
                clearInterval(countdownInterval);
                window.location.href = "{{ route('home') }}";
            }
        }, 1000);
        
        // Show toast notification
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
        
        // Show success toast
        showToast('Booking successful! Thank you for your purchase.', 'success');
    });
</script>
@endsection
