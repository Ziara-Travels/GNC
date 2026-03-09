<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Support Message</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
        .content { background: white; padding: 20px; border: 1px solid #dee2e6; border-radius: 8px; }
        .info-row { margin-bottom: 15px; }
        .label { font-weight: bold; color: #495057; }
        .value { color: #333; }
        .message-box { background: #f8f9fa; padding: 15px; border-radius: 5px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>New Support Message Received</h2>
            <p>You have received a new support message from your website.</p>
        </div>

        <div class="content">
            <div class="info-row">
                <span class="label">Name:</span>
                <span class="value">{{ $supportData['name'] }}</span>
            </div>

            <div class="info-row">
                <span class="label">Email:</span>
                <span class="value">{{ $supportData['email'] }}</span>
            </div>

            @if($supportData['phone'])
            <div class="info-row">
                <span class="label">Phone:</span>
                <span class="value">{{ $supportData['phone'] }}</span>
            </div>
            @endif

            <div class="info-row">
                <span class="label">Subject:</span>
                <span class="value">{{ $supportData['subject'] }}</span>
            </div>

            <div class="info-row">
                <span class="label">Submitted:</span>
                <span class="value">{{ $supportData['submitted_at']->format('F j, Y \a\t g:i A') }}</span>
            </div>

            <div class="message-box">
                <div class="label">Message:</div>
                <div class="value">{{ nl2br($supportData['message']) }}</div>
            </div>
        </div>
    </div>
</body>
</html>