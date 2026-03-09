{{-- resources/views/emails/password-changed.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Password Changed Successfully</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #4CAF50; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f9f9f9; }
        .footer { padding: 10px; text-align: center; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Password Changed Successfully</h1>
        </div>
        <div class="content">
            <p>Hello {{ $user->name }},</p>
            
            <p>This email confirms that your password was successfully changed on {{ $changeTime }}.</p>
            
            <p>If you did not make this change, please contact our support team immediately.</p>
            
            <p>For your security:</p>
            <ul>
                <li>Never share your password with anyone</li>
                <li>Use a strong, unique password</li>
                <li>Log out from shared computers</li>
            </ul>
            
            <p>Thank you for keeping your account secure!</p>
            
            <p>Best regards,<br>{{ config('app.name') }} Team</p>
        </div>
        <div class="footer">
            <p>This is an automated message. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>