@extends('layouts.main')

@section('title', 'My Tour Bookings')

@section('content')
<style>
.ziara-navlist { display: grid; gap: 4px; }
.ziara-navlist a { display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 10px; font-size: 14.5px; font-weight: 600; color: var(--muted); transition: background .25s, color .25s; }
.ziara-navlist a i { width: 18px; text-align: center; color: var(--gold); }
.ziara-navlist a:hover { background: var(--gold-pale); color: var(--maroon-deep); }
.ziara-navlist a.is-active { background: var(--maroon); color: #fff; }
.ziara-navlist a.is-active i { color: var(--gold-bright); }
.ziara-navlist__sep { margin: 22px 0 16px; border: 0; border-top: 1px solid var(--line); }
.ziara-table-wrap { overflow-x: auto; }
.ziara-table { width: 100%; border-collapse: collapse; font-size: 14.5px; }
.ziara-table th { text-align: left; padding: 14px 18px; font-size: 11px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: var(--muted); background: var(--gold-pale); border-bottom: 1px solid var(--line); white-space: nowrap; }
.ziara-table td { padding: 16px 18px; border-bottom: 1px solid var(--line); color: var(--ink); vertical-align: middle; }
.ziara-table tbody tr { transition: background .25s; }
.ziara-table tbody tr:hover { background: var(--gold-pale); }
.ziara-table tbody tr:last-child td { border-bottom: 0; }
.ziara-table__ref { font-weight: 700; color: var(--maroon-deep); }
.ziara-table__sub { display: block; margin-top: 3px; font-size: 12.5px; color: var(--muted); }
.ziara-table__amount { font-weight: 700; color: var(--maroon); }
.ziara-table__due { display: block; margin-top: 4px; font-size: 12.5px; color: #b3261e; }
.ziara-status { display: inline-block; padding: 5px 14px; border-radius: 50px; font-size: 11px; font-weight: 700; letter-spacing: .6px; text-transform: uppercase; }
.ziara-status--pending { background: var(--gold-pale); color: var(--gold-dim); border: 1px solid var(--gold-border); }
.ziara-status--confirmed { background: #eef7ef; color: #167a3a; border: 1px solid rgba(22,122,58,.28); }
.ziara-status--cancelled { background: #fdf0ee; color: #b3261e; border: 1px solid rgba(179,38,30,.28); }
.ziara-status--completed { background: var(--maroon); color: #fff; }
.ziara-status--other { background: #f1ece6; color: var(--muted); }
.ziara-menu { position: relative; }
.ziara-menu__btn { padding: 8px 16px; font-family: var(--font-body); font-size: 11.5px; font-weight: 700; letter-spacing: .6px; text-transform: uppercase; color: var(--maroon); background: transparent; border: 1.5px solid var(--maroon); border-radius: 50px; cursor: pointer; }
.ziara-menu__btn:hover { background: var(--maroon); color: #fff; }
.ziara-menu__list { position: absolute; right: 0; top: calc(100% + 6px); z-index: 20; min-width: 200px; margin: 0; padding: 6px; list-style: none; background: #fff; border: 1px solid var(--line); border-radius: 12px; box-shadow: var(--shadow-md); display: none; }
.ziara-menu__list.show { display: block; }
.ziara-menu__list a,
.ziara-menu__list button { display: flex; align-items: center; gap: 10px; width: 100%; padding: 10px 12px; border: 0; background: none; border-radius: 8px; font-family: var(--font-body); font-size: 14px; color: var(--ink); text-align: left; cursor: pointer; }
.ziara-menu__list a:hover,
.ziara-menu__list button:hover { background: var(--gold-pale); }
.ziara-menu__list i { color: var(--gold); width: 16px; text-align: center; }
.ziara-menu__list .is-danger { color: #b3261e; }
.ziara-menu__list .is-danger i { color: #b3261e; }
</style>

<div class="ziara-archive">
    @include('partials.ziara-page-hero', [
        'title' => 'My Tour <em>Bookings</em>',
        'kicker' => 'My Account',
        'intro' => 'Manage and view your tour bookings.',
        'crumbs' => [
            ['label' => 'Home', 'url' => url('/')],
            ['label' => 'Tours', 'url' => route('tours.index')],
            ['label' => 'My Bookings', 'url' => route('tours.my-bookings')],
        ],
    ])

    <div style="display: flex; flex-wrap: wrap; gap: 30px; align-items: flex-start;">
        <aside style="flex: 0 0 290px; width: 100%;">
            <div class="ziara-panel" style="position: sticky; top: 24px;">
                <h2 class="ziara-panel__title">Booking Management</h2>
                <div class="ziara-navlist">
                    <a class="is-active" href="{{ route('tours.my-bookings') }}">
                        <i class="fa-solid fa-compass" aria-hidden="true"></i> Tour Bookings
                    </a>
                    <a href="{{ route('user.bookings.all') }}">
                        <i class="fa-solid fa-plane" aria-hidden="true"></i> Flight Bookings
                    </a>
                    <a href="{{ route('user.hotels.my') }}">
                        <i class="fa-solid fa-hotel" aria-hidden="true"></i> Hotel Bookings
                    </a>
                </div>

                <hr class="ziara-navlist__sep">

                <h2 class="ziara-panel__title">Quick Links</h2>
                <div class="ziara-navlist">
                    <a href="{{ route('tours.index') }}">
                        <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i> Browse Tours
                    </a>
                    <a href="{{ route('user.profile.my') }}">
                        <i class="fa-solid fa-user" aria-hidden="true"></i> My Profile
                    </a>
                </div>
            </div>
        </aside>

        <div style="flex: 1 1 560px; min-width: 0;">
            @if(count($tourBookings ?? []) > 0 || count($generalBookings ?? []) > 0)
                <div class="ziara-panel" style="padding: 0; overflow: hidden;">
                    <h2 class="ziara-panel__title" style="padding: 24px 28px 0; margin-bottom: 18px;">Your Tour Bookings</h2>
                    <div class="ziara-table-wrap">
                        <table class="ziara-table">
                            <thead>
                                <tr>
                                    <th scope="col">Booking Ref</th>
                                    <th scope="col">Tour</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tourBookings ?? [] as $booking)
                                    <tr>
                                        <td>
                                            <span class="ziara-table__ref">{{ $booking->booking_reference }}</span>
                                            <small class="ziara-table__sub">{{ $booking->created_at->format('M d, Y') }}</small>
                                        </td>
                                        <td>
                                            {{ $booking->tour->name ?? 'Tour' }}
                                            <small class="ziara-table__sub">{{ $booking->tour->city ?? 'Location' }}</small>
                                        </td>
                                        <td>
                                            {{ $booking->activity_date->format('M d, Y') }}
                                            <small class="ziara-table__sub">{{ $booking->traveler_count }} {{ $booking->traveler_count > 1 ? 'participants' : 'participant' }}</small>
                                        </td>
                                        <td>
                                            <span class="ziara-table__amount">{{ $booking->currency }} {{ number_format($booking->total_amount, 2) }}</span>
                                        </td>
                                        <td>
                                            @if($booking->status == 'pending')
                                                <span class="ziara-status ziara-status--pending">Pending</span>
                                            @elseif($booking->status == 'confirmed')
                                                <span class="ziara-status ziara-status--confirmed">Confirmed</span>
                                            @elseif($booking->status == 'cancelled')
                                                <span class="ziara-status ziara-status--cancelled">Cancelled</span>
                                            @elseif($booking->status == 'completed')
                                                <span class="ziara-status ziara-status--completed">Completed</span>
                                            @else
                                                <span class="ziara-status ziara-status--other">{{ ucfirst($booking->status) }}</span>
                                            @endif

                                            @if($booking->payment_status != 'paid')
                                                <small class="ziara-table__due">Payment: {{ ucfirst($booking->payment_status) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="ziara-menu">
                                                <button class="ziara-menu__btn" type="button" id="tourDropdownMenuButton" aria-expanded="false" aria-haspopup="true">
                                                    Actions
                                                </button>
                                                <ul class="ziara-menu__list" aria-labelledby="tourDropdownMenuButton">
                                                    <li>
                                                        <a href="{{ route('tour.confirmation', ['reference' => $booking->booking_reference]) }}">
                                                            <i class="fa-solid fa-file-lines" aria-hidden="true"></i> View Details
                                                        </a>
                                                    </li>

                                                    @if($booking->payment_status == 'pending')
                                                        <li>
                                                            <a href="{{ route('tour.payment', ['reference' => $booking->booking_reference]) }}">
                                                                <i class="fa-solid fa-credit-card" aria-hidden="true"></i> Complete Payment
                                                            </a>
                                                        </li>
                                                    @endif

                                                    @if($booking->status != 'cancelled' && $booking->status != 'completed')
                                                        <li>
                                                            <button class="cancel-booking-btn is-danger" type="button"
                                                                   data-bs-toggle="modal"
                                                                   data-bs-target="#cancelBookingModal"
                                                                   data-reference="{{ $booking->booking_reference }}">
                                                                <i class="fa-solid fa-xmark" aria-hidden="true"></i> Cancel Booking
                                                            </button>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach

                                @foreach($generalBookings ?? [] as $booking)
                                    @php
                                        $tourDetails = json_decode($booking->hotel_details ?? '{}', true);
                                        $travelerDetails = json_decode($booking->traveler_details ?? '{}', true);
                                    @endphp
                                    <tr>
                                        <td>
                                            <span class="ziara-table__ref">{{ $booking->booking_reference }}</span>
                                            <small class="ziara-table__sub">{{ $booking->created_at->format('M d, Y') }}</small>
                                        </td>
                                        <td>
                                            {{ $booking->title }}
                                            <small class="ziara-table__sub">{{ $tourDetails['city'] ?? 'Location' }}</small>
                                        </td>
                                        <td>
                                            {{ $travelerDetails['tour_date'] ?? 'Date not specified' }}
                                            <small class="ziara-table__sub">{{ $booking->adults ?? 1 }} {{ $booking->adults > 1 ? 'participants' : 'participant' }}</small>
                                        </td>
                                        <td>
                                            <span class="ziara-table__amount">{{ $booking->currency }} {{ number_format($booking->total_amount, 2) }}</span>
                                        </td>
                                        <td>
                                            @if($booking->status == 'pending')
                                                <span class="ziara-status ziara-status--pending">Pending</span>
                                            @elseif($booking->status == 'confirmed')
                                                <span class="ziara-status ziara-status--confirmed">Confirmed</span>
                                            @elseif($booking->status == 'cancelled')
                                                <span class="ziara-status ziara-status--cancelled">Cancelled</span>
                                            @elseif($booking->status == 'completed')
                                                <span class="ziara-status ziara-status--completed">Completed</span>
                                            @else
                                                <span class="ziara-status ziara-status--other">{{ ucfirst($booking->status) }}</span>
                                            @endif

                                            @if($booking->payment_status != 'paid')
                                                <small class="ziara-table__due">Payment: {{ ucfirst($booking->payment_status) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="ziara-menu">
                                                <button class="ziara-menu__btn" type="button" id="hotelDropdownMenuButton" aria-expanded="false" aria-haspopup="true">
                                                    Actions
                                                </button>
                                                <ul class="ziara-menu__list" aria-labelledby="hotelDropdownMenuButton">
                                                    <li>
                                                        <a href="{{ route('booking.confirmation', ['reference' => $booking->booking_reference]) }}">
                                                            <i class="fa-solid fa-file-lines" aria-hidden="true"></i> View Details
                                                        </a>
                                                    </li>

                                                    @if($booking->payment_status == 'pending')
                                                        <li>
                                                            <a href="{{ route('tour.payment', $booking->booking_reference) }}">
                                                                <i class="fa-solid fa-credit-card" aria-hidden="true"></i> Complete Payment
                                                            </a>
                                                        </li>
                                                    @endif

                                                    @if($booking->status != 'cancelled' && $booking->status != 'completed')
                                                        <li>
                                                            <button class="cancel-booking-btn is-danger" type="button"
                                                                   data-bs-toggle="modal"
                                                                   data-bs-target="#cancelBookingModal"
                                                                   data-reference="{{ $booking->booking_reference }}">
                                                                <i class="fa-solid fa-xmark" aria-hidden="true"></i> Cancel Booking
                                                            </button>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="ziara-panel" style="text-align: center; padding: 60px 30px;">
                    <i class="fa-solid fa-compass" style="font-size: 48px; color: var(--gold);" aria-hidden="true"></i>
                    <h2 class="ziara-panel__title" style="display: inline-block; margin-top: 18px;">No Tour Bookings Yet</h2>
                    <p style="font-size: 15.5px; line-height: 1.75; color: var(--muted); max-width: 520px; margin: 0 auto 26px;">
                        You haven't booked any tours yet. Start exploring our amazing tours in East Africa!
                    </p>
                    <a href="{{ route('tours.index') }}" class="ziara-btn ziara-btn--maroon">
                        <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i> Browse Tours
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<div class="modal fade" id="cancelBookingModal" tabindex="-1" aria-labelledby="cancelBookingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border: 1px solid var(--line); border-radius: 16px; overflow: hidden;">
            <div class="modal-header" style="background: var(--maroon); border-bottom: 3px solid var(--gold);">
                <h5 class="modal-title" id="cancelBookingModalLabel" style="font-family: var(--font-display); font-size: 24px; font-weight: 700; color: #fff;">Cancel Booking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="cancelBookingForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="ziara-note ziara-error" style="margin-bottom: 20px;">
                        <i class="fa-solid fa-triangle-exclamation" aria-hidden="true"></i>
                        <b>Warning:</b> Cancellation may be subject to fees depending on the tour's cancellation policy.
                    </div>

                    <div class="mb-3">
                        <label for="cancellation_reason" style="display: block; font-size: 11px; font-weight: 700; letter-spacing: .8px; text-transform: uppercase; color: var(--muted); margin-bottom: 8px;">Please provide a reason for cancellation:</label>
                        <textarea id="cancellation_reason" name="cancellation_reason" rows="3" required style="width: 100%; padding: 13px 14px; font-family: var(--font-body); font-size: 15px; color: var(--ink); background: #fff; border: 1px solid var(--line); border-radius: 10px;"></textarea>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid var(--line);">
                    <button type="button" class="ziara-btn ziara-btn--outline" data-bs-dismiss="modal" style="padding: 11px 24px;">Close</button>
                    <button type="submit" class="ziara-btn" style="padding: 11px 24px; background: #b3261e; color: #fff;">
                        <i class="fa-solid fa-xmark" aria-hidden="true"></i> Confirm Cancellation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.cancel-booking-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const reference = this.dataset.reference;
            document.getElementById('cancelBookingForm').action = `/tours/cancel/${reference}`;
        });
    });

    function closeMenus(except) {
        document.querySelectorAll('.ziara-menu__list.show').forEach(function(open) {
            if (open === except) { return; }
            open.classList.remove('show');
            const owner = open.parentElement.querySelector('.ziara-menu__btn');
            if (owner) { owner.setAttribute('aria-expanded', 'false'); }
        });
    }

    document.querySelectorAll('.ziara-menu__btn').forEach(function(btn) {
        const list = btn.nextElementSibling;
        btn.addEventListener('click', function(event) {
            event.stopPropagation();
            closeMenus(list);
            if (list) {
                const open = list.classList.toggle('show');
                this.setAttribute('aria-expanded', open ? 'true' : 'false');
            }
        });
    });

    document.addEventListener('click', function() {
        closeMenus(null);
    });
});
</script>
@endsection
