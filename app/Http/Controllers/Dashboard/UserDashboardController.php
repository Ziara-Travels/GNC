<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Booking;
use App\Models\Flight;
use App\Models\Hotel;
use App\Models\Activity;
use App\Models\Wishlist;
use App\Models\Notification;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Referral;
use App\Models\Reward;
use App\Models\Payment;

class UserDashboardController extends Controller
{
    public function __construct()
{
    $this->middleware('auth');
    $this->middleware(function ($request, $next) {
        if (auth()->check() && auth()->user()->role !== 'user') {
        }
        return $next($request);
    });
}

    // =====================================
    // DASHBOARD SECTION
    // =====================================
    
    /**
     * Main Dashboard Overview
     */
   
    public function index()
    {
        $user = auth()->user();

        // Only regular users should access this method
        if ($user->role !== 'user') {
            abort(403, 'Unauthorized');
        }

        try {
            // Get all user bookings from unified table
            $userBookings = Booking::where('user_id', $user->id)->latest()->get();
            $userFlightBookings = $userBookings->where('type', 'flight');
            $userHotelBookings = $userBookings->where('type', 'hotel');
            $userTourBookings = $userBookings->where('type', 'tour');
            
            // Get payments
            $userPayments = Payment::where('user_id', $user->id)->latest()->get();
            
            // Get notifications
            $userNotifications = Notification::where('user_id', $user->id)->latest()->take(10)->get();
            
            // Try to get activities (may not exist in all setups)
            $userActivities = collect();
            try {
                if (class_exists('\App\Models\Activity')) {
                    $userActivities = \App\Models\Activity::where('user_id', $user->id)->latest()->take(15)->get();
                }
            } catch (\Exception $e) {
                // Activity model doesn't exist, use empty collection
                $userActivities = collect();
            }

            // Calculate spending from both payments and booking tables
            $paymentSpent = $userPayments->where('status', 'completed')->sum('amount');
            $bookingSpent = $userBookings->where('payment_status', 'paid')->sum('total_price');
            $totalSpent = max($paymentSpent, $bookingSpent); // Use the higher value

            // User statistics
            $stats = [
                'total_bookings' => $userBookings->count(),
                'flight_bookings' => $userFlightBookings->count(),
                'hotel_bookings' => $userHotelBookings->count(),
                'tour_bookings' => $userTourBookings->count(),
                'pending_bookings' => $userBookings->where('booking_status', 'pending')->count(),
                'completed_bookings' => $userBookings->where('booking_status', 'confirmed')->count(),
                'cancelled_bookings' => $userBookings->where('booking_status', 'cancelled')->count(),
                'total_spent' => $totalSpent,
                'pending_payments' => $userPayments->where('status', 'pending')->sum('amount'),
                'total_notifications' => $userNotifications->count(),
                'unread_notifications' => $userNotifications->where('read_at', null)->count(),
                'total_searches' => $userActivities->where('action', 'search_performed')->count(),
                'profile_completion' => $this->calculateProfileCompletion($user),
            ];

            // Travel statistics based on actual bookings
            $uniqueDestinations = collect();
            $uniqueCountries = collect();
            
            // Collect destinations from flight bookings
            foreach ($userFlightBookings as $booking) {
                if ($booking->destination_city) {
                    $uniqueDestinations->push($booking->destination_city);
                }
                if ($booking->country) {
                    $uniqueCountries->push($booking->country);
                }
            }
            
            // Collect destinations from hotel bookings
            foreach ($userHotelBookings as $booking) {
                if ($booking->city) {
                    $uniqueDestinations->push($booking->city);
                }
                if ($booking->country) {
                    $uniqueCountries->push($booking->country);
                }
            }
            
            // Collect destinations from tour bookings
            foreach ($userTourBookings as $booking) {
                if ($booking->destination) {
                    $uniqueDestinations->push($booking->destination);
                }
                if ($booking->country) {
                    $uniqueCountries->push($booking->country);
                }
            }

            $travelStats = [
                'countries_visited' => $uniqueCountries->unique()->count(),
                'cities_visited' => $uniqueDestinations->unique()->count(),
                'total_miles' => $this->calculateTotalMiles($user->id),
                'loyalty_points' => $this->getLoyaltyPoints($user->id),
                'average_trip_cost' => $userBookings->count() > 0 ? round($totalSpent / $userBookings->count(), 2) : 0,
            ];

            // Recent activities and bookings - Use paginated query for recent bookings
            $recentBookings = Booking::where('user_id', $user->id)
                ->latest()
                ->take(5)
                ->get(); // Keep as collection for dashboard display
            
            // Get paginated flight bookings for dashboard table
            $flight_bookings = $userFlightBookings->take(10);
            $recentFlightBookings = $userFlightBookings->take(5);
            $recentHotelBookings = $userHotelBookings->take(5);
            $recentTourBookings = $userTourBookings->take(5);
            $recentPayments = $userPayments->take(5);

            // Chart data for user dashboard
            $chartData = [
                'bookingTrends' => $this->getBookingTrends($user->id),
                'spendingTrends' => $this->getSpendingTrends($user->id),
                'travelMonths' => $this->getTravelMonthlyData($user->id),
            ];

            // Top destinations for user
            $topDestinations = $this->getUserTopDestinations($user->id);

            // Upcoming trips
            $upcomingTrips = $this->getUpcomingTrips($user->id);

            $flightData = session('flight_data');
            $priceKes = session('price_kes');

            // Get recent activities for the dashboard
            $recentActivities = $this->getRecentActivities($user->id, 10);

            // Get user's wishlist
            $userWishlist = $this->getUserWishlist($user->id);

            $data = [
                'user' => $user,
                'stats' => $stats,
                'travelStats' => $travelStats,
                'recentBookings' => $recentBookings, // Collection for dashboard display
                'flight_bookings' => $flight_bookings, // Add paginated flight bookings
                'recentFlightBookings' => $recentFlightBookings,
                'recentHotelBookings' => $recentHotelBookings,
                'recentPayments' => $recentPayments,
                'recentActivities' => $recentActivities,
                'userWishlist' => $userWishlist,
                'notifications' => $userNotifications,
                'chartData' => $chartData,
                'topDestinations' => $topDestinations,
                'upcomingTrips' => $upcomingTrips,
                'flightData' => $flightData,
                'priceKes' => $priceKes,
                'role' => 'user',
            ];

            // Log dashboard access
            $this->logActivity('dashboard_access', 'User accessed main dashboard');

            return view('dashboard.user', $data);

        } catch (\Exception $e) {
            \Log::error('User dashboard error: ' . $e->getMessage());

            // Return fallback data on error
            $fallbackData = [
                'user' => $user,
                'stats' => [
                    'total_bookings' => 0,
                    'flight_bookings' => 0,
                    'hotel_bookings' => 0,
                    'pending_bookings' => 0,
                    'completed_bookings' => 0,
                    'cancelled_bookings' => 0,
                    'total_spent' => 0,
                    'pending_payments' => 0,
                    'total_notifications' => 0,
                    'unread_notifications' => 0,
                ],
                'travelStats' => [
                    'countries_visited' => 0,
                    'cities_visited' => 0,
                    'total_miles' => 0,
                    'loyalty_points' => 0,
                    'average_trip_cost' => 0,
                ],
                'recentBookings' => collect(),
                'recentFlightBookings' => collect(),
                'recentHotelBookings' => collect(),
                'recentPayments' => collect(),
                'recentActivities' => collect(),
                'notifications' => collect(),
                'chartData' => [],
                'topDestinations' => [],
                'upcomingTrips' => collect(),
                'flightData' => null,
                'priceKes' => null,
                'role' => 'user',
            ];

            return view('dashboard.user', $fallbackData);
        }
    }

    /**
     * Dashboard Home Page
     */
    public function home()
    {
        return $this->index();
    }

    

    // =====================================
    // BOOKINGS MANAGEMENT SECTION
    // =====================================

       /**
     * Bookings overview for current user
     */
    public function myBookings(Request $request)
    {
        $user = Auth::user();
        $filter = $request->get('filter', 'all');

        $query = Booking::with(['user', 'flight', 'hotel'])
            ->where('user_id', $user->id);

        if ($filter !== 'all') {
            $query->where('status', $filter);
        }

        $bookings = $query->latest()->paginate(15);
        
        $stats = [
            'all' => Booking::where('user_id', $user->id)->count(),
            'completed' => Booking::where('user_id', $user->id)->where('status', 'completed')->count(),
            'pending' => Booking::where('user_id', $user->id)->where('status', 'pending')->count(),
            'cancelled' => Booking::where('user_id', $user->id)->where('status', 'cancelled')->count(),
            'processing' => Booking::where('user_id', $user->id)->where('status', 'processing')->count(),
        ];

        $this->logActivity('view_bookings', 'Viewed my bookings');

        return view('dashboard.bookings.user.bookings', compact('bookings', 'stats', 'filter'));
    }

   

   

    /**
     * All bookings history
     */
    public function bookingsAll(Request $request)
    {
        $filter = $request->get('filter', 'all');
        
        $query = Booking::with(['user', 'flight', 'hotel']);
        
        if ($filter !== 'all') {
            $query->where('status', $filter);
        }

        $bookings = $query->latest()->paginate(15);
        $stats = $this->getBookingStats();

        return view('superadmin.bookings.all', compact('bookings', 'stats', 'filter'));
    }

    private function getBookingStats()
    {
        $userId = auth()->id();
        
        // Use short cache time to ensure data is relatively fresh but avoids excessive database queries
        return \Cache::remember('booking_stats_user_' . $userId, 5, function () use ($userId) {
            return [
                'all' => Booking::where('user_id', $userId)->count(),
                'completed' => Booking::where('user_id', $userId)->where('status', 'completed')->count(),
                'failed' => Booking::where('user_id', $userId)->where('status', 'failed')->count(),
                'processing' => Booking::where('user_id', $userId)->where('status', 'processing')->count(),
                'last_updated' => now()->format('Y-m-d H:i:s')
            ];
        });
    }

    // =====================================
    // FLIGHTS MANAGEMENT SECTION
    // =====================================

   

  

