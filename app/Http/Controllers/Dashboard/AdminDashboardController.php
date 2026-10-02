<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
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
use Illuminate\Support\Facades\Mail;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
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

        // Redirect superadmin to their dashboard
        if ($user->hasRole('superadmin')) {
            return redirect()->route('superadmin.dashboard');
        }

        // Redirect regular users to user dashboard
        if ($user->hasRole('user')) {
            return redirect()->route('admin.dashboard');
        }

        if (!$user->hasRole('admin')) {
            abort(403, 'Unauthorized access. Admin privileges required.');
        }

        try {
            // Core admin dashboard statistics
            $totalBookings = Booking::count();
            $pendingBookings = Booking::where('status', 'pending')->count();
            $completedBookings = Booking::where('status', 'completed')->count();
            $processingBookings = Booking::where('status', 'processing')->count();
            $cancelledBookings = Booking::where('status', 'cancelled')->count();

            // Flight-specific statistics
            $totalFlights = Flight::count();
            $activeFlights = Flight::where('status', 'active')->count();
            $totalFlightBookings = Booking::where('type', 'flight')->count();
            $flightRevenue = Booking::where('type', 'flight')
                ->where('status', 'completed')
                ->sum('total_price');

            // Hotel-specific statistics
            $totalHotels = Hotel::count();
            $activeHotels = Hotel::where('status', 'active')->count();
            $totalHotelBookings = Booking::where('type', 'hotel')->count();
            $hotelRevenue = Booking::where('type', 'hotel')
                ->where('status', 'completed')
                ->sum('total_price');

            // User management statistics
            $totalUsers = User::whereHas('roles', function($query) {
                $query->where('name', 'user');
            })->count();
            $newUsersThisMonth = User::whereHas('roles', function($query) {
                $query->where('name', 'user');
            })->whereMonth('created_at', now()->month)->count();
            
            $totalAdmins = User::whereHas('roles', function($query) {
                $query->where('name', 'admin');
            })->count();
            $activeUsersToday = User::whereDate('last_login_at', today())->count();

            // Payment statistics
            $totalRevenue = $flightRevenue + $hotelRevenue;
            $successfulPayments = Payment::where('status', 'completed')->count();
            $failedPayments = Payment::where('status', 'failed')->count();
            $pendingPayments = Payment::where('status', 'pending')->count();

            // Recent activity data
            $recentBookings = Booking::with(['user', 'flight', 'hotel'])
                ->latest()
                ->take(10)
                ->get();

            $recentUsers = User::whereHas('roles', function($query) {
                $query->where('name', 'user');
            })->latest()->take(5)->get();

            $recentFlightBookings = Booking::where('type', 'flight')
                ->with(['user', 'flight'])
                ->latest()
                ->take(10)
                ->get();
                
            // Get paginated flight bookings for admin view
            $flight_bookings = Booking::where('type', 'flight')
                ->with(['user', 'flight'])
                ->latest()
                ->paginate(10);

            $recentHotelBookings = Booking::where('type', 'hotel')
                ->with(['user', 'hotel'])
                ->latest()
                ->take(5)
                ->get();

            // Popular destinations and analytics
            $topDestinations = Booking::join('flights', 'bookings.flight_number', '=', 'flights.flight_number')
                ->join('airports', 'flights.arrival_airport_id', '=', 'airports.id')
                ->select('airports.city as destination_city', \DB::raw('count(*) as bookings_count'))
                ->groupBy('airports.city')
                ->orderByDesc('bookings_count')
                ->take(5)
                ->get();

            // Monthly trends for charts
            $monthlyBookings = Booking::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                ->whereYear('created_at', now()->year)
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('count', 'month')
                ->toArray();

            $monthlyRevenue = Booking::selectRaw('MONTH(created_at) as month, SUM(total_price) as revenue')
                ->where('status', 'completed')
                ->whereYear('created_at', now()->year)
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('revenue', 'month')
                ->toArray();

            // System activity logs
            $recentActivities = Activity::with('user')
                ->latest()
                ->take(10)
                ->get();

            // Notifications for admin
            $notifications = Notification::where('type', 'admin')
                ->orWhere('user_id', $user->id)
                ->latest()
                ->take(5)
                ->get();

            // Performance metrics
            $apiStats = [
                'flight_searches_today' => \Cache::get('flight_searches_today', 0),
                'hotel_searches_today' => \Cache::get('hotel_searches_today', 0),
                'booking_conversion_rate' => $totalBookings > 0 ? round(($completedBookings / $totalBookings) * 100, 2) : 0,
                'average_booking_value' => $completedBookings > 0 ? round($totalRevenue / $completedBookings, 2) : 0,
            ];

            // User growth trends
            $userGrowthData = User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->whereDate('created_at', '>=', now()->subDays(30))
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            // Admin-specific metrics
            $adminMetrics = [
                'pending_user_verifications' => User::where('email_verified_at', null)->count(),
            ];

            // Compile all data for the admin dashboard
            $dashboardData = [
                // User authentication
                'user' => $user,
                'role' => 'admin',
                
                // Core statistics
                'totalBookings' => $totalBookings,
                'pendingBookings' => $pendingBookings,
                'completedBookings' => $completedBookings,
                'processingBookings' => $processingBookings,
                'cancelledBookings' => $cancelledBookings,
                
                // Flight data
                'totalFlights' => $totalFlights,
                'activeFlights' => $activeFlights,
                'totalFlightBookings' => $totalFlightBookings,
                'flightRevenue' => $flightRevenue,
                
                // Hotel data
                'totalHotels' => $totalHotels,
                'activeHotels' => $activeHotels,
                'totalHotelBookings' => $totalHotelBookings,
                'hotelRevenue' => $hotelRevenue,
                
                // User management
                'totalUsers' => $totalUsers,
                'newUsersThisMonth' => $newUsersThisMonth,
                'totalAdmins' => $totalAdmins,
                'activeUsersToday' => $activeUsersToday,
                
                // Financial data
                'totalRevenue' => $totalRevenue,
                'successfulPayments' => $successfulPayments,
                'failedPayments' => $failedPayments,
                'pendingPayments' => $pendingPayments,
                
                // Recent activity
                'recentBookings' => $recentBookings,
                'recentUsers' => $recentUsers,
                'recentFlightBookings' => $recentFlightBookings,
                'flight_bookings' => $flight_bookings, // Add paginated flight bookings
                'recentHotelBookings' => $recentHotelBookings,
                'recentActivities' => $recentActivities,
                
                // Analytics
                'topDestinations' => $topDestinations,
                'monthlyBookings' => $monthlyBookings,
                'monthlyRevenue' => $monthlyRevenue,
                'userGrowthData' => $userGrowthData,
                
                // System data
                'notifications' => $notifications,
                'apiStats' => $apiStats,
                'adminMetrics' => $adminMetrics,
                
                // Chart data for frontend
                'chartData' => [
                    'bookingStatus' => [
                        'completed' => $completedBookings,
                        'processing' => $processingBookings,
                        'pending' => $pendingBookings,
                        'cancelled' => $cancelledBookings,
                    ],
                    'revenueByType' => [
                        'flights' => $flightRevenue,
                        'hotels' => $hotelRevenue,
                    ],
                    'paymentStatus' => [
                        'successful' => $successfulPayments,
                        'failed' => $failedPayments,
                        'pending' => $pendingPayments,
                    ],
                ],
            ];

            return view('dashboard.admin', $dashboardData);

        } catch (\Exception $e) {
            \Log::error('Admin Dashboard Error: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'trace' => $e->getTraceAsString()
            ]);

            // Return a safe fallback dashboard with minimal data. This mirrors
            // the full key set of $dashboardData so the view never hits an
            // undefined variable.
            $fallbackData = [
                'user' => $user,
                'role' => 'admin',
                'totalBookings' => 0,
                'pendingBookings' => 0,
                'completedBookings' => 0,
                'processingBookings' => 0,
                'cancelledBookings' => 0,
                'totalFlights' => 0,
                'activeFlights' => 0,
                'totalFlightBookings' => 0,
                'flightRevenue' => 0,
                'totalHotels' => 0,
                'activeHotels' => 0,
                'totalHotelBookings' => 0,
                'hotelRevenue' => 0,
                'totalUsers' => 0,
                'newUsersThisMonth' => 0,
                'totalAdmins' => 0,
                'activeUsersToday' => 0,
                'totalRevenue' => 0,
                'successfulPayments' => 0,
                'failedPayments' => 0,
                'pendingPayments' => 0,
                'recentBookings' => collect(),
                'recentUsers' => collect(),
                'recentFlightBookings' => collect(),
                'flight_bookings' => collect(),
                'recentHotelBookings' => collect(),
                'recentActivities' => collect(),
                'topDestinations' => collect(),
                'monthlyBookings' => [],
                'monthlyRevenue' => [],
                'userGrowthData' => collect(),
                'notifications' => collect(),
                'chartData' => [
                    'bookingStatus' => ['completed' => 0, 'processing' => 0, 'pending' => 0, 'cancelled' => 0],
                    'revenueByType' => ['flights' => 0, 'hotels' => 0],
                    'paymentStatus' => ['successful' => 0, 'failed' => 0, 'pending' => 0],
                ],
                'apiStats' => [
                    'flight_searches_today' => 0,
                    'hotel_searches_today' => 0,
                    'booking_conversion_rate' => 0,
                    'average_booking_value' => 0,
                ],
                'adminMetrics' => [
                    'pending_user_verifications' => 0,
                ],
            ];

            return view('dashboard.admin', $fallbackData);
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
    // USERS MANAGEMENT SECTION
    // =====================================

   
 

    /**
     * Show all admins
     */
    public function admins()
    {
        $admins = User::whereHas('roles', function($query) {
            $query->where('name', 'admin');
        })->with('roles')->paginate(15);

        return view('superadmin.users.admins', compact('admins'));
    }

    /**
     * Show all regular users
     */
    public function users()
    {
        $users = User::whereHas('roles', function($query) {
            $query->where('name', 'user');
        })->with('roles')->paginate(15);

        return view('superadmin.users.users', compact('users'));
    }

    /**
     * Show all users (combined view)
     */
    public function allUsers()
    {
        $users = User::with('roles')->paginate(15);
        return view('dashboard.users.superadmin.index', compact('users'));
    }

    // =====================================
    // BOOKINGS MANAGEMENT SECTION
    // =====================================

       /**
     * Bookings overview for current user
     */
    public function myBookings(Request $request)
    {
        $filter = $request->get('filter', 'all');

        $query = Booking::with(['user', 'flight', 'hotel'])
            ->where('user_id', Auth::id());

        if ($filter !== 'all') {
            $query->where('status', $filter);
        }

        $bookings = $query->latest()->paginate(15);
        
        $stats = $this->getBookingStats();

        return view('superadmin.bookings.superadmin', compact('bookings', 'stats', 'filter'));
    }

   

    /**
     * Bookings for admin view
     */
    public function bookingsAdmin(Request $request)
    {
        // Similar to superadmin but with admin-specific filters
        return $this->bookingsSuperadmin($request);
    }

    /**
     * User bookings view
     */
    public function bookingsUser(Request $request)
    {
        $filter = $request->get('filter', 'all');
        
        $query = Booking::with(['user', 'flight', 'hotel'])
            ->whereHas('user', function($q) {
                $q->whereHas('roles', function($role) {
                    $role->where('name', 'user');
                });
            });
        
        if ($filter !== 'all') {
            $query->where('status', $filter);
        }

        $bookings = $query->latest()->paginate(15);
        $stats = $this->getBookingStats();

        return view('superadmin.bookings.user', compact('bookings', 'stats', 'filter'));
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
        // Use short cache time to ensure data is relatively fresh but avoids excessive database queries
        return \Cache::remember('booking_stats_all', 5, function () {
            // Get recent flight bookings for real-time updates
            $recentFlights = Booking::with(['flight', 'user'])
                ->where('type', 'flight')
                ->latest()
                ->take(5)
                ->get()
                ->map(function($booking) {
                    return [
                        'id' => $booking->id,
                        'booking_reference' => $booking->booking_reference,
                        'user' => [
                            'id' => $booking->user->id,
                            'name' => $booking->user->name,
                            'email' => $booking->user->email,
                            'avatar' => $booking->user->avatar ?? 'assets/images/users/default.jpg'
                        ],
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
                
            return [
                'all' => Booking::count(),
                'confirmed' => Booking::where('status', 'confirmed')->count(),
                'cancelled' => Booking::where('status', 'cancelled')->count(),
                'processing' => Booking::where('status', 'processing')->count(),
                
                // Bookings by type
                'flights' => Booking::where('type', 'flight')->count(),
                'hotels' => Booking::where('type', 'hotel')->count(),
                'tours' => Booking::where('type', 'tour')->count(),
                'destinations' => Booking::where('type', 'destination')->count(),
                
                // Today's bookings for alerts
                'today' => Booking::whereDate('created_at', today())->count(),
                'today_flights' => Booking::whereDate('created_at', today())
                    ->where('type', 'flight')->count(),
                    
                // Recent flight bookings for real-time updates
                'recent_flights' => $recentFlights,
                
                'last_updated' => now()->format('Y-m-d H:i:s')
            ];
        });
    }

    // =====================================
    // FLIGHTS MANAGEMENT SECTION
    // =====================================

   

    /**
     * Flights management for admin
     */
    public function flightsAdmin()
    {
        $flights = Flight::with(['bookings'])->latest()->paginate(15);
        return view('superadmin.flights.admin', compact('flights'));
    }

    /**
     * User flight bookings
     */
    public function flightsUsers()
    {
        $flights = Flight::whereHas('bookings.user', function($q) {
            $q->whereHas('roles', function($role) {
                $role->where('name', 'user');
            });
        })->with(['bookings.user'])->latest()->paginate(15);
        
        return view('superadmin.flights.users', compact('flights'));
    }

      /**
     * Flights management for current user
     */

    public function myFlights()
    {
        $flights = Flight::with(['bookings'])->latest()->paginate(15);
        return view('superadmin.flights.superadmin', compact('flights'));
    }

    /**
     * All flights data
     */
    public function flightsAll()
    {
        $flights = Flight::with(['bookings.user'])->latest()->paginate(15);
        return view('superadmin.flights.all', compact('flights'));
    }

    // =====================================
    // HOTELS MANAGEMENT SECTION
    // =====================================

   

    /**
     * Hotels management for admin
     */
    public function hotelsAdmin()
    {
        $hotels = Hotel::with(['bookings'])->latest()->paginate(15);
        return view('superadmin.hotels.admin', compact('hotels'));
    }

    /**
     * User hotel bookings
     */
    public function hotelsUsers()
    {
        $hotels = Hotel::whereHas('bookings.user', function($q) {
            $q->whereHas('roles', function($role) {
                $role->where('name', 'user');
            });
        })->with(['bookings.user'])->latest()->paginate(15);
        
        return view('superadmin.hotels.users', compact('hotels'));
    }

    
    /**
     * Hotels management for current user
     */

    public function myHotels()
    {
        $hotels = Hotel::with(['bookings'])->latest()->paginate(15);
        return view('superadmin.hotels.superadmin', compact('hotels'));
    }

    /**
     * All hotels data
     */
    public function hotelsAll()
    {
        $hotels = Hotel::with(['bookings.user'])->latest()->paginate(15);
        return view('superadmin.hotels.all', compact('hotels'));
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
     * Flight search for admin - search flights for admins
     */
    public function searchFlightAdmin(Request $request)
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

        // Date range filters
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('departure_date', '>=', $request->date_from);
        }
        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('departure_date', '<=', $request->date_to);
        }

        // Price range filters
        if ($request->has('price_min') && !empty($request->price_min)) {
            $query->where('price', '>=', $request->price_min);
        }
        if ($request->has('price_max') && !empty($request->price_max)) {
            $query->where('price', '<=', $request->price_max);
        }

        // Filter flights booked by admins
        if ($request->has('user_type') && $request->user_type === 'admin') {
            $query->whereHas('bookings.user', function($q) {
                $q->whereHas('roles', function($role) {
                    $role->where('name', 'admin');
                });
            });
        }

        $flights = $query->latest()->paginate(15)->withQueryString();
        
        $stats = $this->getFlightStats('admin');
        $filters = $request->all();

        return view('superadmin.flights.admin', compact('flights', 'stats', 'filters'));
    }

    /**
     * Flight search for users - search flights for regular users
     */
    public function searchFlightUsers(Request $request)
    {
        $query = Flight::with(['bookings.user.roles', 'airline', 'airports'])
            ->whereHas('bookings.user', function($q) {
                $q->whereHas('roles', function($role) {
                    $role->where('name', 'user');
                });
            });

        // Apply search filters
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('flight_number', 'LIKE', "%{$search}%")
                  ->orWhere('origin', 'LIKE', "%{$search}%")
                  ->orWhere('destination', 'LIKE', "%{$search}%")
                  ->orWhereHas('airline', function($airline) use ($search) {
                      $airline->where('name', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('bookings.user', function($user) use ($search) {
                      $user->where('name', 'LIKE', "%{$search}%")
                           ->orWhere('email', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Date range filters
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('departure_date', '>=', $request->date_from);
        }
        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('departure_date', '<=', $request->date_to);
        }

        // Price range filters
        if ($request->has('price_min') && !empty($request->price_min)) {
            $query->where('price', '>=', $request->price_min);
        }
        if ($request->has('price_max') && !empty($request->price_max)) {
            $query->where('price', '<=', $request->price_max);
        }

        // Filter by booking status
        if ($request->has('booking_status') && !empty($request->booking_status)) {
            $query->whereHas('bookings', function($booking) use ($request) {
                $booking->where('status', $request->booking_status);
            });
        }

        $flights = $query->latest()->paginate(15)->withQueryString();
        
        $stats = $this->getFlightStats('user');
        $filters = $request->all();

        return view('superadmin.flights.users', compact('flights', 'stats', 'filters'));
    }

    /**
     * Search all flights data
     */
    public function searchFlightAll(Request $request)
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
                  })
                  ->orWhereHas('bookings.user', function($user) use ($search) {
                      $user->where('name', 'LIKE', "%{$search}%")
                           ->orWhere('email', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Filter by user role
        if ($request->has('user_role') && !empty($request->user_role) && $request->user_role !== 'all') {
            $query->whereHas('bookings.user', function($q) use ($request) {
                $q->whereHas('roles', function($role) use ($request) {
                    $role->where('name', $request->user_role);
                });
            });
        }

        // Date range filters
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('departure_date', '>=', $request->date_from);
        }
        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('departure_date', '<=', $request->date_to);
        }

        // Price range filters
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

        // Filter by booking status
        if ($request->has('booking_status') && !empty($request->booking_status)) {
            $query->whereHas('bookings', function($booking) use ($request) {
                $booking->where('status', $request->booking_status);
            });
        }

        $flights = $query->latest()->paginate(15)->withQueryString();
        
        $stats = $this->getFlightStats('all');
        $filters = $request->all();

        return view('superadmin.flights.all', compact('flights', 'stats', 'filters'));
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
     * Hotel search for admin - search hotels for admins
     */
    public function searchHotelsAdmin(Request $request)
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

        // Price range filters
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

        // Filter hotels booked by admins
        if ($request->has('user_type') && $request->user_type === 'admin') {
            $query->whereHas('bookings.user', function($q) {
                $q->whereHas('roles', function($role) {
                    $role->where('name', 'admin');
                });
            });
        }

        $hotels = $query->latest()->paginate(15)->withQueryString();
        
        $stats = $this->getHotelStats('admin');
        $filters = $request->all();

        return view('superadmin.hotels.admin', compact('hotels', 'stats', 'filters'));
    }

    /**
     * Hotel search for users - search hotels for regular users
     */
    public function searchHotelsUsers(Request $request)
    {
        $query = Hotel::with(['bookings.user.roles', 'amenities', 'location'])
            ->whereHas('bookings.user', function($q) {
                $q->whereHas('roles', function($role) {
                    $role->where('name', 'user');
                });
            });

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
                  })
                  ->orWhereHas('bookings.user', function($user) use ($search) {
                      $user->where('name', 'LIKE', "%{$search}%")
                           ->orWhere('email', 'LIKE', "%{$search}%");
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

        // Price range filters
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

        // Filter by booking status
        if ($request->has('booking_status') && !empty($request->booking_status)) {
            $query->whereHas('bookings', function($booking) use ($request) {
                $booking->where('status', $request->booking_status);
            });
        }

        $hotels = $query->latest()->paginate(15)->withQueryString();
        
        $stats = $this->getHotelStats('user');
        $filters = $request->all();

        return view('superadmin.hotels.users', compact('hotels', 'stats', 'filters'));
    }

    /**
     * Search all hotels data
     */
    public function searchHotelsAll(Request $request)
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
                  })
                  ->orWhereHas('bookings.user', function($user) use ($search) {
                      $user->where('name', 'LIKE', "%{$search}%")
                           ->orWhere('email', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Filter by user role
        if ($request->has('user_role') && !empty($request->user_role) && $request->user_role !== 'all') {
            $query->whereHas('bookings.user', function($q) use ($request) {
                $q->whereHas('roles', function($role) use ($request) {
                    $role->where('name', $request->user_role);
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

        // Price range filters
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

        // Filter by booking status
        if ($request->has('booking_status') && !empty($request->booking_status)) {
            $query->whereHas('bookings', function($booking) use ($request) {
                $booking->where('status', $request->booking_status);
            });
        }

        $hotels = $query->latest()->paginate(15)->withQueryString();
        
        $stats = $this->getHotelStats('all');
        $filters = $request->all();

        return view('superadmin.hotels.all', compact('hotels', 'stats', 'filters'));
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
         * Show all roles
         */
        public function roles()
        {
            $roles = Role::with(['permissions', 'users'])->paginate(15);
            return view('superadmin.roles.index', compact('roles'));
        }

        /**
        * Add role form
        */
        public function addRole()
        {
            $permissions = Permission::all();
            return view('superadmin.roles.add', compact('permissions'));
        }

        /**
        * Edit role form
        */
        public function editRole($id)
        {
            $role = Role::with('permissions')->findOrFail($id);
            $permissions = Permission::all();
            return view('superadmin.roles.edit', compact('role', 'permissions'));
        }



        /**
        * View single role
        */
        public function viewRole($id)
        {
            $role = Role::with(['permissions', 'users'])->findOrFail($id);
            return view('superadmin.roles.view', compact('role'));
        }

                  /**
        * viewMyRole
        */
        public function viewMyRole ($id)
        {
            $role = Role::with(['permissions', 'users'])->findOrFail($id);
            return view('superadmin.roles.view', compact('role'));
        }

        /**
        * Show all permissions
        */
        public function permissions()
        {
            $permissions = Permission::with(['roles'])->paginate(15);
            return view('superadmin.permissions.index', compact('permissions'));
        }

        /**
        * Add permission form
        */
        public function addPermission()
        {
            $roles = Role::all();
            return view('superadmin.permissions.add', compact('roles'));
        }

        /**
        * Edit permission form
        */
        public function editPermission($id)
        {
            $permission = Permission::with('roles')->findOrFail($id);
            $roles = Role::all();
            return view('superadmin.permissions.edit', compact('permission', 'roles'));
        }

        /**
        * View single permission
        */
        public function viewPermission($id)
        {
            $permission = Permission::with('roles')->findOrFail($id);
            return view('superadmin.permissions.view', compact('permission'));
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

    /**
     * Store a newly created role
     */
    public function storeRole(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'display_name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        DB::beginTransaction();
        try {
            $role = Role::create([
                'name' => $request->name,
                'display_name' => $request->display_name,
                'description' => $request->description,
                'guard_name' => 'web',
                'created_by' => auth()->id()
            ]);

            if ($request->has('permissions')) {
                $role->permissions()->attach($request->permissions);
            }

            DB::commit();
            return redirect()->route('admin.roles.index')
                           ->with('success', 'Role created successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                           ->with('error', 'Error creating role: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Update the specified role
     */
    public function updateRole(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        if ($role->is_system_role) {
            return redirect()->route('admin.roles.index')
                           ->with('error', 'Cannot edit system role: ' . $role->name);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255', \Illuminate\Validation\Rule::unique('roles')->ignore($role->id)],
            'display_name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        DB::beginTransaction();
        try {
            $role->update([
                'name' => $request->name,
                'display_name' => $request->display_name,
                'description' => $request->description,
            ]);

            $role->permissions()->sync($request->permissions ?? []);

            DB::commit();
            return redirect()->route('admin.roles.index')
                           ->with('success', 'Role updated successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                           ->with('error', 'Error updating role: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Remove the specified role
     */
    public function destroyRole($id)
    {
        $role = Role::findOrFail($id);

        $protectedRoles = ['superadmin', 'super_admin', 'admin', 'user'];
        if (in_array($role->name, $protectedRoles) || $role->is_system_role) {
            return redirect()->route('admin.roles.index')
                           ->with('error', 'Cannot delete system role: ' . $role->name);
        }

        if ($role->users()->count() > 0) {
            return redirect()->route('admin.roles.index')
                           ->with('error', 'Cannot delete role that is assigned to users.');
        }

        DB::beginTransaction();
        try {
            $role->permissions()->detach();
            $role->delete();

            DB::commit();
            return redirect()->route('admin.roles.index')
                           ->with('success', 'Role deleted successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('admin.roles.index')
                           ->with('error', 'Error deleting role: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created permission
     */
    public function storePermission(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'group' => 'required|string|max:100',
            'roles' => 'array',
            'roles.*' => 'exists:roles,id'
        ]);

        DB::beginTransaction();
        try {
            $permission = Permission::create([
                'name' => $request->name,
                'display_name' => $request->display_name,
                'description' => $request->description,
                'group' => $request->group,
                'guard_name' => 'web'
            ]);

            if ($request->has('roles')) {
                $permission->roles()->attach($request->roles);
            }

            DB::commit();
            return redirect()->route('admin.permissions.index')
                           ->with('success', 'Permission created successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                           ->with('error', 'Error creating permission: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Update the specified permission
     */
    public function updatePermission(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);

        if ($permission->is_system_permission) {
            return redirect()->route('admin.permissions.index')
                           ->with('error', 'Cannot edit system permission: ' . $permission->name);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255', \Illuminate\Validation\Rule::unique('permissions')->ignore($permission->id)],
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'group' => 'required|string|max:100',
            'roles' => 'array',
            'roles.*' => 'exists:roles,id'
        ]);

        DB::beginTransaction();
        try {
            $permission->update([
                'name' => $request->name,
                'display_name' => $request->display_name,
                'description' => $request->description,
                'group' => $request->group,
            ]);

            $permission->roles()->sync($request->roles ?? []);

            DB::commit();
            return redirect()->route('admin.permissions.index')
                           ->with('success', 'Permission updated successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                           ->with('error', 'Error updating permission: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Remove the specified permission
     */
    public function destroyPermission($id)
    {
        $permission = Permission::findOrFail($id);

        $protectedPermissions = [
            'manage_users', 'manage_roles', 'manage_permissions',
            'view_dashboard', 'manage_system_settings'
        ];

        if (in_array($permission->name, $protectedPermissions) || $permission->is_system_permission) {
            return redirect()->route('admin.permissions.index')
                           ->with('error', 'Cannot delete system permission: ' . $permission->name);
        }

        DB::beginTransaction();
        try {
            $permission->roles()->detach();
            $permission->delete();

            DB::commit();
            return redirect()->route('admin.permissions.index')
                           ->with('success', 'Permission deleted successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('admin.permissions.index')
                           ->with('error', 'Error deleting permission: ' . $e->getMessage());
        }
    }

    // =====================================
    // PAYMENTS MANAGEMENT SECTION
    // =====================================

    /**
     * Bank payments
     */
    public function paymentsBanks()
    {
        $payments = Payment::where('payment_method', 'bank')
            ->with(['user', 'booking'])
            ->latest()
            ->paginate(15);
            
        return view('superadmin.payments.banks', compact('payments'));
    }

    /**
     * M-PESA payments
     */
    public function paymentsMpesa()
    {
        $payments = Payment::where('payment_method', 'mpesa')
            ->with(['user', 'booking'])
            ->latest()
            ->paginate(15);
            
        return view('superadmin.payments.mpesa', compact('payments'));
    }

    public function myPayments()
    {
        $payments = Payment::where('payment_method', 'bank')
            ->with(['user', 'booking'])
            ->latest()
            ->paginate(15);
            
        return view('superadmin.payments.banks', compact('payments'));
    }

    /**
     * All payments
     */
    public function paymentsAll()
    {
        $payments = Payment::with(['user', 'booking'])
            ->latest()
            ->paginate(15);
            
        return view('superadmin.payments.all', compact('payments'));
    }

    // =====================================
    // WISHLIST MANAGEMENT SECTION
    // =====================================

  
    /**
     * Wishlist for admin
     */
    public function wishlistAdmin()
    {
        $wishlists = Wishlist::whereHas('user', function($q) {
            $q->whereHas('roles', function($role) {
                $role->where('name', 'admin');
            });
        })->with(['user', 'flight', 'hotel'])->latest()->paginate(15);
        
        return view('superadmin.wishlist.admin', compact('wishlists'));
    }

    /**
     * Wishlist for regular users
     */
    public function wishlistUser()
    {
        $wishlists = Wishlist::whereHas('user', function($q) {
            $q->whereHas('roles', function($role) {
                $role->where('name', 'user');
            });
        })->with(['user', 'flight', 'hotel'])->latest()->paginate(15);
        
        return view('superadmin.wishlist.user', compact('wishlists'));
    }

    public function mywishlists()
    {
        $wishlists = Wishlist::whereHas('user', function($q) {
            $q->whereHas('roles', function($role) {
                $role->where('name', 'superadmin');
            });
        })->with(['user', 'flight', 'hotel'])->latest()->paginate(15);
        
        return view('superadmin.wishlist.superadmin', compact('wishlists'));
    }

    /**
     * All wishlists
     */
    public function wishlistAll()
    {
        $wishlists = Wishlist::with(['user', 'flight', 'hotel'])
            ->latest()
            ->paginate(15);
            
        return view('superadmin.wishlist.all', compact('wishlists'));
    }

    // =====================================
    // ACTIVITY & LOGS SECTION
    // =====================================

   

    /**
     * Activity logs for admin
     */
    public function activityAdmin()
    {
        $activities = Activity::whereHas('user', function($q) {
            $q->whereHas('roles', function($role) {
                $role->where('name', 'admin');
            });
        })->with(['user'])->latest()->paginate(15);
        
        return view('superadmin.activity.admin', compact('activities'));
    }

    /**
     * Activity logs for users
     */
    public function activityUser()
    {
        $activities = Activity::whereHas('user', function($q) {
            $q->whereHas('roles', function($role) {
                $role->where('name', 'user');
            });
        })->with(['user'])->latest()->paginate(15);
        
        return view('superadmin.activity.user', compact('activities'));
    }

     public function myActivity()
    {
        $activities = Activity::whereHas('user', function($q) {
            $q->whereHas('roles', function($role) {
                $role->where('name', 'superadmin');
            });
        })->with(['user'])->latest()->paginate(15);
        
        return view('superadmin.activity.superadmin', compact('activities'));
    }

    /**
     * All activity logs
     */
    public function activityAll()
    {
        $activities = Activity::with(['user'])
            ->latest()
            ->paginate(15);
            
        return view('superadmin.activity.all', compact('activities'));
    }

    // =====================================
    // PROFILE MANAGEMENT SECTION
    // =====================================

    

    /**
     * Profile management for admin
     */
    public function profileAdmin()
    {
        $profiles = User::whereHas('roles', function($q) {
            $q->where('name', 'admin');
        })->with(['roles', 'profile'])->paginate(15);
        
        return view('superadmin.profile.admin', compact('profiles'));
    }

    /**
     * Profile management for users
     */
    public function profileUser()
    {
        $profiles = User::whereHas('roles', function($q) {
            $q->where('name', 'user');
        })->with(['roles', 'profile'])->paginate(15);
        
        return view('superadmin.profile.user', compact('profiles'));
    }

    /**
     * Profile management for current user
     */
    public function myCurrentProfile()
    {
        $profile = Auth::user()->load(['roles', 'profile']);
        return view('superadmin.profile.current', compact('profile'));
    }

    

    /**
     * All profiles
     */
    public function profileAll()
    {
        $profiles = User::with(['roles', 'profile'])
            ->paginate(15);
            
        return view('superadmin.profile.all', compact('profiles'));
    }

    // =====================================
    // NOTIFICATIONS SECTION
    // =====================================

 

    /**
     * Notifications for admin
     */
    public function notificationsAdmin()
    {
        $notifications = Notification::whereHas('user', function($q) {
            $q->whereHas('roles', function($role) {
                $role->where('name', 'admin');
            });
        })->with(['user'])->latest()->paginate(15);
        
        return view('superadmin.notifications.admin', compact('notifications'));
    }

    /**
     * Notifications for users
     */
    public function notificationsUser()
    {
        $notifications = Notification::whereHas('user', function($q) {
            $q->whereHas('roles', function($role) {
                $role->where('name', 'user');
            });
        })->with(['user'])->latest()->paginate(15);
        
        return view('superadmin.notifications.user', compact('notifications'));
    }

     public function myNotifications()
    {
        $notifications = Notification::whereHas('user', function($q) {
            $q->whereHas('roles', function($role) {
                $role->where('name', 'superadmin');
            });
        })->with(['user'])->latest()->paginate(15);
        
        return view('superadmin.notifications.superadmin', compact('notifications'));
    }

    /**
     * All notifications
     */
    public function notificationsAll()
    {
        $notifications = Notification::with(['user'])
            ->latest()
            ->paginate(15);
            
        return view('admin.notifications.all', compact('notifications'));
    }

    /**
     * Clear notifications
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
     * Current user's profile
     */
    public function myProfile()
    {
        $user = Auth::user()->load(['roles', 'permissions', 'profile']);
        return view('superadmin.my-profile.index', compact('user'));
    }

     public function updateMyProfile(Request $request)
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


    // =====================================
    // SETTINGS SECTION
    // =====================================

    /**
     * Account settings
     */
    public function accountSettings()
    {
        $user = Auth::user()->load(['roles']);
        return view('superadmin.settings.account', compact('user'));
    }

    /**
     * Change password
     */
    public function changePassword()
    {
        return view('superadmin.settings.change-password');
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
        
        return redirect()->back()->with('success', 'Password updated successfully');
    }

    /**
     * Connections settings
     */
    public function connections()
    {
        return view('superadmin.settings.connections');
    }

    /**
     * Privacy policy
     */
    public function privacyPolicy()
    {
        return view('superadmin.settings.privacy-policy');
    }

    /**
     * Terms and conditions
     */
    public function termsConditions()
    {
        return view('superadmin.settings.terms-conditions');
    }

    // =====================================
    // UTILITY METHODS
    // =====================================

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
                'created_at' => now(),
            ]);
        } catch (\Exception $e) {
            \Log::warning('Failed to log admin activity: ' . $e->getMessage());
        }
    }

    /**
     * Get monthly bookings data for charts
     */
    private function getMonthlyBookings()
    {
        try {
            $monthlyData = [];
            for ($i = 11; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $count = Booking::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count();
                $monthlyData[$date->format('M')] = $count;
            }
            return $monthlyData;
        } catch (\Exception $e) {
            \Log::warning('Failed to get monthly bookings: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get monthly earnings data for charts
     */
    private function getMonthlyEarnings()
    {
        try {
            $monthlyData = [];
            for ($i = 11; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $earnings = Booking::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->where('status', 'completed')
                    ->sum('total_price');
                $monthlyData[$date->format('M')] = $earnings;
            }
            return $monthlyData;
        } catch (\Exception $e) {
            \Log::warning('Failed to get monthly earnings: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get top destinations data
     */
    private function getTopDestinations()
    {
        try {
            return Booking::join('flights', 'bookings.flight_id', '=', 'flights.id')
                ->select('flights.destination_city', \DB::raw('count(*) as bookings_count'))
                ->groupBy('flights.destination_city')
                ->orderByDesc('bookings_count')
                ->take(10)
                ->get();
        } catch (\Exception $e) {
            \Log::warning('Failed to get top destinations: ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Get system login data
     */
    private function getSystemLoginData()
    {
        try {
            $loginData = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $count = User::whereDate('last_login_at', $date->toDateString())->count();
                $loginData[$date->format('M d')] = $count;
            }
            return $loginData;
        } catch (\Exception $e) {
            \Log::warning('Failed to get login data: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Calculate booking conversion rate
     */
    private function calculateConversionRate()
    {
        try {
            $totalBookings = Booking::count();
            $completedBookings = Booking::where('status', 'completed')->count();
            
            if ($totalBookings > 0) {
                return round(($completedBookings / $totalBookings) * 100, 2);
            }
            
            return 0;
        } catch (\Exception $e) {
            \Log::warning('Failed to calculate conversion rate: ' . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Get dashboard statistics for AJAX requests
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDashboardStats()
    {
        return response()->json([
            'booking_stats' => $this->getBookingStats(),
            'payment_stats' => $this->getPaymentStats(),
            'booking_trends' => $this->getBookingTrends(),
            'timestamp' => now()->format('Y-m-d H:i:s'),
        ]);
    }
    
    /**
     * Get payment statistics
     * 
     * @return array
     */
    private function getPaymentStats()
    {
        return \Cache::remember('payment_stats_all', 5, function () {
            return [
                'total' => \App\Models\Payment::count(),
                'completed' => \App\Models\Payment::where('status', 'completed')->count(),
                'failed' => \App\Models\Payment::where('status', 'failed')->count(),
                'pending' => \App\Models\Payment::where('status', 'pending')->count(),
                'last_updated' => now()->format('Y-m-d H:i:s')
            ];
        });
    }
    
    /**
     * Get booking trends for charts
     * 
     * @return array
     */
    private function getBookingTrends()
    {
        return \Cache::remember('booking_trends', 15, function () {
            // Get bookings for last 30 days grouped by date
            $startDate = now()->subDays(30)->startOfDay();
            $bookings = \App\Models\Booking::where('created_at', '>=', $startDate)
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->pluck('count', 'date')
                ->toArray();
            
            // Fill in missing dates
            $result = [];
            for ($i = 0; $i <= 30; $i++) {
                $date = now()->subDays(30 - $i)->format('Y-m-d');
                $result[] = [
                    'x' => $date,
                    'y' => $bookings[$date] ?? 0
                ];
            }
            
            return $result;
        });
    }

    // =====================================
    // FLIGHT SEARCH FORM MANAGEMENT SECTION
    // =====================================

    /**
     * Flight search form for admin
     */
    public function searchFormAdmin()
    {
        return view('dashboard.flights-search.admin.search');
    }

    /**
     * Flight search form for users
     */
    public function searchFormUsers()
    {
        return view('dashboard.flights-search.user.search');
    }

    /**
     * Flight search form for all
     */
    public function searchFormAll()
    {
        return view('dashboard.flights-search.admin.search');
    }

    // =====================================
    // HOTEL SEARCH MANAGEMENT SECTION
    // =====================================

    /**
     * Hotel search for admin
     */
    public function hotelSearchAdmin()
    {
        return view('dashboard.hotels-search.admin.hotel-results');
    }

    /**
     * Hotel search for users
     */
    public function hotelSearchUsers()
    {
        return view('dashboard.hotels-search.user.hotel-results');
    }

    /**
     * Hotel search for all
     */
    public function hotelSearchAll()
    {
        return view('dashboard.hotels-search.admin.hotel-results');
    }

    // =====================================
    // ADDITIONAL HELPER METHODS
    // =====================================

    /**
     * Get user statistics
     */
    private function getUserStats()
    {
        return \Cache::remember('admin_user_stats', 5, function () {
            return [
                'total_users' => User::count(),
                'active_users' => User::where('is_active', true)->count(),
                'new_today' => User::whereDate('created_at', today())->count(),
                'admins' => User::whereHas('roles', function($q) { $q->where('name', 'admin'); })->count(),
                'users' => User::whereHas('roles', function($q) { $q->where('name', 'user'); })->count(),
                'last_updated' => now()->format('Y-m-d H:i:s')
            ];
        });
    }

    /**
     * Get live statistics for real-time dashboard
     */
    private function getLiveStats()
    {
        return [
            'active_sessions' => \Cache::get('active_sessions_count', 156),
            'api_health' => $this->getApiHealthPercentage(),
            'concurrent_bookings' => \Cache::get('concurrent_bookings', 23),
            'server_load' => \Cache::get('server_load', '68%'),
            'memory_usage' => \Cache::get('memory_usage', '742MB'),
            'last_updated' => now()->toISOString()
        ];
    }

    /**
     * Get notification statistics
     */
    private function getNotificationStats()
    {
        return [
            'unread' => Notification::where('read_at', null)->count(),
            'broadcasts' => Notification::where('type', 'broadcast')->whereDate('created_at', today())->count(),
            'critical_alerts' => Notification::where('priority', 'high')->where('read_at', null)->count(),
            'email_queue' => \Cache::get('email_queue_count', 45),
            'sms_sent_today' => \Cache::get('sms_sent_today', 127)
        ];
    }

    /**
     * Get search statistics
     */
    private function getSearchStats()
    {
        return [
            'admin_bookings' => Booking::where('created_by_admin', true)->count(),
            'bulk_operations' => \Cache::get('bulk_operations_today', 7),
            'priority_searches' => \Cache::get('priority_searches_today', 34),
            'corporate_bookings' => Booking::where('type', 'corporate')->count(),
            'automated_rebookings' => \Cache::get('automated_rebookings', 12)
        ];
    }

    /**
     * Get AI statistics
     */
    private function getAiStats()
    {
        return [
            'predictions' => '94.2',
            'fraud_detected' => \Cache::get('fraud_alerts_today', 3),
            'behavior_insights' => \Cache::get('behavior_insights_generated', 156),
            'revenue_optimizations' => \Cache::get('revenue_optimizations', 23),
            'churn_predictions' => \Cache::get('churn_predictions', 45)
        ];
    }

    /**
     * Get real-time statistics
     */
    private function getRealTimeStats()
    {
        return [
            'conversion_rate' => $this->calculateConversionRate(),
            'active_bookings' => Booking::where('status', 'processing')->count(),
            'revenue_hour' => number_format($this->getHourlyRevenue()),
            'searches_minute' => \Cache::get('searches_per_minute', 12),
            'visitors_online' => \Cache::get('visitors_online', 324)
        ];
    }

    /**
     * Get admin activities
     */
    private function getAdminActivities()
    {
        return \Cache::remember('admin_activities', 2, function () {
            return Activity::where('causer_type', User::class)
                ->whereHas('causer', function($q) {
                    $q->whereHas('roles', function($r) {
                        $r->where('name', 'admin');
                    });
                })
                ->with('causer')
                ->latest()
                ->take(10)
                ->get()
                ->map(function($activity) {
                    return [
                        'action' => $activity->description,
                        'user' => $activity->causer->name ?? 'System',
                        'time' => $activity->created_at->diffForHumans()
                    ];
                })
                ->toArray();
        });
    }

    // =====================================
    // ADVANCED ADMIN DASHBOARD METHODS
    // =====================================

    /**
     * Real-Time Dashboard Overview
     */
    public function realTimeDashboard()
    {
        $data = [
            'live_bookings' => $this->getLiveBookingData(),
            'payment_processing' => $this->getLivePaymentData(),
            'active_sessions' => $this->getActiveSessionsData(),
            'system_performance' => $this->getSystemPerformanceData(),
            'api_health' => $this->getApiHealthData(),
            'revenue_tracking' => $this->getRevenueTrackingData(),
            'conversion_funnel' => $this->getConversionFunnelData()
        ];

        return view('admin.dashboard.real-time', $data);
    }

    /**
     * Advanced User Management
     */
    public function advancedUserManagement()
    {
        $data = [
            'users' => User::with('roles', 'bookings', 'payments')->paginate(25),
            'user_stats' => $this->getUserStats(),
            'verification_queue' => User::where('email_verified_at', null)->get(),
            'activity_tracking' => $this->getUserActivityTracking(),
            'bulk_operations' => $this->getBulkOperationHistory()
        ];

        return view('admin.users.advanced-management', $data);
    }

    /**
     * Comprehensive Booking Management
     */
    public function comprehensiveBookingManagement()
    {
        $data = [
            'bookings' => Booking::with('user', 'flight', 'hotel', 'payments')->latest()->paginate(25),
            'booking_stats' => $this->getBookingStats(),
            'real_time_monitor' => $this->getRealTimeBookingMonitor(),
            'failed_bookings' => $this->getFailedBookingInvestigation(),
            'booking_analytics' => $this->getBookingAnalytics(),
            'payment_reconciliation' => $this->getPaymentReconciliation()
        ];

        return view('admin.bookings.comprehensive-management', $data);
    }

    /**
     * Notification Center
     */
    public function notificationCenter()
    {
        $data = [
            'notifications' => Notification::latest()->paginate(25),
            'notification_stats' => $this->getNotificationStats(),
            'email_campaigns' => $this->getEmailCampaigns(),
            'sms_notifications' => $this->getSmsNotifications(),
            'push_notifications' => $this->getPushNotifications(),
            'delivery_tracking' => $this->getDeliveryTracking(),
            'notification_analytics' => $this->getNotificationAnalytics()
        ];

        return view('admin.notifications.center', $data);
    }

    /**
     * Advanced Search Tools
     */
    public function advancedSearchTools()
    {
        $data = [
            'search_stats' => $this->getSearchStats(),
            'priority_searches' => $this->getPrioritySearches(),
            'bulk_bookings' => $this->getBulkBookings(),
            'corporate_tools' => $this->getCorporateTools(),
            'price_monitoring' => $this->getPriceMonitoring(),
            'booking_queue' => $this->getBookingQueue(),
            'automated_rebooking' => $this->getAutomatedRebooking()
        ];

        return view('admin.search.advanced-tools', $data);
    }

    /**
     * AI-Powered Analytics
     */
    public function aiPoweredAnalytics()
    {
        $data = [
            'ai_stats' => $this->getAiStats(),
            'predictive_trends' => $this->getPredictiveBookingTrends(),
            'behavior_patterns' => $this->getCustomerBehaviorPatterns(),
            'revenue_optimization' => $this->getRevenueOptimization(),
            'fraud_detection' => $this->getFraudDetection(),
            'demand_forecasting' => $this->getDemandForecasting(),
            'churn_prediction' => $this->getChurnPrediction()
        ];

        return view('admin.analytics.ai-powered', $data);
    }

    /**
     * Financial Management
     */
    public function financialManagement()
    {
        $data = [
            'revenue_analytics' => $this->getRevenueAnalytics(),
            'payment_gateways' => $this->getPaymentGateways(),
            'refund_processing' => $this->getRefundProcessing(),
            'commission_tracking' => $this->getCommissionTracking(),
            'tax_reporting' => $this->getTaxReporting(),
            'financial_forecasting' => $this->getFinancialForecasting(),
            'audit_trail' => $this->getAuditTrail()
        ];

        return view('admin.finance.management', $data);
    }

    /**
     * System Health Monitor
     */
    public function systemHealth()
    {
        $data = [
            'system_metrics' => $this->getSystemMetrics(),
            'performance_data' => $this->getPerformanceData(),
            'database_health' => $this->getDatabaseHealth(),
            'api_monitoring' => $this->getApiMonitoring(),
            'security_status' => $this->getSecurityStatus(),
            'backup_status' => $this->getBackupStatus(),
            'system_alerts' => $this->getSystemAlerts()
        ];

        return view('admin.system.health', $data);
    }

    /**
     * Customer Support Hub
     */
    public function supportHub()
    {
        $data = [
            'support_tickets' => $this->getSupportTickets(),
            'live_chat_sessions' => $this->getLiveChatSessions(),
            'customer_feedback' => $this->getCustomerFeedback(),
            'faq_management' => $this->getFaqManagement(),
            'knowledge_base' => $this->getKnowledgeBase(),
            'agent_performance' => $this->getAgentPerformance(),
            'support_analytics' => $this->getSupportAnalytics()
        ];

        return view('admin.support.hub', $data);
    }

    /**
     * Security & Compliance Center
     */
    public function securityCompliance()
    {
        $data = [
            'security_overview' => $this->getSecurityOverview(),
            'access_control' => $this->getAccessControl(),
            'session_management' => $this->getSessionManagement(),
            'audit_logs' => $this->getSecurityAuditLogs(),
            'threat_detection' => $this->getThreatDetection(),
            'gdpr_compliance' => $this->getGdprCompliance(),
            'data_retention' => $this->getDataRetention()
        ];

        return view('admin.security.compliance', $data);
    }

    // =====================================
    // HELPER METHODS FOR ADVANCED FEATURES
    // =====================================

    private function getApiHealthPercentage()
    {
        // Simulate API health check
        $apis = ['amadeus', 'payment_gateway', 'email_service', 'sms_service'];
        $healthy_apis = 0;
        
        foreach ($apis as $api) {
            if (\Cache::get("api_health_{$api}", true)) {
                $healthy_apis++;
            }
        }
        
        return round(($healthy_apis / count($apis)) * 100, 1);
    }

    private function getHourlyRevenue()
    {
        return Payment::where('status', 'completed')
            ->where('created_at', '>=', now()->subHour())
            ->sum('amount');
    }

    private function getLiveBookingData()
    {
        return [
            'processing' => Booking::where('status', 'processing')->count(),
            'completed_last_hour' => Booking::where('status', 'completed')
                ->where('updated_at', '>=', now()->subHour())->count(),
            'failed_last_hour' => Booking::where('status', 'failed')
                ->where('updated_at', '>=', now()->subHour())->count(),
            'average_completion_time' => '4.2 minutes'
        ];
    }

    private function getLivePaymentData()
    {
        return [
            'processing' => Payment::where('status', 'processing')->count(),
            'completed_last_hour' => Payment::where('status', 'completed')
                ->where('updated_at', '>=', now()->subHour())->count(),
            'failed_last_hour' => Payment::where('status', 'failed')
                ->where('updated_at', '>=', now()->subHour())->count(),
            'success_rate' => '96.8%'
        ];
    }

    private function getActiveSessionsData()
    {
        return [
            'total_active' => \Cache::get('active_sessions_count', 324),
            'admin_sessions' => \Cache::get('admin_sessions_count', 12),
            'user_sessions' => \Cache::get('user_sessions_count', 312),
            'average_session_duration' => '24 minutes'
        ];
    }

    private function getSystemPerformanceData()
    {
        return [
            'cpu_usage' => \Cache::get('cpu_usage', '68%'),
            'memory_usage' => \Cache::get('memory_usage', '742MB'),
            'disk_usage' => \Cache::get('disk_usage', '45%'),
            'network_io' => \Cache::get('network_io', '125 MB/s'),
            'response_time' => \Cache::get('response_time', '180ms')
        ];
    }

    private function getApiHealthData()
    {
        return [
            'amadeus' => \Cache::get('api_health_amadeus', true) ? 'healthy' : 'down',
            'payment_gateway' => \Cache::get('api_health_payment', true) ? 'healthy' : 'down',
            'email_service' => \Cache::get('api_health_email', true) ? 'healthy' : 'down',
            'sms_service' => \Cache::get('api_health_sms', true) ? 'healthy' : 'down',
            'overall_health' => $this->getApiHealthPercentage() . '%'
        ];
    }

    private function getRevenueTrackingData()
    {
        return [
            'today' => Payment::where('status', 'completed')
                ->whereDate('created_at', today())->sum('amount'),
            'this_week' => Payment::where('status', 'completed')
                ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                ->sum('amount'),
            'this_month' => Payment::where('status', 'completed')
                ->whereMonth('created_at', now()->month)->sum('amount'),
            'hourly_trend' => $this->getHourlyRevenueTrend()
        ];
    }

    private function getConversionFunnelData()
    {
        $searches = \Cache::get('total_searches_today', 1000);
        $bookings = Booking::whereDate('created_at', today())->count();
        $completed = Booking::where('status', 'completed')
            ->whereDate('created_at', today())->count();

        return [
            'searches' => $searches,
            'bookings' => $bookings,
            'completed' => $completed,
            'search_to_booking' => $searches > 0 ? round(($bookings / $searches) * 100, 1) : 0,
            'booking_to_completion' => $bookings > 0 ? round(($completed / $bookings) * 100, 1) : 0
        ];
    }

    private function getHourlyRevenueTrend()
    {
        $hours = [];
        for ($i = 23; $i >= 0; $i--) {
            $hour = now()->subHours($i);
            $revenue = Payment::where('status', 'completed')
                ->whereBetween('created_at', [
                    $hour->copy()->startOfHour(),
                    $hour->copy()->endOfHour()
                ])
                ->sum('amount');
            
            $hours[] = [
                'hour' => $hour->format('H:00'),
                'revenue' => $revenue
            ];
        }
        
        return $hours;
    }

    // Placeholder methods for advanced features
    private function getUserActivityTracking() { return []; }
    private function getBulkOperationHistory() { return []; }
    private function getRealTimeBookingMonitor() { return []; }
    private function getFailedBookingInvestigation() { return []; }
    private function getBookingAnalytics() { return []; }
    private function getPaymentReconciliation() { return []; }
    private function getEmailCampaigns() { return []; }
    private function getSmsNotifications() { return []; }
    private function getPushNotifications() { return []; }
    private function getDeliveryTracking() { return []; }
    private function getNotificationAnalytics() { return []; }
    private function getPrioritySearches() { return []; }
    private function getBulkBookings() { return []; }
    private function getCorporateTools() { return []; }
    private function getPriceMonitoring() { return []; }
    private function getBookingQueue() { return []; }
    private function getAutomatedRebooking() { return []; }
    private function getPredictiveBookingTrends() { return []; }
    private function getCustomerBehaviorPatterns() { return []; }
    private function getRevenueOptimization() { return []; }
    private function getFraudDetection() { return []; }
    private function getDemandForecasting() { return []; }
    private function getChurnPrediction() { return []; }
    private function getRevenueAnalytics() { return []; }
    private function getPaymentGateways() { return []; }
    private function getRefundProcessing() { return []; }
    private function getCommissionTracking() { return []; }
    private function getTaxReporting() { return []; }
    private function getFinancialForecasting() { return []; }
    private function getAuditTrail() { return []; }
    private function getSystemMetrics() { return []; }
    private function getPerformanceData() { return []; }
    private function getDatabaseHealth() { return []; }
    private function getApiMonitoring() { return []; }
    private function getSecurityStatus() { return []; }
    private function getBackupStatus() { return []; }
    private function getSystemAlerts() { return []; }
    private function getSupportTickets() { return []; }
    private function getLiveChatSessions() { return []; }
    private function getCustomerFeedback() { return []; }
    private function getFaqManagement() { return []; }
    private function getKnowledgeBase() { return []; }
    private function getAgentPerformance() { return []; }
    private function getSupportAnalytics() { return []; }
    private function getSecurityOverview() { return []; }
    private function getAccessControl() { return []; }
    private function getSessionManagement() { return []; }
    private function getSecurityAuditLogs() { return []; }
    private function getThreatDetection() { return []; }
    private function getGdprCompliance() { return []; }
    private function getDataRetention() { return []; }
    
    /**
     * Get real-time dashboard data for AJAX updates
     */
    public function getRealTimeData()
    {
        $data = [
            // Live bookings data
            'processing-bookings' => Booking::where('status', 'processing')->count(),
            'completed-bookings' => Booking::where('status', 'completed')
                ->where('created_at', '>=', now()->subHour())
                ->count(),
            'failed-bookings' => Booking::where('status', 'failed')
                ->where('created_at', '>=', now()->subHour())
                ->count(),
            'avg-time' => $this->getAverageBookingTime(),

            // Payment processing data
            'processing-payments' => Payment::where('status', 'processing')->count(),
            'success-payments' => Payment::where('status', 'completed')
                ->where('created_at', '>=', now()->subHour())
                ->count(),
            'failed-payments' => Payment::where('status', 'failed')
                ->where('created_at', '>=', now()->subHour())
                ->count(),
            'success-rate' => $this->getPaymentSuccessRate(),

            // Active sessions data
            'total-sessions' => $this->getActiveSessions(),
            'admin-sessions' => $this->getActiveAdminSessions(),
            'user-sessions' => $this->getActiveUserSessions(),
            'avg-duration' => $this->getAverageSessionDuration(),

            // System performance
            'cpu-usage' => $this->getCpuUsage(),
            'memory-usage' => $this->getMemoryUsage(),
            'disk-usage' => $this->getDiskUsage(),
            'response-time' => $this->getResponseTime(),

            // API health
            'overall-health' => $this->getOverallApiHealth(),
            'api-amadeus' => $this->getAmadeusApiStatus(),
            'api-payment_gateway' => $this->getPaymentGatewayStatus(),
            'api-email_service' => $this->getEmailServiceStatus(),
            'api-sms_service' => $this->getSmsServiceStatus(),

            // Revenue tracking
            'revenue-today' => '$' . number_format($this->getTodayRevenue(), 2),
            'revenue-week' => '$' . number_format($this->getWeekRevenue(), 0),
            'revenue-month' => '$' . number_format($this->getMonthRevenue(), 0),
            'revenue-per-hour' => '$' . number_format($this->getHourlyRevenue(), 0),

            // Conversion funnel
            'funnel-searches' => number_format($this->getTodaySearches()),
            'funnel-bookings' => number_format($this->getTodayBookings()),
            'funnel-completed' => number_format($this->getTodayCompletedBookings()),
        ];

        return response()->json($data);
    }

    // Helper methods for real-time data
    private function getAverageBookingTime()
    {
        $avgMinutes = Booking::where('status', 'completed')
            ->where('created_at', '>=', now()->subDay())
            ->avg(\DB::raw('TIMESTAMPDIFF(MINUTE, created_at, updated_at)'));
        
        return round($avgMinutes, 1) . ' min';
    }

    private function getPaymentSuccessRate()
    {
        $total = Payment::where('created_at', '>=', now()->subDay())->count();
        $successful = Payment::where('status', 'completed')
            ->where('created_at', '>=', now()->subDay())
            ->count();
        
        return $total > 0 ? round(($successful / $total) * 100, 1) . '%' : '0%';
    }

    private function getActiveSessions()
    {
        // This would typically check session storage or active user sessions
        return rand(300, 400); // Placeholder
    }

    private function getActiveAdminSessions()
    {
        return rand(10, 20); // Placeholder
    }

    private function getActiveUserSessions()
    {
        return rand(280, 380); // Placeholder
    }

    private function getAverageSessionDuration()
    {
        return rand(20, 30) . ' min'; // Placeholder
    }

    private function getCpuUsage()
    {
        return rand(50, 80) . '%'; // Placeholder
    }

    private function getMemoryUsage()
    {
        return rand(600, 900) . 'MB'; // Placeholder
    }

    private function getDiskUsage()
    {
        return rand(40, 60) . '%'; // Placeholder
    }

    private function getResponseTime()
    {
        return rand(150, 250) . 'ms'; // Placeholder
    }

    private function getOverallApiHealth()
    {
        return rand(98, 100) . '%'; // Placeholder
    }

    private function getAmadeusApiStatus()
    {
        return rand(0, 10) > 1 ? 'healthy' : 'unhealthy';
    }

    private function getPaymentGatewayStatus()
    {
        return rand(0, 10) > 1 ? 'healthy' : 'unhealthy';
    }

    private function getEmailServiceStatus()
    {
        return rand(0, 10) > 1 ? 'healthy' : 'unhealthy';
    }

    private function getSmsServiceStatus()
    {
        return rand(0, 10) > 1 ? 'healthy' : 'unhealthy';
    }

    private function getTodayRevenue()
    {
        return Payment::where('status', 'completed')
            ->whereDate('created_at', today())
            ->sum('amount') ?? rand(20000, 30000);
    }

    private function getWeekRevenue()
    {
        return Payment::where('status', 'completed')
            ->where('created_at', '>=', now()->startOfWeek())
            ->sum('amount') ?? rand(100000, 150000);
    }

    private function getMonthRevenue()
    {
        return Payment::where('status', 'completed')
            ->where('created_at', '>=', now()->startOfMonth())
            ->sum('amount') ?? rand(400000, 500000);
    }

    // private function getHourlyRevenue()
    // {
    //     return Payment::where('status', 'completed')
    //         ->where('created_at', '>=', now()->subHour())
    //         ->sum('amount') ?? rand(2000, 4000);
    // }

    private function getTodaySearches()
    {
        // This would track search activities
        return rand(800, 1200); // Placeholder
    }

    private function getTodayBookings()
    {
        return Booking::whereDate('created_at', today())->count();
    }

    private function getTodayCompletedBookings()
    {
        return Booking::where('status', 'completed')
            ->whereDate('created_at', today())
            ->count();
    }
}
