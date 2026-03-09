<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Referral Code - Ziara Travel</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #4f46e5; color: white; padding: 20px; text-align: center; }
        .content { padding: 30px 20px; background: #f9fafb; }
        .referral-code { background: #e0f2fe; padding: 20px; border-radius: 8px; text-align: center; margin: 20px 0; }
        .code { font-size: 24px; font-weight: bold; color: #0369a1; letter-spacing: 2px; }
        .footer { background: #374151; color: white; padding: 20px; text-align: center; font-size: 14px; }
        .btn { display: inline-block; background: #4f46e5; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; margin: 15px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Your Referral Code is Ready!</h1>
        </div>
        
        <div class="content">
            <h2>Hello {{ $user->name }}!</h2>
            
            <p>Congratulations! Your referral program participation has been activated. You can now start earning rewards by referring friends and family to Ziara Travel.</p>
            
            <div class="referral-code">
                <h3>Your Unique Referral Code:</h3>
                <div class="code">{{ $code }}</div>
                <p><em>Share this code with your friends to earn rewards!</em></p>
            </div>
            
            <h3>How it Works:</h3>
            <ul>
                <li>Share your referral code with friends and family</li>
                <li>When they book using your code, you both earn rewards</li>
                <li>Track your referrals in your dashboard</li>
                <li>Earn up to ${{ $referral->reward_amount ?? 50 }} per successful referral</li>
            </ul>
            
            <p>Start sharing today and begin earning rewards with every successful referral!</p>
            
            <a href="{{ url('/dashboard/referrals') }}" class="btn">View My Dashboard</a>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Ziara Travel. All rights reserved.</p>
            <p>Questions? Contact us at support@ziaratravel.com</p>
        </div>
    </div>
</body>
</html>