    /**
     * User flights management
     */
    public function myFlights(Request $request)
    {
        $user = Auth::user();
        $filter = $request->get('filter', 'all');

        $query = Booking::with(['flight', 'user'])
            ->where('user_id', $user->id)
            ->where('type', 'flight');

        if ($filter !== 'all') {
            $query->where('status', $filter);
        }

        $flightBookings = $query->latest()->paginate(15);
        
        $stats = [
            'total_flights' => Booking::where('user_id', $user->id)->where('type', 'flight')->count(),
            'upcoming_flights' => Booking::where('user_id', $user->id)
                ->where('type', 'flight')
                ->where('status', 'confirmed')
                ->whereHas('flight', function($q) {
                    $q->where('departure_date', '>', now());
                })->count(),
            'completed_flights' => Booking::where('user_id', $user->id)
                ->where('type', 'flight')
                ->where('status', 'completed')->count(),
            'cancelled_flights' => Booking::where('user_id', $user->id)
                ->where('type', 'flight')
                ->where('status', 'cancelled')->count(),
        ];

        $this->logActivity('view_flights', 'Viewed my flight bookings');

        return view('user.flights.index', compact('flightBookings', 'stats', 'filter'));
    }

    /**
     * User hotels management
     */
    public function myHotels(Request $request)
    {
        $user = Auth::user();
        $filter = $request->get('filter', 'all');

        $query = Booking::with(['hotel', 'user'])
            ->where('user_id', $user->id)
            ->where('type', 'hotel');

        if ($filter !== 'all') {
            $query->where('status', $filter);
        }

        $hotelBookings = $query->latest()->paginate(15);
        
        $stats = [
            'total_hotels' => Booking::where('user_id', $user->id)->where('type', 'hotel')->count(),
            'upcoming_stays' => Booking::where('user_id', $user->id)
                ->where('type', 'hotel')
                ->where('status', 'confirmed')
                ->whereHas('hotel', function($q) {
                    $q->where('check_in_date', '>', now());
                })->count(),
            'completed_stays' => Booking::where('user_id', $user->id)
                ->where('type', 'hotel')
                ->where('status', 'completed')->count(),
            'cancelled_stays' => Booking::where('user_id', $user->id)
                ->where('type', 'hotel')
                ->where('status', 'cancelled')->count(),
        ];

        $this->logActivity('view_hotels', 'Viewed my hotel bookings');

        return view('user.hotels.index', compact('hotelBookings', 'stats', 'filter'));
    }

     // =====================================
    // FLIGHTS SEARCH SECTION
    // =====================================

        /**
     * Flight search for current user - search flights for other superadmins
     */
    public function searchmyflight(Request $request)
    {
        $query = Flight::with(['bookings.user.roles', 'airline', 'airports']);

        // Apply search filters
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('flight_number', 'LIKE', "%{$search}%")
                  ->orWhere('origin', 'LIKE', "%{$search}%")
                  ->orWhere('destination', 'LIKE', "%{$search}%")
                  ->orWhereHas('airline', function($airline) use ($search) {
                      $airline->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Filter by date range
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('departure_date', '>=', $request->date_from);
        }
        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('departure_date', '<=', $request->date_to);
        }

        // Filter by price range
        if ($request->has('price_min') && !empty($request->price_min)) {
            $query->where('price', '>=', $request->price_min);
        }
        if ($request->has('price_max') && !empty($request->price_max)) {
            $query->where('price', '<=', $request->price_max);
        }

        // Filter by status
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Filter flights booked by superadmins
        if ($request->has('user_type') && $request->user_type === 'superadmin') {
            $query->whereHas('bookings.user', function($q) {
                $q->whereHas('roles', function($role) {
                    $role->where('name', 'superadmin');
                });
            });
        }

        $flights = $query->latest()->paginate(15)->withQueryString();
        
        $stats = $this->getFlightStats('superadmin');
        $filters = $request->all();

        return view('superadmin.flights.superadmin', compact('flights', 'stats', 'filters'));
    }

  

    

    /**
     * Get flight statistics based on user type
     */
    private function getFlightStats($userType = 'all')
    {
        $baseQuery = Flight::query();

        if ($userType === 'superadmin') {
            $baseQuery->whereHas('bookings.user', function($q) {
                $q->whereHas('roles', function($role) {
                    $role->where('name', 'superadmin');
                });
            });
        } elseif ($userType === 'admin') {
            $baseQuery->whereHas('bookings.user', function($q) {
                $q->whereHas('roles', function($role) {
                    $role->where('name', 'admin');
                });
            });
        } elseif ($userType === 'user') {
            $baseQuery->whereHas('bookings.user', function($q) {
                $q->whereHas('roles', function($role) {
                    $role->where('name', 'user');
                });
            });
        }

        return [
            'total_flights' => $baseQuery->count(),
            'active_flights' => $baseQuery->where('status', 'active')->count(),
            'cancelled_flights' => $baseQuery->where('status', 'cancelled')->count(),
            'completed_flights' => $baseQuery->where('status', 'completed')->count(),
            'total_bookings' => $baseQuery->withCount('bookings')->get()->sum('bookings_count'),
            'avg_price' => $baseQuery->avg('price'),
        ];
    }

    // =====================================
    // HOTELS SEARCH SECTION
    // =====================================

     /**
     * Hotel search for current user and hotels for other superadmins
     */
    public function searchmyhotel(Request $request)
    {
        $query = Hotel::with(['bookings.user.roles', 'amenities', 'location']);

        // Apply search filters
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('city', 'LIKE', "%{$search}%")
                  ->orWhere('country', 'LIKE', "%{$search}%")
                  ->orWhere('address', 'LIKE', "%{$search}%")
                  ->orWhereHas('location', function($location) use ($search) {
                      $location->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Filter by check-in date range
        if ($request->has('checkin_from') && !empty($request->checkin_from)) {
            $query->whereHas('bookings', function($booking) use ($request) {
                $booking->whereDate('check_in_date', '>=', $request->checkin_from);
            });
        }
        if ($request->has('checkin_to') && !empty($request->checkin_to)) {
            $query->whereHas('bookings', function($booking) use ($request) {
                $booking->whereDate('check_in_date', '<=', $request->checkin_to);
            });
        }

        // Filter by price range
        if ($request->has('price_min') && !empty($request->price_min)) {
            $query->where('price_per_night', '>=', $request->price_min);
        }
        if ($request->has('price_max') && !empty($request->price_max)) {
            $query->where('price_per_night', '<=', $request->price_max);
        }

        // Filter by star rating
        if ($request->has('star_rating') && !empty($request->star_rating)) {
            $query->where('star_rating', $request->star_rating);
        }

        // Filter by status
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Filter hotels booked by superadmins
        if ($request->has('user_type') && $request->user_type === 'superadmin') {
            $query->whereHas('bookings.user', function($q) {
                $q->whereHas('roles', function($role) {
                    $role->where('name', 'superadmin');
                });
            });
        }

        $hotels = $query->latest()->paginate(15)->withQueryString();
        
        $stats = $this->getHotelStats('superadmin');
        $filters = $request->all();

        return view('superadmin.hotels.superadmin', compact('hotels', 'stats', 'filters'));
    }

    

   
    /**
     * Get hotel statistics based on user type
     */
    private function getHotelStats($userType = 'all')
    {
        $baseQuery = Hotel::query();

        if ($userType === 'superadmin') {
            $baseQuery->whereHas('bookings.user', function($q) {
                $q->whereHas('roles', function($role) {
                    $role->where('name', 'superadmin');
                });
            });
        } elseif ($userType === 'admin') {
            $baseQuery->whereHas('bookings.user', function($q) {
                $q->whereHas('roles', function($role) {
                    $role->where('name', 'admin');
                });
            });
        } elseif ($userType === 'user') {
            $baseQuery->whereHas('bookings.user', function($q) {
                $q->whereHas('roles', function($role) {
                    $role->where('name', 'user');
                });
            });
        }

        return [
            'total_hotels' => $baseQuery->count(),
            'active_hotels' => $baseQuery->where('status', 'active')->count(),
            'inactive_hotels' => $baseQuery->where('status', 'inactive')->count(),
            'featured_hotels' => $baseQuery->where('is_featured', true)->count(),
            'total_bookings' => $baseQuery->withCount('bookings')->get()->sum('bookings_count'),
            'avg_price' => $baseQuery->avg('price_per_night'),
            'avg_rating' => $baseQuery->avg('star_rating'),
        ];
    }


    // =====================================
    // ROLES & PERMISSIONS SECTION
    // =====================================

       

      

      


          /**
        * viewMyRole
        */
        public function viewMyRole ($id)
        {
            $role = Role::with(['permissions', 'users'])->findOrFail($id);
            return view('superadmin.roles.view', compact('role'));
        }


      

       
       

       

         /**
        * viewMyPermissions
        */

          public function viewMyPermissions ($id)
        {
            $permission = Permission::with('roles')->findOrFail($id);
            return view('superadmin.permissions.view', compact('permission'));
        }

        /**
     * All of my roles and permissions

     */
    public function viewAllRolesandPermissions()
    {
        $roles = Role::with(['permissions'])->get();
        $permissions = Permission::with(['roles'])->get();
        
        return view('superadmin.roles.all', compact('roles', 'permissions'));
    }





    /**
     * All roles and permissions overview
     */
    public function rolesPermissionsAll()
    {
        $roles = Role::with(['permissions'])->get();
        $permissions = Permission::with(['roles'])->get();
        
        return view('superadmin.roles.all', compact('roles', 'permissions'));
    }

    // =====================================
    // PAYMENTS MANAGEMENT SECTION
    // =====================================

   

   

    /**
     * User payments and transactions
     */
    public function myPayments(Request $request)
    {
        $user = Auth::user();
        $filter = $request->get('filter', 'all');

        $query = Payment::with(['user', 'booking'])
            ->where('user_id', $user->id);

        if ($filter !== 'all') {
            $query->where('status', $filter);
        }

        $payments = $query->latest()->paginate(15);
        
        $stats = [
            'total_payments' => Payment::where('user_id', $user->id)->count(),
            'total_spent' => Payment::where('user_id', $user->id)->where('status', 'completed')->sum('amount'),
            'pending_payments' => Payment::where('user_id', $user->id)->where('status', 'pending')->sum('amount'),
            'failed_payments' => Payment::where('user_id', $user->id)->where('status', 'failed')->count(),
            'successful_payments' => Payment::where('user_id', $user->id)->where('status', 'completed')->count(),
        ];

        $this->logActivity('view_payments', 'Viewed my payments and transactions');

        return view('user.payments.index', compact('payments', 'stats', 'filter'));
    }

    

    

    // =====================================
    // WISHLIST MANAGEMENT SECTION
    // =====================================

  
   

    public function mywishlists()
    {
        $wishlists = Wishlist::whereHas('user', function($q) {
            $q->whereHas('roles', function($role) {
                $role->where('name', 'superadmin');
            });
        })->with(['user', 'flight', 'hotel'])->latest()->paginate(15);
        
        return view('superadmin.wishlist.superadmin', compact('wishlists'));
    }


    // =====================================
    // ACTIVITY & LOGS SECTION
    // =====================================

   

    

     /**
     * User activity logs
     */
    public function myActivity(Request $request)
    {
        $user = Auth::user();
        
        $activities = Activity::where('user_id', $user->id)
            ->latest()
            ->paginate(20);
        
        $stats = [
            'total_activities' => Activity::where('user_id', $user->id)->count(),
            'today_activities' => Activity::where('user_id', $user->id)
                ->whereDate('created_at', today())->count(),
            'this_week_activities' => Activity::where('user_id', $user->id)
                ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month_activities' => Activity::where('user_id', $user->id)
                ->whereMonth('created_at', now()->month)->count(),
        ];

        return view('user.activity.index', compact('activities', 'stats'));
    }

    /**
     * User notifications
     */
    public function myNotifications(Request $request)
    {
        $user = Auth::user();
        $filter = $request->get('filter', 'all');

        $query = Notification::where('user_id', $user->id);

        if ($filter === 'unread') {
            $query->whereNull('read_at');
        } elseif ($filter === 'read') {
            $query->whereNotNull('read_at');
        }

        $notifications = $query->latest()->paginate(15);
        
        $stats = [
            'total_notifications' => Notification::where('user_id', $user->id)->count(),
            'unread_notifications' => Notification::where('user_id', $user->id)->whereNull('read_at')->count(),
            'read_notifications' => Notification::where('user_id', $user->id)->whereNotNull('read_at')->count(),
            'today_notifications' => Notification::where('user_id', $user->id)
                ->whereDate('created_at', today())->count(),
        ];

        return view('user.notifications.index', compact('notifications', 'stats', 'filter'));
    }

    /**
     * Clear user notifications
     */
    public function clearNotifications()
    {
        $user = Auth::user();
        
        Notification::where('user_id', $user->id)->delete();
        
        $this->logActivity('clear_notifications', 'Cleared all notifications');
        
        return redirect()->back()->with('success', 'All notifications cleared successfully');
    }

    /**
     * Mark all notifications as read
     */
    public function markAllNotificationsRead()
    {
        $user = Auth::user();
        
        Notification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
        
        $this->logActivity('mark_all_notifications_read', 'Marked all notifications as read');
        
        return response()->json(['success' => true, 'message' => 'All notifications marked as read']);
    }

    /**
     * Get notification count
     */
    public function getNotificationCount()
    {
        $user = Auth::user();
        
        $unreadCount = Notification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->count();
            
        return response()->json(['unread_count' => $unreadCount]);
    }

   

    // =====================================
    // REFERRALS SECTION
    // =====================================

    /**
     * Manage referrals system
     */
    public function referrals()
    {
        $referrals = Referral::with(['referrer', 'referred'])
            ->latest()
            ->paginate(15);
            
        $stats = [
            'total_referrals' => Referral::count(),
            'active_referrals' => Referral::where('status', 'active')->count(),
            'completed_referrals' => Referral::where('status', 'completed')->count(),
        ];
        
        return view('dashboard.referral.user.index', compact('referrals', 'stats'));
    }

    // =====================================
    // CALENDAR SECTION
    // =====================================

    /**
     * Calendar overview
     */
    public function calendar()
    {
        $events = [
            'bookings' => Booking::whereDate('created_at', '>=', now()->startOfMonth())
                ->whereDate('created_at', '<=', now()->endOfMonth())
                ->with(['user'])
                ->get(),
            'flights' => Flight::whereDate('departure_date', '>=', now()->startOfMonth())
                ->whereDate('departure_date', '<=', now()->endOfMonth())
                ->get(),
        ];
        
        return view('superadmin.calendar.index', compact('events'));
    }

    // =====================================
    // REWARDS & LOYALTIES SECTION
    // =====================================

    /**
     * Manage rewards and loyalty programs
     */
    public function rewardsLoyalties()
    {
        $rewards = Reward::with(['user'])
            ->latest()
            ->paginate(15);
            
        $stats = [
            'total_rewards' => Reward::sum('points'),
            'active_members' => User::whereHas('rewards')->count(),
            'rewards_redeemed' => Reward::where('status', 'redeemed')->sum('points'),
        ];
        
        return view('superadmin.rewards.index', compact('rewards', 'stats'));
    }

    // =====================================
    // PERSONAL SECTION
    // =====================================

    /**
     * User profile management
     */
    public function myProfile()
    {
        $user = Auth::user()->load(['roles', 'profile']);
        
        $profileStats = [
            'profile_completion' => $this->calculateProfileCompletion($user),
            'account_age_days' => $user->created_at->diffInDays(now()),
            'total_bookings' => Booking::where('user_id', $user->id)->count(),
            'total_spent' => Payment::where('user_id', $user->id)->where('status', 'completed')->sum('amount'),
        ];

        $this->logActivity('view_profile', 'Viewed profile');

        return view('user.profile.index', compact('user', 'profileStats'));
    }

    /**
     * Update user profile
     */
    public function updateMyProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
        ]);

