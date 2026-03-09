@extends('layouts.main')

@section('content')
<div class="email-container">
    <div class="header">
        <h1>New Custom Trip Request</h1>
    </div>
    
    <div class="content">
        <p>A new custom trip request has been submitted through the website.</p>
        
        <div class="request-details" style="background-color: #f8fafc; border-left: 4px solid #0891b2; padding: 15px; margin: 20px 0;">
            <h3>Customer Details:</h3>
            <ul style="list-style: none; padding-left: 0;">
                <li><strong>Name:</strong> {{ $customTrip->name }}</li>
                <li><strong>Email:</strong> {{ $customTrip->email }}</li>
                <li><strong>Phone:</strong> {{ $customTrip->phone }}</li>
                <li><strong>Submitted:</strong> {{ $customTrip->created_at->format('M d, Y H:i') }}</li>
                <li><strong>Travel Timeframe:</strong> {{ $customTrip->travel_timing ?: 'Not specified' }}</li>
                <li><strong>Newsletter Opt-in:</strong> {{ $customTrip->subscribe_newsletter ? 'Yes' : 'No' }}</li>
                <li><strong>User Account:</strong> {{ $customTrip->user_id ? 'Yes (ID: '.$customTrip->user_id.')' : 'No' }}</li>
            </ul>
            
            <h3>Trip Request:</h3>
            <div style="background-color: white; padding: 10px; border-radius: 5px;">
                {{ $customTrip->description }}
            </div>
        </div>
        
        <p>Please follow up with this customer within the next 24 hours.</p>
        
        <div class="actions" style="text-align: center; margin-top: 30px;">
            <a href="{{ route('admin.custom-trips.show', $customTrip->id) }}" style="background-color: #0891b2; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">View in Admin Panel</a>
        </div>
    </div>
</div>
@endsection