@extends('layouts.main')

@section('content')
<div class="email-container">
    <div class="header">
        <h1>Custom Trip Request Received</h1>
    </div>
    
    <div class="content">
        <p>Dear {{ $customTrip->name }},</p>
        
        <p>Thank you for sharing your travel dreams with us! We've received your custom trip request and are excited to help create your perfect East African adventure.</p>
        
        <div class="request-summary">
            <h3>Your Request Details:</h3>
            <ul>
                <li><strong>Name:</strong> {{ $customTrip->name }}</li>
                <li><strong>Timeframe:</strong> {{ $customTrip->travel_timing ?: 'Not specified' }}</li>
                <li><strong>Description:</strong> {{ $customTrip->description }}</li>
            </ul>
        </div>
        
        <p>What happens next?</p>
        
        <ol>
            <li>One of our travel experts will review your request</li>
            <li>We'll contact you within 24 hours to discuss your travel preferences in more detail</li>
            <li>Our team will craft a personalized itinerary and quote based on your requirements</li>
        </ol>
        
        <p>In the meantime, feel free to explore our <a href="{{ route('home') }}">travel guides and destinations</a> for inspiration.</p>
        
        <div class="signature">
            <p>Safe travels,<br>The Ziara Travel Team</p>
        </div>
    </div>
</div>
@endsection