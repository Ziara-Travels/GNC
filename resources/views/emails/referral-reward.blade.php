<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Referral Reward - Ziara Travel</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #7c3aed; color: white; padding: 20px; text-align: center; }
        .content { padding: 30px 20px; background: #f9fafb; }
        .reward-box { background: #ede9fe; padding: 20px; border-radius: 8px; text-align: center; margin: 20px 0; border-left: 4px solid #7c3aed; }
        .reward-amount { font-size: 32px; font-weight: bold; color: #7c3aed; }
        .status-badge { display: inline-block; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; text-transform: uppercase; }
        .status-approved { background: #d1fae5; color: #065f46; }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-completed { background: #dbeafe; color: #1e40af; }
        .footer { background: #374151; color: white; padding: 20px; text-align: center; font-size: 14px; }
        .btn { display: inline-block; background: #7c3aed; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; margin: 15px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>💰 Your Referral Reward!</h1>
        </div>
        
        <div class="content">
            <h2>Hello {{ $user->name }}!</h2>
            
            @if($status === 'approved')
                <p>Fantastic news! Your referral reward has been approved and is ready for processing.</p>
            @elseif($status === 'completed')
                <p>Congratulations! Your referral reward has been successfully processed and credited to your account.</p>
            @else
                <p>We're pleased to inform you about the status update on your referral reward.</p>
            @endif
            
            <div class="reward-box">
                <h3>Reward Amount:</h3>
                <div class="reward-amount">${{ number_format($rewardAmount, 2) }}</div>
                <p>
                    <span class="status-badge status-{{ $status }}">
                        {{ ucfirst($status) }}
                    </span>
                </p>
            </div>
            
            <h3>Referral Information:</h3>
            <ul>
                <li><strong>Referral Code:</strong> {{ $referral->code }}</li>
                <li><strong>Status:</strong> {{ ucfirst($referral->status) }}</li>
                <li><strong>Reward Amount:</strong> ${{ number_format($referral->reward_amount, 2) }}</li>
                <li><strong>Date Created:</strong> {{ $referral->created_at->format('M d, Y') }}</li>
                @if($referral->completed_at)
                    <li><strong>Completed:</strong> {{ $referral->completed_at->format('M d, Y H:i') }}</li>
                @endif
            </ul>
            
            @if($status === 'approved')
                <h3>Next Steps:</h3>
                <ul>
                    <li>Your reward will be processed within 3-5 business days</li>
                    <li>You'll receive a confirmation once the payment is sent</li>
                    <li>Check your payment method for the credited amount</li>
                </ul>
            @elseif($status === 'completed')
                <p><strong>🎉 Your reward has been successfully credited to your account!</strong></p>
                <p>Thank you for being an amazing part of the Ziara Travel community.</p>
            @endif
            
            <a href="{{ url('/dashboard/referrals') }}" class="btn">View My Dashboard</a>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Ziara Travel. All rights reserved.</p>
            <p>Questions about your reward? Contact us at rewards@ziaratravel.com</p>
        </div>
    </div>
</body>
</html>
