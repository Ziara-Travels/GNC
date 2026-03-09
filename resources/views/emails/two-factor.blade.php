<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Two-Factor Authentication Code - Ziara Travel</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #1e40af; color: white; padding: 20px; text-align: center; }
        .content { padding: 30px 20px; background: #f9fafb; }
        .code-box { background: #dbeafe; padding: 20px; border-radius: 8px; text-align: center; margin: 20px 0; border-left: 4px solid #1e40af; }
        .verification-code { font-size: 36px; font-weight: bold; color: #1e40af; letter-spacing: 8px; font-family: 'Courier New', monospace; }
        .security-notice { background: #fef3c7; padding: 15px; border-radius: 6px; margin: 15px 0; border-left: 4px solid #d97706; }
        .footer { background: #374151; color: white; padding: 20px; text-align: center; font-size: 14px; }
        .btn { display: inline-block; background: #1e40af; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; margin: 15px 0; }
        .timer { color: #dc2626; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔐 Two-Factor Authentication</h1>
        </div>
        
        <div class="content">
            <h2>Hello {{ $user->name }}!</h2>
            
            @if($action === 'verification')
                <p>You've requested to sign in to your Ziara Travel account. To complete the authentication process, please use the verification code below:</p>
            @else
                <p>You're setting up Two-Factor Authentication for your Ziara Travel account. Use the code below to complete the setup:</p>
            @endif
            
            <div class="code-box">
                <h3>Your {{ $action === 'verification' ? 'Verification' : 'Setup' }} Code:</h3>
                <div class="verification-code">{{ $code }}</div>
                <p class="timer">⏰ Expires in 10 minutes ({{ $expiresAt }})</p>
            </div>
            
            @if($action === 'verification')
                <h3>How to Use This Code:</h3>
                <ol>
                    <li>Return to the login page where you were prompted for 2FA</li>
                    <li>Enter the 6-digit code exactly as shown above</li>
                    <li>Click "Verify" to complete your sign-in</li>
                    <li>You'll be redirected to your dashboard upon success</li>
                </ol>
            @else
                <h3>Setting Up 2FA:</h3>
                <ol>
                    <li>Enter this code in the 2FA setup form</li>
                    <li>Save your backup codes in a secure location</li>
                    <li>Complete the setup process</li>
                    <li>Your account will be more secure going forward</li>
                </ol>
            @endif
            
            <div class="security-notice">
                <h4>🛡️ Security Notice:</h4>
                <ul>
                    <li><strong>Never share this code</strong> with anyone, including Ziara Travel staff</li>
                    <li>This code expires in <strong>10 minutes</strong> for your security</li>
                    <li>If you didn't request this code, please contact support immediately</li>
                    <li>Ensure you're on the official Ziara Travel website before entering the code</li>
                </ul>
            </div>
            
            <h3>Didn't Request This?</h3>
            <p>If you didn't request this verification code, please:</p>
            <ul>
                <li>Change your password immediately</li>
                <li>Contact our security team</li>
                <li>Review your recent account activity</li>
            </ul>
            
            <a href="{{ url('/login') }}" class="btn">Go to Login Page</a>
            <a href="{{ url('/contact') }}" class="btn">Contact Support</a>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Ziara Travel. All rights reserved.</p>
            <p>For security questions, contact us at security@ziaratravel.com</p>
            <p>This is an automated security message. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
