<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\User;
use App\Models\Notification;
use App\Notifications\WishlistItemAdded;
use App\Mail\WishlistNotificationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class WishlistController extends Controller
{
        // Public user wishlist page
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to view your wishlist');
        }

        $user = Auth::user();
        
        $wishlistItems = Wishlist::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        $stats = [
            'total' => $wishlistItems->total(),
            'flights' => Wishlist::where('user_id', $user->id)->where('item_type', 'flight')->count(),
            'hotels' => Wishlist::where('user_id', $user->id)->where('item_type', 'hotel')->count(),
            'tours' => Wishlist::where('user_id', $user->id)->where('item_type', 'tour')->count(),
            'destinations' => Wishlist::where('user_id', $user->id)->where('item_type', 'destination')->count(),
        ];
        
        return view('wishlist.index', compact('wishlistItems', 'stats'));
    }

        // Superadmin - View all wishlists
    public function superadminIndex()
    {
        $user = auth()->user();
        
        if (!$user->hasRole('superadmin')) {
            abort(403, 'Unauthorized');
        }

        $wishlistItems = Wishlist::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $stats = [
            'total' => Wishlist::count(),
            'total_users' => Wishlist::distinct('user_id')->count(),
            'flights' => Wishlist::where('item_type', 'flight')->count(),
            'hotels' => Wishlist::where('item_type', 'hotel')->count(),
            'tours' => Wishlist::where('item_type', 'tour')->count(),
            'destinations' => Wishlist::where('item_type', 'destination')->count(),
        ];

        return view('dashboard.wishlist.superadmin.wishlist', compact('wishlistItems', 'stats'));
    }

        // Admin - View user wishlists only
    public function adminIndex()
    {
        $user = auth()->user();
        
        if (!$user->hasRole('admin')) {
            abort(403, 'Unauthorized');
        }

        // Admin can see their own wishlist + user role wishlists
        $wishlistItems = Wishlist::with('user')
            ->whereHas('user', function($query) {
                $query->whereIn('role', ['user']);
            })
            ->orWhere('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = [
            'total' => $wishlistItems->total(),
            'total_users' => Wishlist::whereHas('user', function($query) {
                $query->where('role', 'user');
            })->distinct('user_id')->count(),
            'flights' => Wishlist::whereHas('user', function($query) {
                $query->where('role', 'user');
            })->where('item_type', 'flight')->count(),
            'hotels' => Wishlist::whereHas('user', function($query) {
                $query->where('role', 'user');
            })->where('item_type', 'hotel')->count(),
        ];

        return view('dashboard.wishlist.admin.wishlist', compact('wishlistItems', 'stats'));
    }

        // User - View own wishlist
    public function userIndex()
    {
        $user = auth()->user();
        
        if (!$user->hasRole('user')) {
            abort(403, 'Unauthorized');
        }

        $wishlistItems = Wishlist::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $stats = [
            'total' => $wishlistItems->total(),
            'flights' => Wishlist::where('user_id', $user->id)->where('item_type', 'flight')->count(),
            'hotels' => Wishlist::where('user_id', $user->id)->where('item_type', 'hotel')->count(),
            'tours' => Wishlist::where('user_id', $user->id)->where('item_type', 'tour')->count(),
            'destinations' => Wishlist::where('user_id', $user->id)->where('item_type', 'destination')->count(),
        ];

        return view('dashboard.wishlist.user.wishlist', compact('wishlistItems', 'stats'));
    }

    // Add item to wishlist
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:flight,hotel,tour,destination',
            'item_data' => 'required|array',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'price' => 'nullable|numeric',
            'original_price' => 'nullable|numeric',
            'location' => 'nullable|string|max:255',
            'item_id' => 'nullable|string'
        ]);

        if (!Auth::check()) {
            // Store in session for guest users
            $guestWishlist = session()->get('guest_wishlist', []);
            $guestWishlist[] = [
                'type' => $request->type,
                'item_data' => $request->item_data,
                'title' => $request->title,
                'description' => $request->description,
                'image' => $request->image,
                'price' => $request->price,
                'original_price' => $request->original_price,
                'location' => $request->location,
                'item_id' => $request->item_id,
                'added_at' => now()->toISOString()
            ];
            session()->put('guest_wishlist', $guestWishlist);
            
            return response()->json([
                'success' => false,
                'message' => 'Please create an account to add items to your wishlist. Your item will be saved when you sign up!',
                'require_login' => true,
                'guest_item_stored' => true
            ]);
        }

        $user = Auth::user();

        // Check if item already exists in wishlist
        $existingItem = Wishlist::where('user_id', $user->id)
            ->where('type', $request->type)
            ->where('item_id', $request->item_id)
            ->first();

        if ($existingItem) {
            return response()->json([
                'success' => false,
                'message' => 'Item already in your wishlist'
            ]);
        }

        $wishlistItem = Wishlist::create([
            'user_id' => $user->id,
            'type' => $request->type,
            'item_data' => $request->item_data,
            'title' => $request->title,
            'description' => $request->description,
            'image' => $request->image,
            'price' => $request->price,
            'original_price' => $request->original_price,
            'location' => $request->location,
            'item_id' => $request->item_id
        ]);
        
        // Send notification to admins and superadmins
        $this->notifyAdminsAndSuperAdmins($user, $wishlistItem);

        return response()->json([
            'success' => true,
            'message' => 'Item added to your wishlist successfully!'
        ]);
    }

    // Remove item from wishlist
    public function destroy($id)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ]);
        }

        $wishlistItem = Wishlist::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$wishlistItem) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found in your wishlist'
            ]);
        }

        $wishlistItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item removed from wishlist'
        ]);
    }

    // Clear all wishlist items
    public function clear()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ]);
        }

        Wishlist::where('user_id', $user->id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Wishlist cleared successfully'
        ]);
    }

    // Check if item is in wishlist
    public function checkWishlist(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['in_wishlist' => false]);
        }

        $user = Auth::user();
        
        $inWishlist = Wishlist::where('user_id', $user->id)
            ->where('type', $request->type)
            ->where('item_id', $request->item_id)
            ->exists();

        return response()->json(['in_wishlist' => $inWishlist]);
    }

    // Get wishlist count
    public function getWishlistCount()
    {
        if (!Auth::check()) {
            return response()->json(['count' => 0]);
        }

        $count = Wishlist::where('user_id', Auth::id())->count();

        return response()->json(['count' => $count]);
    }
    
    /**
     * Notify admins and superadmins about new wishlist item
     */
    private function notifyAdminsAndSuperAdmins($user, $wishlistItem)
    {
        // Get all admins and superadmins
        $admins = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['admin', 'superadmin']);
        })->get();
        
        // Prepare item details for email
        $itemDetails = [
            'type' => $wishlistItem->type,
            'price' => $wishlistItem->price,
            'location' => $wishlistItem->location,
            'notes' => $wishlistItem->description
        ];
        
        foreach ($admins as $admin) {
            // Send notification
            $admin->notify(new \App\Notifications\WishlistItemAdded($user, $wishlistItem));
            
            // Send email notification
            Mail::to($admin->email)->send(new WishlistNotificationMail($user, $wishlistItem->title, $itemDetails));
        }
    }

    /**
     * Process guest wishlist items when user logs in
     */
    public function processGuestWishlist()
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Not authenticated']);
        }

        $guestWishlist = session()->get('guest_wishlist', []);
        $addedItems = 0;
        $user = Auth::user();

        foreach ($guestWishlist as $item) {
            // Check if item already exists
            $existingItem = Wishlist::where('user_id', $user->id)
                ->where('type', $item['type'])
                ->where('item_id', $item['item_id'])
                ->first();

            if (!$existingItem) {
                $wishlistItem = Wishlist::create([
                    'user_id' => $user->id,
                    'type' => $item['type'],
                    'item_data' => $item['item_data'],
                    'title' => $item['title'],
                    'description' => $item['description'],
                    'image' => $item['image'],
                    'price' => $item['price'],
                    'original_price' => $item['original_price'],
                    'location' => $item['location'],
                    'item_id' => $item['item_id']
                ]);

                $this->notifyAdminsAndSuperAdmins($user, $wishlistItem);
                $addedItems++;
            }
        }

        // Clear guest wishlist from session
        session()->forget('guest_wishlist');

        return response()->json([
            'success' => true,
            'message' => $addedItems > 0 ? "{$addedItems} items have been added to your wishlist!" : 'No new items to add.',
            'items_added' => $addedItems
        ]);
    }

    /**
     * Get guest wishlist count
     */
    public function getGuestWishlistCount()
    {
        $guestWishlist = session()->get('guest_wishlist', []);
        return response()->json(['count' => count($guestWishlist)]);
    }

    /**
     * Clear guest wishlist
     */
    public function clearGuestWishlist()
    {
        session()->forget('guest_wishlist');
        return response()->json(['success' => true, 'message' => 'Guest wishlist cleared']);
    }
}