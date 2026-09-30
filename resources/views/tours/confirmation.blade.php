@extends('layouts.main')

@section('title', 'Booking Confirmation')

@section('content')
<style>
.ziara-conf-hero { background: radial-gradient(900px 400px at 88% -20%, rgba(201,162,39,.18), transparent 60%), linear-gradient(135deg, var(--maroon-deep) 0%, var(--maroon) 60%, #7e3425 100%); color: #fff; }
.ziara-conf-hero__inner { max-width: 1180px; margin: 0 auto; padding: 56px 24px; display: flex; flex-wrap: wrap; gap: 28px; align-items: center; }
.ziara-conf-hero__icon { width: 88px; height: 88px; flex-shrink: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; background: var(--gold-bright); color: var(--maroon-deep); font-size: 40px; }
.ziara-conf-hero h1 { font-family: var(--font-display); font-size: 40px; font-weight: 700; line-height: 1.1; margin: 0 0 8px; }
.ziara-conf-hero p { margin: 0; font-size: 16px; color: rgba(255,255,255,.85); }
.ziara-conf-hero strong { color: var(--gold-bright); letter-spacing: .5px; }
.ziara-conf-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 26px 34px; }
.ziara-conf-field span { display: block; font-size: 11px; font-weight: 700; letter-spacing: .8px; text-transform: uppercase; color: var(--muted); margin-bottom: 5px; }
.ziara-conf-field p { margin: 0; font-size: 15.5px; color: var(--ink); }
.ziara-conf-field--full { grid-column: 1 / -1; }
.ziara-status {
    display: inline-block;
    font-size: 11.5px;
    font-weight: 700;
    letter-spacing: .6px;
    text-transform: uppercase;
    padding: 5px 13px;
    border-radius: 999px;
    border: 1px solid transparent;
    margin: 0;
}
.ziara-status--confirmed { background: rgba(22,122,58,.10); color: #167a3a; border-color: rgba(22,122,58,.28); }
.ziara-status--pending { background: var(--gold-pale); color: var(--gold-dim); border-color: var(--gold-border); }
.ziara-status--other { background: #f1ece3; color: var(--muted); border-color: var(--line); }
.ziara-conf-list { list-style: none; margin: 0; padding: 0; }
.ziara-conf-list li { display: flex; justify-content: space-between; gap: 14px; padding: 11px 0; font-size: 15px; border-bottom: 1px dashed var(--line); }
.ziara-conf-list li:last-child { border-bottom: 0; }
.ziara-conf-list li span { color: var(--muted); }
.ziara-conf-list li strong { color: var(--ink); font-weight: 700; text-align: right; }
.ziara-conf-list li.ziara-conf-list__total { border-top: 2px solid var(--gold); border-bottom: 0; margin-top: 6px; }
.ziara-conf-list li.ziara-conf-list__total span,
.ziara-conf-list li.ziara-conf-list__total strong { font-size: 17px; }
.ziara-conf-list li.ziara-conf-list__total strong { color: var(--maroon); }
.ziara-conf-hero__img { width: 100%; border-radius: 14px; object-fit: cover; aspect-ratio: 4 / 3; }
.ziara-next { display: flex; gap: 16px; align-items: flex-start; }
.ziara-next__icon { width: 46px; height: 46px; flex-shrink: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; background: var(--gold-pale); color: var(--maroon); font-size: 17px; }
.ziara-next h6 { font-size: 15.5px; font-weight: 700; color: var(--ink); margin: 0 0 4px; }
.ziara-next p { margin: 0; font-size: 13.5px; line-height: 1.65; color: var(--muted); }
@media (max-width: 700px) { .ziara-conf-grid { grid-template-columns: 1fr; } .ziara-conf-hero h1 { font-size: 30px; } }
</style>

<div class="ziara-archive">
    <section class="ziara-conf-hero">
        <div class="ziara-conf-hero__inner">
            <span class="ziara-conf-hero__icon" aria-hidden="true">
                <i class="fa-solid fa-check"></i>
            </span>
            <div>
                <h1>Thank You! Your booking is confirmed</h1>
                <p>Booking Reference: <strong>{{ $booking->booking_reference }}</strong></p>
            </div>
        </div>
    </section>

    <div class="ziara-panel" style="margin-top: 32px;">
        <div style="display: flex; flex-wrap: wrap; gap: 16px; align-items: center; justify-content: space-between; margin-bottom: 24px;">
            <h2 class="ziara-panel__title" style="margin: 0;">Booking Details</h2>
            <a href="javascript:window.print();" class="ziara-btn ziara-btn--outline" style="padding: 10px 22px;">
                <i class="fa-solid fa-print" aria-hidden="true"></i> Print
            </a>
        </div>

        <div style="display: flex; flex-wrap: wrap; gap: 30px; align-items: flex-start;">
            <div style="flex: 0 0 300px; width: 100%;">
                @if(isset($booking->tour->main_image))
                    <img src="{{ $booking->tour->main_image }}" alt="{{ $booking->tour->name }}" class="ziara-conf-hero__img">
                @elseif(isset($booking->hotel_details) && is_string($booking->hotel_details))
                    @php
                        $tourDetails = json_decode($booking->hotel_details, true);
                        $image = $tourDetails['images'][0] ?? '/images/travel/default-tour.jpg';
                    @endphp
                    <img src="{{ $image }}" alt="Tour Image" class="ziara-conf-hero__img">
                @else
                    <img src="/images/travel/default-tour.jpg" alt="Tour" class="ziara-conf-hero__img">
                @endif
            </div>
            <div style="flex: 1 1 320px; min-width: 0;">
                <h3 style="font-family: var(--font-display); font-size: 30px; font-weight: 700; line-height: 1.15; color: var(--maroon-deep); margin: 0 0 22px;">
                    @if(isset($booking->tour->name))
                        {{ $booking->tour->name }}
                    @else
                        {{ $booking->title }}
                    @endif
                </h3>

                <div class="ziara-conf-grid">
                    <div class="ziara-conf-field">
                        <span>Booking Reference</span>
                        <p><strong>{{ $booking->booking_reference }}</strong></p>
                    </div>
                    <div class="ziara-conf-field">
                        <span>Status</span>
                        <p>
                            <span class="ziara-status ziara-status--{{ $booking->status == 'confirmed' ? 'confirmed' : ($booking->status == 'pending' ? 'pending' : 'other') }}">{{ ucfirst($booking->status) }}</span>
                        </p>
                    </div>
                    <div class="ziara-conf-field">
                        <span>Tour Date</span>
                        <p>{{ $booking->activity_date ? $booking->activity_date->format('M d, Y') : 'N/A' }}</p>
                    </div>
                    <div class="ziara-conf-field">
                        <span>Tour Time</span>
                        <p>{{ $booking->activity_time ?? 'As scheduled' }}</p>
                    </div>
                    <div class="ziara-conf-field">
                        <span>Number of Participants</span>
                        <p>{{ $booking->traveler_count ?? $booking->adults ?? 1 }}</p>
                    </div>
                    <div class="ziara-conf-field">
                        <span>Payment Status</span>
                        <p>
                            <span class="ziara-status ziara-status--{{ $booking->payment_status == 'paid' ? 'confirmed' : 'pending' }}">{{ ucfirst($booking->payment_status) }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <hr style="border: 0; border-top: 1px solid var(--line); margin: 30px 0;">

        <div style="display: flex; flex-wrap: wrap; gap: 40px;">
            <div style="flex: 1 1 280px;">
                <h3 class="ziara-panel__title">Contact Information</h3>
                <ul class="ziara-conf-list">
                    <li><span>Name</span><strong>{{ $booking->contact_name ?? 'N/A' }}</strong></li>
                    <li><span>Email</span><strong>{{ $booking->contact_email ?? 'N/A' }}</strong></li>
                    <li><span>Phone</span><strong>{{ $booking->contact_phone ?? 'N/A' }}</strong></li>
                </ul>
            </div>

            <div style="flex: 1 1 280px;">
                <h3 class="ziara-panel__title">Payment Information</h3>
                <ul class="ziara-conf-list">
                    <li class="ziara-conf-list__total">
                        <span>Total Amount</span>
                        <strong>
                            @if(isset($booking->currency) && isset($booking->total_amount))
                                {{ $booking->currency }} {{ number_format($booking->total_amount, 2) }}
                                @if($booking->currency == 'USD')
                                    <span style="display: block; font-size: 13px; font-weight: 400; color: var(--muted);">KES {{ number_format($booking->total_amount * 125, 0) }}</span>
                                @endif
                            @else
                                {{ $booking->currency ?? 'USD' }} {{ number_format($booking->amount ?? 0, 2) }}
                            @endif
                        </strong>
                    </li>
                    <li><span>Payment Method</span><strong>{{ ucfirst($booking->payment_method ?? 'N/A') }}</strong></li>
                    @if(isset($booking->payment_date))
                        <li><span>Payment Date</span><strong>{{ $booking->payment_date->format('M d, Y') }}</strong></li>
                    @endif
                </ul>
            </div>
        </div>

        @if(isset($booking->special_requirements) && !empty($booking->special_requirements))
            <div class="ziara-note" style="margin-top: 24px;">
                <b>Special Requirements</b><br>
                {{ $booking->special_requirements }}
            </div>
        @endif

        @if(isset($booking->pickup_location) && !empty($booking->pickup_location))
            <div class="ziara-note" style="margin-top: 16px;">
                <b>Pickup Information</b><br>
                {{ $booking->pickup_location }}
            </div>
        @endif

        <div style="display: flex; flex-wrap: wrap; gap: 14px; align-items: center; justify-content: space-between; margin-top: 30px; padding-top: 24px; border-top: 1px solid var(--line);">
            <a href="{{ route('tours.index') }}" class="ziara-btn ziara-btn--outline">
                <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i> Browse More Tours
            </a>
            @if($booking->status !== 'cancelled')
                <button type="button" class="ziara-btn" style="background: transparent; color: #b3261e; border: 1.5px solid #b3261e;" data-bs-toggle="modal" data-bs-target="#cancelModal">
                    <i class="fa-regular fa-circle-xmark" aria-hidden="true"></i> Cancel Booking
                </button>
            @endif
        </div>
    </div>

    <div class="ziara-panel" style="margin-top: 26px;">
        <h2 class="ziara-panel__title">What's Next?</h2>
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px;">
            <div class="ziara-next">
                <span class="ziara-next__icon" aria-hidden="true"><i class="fa-solid fa-envelope"></i></span>
                <div>
                    <h6>Confirmation Email</h6>
                    <p>A confirmation email has been sent to your provided email address.</p>
                </div>
            </div>
            <div class="ziara-next">
                <span class="ziara-next__icon" aria-hidden="true"><i class="fa-solid fa-location-dot"></i></span>
                <div>
                    <h6>Tour Details</h6>
                    <p>We'll send detailed instructions about your tour before the tour date.</p>
                </div>
            </div>
            <div class="ziara-next">
                <span class="ziara-next__icon" aria-hidden="true"><i class="fa-solid fa-circle-question"></i></span>
                <div>
                    <h6>Need Help?</h6>
                    <p>Contact our support team at support@example.com</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border: 1px solid var(--line); border-radius: 16px; overflow: hidden;">
            <div class="modal-header" style="background: var(--maroon); border-bottom: 3px solid var(--gold);">
                <h5 class="modal-title" id="cancelModalLabel" style="font-family: var(--font-display); font-size: 24px; font-weight: 700; color: #fff;">Cancel Booking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('tours.cancel', ['reference' => $booking->booking_reference]) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="ziara-note ziara-error" style="margin-bottom: 20px;">
                        <i class="fa-solid fa-triangle-exclamation" aria-hidden="true"></i>
                        Are you sure you want to cancel this booking? This action cannot be undone.
                    </div>

                    <div class="mb-3">
                        <label for="cancellationReason" style="display: block; font-size: 11px; font-weight: 700; letter-spacing: .8px; text-transform: uppercase; color: var(--muted); margin-bottom: 8px;">Cancellation Reason</label>
                        <textarea id="cancellationReason" name="cancellation_reason" rows="3" required style="width: 100%; padding: 13px 14px; font-family: var(--font-body); font-size: 15px; color: var(--ink); background: #fff; border: 1px solid var(--line); border-radius: 10px;"></textarea>
                    </div>

                    @if(isset($booking->tour) && isset($booking->tour->cancellation_policy))
                        <div class="ziara-note" style="margin-top: 18px;">
                            <b>Cancellation Policy</b><br>
                            {{ $booking->tour->cancellation_policy }}
                        </div>
                    @endif
                </div>
                <div class="modal-footer" style="border-top: 1px solid var(--line);">
                    <button type="button" class="ziara-btn ziara-btn--outline" data-bs-dismiss="modal" style="padding: 11px 24px;">Close</button>
                    <button type="submit" class="ziara-btn" style="padding: 11px 24px; background: #b3261e; color: #fff;">Confirm Cancellation</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
