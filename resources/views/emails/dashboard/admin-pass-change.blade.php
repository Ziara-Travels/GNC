<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Password Change Notification</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #2196F3; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f9f9f9; }
        .user-info { background: white; padding: 15px; border-left: 4px solid #2196F3; margin: 15px 0; }
        .footer { padding: 10px; text-align: center; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>User Password Change Alert</h1>
        </div>
        <div class="content">
            <p>Hello {{ $admin->name }},</p>
            
            <p>This is to notify you that a user has changed their password in the system.</p>
            
            <div class="user-info">
                <h3>User Details:</h3>
                <p><strong>Name:</strong> {{ $user->name }}</p>
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>Change Time:</strong> {{ $changeTime }}</p>
            </div>
            
            <p>This is an automated notification for your records. No action is required unless you suspect unauthorized activity.</p>
            
            <p>Best regards,<br>{{ config('app.name') }} System</p>
        </div>
        <div class="footer">
            <p>This is an automated administrative notification.</p>
        </div>
    </div>
</body>
</html>