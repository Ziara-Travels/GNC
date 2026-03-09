<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Data Export - Ziara Travel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            line-height: 1.6;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #3B82F6;
        }
        .section {
            margin-bottom: 30px;
            padding: 20px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
        }
        .section h2 {
            color: #3B82F6;
            margin-bottom: 15px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .data-table th,
        .data-table td {
            border: 1px solid #d1d5db;
            padding: 8px 12px;
            text-align: left;
        }
        .data-table th {
            background-color: #f3f4f6;
            font-weight: bold;
        }
        .empty-data {
            color: #6b7280;
            font-style: italic;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Your Personal Data Export</h1>
        <p>Generated on: {{ now()->format('F j, Y \a\t g:i A') }}</p>
        <p>User: {{ $userData['user_profile']['name'] ?? 'N/A' }} ({{ $userData['user_profile']['email'] ?? 'N/A' }})</p>
    </div>

    <!-- User Profile Data -->
    <div class="section">
        <h2>Profile Information</h2>
        @if(!empty($userData['user_profile']))
            <table class="data-table">
                <tr>
                    <th>Field</th>
                    <th>Value</th>
                </tr>
                <tr>
                    <td>Name</td>
                    <td>{{ $userData['user_profile']['name'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td>{{ $userData['user_profile']['email'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Phone</td>
                    <td>{{ $userData['user_profile']['phone'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Date of Birth</td>
                    <td>{{ $userData['user_profile']['date_of_birth'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Gender</td>
                    <td>{{ $userData['user_profile']['gender'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Country</td>
                    <td>{{ $userData['user_profile']['country'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Account Created</td>
                    <td>{{ $userData['user_profile']['created_at'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Last Login</td>
                    <td>{{ $userData['user_profile']['last_login_at'] ?? 'N/A' }}</td>
                </tr>
            </table>
        @else
            <p class="empty-data">No profile data available.</p>
        @endif
    </div>

    <!-- Bookings Data -->
    <div class="section">
        <h2>Booking History</h2>
        @if(!empty($userData['bookings']) && count($userData['bookings']) > 0)
            <table class="data-table">
                <tr>
                    <th>Booking Reference</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Total Amount</th>
                    <th>Date Created</th>
                </tr>
                @foreach($userData['bookings'] as $booking)
                    <tr>
                        <td>{{ $booking['reference'] ?? 'N/A' }}</td>
                        <td>{{ $booking['type'] ?? 'N/A' }}</td>
                        <td>{{ $booking['status'] ?? 'N/A' }}</td>
                        <td>{{ $booking['total_amount'] ?? 'N/A' }}</td>
                        <td>{{ $booking['created_at'] ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            </table>
        @else
            <p class="empty-data">No booking data available.</p>
        @endif
    </div>

    <!-- Payments Data -->
    <div class="section">
        <h2>Payment History</h2>
        @if(!empty($userData['payments']) && count($userData['payments']) > 0)
            <table class="data-table">
                <tr>
                    <th>Payment Reference</th>
                    <th>Amount</th>
                    <th>Currency</th>
                    <th>Status</th>
                    <th>Method</th>
                    <th>Date</th>
                </tr>
                @foreach($userData['payments'] as $payment)
                    <tr>
                        <td>{{ $payment['reference'] ?? 'N/A' }}</td>
                        <td>{{ $payment['amount'] ?? 'N/A' }}</td>
                        <td>{{ $payment['currency'] ?? 'N/A' }}</td>
                        <td>{{ $payment['status'] ?? 'N/A' }}</td>
                        <td>{{ $payment['payment_method'] ?? 'N/A' }}</td>
                        <td>{{ $payment['created_at'] ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            </table>
        @else
            <p class="empty-data">No payment data available.</p>
        @endif
    </div>

    <!-- Consent History -->
    <div class="section">
        <h2>Privacy Consent History</h2>
        @if(!empty($userData['consents']) && count($userData['consents']) > 0)
            <table class="data-table">
                <tr>
                    <th>Consent Type</th>
                    <th>Status</th>
                    <th>Given Date</th>
                    <th>Withdrawn Date</th>
                </tr>
                @foreach($userData['consents'] as $consent)
                    <tr>
                        <td>{{ $consent['consent_type'] ?? 'N/A' }}</td>
                        <td>{{ ($consent['consent_given'] ?? false) ? 'Given' : 'Withdrawn' }}</td>
                        <td>{{ $consent['consented_at'] ?? 'N/A' }}</td>
                        <td>{{ $consent['withdrawn_at'] ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            </table>
        @else
            <p class="empty-data">No consent data available.</p>
        @endif
    </div>

    <!-- Notifications Data -->
    <div class="section">
        <h2>Notification History</h2>
        @if(!empty($userData['notifications']) && count($userData['notifications']) > 0)
            <table class="data-table">
                <tr>
                    <th>Type</th>
                    <th>Title</th>
                    <th>Read Status</th>
                    <th>Date</th>
                </tr>
                @foreach($userData['notifications'] as $notification)
                    <tr>
                        <td>{{ $notification['type'] ?? 'N/A' }}</td>
                        <td>{{ $notification['data']['title'] ?? 'N/A' }}</td>
                        <td>{{ $notification['read_at'] ? 'Read' : 'Unread' }}</td>
                        <td>{{ $notification['created_at'] ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            </table>
        @else
            <p class="empty-data">No notification data available.</p>
        @endif
    </div>

    <!-- Loyalty Points -->
    <div class="section">
        <h2>Loyalty Points History</h2>
        @if(!empty($userData['loyalty_points']) && count($userData['loyalty_points']) > 0)
            <table class="data-table">
                <tr>
                    <th>Action</th>
                    <th>Points</th>
                    <th>Description</th>
                    <th>Date</th>
                </tr>
                @foreach($userData['loyalty_points'] as $loyalty)
                    <tr>
                        <td>{{ $loyalty['action'] ?? 'N/A' }}</td>
                        <td>{{ $loyalty['points'] ?? 'N/A' }}</td>
                        <td>{{ $loyalty['description'] ?? 'N/A' }}</td>
                        <td>{{ $loyalty['created_at'] ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            </table>
        @else
            <p class="empty-data">No loyalty points data available.</p>
        @endif
    </div>

    <div class="footer">
        <p>This data export was generated in compliance with GDPR Article 20 (Right to data portability).</p>
        <p>For questions about your data, contact us at privacy@Ziaratravel.com</p>
        <p>&copy; {{ date('Y') }} Ziara Travel. All rights reserved.</p>
    </div>
</body>
</html>
