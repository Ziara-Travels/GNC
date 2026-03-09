<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your GDPR Data Export - Ziara Travel</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #1f2937; color: white; padding: 20px; text-align: center; }
        .content { padding: 30px 20px; background: #f9fafb; }
        .export-box { background: #e0f2fe; padding: 20px; border-radius: 8px; text-align: center; margin: 20px 0; border-left: 4px solid #0369a1; }
        .security-notice { background: #fef3c7; padding: 15px; border-radius: 6px; margin: 15px 0; border-left: 4px solid #d97706; }
        .footer { background: #374151; color: white; padding: 20px; text-align: center; font-size: 14px; }
        .btn { display: inline-block; background: #1f2937; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; margin: 15px 0; }
        .data-list { background: #f3f4f6; padding: 15px; border-radius: 6px; margin: 15px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📋 Your GDPR Data Export</h1>
        </div>
        
        <div class="content">
            <h2>Hello {{ $user->name }}!</h2>
            
            <p>Your request for a personal data export under GDPR Article 15 (Right of Access) has been processed successfully.</p>
            
            <div class="export-box">
                <h3>Data Export Ready</h3>
                <p><strong>Export Date:</strong> {{ $exportDate }}</p>
                <p><strong>User:</strong> {{ $user->email }}</p>
                @if($exportPath)
                    <p><strong>File:</strong> Available for download</p>
                @endif
            </div>
            
            <div class="data-list">
                <h4>Your Export Includes:</h4>
                <ul>
                    <li><strong>Personal Information:</strong> Name, email, phone, address</li>
                    <li><strong>Account Details:</strong> Registration date, last login, preferences</li>
                    <li><strong>Booking History:</strong> All your travel bookings and transactions</li>
                    <li><strong>Profile Data:</strong> Settings, preferences, and customizations</li>
                    <li><strong>Communication Records:</strong> Support tickets and correspondence</li>
                    <li><strong>Consent Records:</strong> Marketing permissions and privacy settings</li>
                    <li><strong>Technical Data:</strong> Login history and device information (anonymized)</li>
                </ul>
            </div>
            
            <div class="security-notice">
                <h4>🔒 Security & Privacy Notice:</h4>
                <ul>
                    <li>This data export is for your personal use only</li>
                    <li>The download link will expire in 7 days for security</li>
                    <li>Please store this information securely</li>
                    <li>Do not share your personal data export with others</li>
                    <li>Contact us if you have any questions about your data</li>
                </ul>
            </div>
            
            <h3>Your GDPR Rights:</h3>
            <ul>
                <li><strong>Right to Rectification:</strong> Update incorrect information</li>
                <li><strong>Right to Erasure:</strong> Request deletion of your data</li>
                <li><strong>Right to Restrict Processing:</strong> Limit how we use your data</li>
                <li><strong>Right to Data Portability:</strong> Transfer your data elsewhere</li>
                <li><strong>Right to Object:</strong> Opt out of certain data processing</li>
            </ul>
            
            @if($exportPath)
                <a href="{{ url('/gdpr/download-export?token=' . encrypt($user->id)) }}" class="btn">Download Your Data</a>
            @endif
            
            <a href="{{ url('/gdpr') }}" class="btn">Manage Privacy Settings</a>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Ziara Travel. All rights reserved.</p>
            <p>Questions about your data? Contact our DPO at privacy@ziaratravel.com</p>
            <p>This export was generated in compliance with GDPR regulations.</p>
        </div>
    </div>
</body>
</html>
