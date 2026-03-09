<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Referral Success - Ziara Travel</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #059669; color: white; padding: 20px; text-align: center; }
        .content { padding: 30px 20px; background: #f9fafb; }
        .success-box { background: #d1fae5; padding: 20px; border-radius: 8px; text-align: center; margin: 20px 0; border-left: 4px solid #059669; }
        .reward-amount { font-size: 28px; font-weight: bold; color: #059669; }
        .footer { background: #374151; color: white; padding: 20px; text-align: center; font-size: 14px; }
        .btn { display: inline-block; background: #059669; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; margin: 15px 0; }
        .user-info { background: #e5e7eb; padding: 15px; border-radius: 6px; margin: 15px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Referral Success!</h1>
        </div>
        
        <div class="content">
            <h2>Congratulations {{ $referrer->name }}!</h2>
            
            <p>Great news! Your referral has been successful. Someone has used your referral code and completed their booking.</p>
            
            <div class="success-box">
                <h3>You've Earned:</h3>
                <div class="reward-amount">${{ number_format($rewardAmount, 2) }}</div>
                <p><em>Referral reward for successful booking</em></p>
            </div>
            
            <div class="user-info">
                <h4>Referral Details:</h4>
                <p><strong>Referred User:</strong> {{ $referredUser->name ?? 'New Customer' }}</p>
                <p><strong>Referral Code:</strong> {{ $referral->code }}</p>
                <p><strong>Status:</strong> {{ ucfirst($referral->status) }}</p>
                <p><strong>Date:</strong> {{ $referral->created_at->format('M d, Y H:i') }}</p>
            </div>
            
            <h3>What's Next?</h3>
            <ul>
                <li>Your reward will be processed within 3-5 business days</li>
                <li>Check your dashboard for detailed referral statistics</li>
                <li>Keep sharing your code to earn more rewards</li>
                <li>Invite more friends to maximize your earnings</li>
            </ul>
            
            <p>Thank you for being an amazing advocate for Ziara Travel!</p>
            
            <a href="{{ url('/dashboard/referrals') }}" class="btn">View My Referrals</a>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Ziara Travel. All rights reserved.</p>
            <p>Questions about your referral? Contact us at referrals@ziaratravel.com</p>
        </div>
    </div>
</body>
</html>
