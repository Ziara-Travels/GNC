<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Support Request Confirmation</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #f59e0b 0%, #ea580c 100%); color: white; padding: 30px; border-radius: 8px; margin-bottom: 20px; text-align: center; }
        .content { background: white; padding: 30px; border: 1px solid #dee2e6; border-radius: 8px; }
        .footer { text-align: center; margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 8px; }
        .button { display: inline-block; padding: 12px 24px; background: #f59e0b; color: white; text-decoration: none; border-radius: 5px; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Thank You for Contacting Ziara Travels!</h2>
            <p>We've received your support request</p>
        </div>

        <div class="content">
            <p>Dear {{ $supportData['name'] }},</p>

            <p>Thank you for reaching out to us. We have successfully received your support request and our team will review it shortly.</p>

            <p><strong>Your Request Details:</strong></p>
            <ul>
                <li><strong>Subject:</strong> {{ $supportData['subject'] }}</li>
                <li><strong>Submitted:</strong> {{ $supportData['submitted_at']->format('F j, Y \a\t g:i A') }}</li>
                <li><strong>Reference:</strong> #{{ strtoupper(substr(md5($supportData['email'] . $supportData['submitted_at']), 0, 8)) }}</li>
            </ul>

            <p><strong>What happens next?</strong></p>
            <ul>
                <li>Our support team will review your request within 24 hours</li>
                <li>You'll receive a detailed response via email</li>
                <li>For urgent matters, you can call us at +254 700 123 456</li>
            </ul>

            <p>If you have any additional information or questions, please don't hesitate to reply to this email.</p>

            <a href="{{ url('/') }}" class="button">Visit Our Website</a>
        </div>

        <div class="footer">
            <p><strong>Ziara Travels Customer Support</strong></p>
            <p>Email: support@ziaratravels.com | Phone: +254 700 123 456</p>
            <p>Available 24/7 for your travel needs</p>
        </div>
    </div>
</body>
</html>