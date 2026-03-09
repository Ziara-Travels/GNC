<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wishlist Notification - Admin</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #dc2626; color: white; padding: 20px; text-align: center; }
        .content { padding: 30px 20px; background: #f9fafb; }
        .notification-box { background: #fee2e2; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #dc2626; }
        .user-info { background: #e5e7eb; padding: 15px; border-radius: 6px; margin: 15px 0; }
        .item-details { background: #fef3c7; padding: 15px; border-radius: 6px; margin: 15px 0; }
        .footer { background: #374151; color: white; padding: 20px; text-align: center; font-size: 14px; }
        .btn { display: inline-block; background: #dc2626; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; margin: 15px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔔 New Wishlist Item Added</h1>
        </div>
        
        <div class="content">
            <h2>Admin Notification</h2>
            
            <div class="notification-box">
                <h3>A user has added a new item to their wishlist</h3>
                <p><strong>Time:</strong> {{ $timestamp }}</p>
            </div>
            
            <div class="user-info">
                <h4>User Information:</h4>
                <p><strong>Name:</strong> {{ $user->name }}</p>
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>User ID:</strong> #{{ $user->id }}</p>
                @if($user->phone)
                    <p><strong>Phone:</strong> {{ $user->phone }}</p>
                @endif
            </div>
            
            <div class="item-details">
                <h4>Wishlist Item:</h4>
                <p><strong>Item Name:</strong> {{ $itemName }}</p>
                
                @if(isset($itemDetails['type']))
                    <p><strong>Type:</strong> {{ ucfirst($itemDetails['type']) }}</p>
                @endif
                
                @if(isset($itemDetails['price']))
                    <p><strong>Price:</strong> ${{ number_format($itemDetails['price'], 2) }}</p>
                @endif
                
                @if(isset($itemDetails['destination']))
                    <p><strong>Destination:</strong> {{ $itemDetails['destination'] }}</p>
                @endif
                
                @if(isset($itemDetails['dates']))
                    <p><strong>Dates:</strong> {{ $itemDetails['dates'] }}</p>
                @endif
                
                @if(isset($itemDetails['notes']))
                    <p><strong>Notes:</strong> {{ $itemDetails['notes'] }}</p>
                @endif
            </div>
            
            <h3>Recommended Actions:</h3>
            <ul>
                <li>Review the wishlist item for potential follow-up</li>
                <li>Consider reaching out to the user with personalized offers</li>
                <li>Track wishlist trends for marketing insights</li>
                <li>Update inventory or promotional campaigns if relevant</li>
            </ul>
            
            <a href="{{ url('/admin/wishlists') }}" class="btn">View All Wishlists</a>
            <a href="{{ url('/admin/users/' . $user->id) }}" class="btn">View User Profile</a>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Ziara Travel Admin Panel. Internal Use Only.</p>
            <p>This is an automated notification from the wishlist monitoring system.</p>
        </div>
    </div>
</body>
</html>
