<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Alert</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #f8f9fa; padding: 20px; border-radius: 8px; text-align: center; }
        .content { padding: 20px 0; }
        .alert-box { background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 5px; margin: 20px 0; }
        .details { background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0; }
        .footer { text-align: center; color: #666; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>🔐 Login Alert</h2>
        </div>
        
        <div class="content">
            <p>Hello {{ $user->name }},</p>
            
            <div class="alert-box">
                <strong>⚠️ New login detected!</strong><br>
                We detected a login to your account from a new device or location.
            </div>
            
            <div class="details">
                <h3>Login Details:</h3>
                <p><strong>Time:</strong> {{ now()->format('F j, Y \a\t g:i A') }}</p>
                <p><strong>IP Address:</strong> {{ $ipAddress }}</p>
                <p><strong>Device/Browser:</strong> {{ $userAgent }}</p>
            </div>
            
            <p>If this was you, you can safely ignore this email.</p>
            
            <p>If you didn't log in, please:</p>
            <ul>
                <li>Change your password immediately</li>
                <li>Review your account activity</li>
                <li>Contact support if you need assistance</li>
            </ul>
        </div>
        
        <div class="footer">
            <p>This is an automated security notification from {{ config('app.name') }}</p>
        </div>
    </div>
</body>
</html>