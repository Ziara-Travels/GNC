@extends('layouts.main')

@section('title', 'Flight Booking Confirmation')

@section('content')
<style>
.ziara-pricing .ziara-archive__hero { --zt-maroon:#7b2a1b;--zt-maroon-deep:#6b1d15;--zt-gold:#d9a561;--zt-gold-bright:#f3d24a; }
.ziara-pricing .ziara-panel__head { background:var(--maroon-deep);color:#fff;padding:16px 26px;display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap; }
.ziara-pricing .ziara-panel__head h2 { margin:0;font-size:15px;font-weight:600; }
.ziara-pricing .ziara-panel__head i { color:var(--gold-bright);margin-right:8px; }
.ziara-pricing .ziara-panel__tag { font-size:12px;padding:5px 14px;border-radius:50px;background:rgba(255,255,255,.14); }
.ziara-pricing .ziara-pricing__step { display:flex;align-items:center;gap:10px; }
.ziara-pricing .ziara-pricing__num { width:34px;height:34px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-size:12px;font-weight:700; }
.ziara-pricing .ziara-pricing__num--done { background:var(--maroon);color:var(--gold-bright); }
.ziara-pricing .ziara-pricing__num--todo { background:#e8ded0;color:var(--muted); }
.ziara-pricing .ziara-pricing__rule { width:52px;height:2px;margin:0 12px; }
.ziara-pricing .ziara-pricing__rule--done { background:var(--gold); }
.ziara-pricing .ziara-pricing__rule--todo { background:#e0d6c6; }
.ziara-pricing .ziara-pricing__tag-chip { display:inline-block;padding:4px 14px;border-radius:50px;font-size:12px;font-weight:600;background:var(--gold-pale);border:1px solid var(--gold-border);color:var(--maroon); }
.ziara-pricing .ziara-pricing__time { font-family:'Cormorant Garamond',Georgia,serif;font-size:30px;font-weight:700;color:var(--ink); }
.ziara-pricing .ziara-pricing__code { font-size:15px;font-weight:700;color:var(--maroon);margin-top:4px; }
.ziara-pricing .ziara-pricing__dot { width:9px;height:9px;border-radius:50%;background:var(--maroon);flex:none; }
.ziara-pricing .ziara-pricing__line { flex:1;height:2px;margin:0 6px;position:relative;background:var(--gold-border); }
.ziara-pricing .ziara-pricing__stop { position:absolute;top:50%;transform:translate(-50%,-50%);width:9px;height:9px;border-radius:50%;background:var(--gold); }
.ziara-pricing .ziara-pricing__total { font-family:'Cormorant Garamond',Georgia,serif;font-size:30px;font-weight:700;color:var(--maroon); }
.ziara-pricing .ziara-summary__total strong { font-family:'Cormorant Garamond',Georgia,serif;font-size:30px;font-weight:700;color:var(--maroon); }
.ziara-pricing .ziara-pricing__divider { margin-top:26px;padding-top:26px;border-top:1px solid var(--line); }
.ziara-pricing .ziara-form input,
.ziara-pricing .ziara-form select { border-color:#e5d3b5; }
.ziara-pricing .ziara-form input:focus,
.ziara-pricing .ziara-form select:focus { border-color:#d9a561 !important;box-shadow:0 0 0 3px rgba(217,165,97,0.22) !important;outline:none; }
</style>

@include('partials.ziara-page-hero', [
    'kicker' => 'Almost There',
    'title'  => 'Confirm Your <em>Flight</em>',
    'intro'  => 'Review the details below before proceeding to booking.',
    'crumbs' => [
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Flights', 'url' => route('flights.public.search')],
        ['label' => 'Confirm', 'url' => route('flights.public.search')],
    ],
])

@if(isset($flight))
@php
    $firstSegment = $flight->itineraries[0]->segments[0];
    $segments = $flight->itineraries[0]->segments;
    $lastSegment = end($segments);
    $carrierCode = $firstSegment->carrierCode;
    $airlineName = isset($flight->dictionaries->carriers->{$carrierCode})
        ? $flight->dictionaries->carriers->{$carrierCode}
        : $carrierCode;
    $numberOfStops = count($segments) - 1;
    $cabin = $flight->travelerPricings[0]->fareDetailsBySegment[0]->cabin ?? 'N/A';
    $duration = $flight->itineraries[0]->duration ?? '';
    $durationFormatted = str_replace(['PT','H','M'], ['','h ','m'], $duration);
@endphp

<section class="zt-section zt-section--pale ziara-pricing" style="--maroon:#7b2a1b;--maroon-deep:#6b1d15;--gold:#d9a561;--gold-bright:#f3d24a;">
    <div class="container" style="max-width:900px;">
        <div class="ziara-panel" style="padding:26px;margin-bottom:34px;display:flex;align-items:center;justify-content:center;gap:8px;flex-wrap:wrap;">
            <div class="ziara-pricing__step">
                <span class="ziara-pricing__num ziara-pricing__num--done">1</span>
                <span style="font-weight:600;">Search</span>
            </div>
            <div class="ziara-pricing__rule ziara-pricing__rule--done"></div>
            <div class="ziara-pricing__step">
                <span class="ziara-pricing__num ziara-pricing__num--done">2</span>
                <span style="font-weight:600;">Review</span>
            </div>
            <div class="ziara-pricing__rule ziara-pricing__rule--todo"></div>
            <div class="ziara-pricing__step">
                <span class="ziara-pricing__num ziara-pricing__num--todo">3</span>
                <span style="color:var(--muted);">Payment</span>
            </div>
            <div class="ziara-pricing__rule ziara-pricing__rule--todo"></div>
            <div class="ziara-pricing__step">
                <span class="ziara-pricing__num ziara-pricing__num--todo">4</span>
                <span style="color:var(--muted);">Confirmed</span>
            </div>
        </div>

        <div class="ziara-panel" style="padding:0;overflow:hidden;margin-bottom:26px;">
            <div class="ziara-panel__head">
                <h2><i class="fas fa-plane" aria-hidden="true"></i>Flight Summary</h2>
                <span class="ziara-panel__tag">{{ $carrierCode }}-{{ $firstSegment->number }}</span>
            </div>
            <div style="padding:28px;">
                <div style="display:flex;flex-wrap:wrap;align-items:center;gap:14px;margin-bottom:26px;font-size:14px;">
                    <span style="font-weight:700;color:var(--ink);">{{ $airlineName }}</span>
                    <span class="ziara-pricing__tag-chip">{{ ucwords(str_replace('_', ' ', strtolower($cabin))) }}</span>
                    <span class="ziara-pricing__tag-chip">{{ $numberOfStops === 0 ? 'Non-stop' : $numberOfStops.' '.($numberOfStops === 1 ? 'Stop' : 'Stops') }}</span>
                    @if($durationFormatted)
                        <span style="color:var(--muted);"><i class="far fa-clock" aria-hidden="true" style="margin-right:5px;"></i>{{ $durationFormatted }}</span>
                    @endif
                </div>

                <div style="display:flex;align-items:center;gap:18px;">
                    <div style="text-align:center;">
                        <div class="ziara-pricing__time">{{ date('H:i', strtotime($firstSegment->departure->at)) }}</div>
                        <div style="font-size:12.5px;color:var(--muted);margin-top:2px;">{{ date('D, M d', strtotime($firstSegment->departure->at)) }}</div>
                        <div class="ziara-pricing__code">{{ $firstSegment->departure->iataCode }}</div>
                    </div>
                    <div style="flex:1;display:flex;flex-direction:column;align-items:center;padding:0 16px;">
                        <div style="width:100%;display:flex;align-items:center;">
                            <div class="ziara-pricing__dot"></div>
                            <div class="ziara-pricing__line">
                                @if($numberOfStops > 0)
                                    @for($s = 0; $s < $numberOfStops; $s++)
                                        <div class="ziara-pricing__stop" style="left: {{ ($s + 1) * 100 / ($numberOfStops + 1) }}%"></div>
                                    @endfor
                                @endif
                            </div>
                            <i class="fas fa-plane" aria-hidden="true" style="font-size:12px;color:var(--maroon);"></i>
                        </div>
                    </div>
                    <div style="text-align:center;">
                        <div class="ziara-pricing__time">{{ date('H:i', strtotime($lastSegment->arrival->at)) }}</div>
                        <div style="font-size:12.5px;color:var(--muted);margin-top:2px;">{{ date('D, M d', strtotime($lastSegment->arrival->at)) }}</div>
                        <div class="ziara-pricing__code">{{ $lastSegment->arrival->iataCode }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="ziara-panel" style="padding:0;overflow:hidden;margin-bottom:26px;">
            <div class="ziara-panel__head">
                <h2><i class="fas fa-receipt" aria-hidden="true"></i>Price Breakdown</h2>
            </div>
            <div style="padding:28px;">
                <ul class="ziara-summary">
                    @foreach($flight->travelerPricings as $index => $traveler)
                        <li>
                            <span>
                                {{ ucfirst(strtolower($traveler->travelerType)) }} {{ $index + 1 }}
                                <span style="color:var(--muted);">&middot;</span>
                                <span style="font-size:13.5px;">Base: {{ $flight->price->currency }} {{ $traveler->price->base }}</span>
                            </span>
                            <strong>{{ $flight->price->currency }} {{ $traveler->price->total }}</strong>
                        </li>
                    @endforeach
                    <li class="ziara-summary__total">
                        <span>Total</span>
                        <strong>{{ $flight->price->currency }} {{ $flight->price->total }}</strong>
                    </li>
                </ul>
            </div>
        </div>

        @guest
            <div class="ziara-panel" style="text-align:center;background:var(--gold-pale);border-color:var(--gold-border);">
                <div style="width:64px;height:64px;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 18px;background:var(--maroon);color:var(--gold-bright);">
                    <i class="fas fa-lock" aria-hidden="true" style="font-size:24px;"></i>
                </div>
                <h3 class="ziara-panel__title" style="font-size:26px;">Login to Complete Your Booking</h3>
                <p style="color:var(--muted);margin-bottom:24px;">Create an account or sign in to proceed with your reservation.</p>
                <div class="flex justify-center gap-3 flex-wrap">
                    <a href="{{ route('login') }}" class="ziara-btn ziara-btn--gold">
                        <i class="fas fa-sign-in-alt" aria-hidden="true"></i> Login
                    </a>
                    <a href="{{ route('register') }}" class="ziara-btn ziara-btn--outline">
                        <i class="fas fa-user-plus" aria-hidden="true"></i> Create Account
                    </a>
                </div>
            </div>
        @else
            <form action="{{ route('booking.process') }}" method="POST" class="ziara-form">
                @csrf
                <input type="hidden" name="flight_data" value="{{ json_encode($flight) }}">

                <div class="ziara-panel" style="padding:0;overflow:hidden;margin-bottom:26px;">
                    <div class="ziara-panel__head">
                        <h2><i class="fas fa-users" aria-hidden="true"></i>Passenger Information</h2>
                    </div>
                    <div style="padding:28px;">
                        @for($i = 0; $i < count($flight->travelerPricings); $i++)
                            <div class="{{ $i > 0 ? 'ziara-pricing__divider' : '' }}">
                                <h3 style="font-size:16px;font-weight:700;color:var(--ink);margin-bottom:14px;">
                                    Passenger {{ $i + 1 }}
                                    <span class="ziara-pricing__tag-chip" style="margin-left:8px;">{{ ucfirst(strtolower($flight->travelerPricings[$i]->travelerType)) }}</span>
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                                        <input type="text" name="passengers[{{ $i }}][firstName]" required
                                            class="w-full border border-gray-300 rounded-lg py-2.5 px-3 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                                        <input type="text" name="passengers[{{ $i }}][lastName]" required
                                            class="w-full border border-gray-300 rounded-lg py-2.5 px-3 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                                        <input type="date" name="passengers[{{ $i }}][dob]" required
                                            class="w-full border border-gray-300 rounded-lg py-2.5 px-3 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                                        <select name="passengers[{{ $i }}][gender]" required
                                            class="w-full border border-gray-300 rounded-lg py-2.5 px-3 text-sm">
                                            <option value="">Select Gender</option>
                                            <option value="MALE">Male</option>
                                            <option value="FEMALE">Female</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>

                <div class="ziara-panel" style="padding:0;overflow:hidden;margin-bottom:32px;">
                    <div class="ziara-panel__head">
                        <h2><i class="fas fa-envelope" aria-hidden="true"></i>Contact Information</h2>
                    </div>
                    <div style="padding:28px;">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" name="contact[email]" value="{{ Auth::user()->email }}" required
                                    class="w-full border border-gray-300 rounded-lg py-2.5 px-3 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                <input type="tel" name="contact[phone]" required
                                    class="w-full border border-gray-300 rounded-lg py-2.5 px-3 text-sm">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between items-center flex-wrap gap-3">
                    <a href="{{ route('flights.results') }}" class="ziara-btn ziara-btn--outline">
                        <i class="fas fa-arrow-left" aria-hidden="true"></i> Back to Results
                    </a>
                    <button type="submit" class="ziara-btn ziara-btn--maroon">
                        <i class="fas fa-check-circle" aria-hidden="true"></i> Confirm &amp; Book
                    </button>
                </div>
            </form>
        @endguest
    </div>
</section>

@else
<section class="zt-section zt-section--pale ziara-pricing" style="--maroon:#7b2a1b;--maroon-deep:#6b1d15;--gold:#d9a561;--gold-bright:#f3d24a;min-height:60vh;display:flex;align-items:center;">
    <div class="container" style="max-width:520px;">
        <div class="ziara-panel" style="text-align:center;padding:44px;">
            <div style="width:80px;height:80px;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;background:var(--gold-pale);color:var(--maroon);">
                <i class="fas fa-exclamation-triangle" aria-hidden="true" style="font-size:32px;"></i>
            </div>
            <h3 class="ziara-panel__title" style="font-size:28px;">No Flight Data Found</h3>
            <p style="color:var(--muted);margin-bottom:26px;">Please return to the search page and try again.</p>
            <a href="/" class="ziara-btn ziara-btn--gold">
                <i class="fas fa-search" aria-hidden="true"></i> New Search
            </a>
        </div>
    </div>
</section>
@endif
@endsection
