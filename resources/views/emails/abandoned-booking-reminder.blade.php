<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Your Booking</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 0 auto; background-color: white; }
        .header { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 30px; text-align: center; }
        .content { padding: 30px; }
        .flight-info { background-color: #f8f9fa; border-radius: 8px; padding: 20px; margin: 20px 0; }
        .hotel-info { background-color: #f8f9fa; border-radius: 8px; padding: 20px; margin: 20px 0; }
        .discount-box { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 8px; padding: 20px; margin: 20px 0; text-align: center; }
        .footer { background-color: #2d3748; color: white; padding: 20px; text-align: center; }
        .button { display: inline-block; background-color: #f5576c; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; margin: 10px 0; }
        .button-primary { background-color: #667eea; }
        .draft-ref { font-size: 20px; font-weight: bold; color: #f5576c; }
        .status-badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; }
        .incomplete { background-color: #fff3cd; color: #856404; }
        .discount-code { font-size: 32px; font-weight: bold; letter-spacing: 2px; margin: 10px 0; }
        .timer { background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0; }
        .urgency { background-color: #f8d7da; border-left: 4px solid #dc3545; padding: 15px; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>⏰ Don't Miss Out!</h1>
            <p>Your booking is waiting for you</p>
        </div>
        
        <div class="content">
            <div style="text-align: center; margin-bottom: 30px;">
                <h2>Hi {{ $draft->passenger_name ?? 'Traveler' }}!</h2>
                <p style="font-size: 16px; color: #666;">You started booking with us but didn't complete it. We've saved your details!</p>
                <div class="draft-ref">Draft: {{ $draft->session_id }}</div>
                <div class="status-badge incomplete">INCOMPLETE</div>
            </div>

            <div class="urgency">
                <h3 style="color: #721c24; margin-top: 0;">🚨 Limited Time Offer</h3>
                <p style="color: #721c24; margin: 0;">Complete your booking within the next 24 hours and save with our exclusive discount!</p>
            </div>

            <div class="discount-box">
                <h3 style="margin-top: 0;">🎉 Special Discount Just For You!</h3>
                <div class="discount-code">{{ $discountCode }}</div>
                <p style="margin: 0;">Get {{ $discountAmount }} off your booking!</p>
                <p style="font-size: 14px; opacity: 0.9; margin: 10px 0 0 0;">Valid until {{ $expiryTime }}</p>
            </div>

            @if($flightDetails)
                <div class="flight-info">
                    <h3>✈️ Your Flight Details</h3>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <strong>{{ $flightDetails['from'] ?? 'Departure' }}</strong>
                            <div style="font-size: 14px; color: #666;">{{ $flightDetails['departure_time'] ?? 'Time TBD' }}</div>
                            <div style="font-size: 12px; color: #999;">{{ $flightDetails['departure_date'] ?? 'Date TBD' }}</div>
                        </div>
                        <div style="text-align: center;">
                            <div>✈️</div>
                            <div style="font-size: 12px; color: #666;">{{ $flightDetails['duration'] ?? 'Duration' }}</div>
                        </div>
                        <div style="text-align: right;">
                            <strong>{{ $flightDetails['to'] ?? 'Arrival' }}</strong>
                            <div style="font-size: 14px; color: #666;">{{ $flightDetails['arrival_time'] ?? 'Time TBD' }}</div>
                            <div style="font-size: 12px; color: #999;">{{ $flightDetails['arrival_date'] ?? 'Date TBD' }}</div>
                        </div>
                    </div>
                    
                    <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #dee2e6;">
                        <div style="display: flex; justify-content: space-between;">
                            <span>Airline:</span>
                            <span><strong>{{ $flightDetails['airline'] ?? 'TBD' }}</strong></span>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span>Flight Number:</span>
                            <span><strong>{{ $flightDetails['flight_number'] ?? 'TBD' }}</strong></span>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span>Class:</span>
                            <span><strong>{{ $flightDetails['class'] ?? 'Economy' }}</strong></span>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span>Estimated Price:</span>
                            <span><strong>${{ $flightDetails['price'] ?? '0' }}</strong></span>
                        </div>
                    </div>
                </div>
            @endif

            @if($hotelDetails)
                <div class="hotel-info">
                    <h3>🏨 Your Hotel Details</h3>
                    <div style="margin-bottom: 15px;">
                        <strong style="font-size: 18px;">{{ $hotelDetails['name'] ?? 'Hotel Name' }}</strong>
                        <div style="color: #666; font-size: 14px;">{{ $hotelDetails['location'] ?? 'Location' }}</div>
                        <div style="color: #999; font-size: 12px;">⭐ {{ $hotelDetails['rating'] ?? 'Rating' }} stars</div>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
                        <div>
                            <strong>Check-in</strong>
                            <div style="font-size: 14px; color: #666;">{{ $hotelDetails['check_in'] ?? 'Date TBD' }}</div>
                        </div>
                        <div style="text-align: center;">
                            <div>📅</div>
                            <div style="font-size: 12px; color: #666;">{{ $hotelDetails['nights'] ?? '0' }} night(s)</div>
                        </div>
                        <div style="text-align: right;">
                            <strong>Check-out</strong>
                            <div style="font-size: 14px; color: #666;">{{ $hotelDetails['check_out'] ?? 'Date TBD' }}</div>
                        </div>
                    </div>
                    
                    <div style="padding-top: 15px; border-top: 1px solid #dee2e6;">
                        <div style="display: flex; justify-content: space-between;">
                            <span>Room Type:</span>
                            <span><strong>{{ $hotelDetails['room_type'] ?? 'Standard' }}</strong></span>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span>Guests:</span>
                            <span><strong>{{ $hotelDetails['guests'] ?? '1' }} guest(s)</strong></span>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span>Estimated Price:</span>
                            <span><strong>${{ $hotelDetails['price'] ?? '0' }}</strong></span>
                        </div>
                    </div>
                </div>
            @endif

            <div class="timer">
                <h4 style="color: #856404; margin-top: 0;">⏰ Time is Running Out!</h4>
                <p style="color: #856404; margin: 0;">Your discount expires in 24 hours. Don't let this amazing deal slip away!</p>
            </div>

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $resumeUrl }}" class="button button-primary" style="font-size: 16px; padding: 15px 30px;">
                    Complete My Booking Now
                </a>
                <br>
                <a href="{{ route('search') }}" class="button">
                    Search Different Options
                </a>
            </div>

            <div style="background-color: #e8f5e8; padding: 20px; border-radius: 8px; margin: 20px 0;">
                <h3 style="color: #2e7d32; margin-top: 0;">✨ Why Complete Your Booking?</h3>
                <ul style="margin: 0; padding-left: 20px; color: #2e7d32;">
                    <li>🔒 Secure your preferred dates and times</li>
                    <li>💰 Lock in current prices (prices may increase)</li>
                    <li>🎁 Get {{ $discountAmount }} off with code: <strong>{{ $discountCode }}</strong></li>
                    <li>📧 Instant confirmation and e-tickets</li>
                    <li>🏆 Earn loyalty points for future savings</li>
                </ul>
            </div>

            <div style="background-color: #fff3cd; padding: 15px; border-radius: 8px; border-left: 4px solid #ffc107;">
                <h4 style="color: #856404; margin-top: 0;">🤝 Need Help?</h4>
                <p style="color: #856404; margin: 0;">Our travel experts are here to help! Contact us at {{ config('mail.support_email', 'support@example.com') }} or call our 24/7 hotline.</p>
            </div>

            <div style="text-align: center; margin: 30px 0; padding: 20px; background-color: #f8f9fa; border-radius: 8px;">
                <h4 style="margin-top: 0; color: #333;">📱 Continue on Mobile</h4>
                <p style="color: #666; margin: 10px 0;">Scan the QR code or click the link to continue on your mobile device</p>
                <a href="{{ $resumeUrl }}" class="button">Continue on Mobile</a>
            </div>
        </div>
        
        <div class="footer">
            <p>We're here to make your travel dreams come true!</p>
            <p>{{ config('app.name') }} - Your Travel Partner</p>
            <p style="font-size: 12px; color: #a0aec0;">
                This is an automated email. Please do not reply to this message.
            </p>
            <p style="font-size: 11px; color: #a0aec0; margin-top: 15px;">
                Don't want to receive these emails? <a href="{{ route('unsubscribe', $draft->session_id) }}" style="color: #a0aec0;">Unsubscribe here</a>
            </p>
        </div>
    </div>
</body>
</html>