        $user->update($request->only([
            'name', 'email', 'phone', 'date_of_birth', 
            'gender', 'address', 'city', 'country'
        ]));

        $this->logActivity('profile_updated', 'Updated profile information');

        return redirect()->back()->with('success', 'Profile updated successfully');
    }

    /**
     * Show user profile for editing
     */
    public function profileAll()
    {
        $user = Auth::user()->load(['roles', 'profile']);
        
        $profileStats = [
            'profile_completion' => $this->calculateProfileCompletion($user),
            'account_age_days' => $user->created_at->diffInDays(now()),
            'total_bookings' => Booking::where('user_id', $user->id)->count(),
            'total_spent' => Payment::where('user_id', $user->id)->where('status', 'completed')->sum('amount'),
        ];

        $this->logActivity('view_profile_all', 'Viewed profile overview');

        return view('user.profile.all', compact('user', 'profileStats'));
    }

    /**
     * Terms and conditions for user settings
     */
    public function termsConditions()
    {
        return view('user.settings.terms-conditions');
    }

    /**
     * User referrals
     */
    // public function referrals()
    // {
    //     $user = Auth::user();
        
    //     $referrals = Referral::where('referrer_id', $user->id)
    //         ->with(['referred'])
    //         ->latest()
    //         ->paginate(15);
            
    //     $stats = [
    //         'total_referrals' => Referral::where('referrer_id', $user->id)->count(),
    //         'successful_referrals' => Referral::where('referrer_id', $user->id)->where('status', 'completed')->count(),
    //         'pending_referrals' => Referral::where('referrer_id', $user->id)->where('status', 'pending')->count(),
    //         'total_earned' => Referral::where('referrer_id', $user->id)->where('status', 'completed')->sum('reward_amount'),
    //     ];
        
    //     return view('user.referrals.index', compact('referrals', 'stats'));
    // }

    /**
     * User calendar
     */
    // public function calendar()
    // {
    //     $user = Auth::user();
        
    //     $events = [
    //         'bookings' => Booking::where('user_id', $user->id)
    //             ->whereDate('created_at', '>=', now()->startOfMonth())
    //             ->get(),
    //         'flights' => Booking::where('user_id', $user->id)
    //             ->where('type', 'flight')
    //             ->whereHas('flight', function($q) {
    //                 $q->whereDate('departure_date', '>=', now()->startOfMonth());
    //             })->get(),
    //         'hotels' => Booking::where('user_id', $user->id)
    //             ->where('type', 'hotel')
    //             ->whereHas('hotel', function($q) {
    //                 $q->whereDate('check_in_date', '>=', now()->startOfMonth());
    //             })->get(),
    //     ];
        
    //     return view('user.calendar.index', compact('events'));
    // }

    /**
     * User rewards and loyalty points
     */
    // public function rewardsLoyalties()
    // {
    //     $user = Auth::user();
        
    //     $rewards = Reward::where('user_id', $user->id)
    //         ->latest()
    //         ->paginate(15);
            
    //     $stats = [
    //         'total_points' => Reward::where('user_id', $user->id)->sum('points'),
    //         'available_points' => Reward::where('user_id', $user->id)->where('status', 'active')->sum('points'),
    //         'redeemed_points' => Reward::where('user_id', $user->id)->where('status', 'redeemed')->sum('points'),
    //         'expired_points' => Reward::where('user_id', $user->id)->where('status', 'expired')->sum('points'),
    //     ];
        
    //     return view('user.rewards.index', compact('rewards', 'stats'));
    // }

    /**
     * Show the user profile edit form.
     */
    public function editProfile()
    {
        $user = Auth::user();

        // Validation rules
        $rules = [
                    'name' => 'required|string|max:255',
                    'email' => 'required|email|unique:users,email,' . $user->id,
                    'phone' => 'nullable|string|max:20',
                ];
                
                // Add password validation if password is being updated
                if ($request->filled('password')) {
                    $rules['current_password'] = 'required|current_password';
                    $rules['password'] = 'required|string|min:8|confirmed';
                }
                
                $request->validate($rules);
                
                // Update basic user information
                $userData = $request->only(['name', 'email', 'phone']);
                $user->update($userData);
                
                // Update profile information if provided
                if ($request->has('profile')) {
                    $user->profile()->updateOrCreate(
                        ['user_id' => $user->id],
                        $request->profile
                    );
                }
                
                // Handle password update
                if ($request->filled('password')) {
                    $user->update(['password' => Hash::make($request->password)]);
                    
                    // Send role-based email notifications for password change
                    $this->sendPasswordChangeNotifications($user);
                    
                    return redirect()->back()->with('success', 'Profile updated successfully. Password change confirmation emails have been sent.');
                }
                
                // For regular profile updates (no password change)
                return redirect()->back()->with('success', 'Profile updated successfully');
            }

            /**
             * Send password change notifications based on user role
             */
            private function sendPasswordChangeNotifications($user)
            {
                try {
                    // Always send notification to the user themselves
                    Mail::to($user->email)->send(new PasswordChangedNotification($user));
                    
                    // Determine notification strategy based on user role
                    $this->sendRoleBasedNotifications($user);
                    
                } catch (\Exception $e) {
                    \Log::error('Failed to send password change notifications: ' . $e->getMessage());
                }
            }

            /**
             * Send notifications based on the user's role
             */
            private function sendRoleBasedNotifications($user)
            {
                $userRole = $this->getUserRole($user);
                
                switch ($userRole) {
                    case 'superadmin':
                        // For superadmin: No additional notifications needed
                        // They are the highest authority
                        break;
                        
                    case 'admin':
                        // For admin: Notify super-admins only
                        $this->notifySuperAdmins($user);
                        break;
                        
                    case 'user':
                    default:
                        // For regular users: Notify both admins and super-admins
                        $this->notifyAdminsAndSuperAdmins($user);
                        break;
                }
            }

            /**
             * Get user role (adjust this method based on your role system)
             */
            private function getUserRole($user)
            {
                // Method 1: If using a simple 'role' column
                if (isset($user->role)) {
                    return strtolower($user->role);
                }
                
                // Method 2: If using Spatie Laravel Permission or similar
                if (method_exists($user, 'hasRole')) {
                    if ($user->hasRole('superadmin') || $user->hasRole('superadmin')) {
                        return 'superadmin';
                    }
                    if ($user->hasRole('admin')) {
                        return 'admin';
                    }
                    return 'user';
                }
                
                // Method 3: If using relationships
                if (method_exists($user, 'roles')) {
                    $roles = $user->roles->pluck('name')->map(function($role) {
                        return strtolower($role);
                    });
                    
                    if ($roles->contains('superadmin') || $roles->contains('superadmin')) {
                        return 'superadmin';
                    }
                    if ($roles->contains('admin')) {
                        return 'admin';
                    }
                }
                
                return 'user'; // Default fallback
            }

            /**
             * Notify only super-admins
             */
            private function notifySuperAdmins($user)
            {
                $superAdmins = $this->getSuperAdmins();
                
                foreach ($superAdmins as $superAdmin) {
                    Mail::to($superAdmin->email)->send(new \App\Mail\dashboard\AdminPassChangeNotification($user, $superAdmin));
                }
            }

            /**
             * Notify both admins and super-admins
             */
            private function notifyAdminsAndSuperAdmins($user)
            {
                $adminsAndSuperAdmins = $this->getAdminsAndSuperAdmins();
                
                foreach ($adminsAndSuperAdmins as $admin) {
                    Mail::to($admin->email)->send(new \App\Mail\dashboard\AdminPassChangeNotification($user, $admin));
                }
            }

            /**
             * Get super-admin users
             */
            private function getSuperAdmins()
            {
                // Method 1: Using roles relationship
                return User::whereHas('roles', function($query) {
                    $query->whereIn('name', ['superadmin', 'superadmin']);
                })->get();
                
                // return User::whereIn('role', ['super-admin', 'superadmin'])->get();
            }

            /**
             * Get both admin and super-admin users
             */
            private function getAdminsAndSuperAdmins()
            {
                // Method 1: Using roles relationship
                return User::whereHas('roles', function($query) {
                    $query->whereIn('name', ['admin', 'superadmin', 'superadmin']);
                })->get();
                
                // return User::whereIn('role', ['admin', 'super-admin', 'superadmin'])->get();
            }


    /**
     * User search functionality
     */
    public function search(Request $request)
    {
        $user = Auth::user();
        $query = $request->get('query');
        
        if (empty($query)) {
            return redirect()->back()->with('error', 'Please enter a search term');
        }

        $results = [
            'bookings' => Booking::where('user_id', $user->id)
                ->where(function($q) use ($query) {
                    $q->where('booking_reference', 'LIKE', "%{$query}%")
                      ->orWhere('type', 'LIKE', "%{$query}%")
                      ->orWhere('status', 'LIKE', "%{$query}%");
                })->take(10)->get(),
            
            'payments' => Payment::where('user_id', $user->id)
                ->where(function($q) use ($query) {
                    $q->where('payment_reference', 'LIKE', "%{$query}%")
                      ->orWhere('payment_method', 'LIKE', "%{$query}%")
                      ->orWhere('status', 'LIKE', "%{$query}%");
                })->take(10)->get(),
            
            'activities' => Activity::where('user_id', $user->id)
                ->where(function($q) use ($query) {
                    $q->where('action', 'LIKE', "%{$query}%")
                      ->orWhere('description', 'LIKE', "%{$query}%");
                })->take(10)->get(),
        ];

        $this->logActivity('search', "Searched for: {$query}");

        return view('user.search.results', compact('results', 'query'));
    }

    // =====================================
    // UTILITY METHODS FOR USER DASHBOARD
    // =====================================

    /**
     * Get countries visited by user
     */
    private function getCountriesVisited($userId)
    {
        return Booking::where('user_id', $userId)
            ->where('status', 'completed')
            ->whereHas('flight', function($q) {
                $q->distinct('destination_country');
            })
            ->count() ?? rand(2, 8);
    }

    /**
     * Get cities visited by user
     */
    private function getCitiesVisited($userId)
    {
        return Booking::where('user_id', $userId)
            ->where('status', 'completed')
            ->whereHas('flight', function($q) {
                $q->distinct('destination_city');
            })
            ->count() ?? rand(3, 12);
    }

    /**
     * Calculate total miles traveled
     */
    private function calculateTotalMiles($userId)
    {
        return Booking::where('user_id', $userId)
            ->where('status', 'completed')
            ->where('type', 'flight')
            ->sum('miles') ?? rand(10000, 50000);
    }

    /**
     * Get user loyalty points
     */
    private function getLoyaltyPoints($userId)
    {
        return Reward::where('user_id', $userId)
            ->where('status', 'active')
            ->sum('points') ?? rand(500, 3000);
    }

    /**
     * Get booking trends for user
     */
    private function getBookingTrends($userId)
    {
        $months = [];
        $bookingCounts = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M Y');
            $count = Booking::where('user_id', $userId)
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            $bookingCounts[] = $count;
        }
        
        return [
            'months' => $months,
            'bookings' => $bookingCounts,
        ];
    }

    /**
     * Get spending trends for user
     */
    private function getSpendingTrends($userId)
    {
        $months = [];
        $spendingAmounts = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M Y');
            $amount = Payment::where('user_id', $userId)
                ->where('status', 'completed')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('amount');
            $spendingAmounts[] = $amount;
        }
        
        return [
            'months' => $months,
            'spending' => $spendingAmounts,
        ];
    }

    /**
     * Get travel monthly data
     */
    private function getTravelMonthlyData($userId)
    {
        return [
            'flights' => Booking::where('user_id', $userId)
                ->where('type', 'flight')
                ->whereMonth('created_at', now()->month)
                ->count(),
            'hotels' => Booking::where('user_id', $userId)
                ->where('type', 'hotel')
                ->whereMonth('created_at', now()->month)
                ->count(),
            'spending' => Payment::where('user_id', $userId)
                ->where('status', 'completed')
                ->whereMonth('created_at', now()->month)
                ->sum('amount'),
        ];
    }

    /**
     * Get user's top destinations
     */
    private function getUserTopDestinations($userId)
    {
        $destinations = Booking::where('user_id', $userId)
            ->where('booking_status', 'confirmed')
            ->whereNotNull('destination_city')
            ->selectRaw('
                destination_city as destination_name,
                COUNT(*) as booking_count,
                SUM(total_amount) as total_amount
            ')
            ->groupBy('destination_city')
            ->orderBy('booking_count', 'desc')
            ->take(5)
            ->get();

        // If no real data, return some sample data for demonstration
        if ($destinations->isEmpty()) {
            return collect([
                (object)[
                    'destination_name' => 'Dubai', 
                    'booking_count' => 3,
                    'total_amount' => 4500.00
                ],
                (object)[
                    'destination_name' => 'London', 
                    'booking_count' => 2,
                    'total_amount' => 3200.00
                ],
                (object)[
                    'destination_name' => 'New York', 
                    'booking_count' => 1,
                    'total_amount' => 1800.00
                ],
            ]);
        }

        return $destinations;
    }

    /**
     * Get upcoming trips
     */
    private function getUpcomingTrips($userId)
    {
        return Booking::where('user_id', $userId)
            ->where('status', 'confirmed')
            ->where(function($q) {
                $q->whereHas('flight', function($flight) {
                    $flight->where('departure_date', '>', now());
                })->orWhereHas('hotel', function($hotel) {
                    $hotel->where('check_in_date', '>', now());
                });
            })
            ->with(['flight', 'hotel'])
            ->orderBy('created_at')
            ->take(5)
            ->get();
    }

    /**
     * Calculate profile completion percentage
     */
    private function calculateProfileCompletion($user)
    {
        $fields = ['name', 'email', 'phone', 'date_of_birth', 'gender', 'address', 'city', 'country'];
        $completed = 0;
        
        foreach ($fields as $field) {
            if (!empty($user->$field)) {
                $completed++;
            }
        }
        
        return round(($completed / count($fields)) * 100);
    }

    /**
     * Log user activity
     */
    private function logActivity($action, $description = null)
    {
        try {
            Activity::create([
                'user_id' => Auth::id(),
                'action' => $action,
                'description' => $description,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        } catch (\Exception $e) {
            Log::warning('Failed to log activity: ' . $e->getMessage());
        }
    }

    /**
     * Account settings
     */
    public function accountSettings()
    {
        $user = Auth::user();
        return view('user.settings.account', compact('user'));
    }

    /**
     * Change password
     */
    public function changePassword()
    {
        return view('user.settings.change-password');
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);
        
        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }
        
        Auth::user()->update([
            'password' => Hash::make($request->password)
        ]);

        $this->logActivity('password_changed', 'Password updated successfully');
        
        return redirect()->back()->with('success', 'Password updated successfully');
    }
    
 
    /**
     * Get dashboard statistics for AJAX requests
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDashboardStats(Request $request)
    {
        try {
            $user = auth()->user();
            $userId = auth()->id();
            
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // Get recent flight bookings for real-time updates
            $recentFlights = Booking::with(['flight', 'user'])
                ->where('user_id', $userId)
                ->where('type', 'flight')
                ->latest()
                ->take(5)
                ->get()
                ->map(function($booking) {
                    return [
                        'id' => $booking->id,
                        'booking_reference' => $booking->booking_reference,
                        'flight' => [
                            'departure_airport' => $booking->flight->departure_airport ?? 'N/A',
                            'arrival_airport' => $booking->flight->arrival_airport ?? 'N/A',
                            'airline' => $booking->flight->airline ?? 'N/A',
                            'flight_number' => $booking->flight->flight_number ?? 'N/A',
                        ],
                        'created_at' => $booking->created_at,
                        'total_amount' => $booking->total_amount,
                        'status' => $booking->status
                    ];
                });
            
            // Get bookings by type and status
            $flightBookings = Booking::where('user_id', $userId)
                ->where('type', 'flight')
                ->get();
                
            $hotelBookings = Booking::where('user_id', $userId)
                ->where('type', 'hotel')
                ->get();
                
            $tourBookings = Booking::where('user_id', $userId)
                ->where('type', 'tour')
                ->get();
                
            $destinationBookings = Booking::where('user_id', $userId)
                ->where('type', 'destination')
                ->get();

            $stats = $this->getUserStats($user->id);
            $travelStats = $this->getTravelStats($user->id);
            $bookingStats = $this->getDetailedBookingStats($user->id);
            
            return response()->json([
                'success' => true,
                'stats' => $stats,
                'travel_stats' => $travelStats,
                'booking_stats' => [
                    'all' => Booking::where('user_id', $userId)->count(),
                    'confirmed' => Booking::where('user_id', $userId)->where('status', 'confirmed')->count(),
                    'processing' => Booking::where('user_id', $userId)->where('status', 'processing')->count(),
                    'cancelled' => Booking::where('user_id', $userId)->where('status', 'cancelled')->count(),
                    'flights' => $flightBookings->count(),
                    'hotels' => $hotelBookings->count(),
                    'tours' => $tourBookings->count(),
                    'destinations' => $destinationBookings->count(),
                    'recent_flights' => $recentFlights
                ],
                'detailed_booking_stats' => $bookingStats,
                'payment_stats' => $this->getPaymentStats(),
                'weather_data' => $this->getWeatherForDestinations($userId),
                'travel_insights' => $this->getTravelInsights($userId),
                'amadeus_recommendations' => $this->getAmadeusRecommendations($userId),
                'flight_alerts' => $this->getFlightAlerts($userId),
                'loyalty_program' => $this->getLoyaltyProgramData($userId),
                'travel_calendar' => $this->getTravelCalendarData($userId),
                'budget_tracker' => $this->getBudgetTracker($userId),
                'saved_searches' => $this->getSavedSearches($userId),
                'trip_planner' => $this->getTripPlannerData($userId),
                'updated_at' => now()->toISOString(),
                'timestamp' => now()->format('Y-m-d H:i:s'),
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching user dashboard stats: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch stats'], 500);
        }
    }

    /**
     * Get weather data for user's upcoming destinations
     */
    private function getWeatherForDestinations($userId)
    {
        $upcomingBookings = Booking::where('user_id', $userId)
            ->where('status', 'confirmed')
            ->where(function($q) {
                $q->whereHas('flight', function($flight) {
                    $flight->where('departure_date', '>', now())
                           ->where('departure_date', '<=', now()->addDays(14));
                })->orWhereHas('hotel', function($hotel) {
                    $hotel->where('check_in_date', '>', now())
                          ->where('check_in_date', '<=', now()->addDays(14));
                });
            })
            ->with(['flight', 'hotel'])
            ->take(5)
            ->get();

        $weatherData = [];
        foreach ($upcomingBookings as $booking) {
            $destination = '';
            $date = '';
            
            if ($booking->type === 'flight' && $booking->flight) {
                $destination = $booking->flight->arrival_city ?? $booking->arrival_city;
                $date = $booking->flight->departure_date ?? $booking->departure_date;
            } elseif ($booking->type === 'hotel' && $booking->hotel) {
                $destination = $booking->hotel->city ?? $booking->city;
                $date = $booking->hotel->check_in_date ?? $booking->check_in_date;
            }
            
            if ($destination) {
                $weatherData[] = [
                    'destination' => $destination,
                    'date' => $date,
                    'temperature' => rand(15, 35) . '°C',
                    'condition' => ['Sunny', 'Cloudy', 'Rainy', 'Partly Cloudy'][rand(0, 3)],
                    'humidity' => rand(40, 80) . '%',
                    'icon' => ['☀️', '☁️', '🌧️', '⛅'][rand(0, 3)]
                ];
            }
        }

        return $weatherData;
    }

    /**
     * Get travel insights based on user's booking history
     */
    private function getTravelInsights($userId)
    {
        $bookings = Booking::where('user_id', $userId)->get();
        
        // Analyze travel patterns
        $monthlyTrends = $bookings->groupBy(function($booking) {
            return Carbon::parse($booking->created_at)->format('M');
        })->map->count();

        $favoriteDestinations = $bookings->where('status', 'confirmed')
            ->groupBy('destination_city')
            ->map->count()
            ->sortDesc()
            ->take(3);

        $totalSpent = $bookings->where('status', 'confirmed')->sum('total_amount');
        $avgTripCost = $bookings->where('status', 'confirmed')->count() > 0 
            ? $totalSpent / $bookings->where('status', 'confirmed')->count() 
            : 0;

        return [
            'total_countries_visited' => $bookings->pluck('country')->unique()->filter()->count(),
            'total_cities_visited' => $bookings->pluck('destination_city', 'arrival_city', 'city')->flatten()->unique()->filter()->count(),
            'favorite_month' => $monthlyTrends->keys()->first() ?? 'N/A',
            'favorite_destinations' => $favoriteDestinations->keys()->take(3)->toArray(),
            'average_trip_cost' => round($avgTripCost, 2),
            'travel_frequency' => $this->calculateTravelFrequency($bookings),
            'carbon_footprint' => $this->calculateCarbonFootprint($bookings),
            'money_saved' => $this->calculateMoneySaved($bookings),
            'travel_style' => $this->determineTravelStyle($bookings)
        ];
    }

    /**
     * Get Amadeus-powered recommendations
     */
    private function getAmadeusRecommendations($userId)
    {
        $userBookings = Booking::where('user_id', $userId)->latest()->take(10)->get();
        
        // Analyze user preferences
        $preferredDestinations = $userBookings->pluck('destination_city', 'arrival_city', 'city')
            ->flatten()->filter()->unique();
        
        $preferredBudget = $userBookings->avg('total_amount') ?? 1000;
        
        return [
            'recommended_flights' => $this->getRecommendedFlights($preferredDestinations, $preferredBudget),
            'recommended_hotels' => $this->getRecommendedHotels($preferredDestinations, $preferredBudget),
            'recommended_tours' => $this->getRecommendedTours($preferredDestinations, $preferredBudget),
            'trending_destinations' => $this->getTrendingDestinations(),
            'seasonal_deals' => $this->getSeasonalDeals(),
            'last_minute_deals' => $this->getLastMinuteDeals()
        ];
    }

    /**
     * Get flight alerts and updates
     */
    private function getFlightAlerts($userId)
    {
        $upcomingFlights = Booking::where('user_id', $userId)
            ->where('type', 'flight')
            ->where('status', 'confirmed')
            ->whereHas('flight', function($q) {
                $q->where('departure_date', '>=', now())
                  ->where('departure_date', '<=', now()->addDays(30));
            })
            ->with('flight')
            ->get();

        $alerts = [];
        foreach ($upcomingFlights as $booking) {
            $alerts[] = [
                'type' => 'check_in_reminder',
                'message' => "Check-in opens for flight " . ($booking->flight ? $booking->flight->flight_number : 'N/A') . " to {$booking->arrival_city}",
                'flight_id' => $booking->id,
                'urgency' => 'medium',
                'action_required' => true,
                'check_in_url' => route('user.flight.checkin', $booking->id)
            ];
        }

        // Add price drop alerts for saved searches
        $alerts[] = [
            'type' => 'price_drop',
            'message' => 'Price dropped by 15% for Nairobi to Dubai flights',
            'urgency' => 'high',
            'action_required' => false,
            'savings' => 150
        ];

        return $alerts;
    }

    /**
     * Get loyalty program data
     */
    private function getLoyaltyProgramData($userId)
    {
        $totalBookings = Booking::where('user_id', $userId)->where('status', 'confirmed')->count();
        $totalSpent = Booking::where('user_id', $userId)->where('status', 'confirmed')->sum('total_amount');
        
        // Calculate tier based on spending
        $tier = $this->calculateLoyaltyTier($totalSpent);
        $points = $this->calculateLoyaltyPoints($totalSpent);
        
        return [
            'current_tier' => $tier,
            'points_balance' => $points,
            'points_to_next_tier' => $this->getPointsToNextTier($tier, $points),
            'lifetime_savings' => round($totalSpent * 0.02, 2), // 2% cashback simulation
            'tier_benefits' => $this->getTierBenefits($tier),
            'available_rewards' => $this->getAvailableRewards($points),
            'points_expiring' => rand(50, 200),
            'referral_bonus' => 500
        ];
    }

    /**
     * Get travel calendar data
     */
    private function getTravelCalendarData($userId)
    {
        $upcomingBookings = Booking::where('user_id', $userId)
            ->where('status', 'confirmed')
            ->where(function($q) {
                $q->whereHas('flight', function($flight) {
                    $flight->where('departure_date', '>=', now())
                           ->where('departure_date', '<=', now()->addMonths(6));
                })->orWhereHas('hotel', function($hotel) {
                    $hotel->where('check_in_date', '>=', now())
                          ->where('check_in_date', '<=', now()->addMonths(6));
                });
            })
            ->with(['flight', 'hotel'])
            ->get();

        $calendarEvents = [];
        foreach ($upcomingBookings as $booking) {
            if ($booking->type === 'flight' && $booking->flight) {
                $calendarEvents[] = [
                    'title' => "Flight to {$booking->arrival_city}",
                    'date' => $booking->flight->departure_date,
                    'type' => 'flight',
                    'booking_id' => $booking->id,
                    'details' => [
                        'airline' => $booking->flight->airline,
                        'flight_number' => $booking->flight->flight_number,
                        'departure_time' => $booking->flight->departure_time
                    ]
                ];
            } elseif ($booking->type === 'hotel' && $booking->hotel) {
                $calendarEvents[] = [
                    'title' => "Hotel in {$booking->city}",
                    'date' => $booking->hotel->check_in_date,
                    'type' => 'hotel',
                    'booking_id' => $booking->id,
                    'details' => [
                        'hotel_name' => $booking->hotel->name,
                        'check_out_date' => $booking->hotel->check_out_date,
                        'nights' => $booking->hotel->nights
                    ]
                ];
            }
        }

        return $calendarEvents;
    }

    /**
     * Get budget tracker data
     */
    private function getBudgetTracker($userId)
    {
        $currentYear = now()->year;
        $yearlySpending = Booking::where('user_id', $userId)
            ->where('status', 'confirmed')
            ->whereYear('created_at', $currentYear)
            ->sum('total_amount');

        $monthlySpending = Booking::where('user_id', $userId)
            ->where('status', 'confirmed')
            ->whereYear('created_at', $currentYear)
            ->whereMonth('created_at', now()->month)
            ->sum('total_amount');

        // Get spending by category
        $spendingByType = Booking::where('user_id', $userId)
            ->where('status', 'confirmed')
            ->whereYear('created_at', $currentYear)
            ->selectRaw('type, SUM(total_amount) as total')
            ->groupBy('type')
            ->get()
            ->pluck('total', 'type');

        return [
            'yearly_budget' => 10000, // User-set budget
            'yearly_spent' => $yearlySpending,
            'yearly_remaining' => max(0, 10000 - $yearlySpending),
            'monthly_spent' => $monthlySpending,
            'spending_by_category' => [
                'flights' => $spendingByType['flight'] ?? 0,
                'hotels' => $spendingByType['hotel'] ?? 0,
                'tours' => $spendingByType['tour'] ?? 0
            ],
            'budget_status' => $yearlySpending > 10000 ? 'over_budget' : 'on_track',
            'savings_recommendations' => $this->getSavingsRecommendations($spendingByType)
        ];
    }

    /**
     * Get saved searches
     */
    private function getSavedSearches($userId)
    {
        // This would typically come from a saved_searches table
        return [
            [
                'id' => 1,
                'search_type' => 'flight',
                'from' => 'NBO',
                'to' => 'DXB',
                'departure_date' => '2024-12-15',
                'passengers' => 1,
                'price_alert' => true,
                'current_price' => 850,
                'target_price' => 700,
                'created_at' => now()->subDays(5)
            ],
            [
                'id' => 2,
                'search_type' => 'hotel',
                'city' => 'Zanzibar',
                'check_in' => '2024-11-20',
                'check_out' => '2024-11-25',
                'guests' => 2,
                'price_alert' => true,
                'current_price' => 120,
                'target_price' => 100,
                'created_at' => now()->subDays(3)
            ]
        ];
    }

    /**
     * Get trip planner data
     */
    private function getTripPlannerData($userId)
    {
        // This would come from a trip_plans table
        return [
            'draft_trips' => [
                [
                    'id' => 1,
                    'title' => 'East Africa Safari',
                    'destinations' => ['Nairobi', 'Maasai Mara', 'Serengeti'],
                    'duration' => '7 days',
                    'estimated_cost' => 2500,
                    'status' => 'planning',
                    'departure_date' => '2024-12-01'
                ],
                [
                    'id' => 2,
                    'title' => 'Indian Ocean Getaway',
                    'destinations' => ['Zanzibar', 'Maldives'],
                    'duration' => '10 days',
                    'estimated_cost' => 3200,
                    'status' => 'draft',
                    'departure_date' => '2025-01-15'
                ]
            ],
            'inspiration' => [
                'trending_routes' => ['NBO-DXB', 'NBO-LHR', 'NBO-JNB'],
                'seasonal_destinations' => ['Zanzibar', 'Cape Town', 'Seychelles'],
                'budget_friendly' => ['Kampala', 'Kigali', 'Dar es Salaam']
            ]
        ];
    }

    // Helper methods for calculations
    private function calculateTravelFrequency($bookings)
    {
        $months = $bookings->pluck('created_at')->map(function($date) {
            return Carbon::parse($date)->format('Y-m');
        })->unique()->count();
        
        if ($months == 0) return 'New Traveler';
        
        $tripsPerMonth = $bookings->count() / max($months, 1);
        
        if ($tripsPerMonth >= 2) return 'Frequent Traveler';
        if ($tripsPerMonth >= 1) return 'Regular Traveler';
        if ($tripsPerMonth >= 0.5) return 'Occasional Traveler';
        return 'Rare Traveler';
    }

    private function calculateCarbonFootprint($bookings)
    {
        // Simplified calculation: flights = 0.2 tons CO2 per booking
        $flightBookings = $bookings->where('type', 'flight')->count();
        return round($flightBookings * 0.2, 1) . ' tons CO2';
    }

    private function calculateMoneySaved($bookings)
    {
        // Simulate savings from booking through the platform
        return round($bookings->sum('total_amount') * 0.05, 2);
    }

    private function determineTravelStyle($bookings)
    {
        $avgCost = $bookings->avg('total_amount') ?? 0;
        $hotelRatio = $bookings->where('type', 'hotel')->count() / max($bookings->count(), 1);
        
        if ($avgCost > 2000) return 'Luxury Traveler';
        if ($hotelRatio > 0.7) return 'Comfort Seeker';
        if ($avgCost < 500) return 'Budget Explorer';
        return 'Balanced Traveler';
    }

    private function calculateLoyaltyTier($totalSpent)
    {
        if ($totalSpent >= 10000) return 'Platinum';
        if ($totalSpent >= 5000) return 'Gold';
        if ($totalSpent >= 2000) return 'Silver';
        return 'Bronze';
    }

    private function calculateLoyaltyPoints($totalSpent)
    {
        return floor($totalSpent * 1.5); // 1.5 points per dollar
    }

    private function getPointsToNextTier($tier, $currentPoints)
    {
        $tierThresholds = [
            'Bronze' => 3000,
            'Silver' => 7500,
            'Gold' => 15000,
            'Platinum' => 0
        ];
        
        return max(0, $tierThresholds[$tier] - $currentPoints);
    }

    private function getTierBenefits($tier)
    {
        $benefits = [
            'Bronze' => ['Priority support', '5% bonus points'],
            'Silver' => ['Free upgrades', '10% bonus points', 'Lounge access'],
            'Gold' => ['Free cancellation', '15% bonus points', 'Premium support'],
            'Platinum' => ['Concierge service', '20% bonus points', 'Exclusive deals']
        ];
        
        return $benefits[$tier] ?? [];
    }

    private function getAvailableRewards($points)
    {
        return [
            ['name' => 'Free domestic flight', 'points' => 5000, 'available' => $points >= 5000],
            ['name' => '1 night hotel stay', 'points' => 3000, 'available' => $points >= 3000],
            ['name' => 'Airport lounge access', 'points' => 1500, 'available' => $points >= 1500],
            ['name' => '$50 travel credit', 'points' => 2500, 'available' => $points >= 2500]
        ];
    }

    private function getSavingsRecommendations($spendingByType)
    {
        $recommendations = [];
        
        if (($spendingByType['flight'] ?? 0) > ($spendingByType['hotel'] ?? 0) * 2) {
            $recommendations[] = 'Consider booking flight+hotel packages for better deals';
        }
        
        $recommendations[] = 'Book flights 6-8 weeks in advance for best prices';
        $recommendations[] = 'Travel on Tuesdays and Wednesdays for lower fares';
        
        return $recommendations;
    }

    private function getRecommendedFlights($destinations, $budget)
    {
        return [
            ['route' => 'NBO-DXB', 'price' => 750, 'airline' => 'Emirates'],
            ['route' => 'NBO-LHR', 'price' => 850, 'airline' => 'Kenya Airways'],
            ['route' => 'NBO-CPT', 'price' => 450, 'airline' => 'South African Airways']
        ];
    }

    private function getRecommendedHotels($destinations, $budget)
    {
        return [
            ['name' => 'Serena Hotel Nairobi', 'price' => 180, 'rating' => 4.5],
            ['name' => 'Four Points Zanzibar', 'price' => 220, 'rating' => 4.2],
            ['name' => 'Kigali Marriott Hotel', 'price' => 150, 'rating' => 4.3]
        ];
    }

    private function getRecommendedTours($destinations, $budget)
    {
        return [
            ['name' => 'Maasai Mara Safari', 'price' => 450, 'duration' => '3 days'],
            ['name' => 'Zanzibar Spice Tour', 'price' => 85, 'duration' => '4 hours'],
            ['name' => 'Gorilla Trekking Rwanda', 'price' => 1500, 'duration' => '2 days']
        ];
    }

    private function getTrendingDestinations()
    {
        return ['Dubai', 'Zanzibar', 'Cape Town', 'Kigali', 'Addis Ababa'];
    }

    private function getSeasonalDeals()
    {
        return [
            ['destination' => 'Zanzibar', 'discount' => '25%', 'valid_until' => '2024-12-31'],
            ['destination' => 'Dubai', 'discount' => '15%', 'valid_until' => '2024-11-30']
        ];
    }

    private function getLastMinuteDeals()
    {
        return [
            ['route' => 'NBO-MBA', 'original_price' => 120, 'sale_price' => 89, 'departure' => 'Tomorrow'],
            ['route' => 'NBO-EBB', 'original_price' => 180, 'sale_price' => 135, 'departure' => 'This Weekend']
        ];
    }

    /**
     * Get user stats helper method
     */
    private function getUserStats($userId)
    {
        $bookings = Booking::where('user_id', $userId)->get();
        
        return [
            'total_bookings' => $bookings->count(),
            'confirmed_bookings' => $bookings->where('status', 'confirmed')->count(),
            'pending_bookings' => $bookings->where('status', 'pending')->count(),
            'cancelled_bookings' => $bookings->where('status', 'cancelled')->count(),
            'total_spent' => $bookings->where('status', 'confirmed')->sum('total_amount'),
            'countries_visited' => $bookings->pluck('country')->unique()->filter()->count(),
            'profile_completion' => $this->calculateProfileCompletion(auth()->user())
        ];
    }

    /**
     * Get travel stats helper method
     */
    private function getTravelStats($userId)
    {
        return [
            'countries_visited' => $this->getCountriesVisited($userId),
            'cities_visited' => $this->getCitiesVisited($userId),
            'total_miles' => $this->calculateTotalMiles($userId),
            'loyalty_points' => $this->getLoyaltyPoints($userId),
            'carbon_offset' => rand(100, 500) . ' kg CO2'
        ];
    }
    
    /**
     * View all user bookings
     * 
     * @return \Illuminate\Http\Response
     */
    public function allBookings()
    {
        $user = auth()->user();
        $bookings = Booking::with(['flight', 'hotel', 'tour', 'destination'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);
            
        return view('dashboard.bookings.user.bookings', [
            'bookings' => $bookings,
            'user' => $user
        ]);
    }
    
    /**
     * View all flight bookings
     * 
     * @return \Illuminate\Http\Response
     */
    public function flightBookings()
    {
        $user = auth()->user();
        $bookings = Booking::with('flight')
            ->where('user_id', $user->id)
            ->where('type', 'flight')
            ->latest()
            ->paginate(10);
            
        return view('dashboard.flight-bookings', [
            'bookings' => $bookings,
            'user' => $user
        ]);
    }
    
    /**
     * Get payment statistics
     * 
     * @return array
     */
    private function getPaymentStats()
    {
        $userId = auth()->id();
        
        return \Cache::remember('payment_stats_user_' . $userId, 5, function () use ($userId) {
            return [
                'total' => \App\Models\Payment::where('user_id', $userId)->count(),
                'completed' => \App\Models\Payment::where('user_id', $userId)->where('status', 'completed')->count(),
                'failed' => \App\Models\Payment::where('user_id', $userId)->where('status', 'failed')->count(),
                'pending' => \App\Models\Payment::where('user_id', $userId)->where('status', 'pending')->count(),
                'last_updated' => now()->format('Y-m-d H:i:s')
            ];
        });
    }


    
    /**
     * Get user's top destinations with booking counts and spending
     */
    // private function getUserTopDestinations($userId, $limit = 5)
    // {
    //     return DB::table('bookings')
    //         ->select(
    //             DB::raw('COALESCE(destination_city, arrival_city, destination, hotel_name, tour_name) as destination_name'),
    //             DB::raw('COUNT(*) as booking_count'),
    //             DB::raw('SUM(total_amount) as total_amount')
    //         )
    //         ->where('user_id', $userId)
    //         ->where('status', '!=', 'cancelled')
    //         ->whereNotNull(DB::raw('COALESCE(destination_city, arrival_city, destination, hotel_name, tour_name)'))
    //         ->groupBy('destination_name')
    //         ->orderBy('booking_count', 'desc')
    //         ->orderBy('total_amount', 'desc')
    //         ->limit($limit)
    //         ->get();
    // }

    /**
     * Get recent user activities
     */
    private function getRecentActivities($userId, $limit = 10)
    {
        $activities = collect();
        
        // Get recent bookings as activities
        $recentBookings = Booking::where('user_id', $userId)
            ->latest()
            ->limit($limit)
            ->get()
            ->map(function ($booking) {
                return (object) [
                    'id' => $booking->id,
                    'action' => 'booking_created',
                    'description' => "Created {$booking->type} booking #{$booking->booking_reference}",
                    'created_at' => $booking->created_at,
                    'type' => 'booking'
                ];
            });

        $activities = $activities->merge($recentBookings);

        // Get recent payments as activities (if you have a payments table)
        try {
            if (Schema::hasTable('payments')) {
                $recentPayments = DB::table('payments')
                    ->where('user_id', $userId)
                    ->latest('created_at')
                    ->limit($limit)
                    ->get()
                    ->map(function ($payment) {
                        return (object) [
                            'id' => $payment->id,
                            'action' => 'payment_made',
                            'description' => "Payment of $" . number_format($payment->amount, 2) . " completed",
                            'created_at' => Carbon::parse($payment->created_at),
                            'type' => 'payment'
                        ];
                    });

                $activities = $activities->merge($recentPayments);
            }
        } catch (\Exception $e) {
            Log::info('Payments table not found, skipping payment activities');
        }

        // Sort by date and limit
        return $activities
            ->sortByDesc('created_at')
            ->take($limit)
            ->values();
    }

    /**
     * Get recent payments for the user
     */
    private function getRecentPayments($userId, $limit = 10)
    {
        try {
            if (Schema::hasTable('payments')) {
                return DB::table('payments')
                    ->where('user_id', $userId)
                    ->latest('created_at')
                    ->limit($limit)
                    ->get()
                    ->map(function ($payment) {
                        return (object) [
                            'id' => $payment->id,
                            'amount' => $payment->amount,
                            'payment_method' => $payment->payment_method ?? 'credit_card',
                            'status' => $payment->status ?? 'completed',
                            'created_at' => Carbon::parse($payment->created_at)
                        ];
                    });
            }
        } catch (\Exception $e) {
            Log::info('Payments table not found, returning empty collection');
        }

        // Return dummy payment data based on bookings if no payments table
        return Booking::where('user_id', $userId)
            ->where('status', 'confirmed')
            ->latest()
            ->limit($limit)
            ->get()
            ->map(function ($booking) {
                return (object) [
                    'id' => $booking->id,
                    'amount' => $booking->total_amount,
                    'payment_method' => 'credit_card',
                    'status' => 'completed',
                    'created_at' => $booking->created_at
                ];
            });
    }

    /**
     * API endpoint to get fresh user stats
     */
    // public function getStats(Request $request)
    // {
    //     try {
    //         $user = auth()->user();
    //         if (!$user) {
    //             return response()->json(['error' => 'Unauthorized'], 401);
    //         }

    //         $stats = $this->getUserStats($user->id);
    //         $travelStats = $this->getTravelStats($user->id);
    //         $bookingStats = $this->getDetailedBookingStats($user->id);

    //         return response()->json([
    //             'success' => true,
    //             'stats' => $stats,
    //             'travel_stats' => $travelStats,
    //             'booking_stats' => $bookingStats,
    //             'updated_at' => now()->toISOString()
    //         ]);

    //     } catch (\Exception $e) {
    //         Log::error('Error fetching user dashboard stats: ' . $e->getMessage());
    //         return response()->json(['error' => 'Failed to fetch stats'], 500);
    //     }
    // }

    /**
     * Get detailed booking statistics for real-time updates
     */
    private function getDetailedBookingStats($userId)
    {
        $bookings = Booking::where('user_id', $userId)->get();

        return [
            'all' => $bookings->count(),
            'confirmed' => $bookings->where('status', 'confirmed')->count(),
            'processing' => $bookings->where('status', 'processing')->count(),
            'cancelled' => $bookings->where('status', 'cancelled')->count(),
            'flights' => $bookings->where('type', 'flight')->count(),
            'hotels' => $bookings->where('type', 'hotel')->count(),
            'tours' => $bookings->where('type', 'tour')->count(),
            'destinations' => $bookings->pluck('destination_city', 'arrival_city', 'destination')
                ->filter()
                ->unique()
                ->count(),
            'recent_flights' => $bookings->where('type', 'flight')
                ->latest()
                ->take(5)
                ->map(function ($booking) {
                    return [
                        'id' => $booking->id,
                        'booking_reference' => $booking->booking_reference,
                        'departure_airport' => $booking->departure_city,
                        'arrival_airport' => $booking->arrival_city,
                        'airline' => $booking->airline,
                        'flight_number' => $booking->flight_number,
                        'total_amount' => $booking->total_amount,
                        'status' => $booking->status,
                        'created_at' => $booking->created_at->toISOString()
                    ];
                })
                ->values()
        ];
    }

    /**
     * Show all user bookings
     */
    // public function allBookings(Request $request)
    // {
    //     $user = auth()->user();
        
    //     $bookings = Booking::where('user_id', $user->id)
    //         ->with(['user'])
    //         ->latest()
    //         ->paginate(20);

    //     return view('dashboard.bookings', compact('bookings', 'user'));
    // }

    /**
     * User Profile Management
     */
    public function profile()
    {
        $user = auth()->user();
        
        $profileData = [
            'user' => $user,
            'stats' => $this->getDashboardStats(),
            'travel_preferences' => [
                'preferred_class' => $user->preferred_class ?? 'economy',
                'seat_preference' => $user->seat_preference ?? 'window',
                'meal_preference' => $user->meal_preference ?? 'standard',
                'special_assistance' => $user->special_assistance ?? false,
            ],
            'notification_preferences' => [
                'email_notifications' => $user->email_notifications ?? true,
                'sms_notifications' => $user->sms_notifications ?? false,
                'push_notifications' => $user->push_notifications ?? true,
                'price_alerts' => $user->price_alerts ?? true,
            ],
            'security_settings' => [
                'two_factor_enabled' => $user->two_factor_enabled ?? false,
                'login_notifications' => $user->login_notifications ?? true,
                'session_timeout' => $user->session_timeout ?? 60,
            ]
        ];

        return view('dashboard.profile', $profileData);
    }

    /**
     * Get user's wishlist items
     */
    private function getUserWishlist($userId)
    {
        try {
            if (class_exists('\App\Models\Wishlist')) {
                return \App\Models\Wishlist::where('user_id', $userId)
                    ->with(['flight', 'hotel'])
                    ->latest()
                    ->take(10)
                    ->get();
            }
        } catch (\Exception $e) {
            \Log::info('Wishlist model not found, using sample data');
        }
        
        // Return sample wishlist data if model doesn't exist
        return collect([
            (object) [
                'id' => 1,
                'type' => 'flight',
                'route' => 'Nairobi → Dubai',
                'price' => '$450',
                'status' => 'tracking',
                'created_at' => now()->subDays(2)
            ],
            (object) [
                'id' => 2,
                'type' => 'hotel',
                'name' => 'Burj Al Arab',
                'location' => 'Dubai',
                'price' => '$800/night',
                'status' => 'available',
                'created_at' => now()->subDays(5)
            ],
            (object) [
                'id' => 3,
                'type' => 'destination',
                'name' => 'Maldives',
                'type_label' => 'Dream Destination',
                'status' => 'planning',
                'created_at' => now()->subWeek()
            ]
        ]);
    }

    /**
     * Get enhanced recent activities with better logging
     */
    // private function getRecentActivities($userId, $limit = 10)
    // {
    //     try {
    //         if (class_exists('\App\Models\Activity')) {
    //             return \App\Models\Activity::where('user_id', $userId)
    //                 ->latest()
    //                 ->take($limit)
    //                 ->get();
    //         }
    //     } catch (\Exception $e) {
    //         \Log::info('Activity model not found, using sample data');
    //     }
        
    //     // Return enhanced sample activity data
    //     return collect([
    //         (object) [
    //             'id' => 1,
    //             'action' => 'login',
    //             'description' => 'Logged into account from new device',
    //             'ip_address' => '192.168.1.1',
    //             'created_at' => now()->subMinutes(30)
    //         ],
    //         (object) [
    //             'id' => 2,
    //             'action' => 'flight_search',
    //             'description' => 'Searched for flights to Dubai',
    //             'details' => json_encode(['route' => 'NBO → DXB', 'passengers' => 1]),
    //             'created_at' => now()->subHours(2)
    //         ],
    //         (object) [
    //             'id' => 3,
    //             'action' => 'booking_created',
    //             'description' => 'Created new flight booking to Mombasa',
    //             'details' => json_encode(['booking_ref' => 'GNC123456', 'amount' => '$250']),
    //             'created_at' => now()->subHours(6)
    //         ],
    //         (object) [
    //             'id' => 4,
    //             'action' => 'payment_completed',
    //             'description' => 'Payment completed successfully',
    //             'details' => json_encode(['amount' => '$250', 'method' => 'credit_card']),
    //             'created_at' => now()->subHours(6)
    //         ],
    //         (object) [
    //             'id' => 5,
    //             'action' => 'wishlist_added',
    //             'description' => 'Added Zanzibar to wishlist',
    //             'details' => json_encode(['destination' => 'Zanzibar', 'type' => 'destination']),
    //             'created_at' => now()->subDay()
    //         ],
    //         (object) [
    //             'id' => 6,
    //             'action' => 'profile_updated',
    //             'description' => 'Updated profile information',
    //             'details' => json_encode(['fields' => ['phone', 'emergency_contact']]),
    //             'created_at' => now()->subDays(2)
    //         ],
    //         (object) [
    //             'id' => 7,
    //             'action' => 'password_changed',
    //             'description' => 'Password changed successfully',
    //             'ip_address' => '192.168.1.1',
    //             'created_at' => now()->subDays(3)
    //         ],
    //         (object) [
    //             'id' => 8,
    //             'action' => 'flight_search',
    //             'description' => 'Searched for hotels in Dubai',
    //             'details' => json_encode(['location' => 'Dubai', 'check_in' => '2024-09-15']),
    //             'created_at' => now()->subDays(4)
    //         ]
    //     ]);
    // }

    /**
     * Enhanced activity logging with more details
     */
    // private function logActivity($action, $description, $details = null)
    // {
    //     try {
    //         if (class_exists('\App\Models\Activity')) {
    //             \App\Models\Activity::create([
    //                 'user_id' => auth()->id(),
    //                 'action' => $action,
    //                 'description' => $description,
    //                 'details' => $details ? json_encode($details) : null,
    //                 'ip_address' => request()->ip(),
    //                 'user_agent' => request()->userAgent(),
    //                 'created_at' => now()
    //             ]);
    //         }
    //     } catch (\Exception $e) {
    //         // Fallback to regular logging if Activity model doesn't exist
    //         \Log::info("User Activity - {$action}: {$description}", [
    //             'user_id' => auth()->id(),
    //             'details' => $details,
    //             'ip' => request()->ip()
    //         ]);
    //     }
    // }

    /**
     * Get user dashboard stats for AJAX updates
     */
    // public function getDashboardStats()
    // {
    //     $user = auth()->user();
    //     $userBookings = Booking::where('user_id', $user->id)->get();
    //     $userPayments = Payment::where('user_id', $user->id)->where('status', 'completed')->get();
        
    //     return response()->json([
    //         'booking_stats' => [
    //             'all' => $userBookings->count(),
    //             'confirmed' => $userBookings->where('booking_status', 'confirmed')->count(),
    //             'cancelled' => $userBookings->where('booking_status', 'cancelled')->count(),
    //             'processing' => $userBookings->where('booking_status', 'pending')->count(),
    //             'flights' => $userBookings->where('type', 'flight')->count(),
    //             'hotels' => $userBookings->where('type', 'hotel')->count(),
    //             'tours' => $userBookings->where('type', 'tour')->count(),
    //             'destinations' => $userBookings->pluck('destination_city')->unique()->count(),
    //             'recent_flights' => $userBookings->where('type', 'flight')->take(5)->toArray()
    //         ],
    //         'payment_stats' => [
    //             'total' => $userPayments->count(),
    //             'completed' => $userPayments->where('status', 'completed')->count(),
    //             'failed' => $userPayments->where('status', 'failed')->count(),
    //             'pending' => $userPayments->where('status', 'pending')->count()
    //         ],
    //         'travel_stats' => [
    //             'total_spent' => $userPayments->sum('amount'),
    //             'countries_visited' => $userBookings->pluck('country')->unique()->count(),
    //             'cities_visited' => $userBookings->pluck('destination_city')->unique()->count()
    //         ]
    //     ]);
    // }

    /**
     * Show travel insights page
     */
    public function travelInsights()
    {
        $user = auth()->user();
        
        // Get comprehensive travel insights
        $travelInsights = $this->getTravelInsights($user->id);
        
        return view('user.travel-insights', compact('travelInsights'));
    }

    /**
     * Generate comprehensive travel insights for user
     */
    // private function getTravelInsights($userId)
    // {
    //     $bookings = Booking::where('user_id', $userId)->get();
    //     $payments = Payment::where('user_id', $userId)->get();
        
    //     // Calculate travel frequency
    //     $tripsThisYear = $bookings->where('created_at', '>=', now()->startOfYear())->count();
    //     $travelFrequency = $this->getTravelFrequencyCategory($tripsThisYear);
        
    //     // Calculate average trip cost
    //     $avgTripCost = $payments->avg('amount') ?? 0;
        
    //     // Determine favorite season
    //     $seasonCounts = [];
    //     foreach ($bookings as $booking) {
    //         $month = $booking->created_at->month;
    //         $season = $this->getSeasonFromMonth($month);
    //         $seasonCounts[$season] = ($seasonCounts[$season] ?? 0) + 1;
    //     }
    //     $favoriteSeasonData = $this->getFavoriteSeasonData($seasonCounts);
        
    //     // Calculate carbon footprint (simplified)
    //     $carbonFootprint = $tripsThisYear * 0.3; // Rough estimate
        
    //     // Analyze booking patterns
    //     $avgBookingAdvance = $this->calculateAverageBookingAdvance($bookings);
    //     $preferredBookingDay = $this->getPreferredBookingDay($bookings);
    //     $avgTripDuration = $this->calculateAverageTripDuration($bookings);
        
    //     // Generate recommendations
    //     $recommendations = $this->generatePersonalizedRecommendations($userId, $bookings, $payments);
        
    //     return [
    //         'travel_frequency' => $travelFrequency,
    //         'trips_per_year' => $tripsThisYear,
    //         'avg_trip_cost' => $avgTripCost,
    //         'budget_category' => $this->getBudgetCategory($avgTripCost),
    //         'favorite_season' => $favoriteSeasonData['season'],
    //         'season_trips' => $favoriteSeasonData['count'],
    //         'carbon_footprint' => number_format($carbonFootprint, 1),
    //         'avg_booking_advance' => $avgBookingAdvance,
    //         'preferred_booking_day' => $preferredBookingDay,
    //         'avg_trip_duration' => $avgTripDuration,
    //         'favorite_region' => $this->getFavoriteRegion($bookings),
    //         'preferred_climate' => $this->getPreferredClimate($bookings),
    //         'destination_type' => $this->getDestinationType($bookings),
    //         'avg_travel_distance' => $this->getAverageTravelDistance($bookings),
    //         'recommendations' => $recommendations
    //     ];
    // }

    private function getTravelFrequencyCategory($tripsCount)
    {
        if ($tripsCount >= 15) return 'Frequent Traveler';
        if ($tripsCount >= 8) return 'Regular Traveler';
        if ($tripsCount >= 4) return 'Occasional Traveler';
        return 'New Traveler';
    }

    private function getSeasonFromMonth($month)
    {
        if (in_array($month, [12, 1, 2])) return 'Winter';
        if (in_array($month, [3, 4, 5])) return 'Spring';
        if (in_array($month, [6, 7, 8])) return 'Summer';
        return 'Fall';
    }

    private function getFavoriteSeasonData($seasonCounts)
    {
        if (empty($seasonCounts)) {
            return ['season' => 'Summer', 'count' => 0];
        }
        
        $favoriteSeason = array_keys($seasonCounts, max($seasonCounts))[0];
        return [
            'season' => $favoriteSeason,
            'count' => $seasonCounts[$favoriteSeason]
        ];
    }

    private function getBudgetCategory($avgCost)
    {
        if ($avgCost >= 1500) return 'Luxury traveler';
        if ($avgCost >= 800) return 'Premium traveler';
        if ($avgCost >= 400) return 'Mid-range traveler';
        return 'Budget-conscious traveler';
    }

    private function calculateAverageBookingAdvance($bookings)
    {
        $advances = [];
        foreach ($bookings as $booking) {
            if ($booking->departure_date) {
                $advance = $booking->created_at->diffInDays(\Carbon\Carbon::parse($booking->departure_date));
                if ($advance > 0) $advances[] = $advance;
            }
        }
        
        if (empty($advances)) return '21 days';
        
        $avgAdvance = array_sum($advances) / count($advances);
        return round($avgAdvance) . ' days';
    }

    private function getPreferredBookingDay($bookings)
    {
        $dayCounts = [];
        foreach ($bookings as $booking) {
            $day = $booking->created_at->format('l');
            $dayCounts[$day] = ($dayCounts[$day] ?? 0) + 1;
        }
        
        if (empty($dayCounts)) return 'Tuesday';
        
        return array_keys($dayCounts, max($dayCounts))[0];
    }

    private function calculateAverageTripDuration($bookings)
    {
        $durations = [];
        foreach ($bookings as $booking) {
            if ($booking->departure_date && $booking->return_date) {
                $duration = \Carbon\Carbon::parse($booking->departure_date)->diffInDays(\Carbon\Carbon::parse($booking->return_date));
                if ($duration > 0) $durations[] = $duration;
            }
        }
        
        if (empty($durations)) return '5 days';
        
        $avgDuration = array_sum($durations) / count($durations);
        return round($avgDuration) . ' days';
    }

    private function getFavoriteRegion($bookings)
    {
        $regions = [];
        foreach ($bookings as $booking) {
            $region = $this->getRegionFromDestination($booking->destination ?? $booking->destination_city ?? 'Unknown');
            $regions[$region] = ($regions[$region] ?? 0) + 1;
        }
        
        if (empty($regions)) return 'East Africa';
        
        return array_keys($regions, max($regions))[0];
    }

    private function getRegionFromDestination($destination)
    {
        $eastAfrica = ['nairobi', 'mombasa', 'dar es salaam', 'zanzibar', 'kampala', 'kigali'];
        $westAfrica = ['lagos', 'accra', 'dakar', 'abidjan'];
        $northAfrica = ['cairo', 'casablanca', 'tunis', 'algiers'];
        
        $dest = strtolower($destination);
        
        foreach ($eastAfrica as $city) {
            if (strpos($dest, $city) !== false) return 'East Africa';
        }
        foreach ($westAfrica as $city) {
            if (strpos($dest, $city) !== false) return 'West Africa';
        }
        foreach ($northAfrica as $city) {
            if (strpos($dest, $city) !== false) return 'North Africa';
        }
        
        return 'East Africa';
    }

    private function getPreferredClimate($bookings)
    {
        // Simplified climate preference based on destinations
        return 'Tropical';
    }

    private function getDestinationType($bookings)
    {
        $businessCount = $bookings->where('purpose', 'business')->count();
        $totalCount = $bookings->count();
        
        if ($totalCount === 0) return 'Leisure traveler';
        
        $businessRatio = $businessCount / $totalCount;
        
        if ($businessRatio >= 0.7) return 'Business traveler';
        if ($businessRatio >= 0.3) return 'Business & Leisure Mix';
        return 'Leisure traveler';
    }

    private function getAverageTravelDistance($bookings)
    {
        // Simplified average distance calculation
        return '1,200 km average';
    }

    private function generatePersonalizedRecommendations($userId, $bookings, $payments)
    {
        return [
            'personal' => [
                'Consider booking flights on Tuesday for better deals',
                'Try extending your trips to 7-10 days for better value',
                'Book accommodation 3 weeks in advance for optimal prices'
            ],
            'destinations' => [
                'Zanzibar - Similar to your coastal preferences',
                'Rwanda - Great for eco-tourism experiences',
                'Seychelles - Perfect for luxury getaways'
            ],
            'savings' => [
                'Travel in May-June for 30% cheaper flights',
                'Use our price alerts for your favorite routes',
                'Consider midweek departures for lower costs'
            ]
        ];
    }
}
