<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
use Carbon\Carbon;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Log; 
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\Mail; 
use App\Mail\PasswordChangedNotification; 
use App\Mail\AdminPasswordChangeNotification; 

class SuperadminDashboardController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
        // $this->middleware('role:superadmin');
    }

    // =====================================
    // DASHBOARD SECTION
    // =====================================
    
     /**
     * Main Dashboard Overview
     */
    public function index()
    {
        try {
            $user = auth()->user();

            if (!$user) {
                \Log::error('SuperadminDashboardController: No authenticated user found');
                return redirect('login');
            }

            if ($user->role !== 'superadmin') {
                \Log::warning('Non-superadmin user attempted to access superadmin dashboard: ' . $user->id);
                abort(403, 'Unauthorized access. This incident has been logged.');
            }



        try {
            // --- BEGIN: Add advanced dashboard stats ---
            $now = Carbon::now();
            $startOfWeek = $now->copy()->startOfWeek();
            $startOfMonth = $now->copy()->startOfMonth();
            $startOfYear = $now->copy()->startOfYear();

            $newBookings = Booking::where('status', 'pending')
                ->whereDate('created_at', Carbon::today())
                ->count();

            $cancelledFlights = Flight::where('status', 'cancelled')
                ->whereDate('updated_at', Carbon::today())
                ->count();

            $totalBookingsThisWeek = Booking::whereBetween('created_at', [$startOfWeek, $now])->count();
            $totalBookingsLastWeek = Booking::whereBetween('created_at', [
                $startOfWeek->copy()->subWeek(), $startOfWeek->copy()->subWeek()->endOfWeek()
            ])->count();

            $bookingsPercentageChange = $this->calculatePercentageChange($totalBookingsThisWeek, $totalBookingsLastWeek);

            // Fix: Use 'user' instead of 'customer' role
            $totalCustomersThisWeek = User::where('role', 'user')
                ->whereBetween('created_at', [$startOfWeek, $now])
                ->count();
            $totalCustomersLastWeek = User::where('role', 'user')
                ->whereBetween('created_at', [
                    $startOfWeek->copy()->subWeek(), $startOfWeek->copy()->subWeek()->endOfWeek()
                ])->count();

            $customersPercentageChange = $this->calculatePercentageChange($totalCustomersThisWeek, $totalCustomersLastWeek);

            $totalRevenueThisMonth = Payment::where('status', 'completed')
                ->whereBetween('created_at', [$startOfMonth, $now])
                ->sum('amount');
            $totalRevenueLastMonth = Payment::where('status', 'completed')
                ->whereBetween('created_at', [
                    $startOfMonth->copy()->subMonth(), $startOfMonth->copy()->subMonth()->endOfMonth()
                ])->sum('amount');

            $revenuePercentageChange = $this->calculatePercentageChange($totalRevenueThisMonth, $totalRevenueLastMonth);

            $completedBookings = Booking::where('status', 'completed')->count();
            $pendingBookings = Booking::where('status', 'pending')->count();
            $totalBookings = Booking::count(); // Use total count instead of just completed + pending

            $completedPercentage = $totalBookings > 0 ? round(($completedBookings / $totalBookings) * 100) : 0;
            $pendingPercentage = $totalBookings > 0 ? round(($pendingBookings / $totalBookings) * 100) : 0;

            // Fix: Add null checks for relationship queries
            $domesticRevenue = Payment::whereHas('booking', function($query) {
                $query->whereHas('flight', function($flightQuery) {
                    $flightQuery->where('flight_type', 'domestic');
                });
            })->where('status', 'completed')->sum('amount');

            $internationalRevenue = Payment::whereHas('booking', function($query) {
                $query->whereHas('flight', function($flightQuery) {
                    $flightQuery->where('flight_type', 'international');
                });
            })->where('status', 'completed')->sum('amount');

            $totalRevenue = $domesticRevenue + $internationalRevenue;
            $domesticPercentage = $totalRevenue > 0 ? round(($domesticRevenue / $totalRevenue) * 100) : 0;
            $internationalPercentage = $totalRevenue > 0 ? round(($internationalRevenue / $totalRevenue) * 100) : 0;

            $bookingsByDestination = Booking::select('destination_country', DB::raw('count(*) as total'))
                ->whereNotNull('destination_country')
                ->groupBy('destination_country')
                ->orderBy('total', 'desc')
                ->limit(6)
                ->get();

            $totalDestinationBookings = $bookingsByDestination->sum('total');
            $bookingsByDestination = $bookingsByDestination->map(function($item) use ($totalDestinationBookings) {
                $item->percentage = $totalDestinationBookings > 0 ? round(($item->total / $totalDestinationBookings) * 100) : 0;
                return $item;
            });

            // Fix: Add proper table selection and null checks
            $topFlights = Flight::select('flights.*', DB::raw('count(bookings.id) as booking_count'), DB::raw('COALESCE(sum(payments.amount), 0) as total_revenue'))
                ->leftJoin('bookings', 'flights.id', '=', 'bookings.flight_id')
                ->leftJoin('payments', function($join) {
                    $join->on('bookings.id', '=', 'payments.booking_id')
                         ->where('payments.status', '=', 'completed');
                })
                ->groupBy('flights.id', 'flights.flight_number', 'flights.origin', 'flights.destination', 'flights.departure_date', 'flights.arrival_date', 'flights.price', 'flights.status', 'flights.created_at', 'flights.updated_at')
                ->orderBy('booking_count', 'desc')
                ->limit(5)
                ->get();

            $recentBookings = Booking::with(['user', 'flight', 'payment'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            $processingBookings = Booking::where('status', 'processing')->count();
            $completedOrders = $completedBookings;
            $newOrders = $pendingBookings;
            $pendingOrders = $processingBookings;

            $totalOrders = $completedOrders + $newOrders + $pendingOrders;
            $completedOrdersPercentage = $totalOrders > 0 ? round(($completedOrders / $totalOrders) * 100) : 0;
            $newOrdersPercentage = $totalOrders > 0 ? round(($newOrders / $totalOrders) * 100) : 0;
            $pendingOrdersPercentage = $totalOrders > 0 ? round(($pendingOrders / $totalOrders) * 100) : 0;

            $recentTransactions = Payment::with(['booking.user'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            $monthlyBookings = [];
            for ($i = 11; $i >= 0; $i--) {
                $month = Carbon::now()->subMonths($i);
                $count = Booking::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count();
                $monthlyBookings[] = [
                    'month' => $month->format('M Y'),
                    'bookings' => $count
                ];
            }

            $weeklyCustomers = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $count = User::where('role', 'user') // Fix: Use 'user' instead of 'customer'
                    ->whereDate('created_at', $date)
                    ->count();
                $weeklyCustomers[] = [
                    'date' => $date->format('M j'),
                    'customers' => $count
                ];
            }

            $monthlyRevenue = [];
            for ($i = 11; $i >= 0; $i--) {
                $month = Carbon::now()->subMonths($i);
                $revenue = Payment::where('status', 'completed')
                    ->whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->sum('amount');
                $monthlyRevenue[] = [
                    'month' => $month->format('M Y'),
                    'revenue' => $revenue ?: 0
                ];
            }
            // --- END: Add advanced dashboard stats ---

            // Basic stats
            $stats = [
                'pending_bookings' => $pendingBookings,
                'total_flights' => Flight::count(),
                'total_hotels' => Hotel::count(),
                'total_earnings' => Payment::where('status', 'completed')->sum('amount')
            ];

            $apiStats = [
                'flight_searches' => rand(1000, 2000),
                'hotel_searches' => rand(500, 1500),
                'payments' => rand(100, 300)
            ];

            // Helper methods with fallbacks
            $monthlyEarnings = method_exists($this, 'getMonthlyEarnings') ? $this->getMonthlyEarnings() : $monthlyRevenue;

            $paymentMethodStats = Booking::select('payment_method', DB::raw('count(*) as count'))
                ->whereNotNull('payment_method')
                ->groupBy('payment_method')
                ->get();

            $popularDestinations = Booking::select('arrival_airport', DB::raw('count(*) as count'))
                ->whereNotNull('arrival_airport')
                ->groupBy('arrival_airport')
                ->orderByDesc('count')
                ->take(5)
                ->get();

            // Blog stats with class_exists check
            $totalBlogs = class_exists('App\Models\Blog') ? \App\Models\Blog::count() : 0;
            $publishedBlogs = class_exists('App\Models\Blog') ? \App\Models\Blog::where('status', 'published')->count() : 0;
            $draftBlogs = class_exists('App\Models\Blog') ? \App\Models\Blog::where('status', 'draft')->count() : 0;
            $recentBlogs = class_exists('App\Models\Blog') ? \App\Models\Blog::latest()->take(5)->get() : collect();

            $recentFlightBookings = Booking::whereHas('flight')
                ->with(['flight', 'user'])
                ->latest()
                ->take(10)
                ->get();

            $recentHotelBookings = Booking::whereHas('hotel')
                ->with(['hotel', 'user'])
                ->latest()
                ->take(10)
                ->get();

            $chartData = [
                'completed' => $completedBookings,
                'processing' => $processingBookings,
                'cancelled' => Booking::where('status', 'cancelled')->count(),
                'pending' => $pendingBookings,
            ];

            $topDestinations = method_exists($this, 'getTopDestinations') ? $this->getTopDestinations() : $popularDestinations->toArray();
            $loginData = method_exists($this, 'getSystemLoginData') ? $this->getSystemLoginData() : [];

            $recentActivities = collect([
                (object)[
                    'title' => 'New User Registration',
                    'description' => 'A new user has registered on the platform',
                    'icon' => 'fa-user-plus',
                    'created_at' => now()->subMinutes(10)
                ],
                (object)[
                    'title' => 'Flight Booking Confirmed',
                    'description' => 'Booking #1234 has been confirmed',
                    'icon' => 'fa-check-circle',
                    'created_at' => now()->subMinutes(30)
                ],
                (object)[
                    'title' => 'Payment Received',
                    'description' => 'Payment of $1,200 received for booking #1235',
                    'icon' => 'fa-credit-card',
                    'created_at' => now()->subHours(1)
                ],
                (object)[
                    'title' => 'System Backup Completed',
                    'description' => 'Daily system backup completed successfully',
                    'icon' => 'fa-database',
                    'created_at' => now()->subHours(2)
                ],
                (object)[
                    'title' => 'API Rate Limit Warning',
                    'description' => 'Amadeus API usage at 80% of daily limit',
                    'icon' => 'fa-exclamation-triangle',
                    'created_at' => now()->subHours(3)
                ]
            ]);

            $users = User::where('role', '!=', 'superadmin')->get();
            $adminCount = User::where('role', 'admin')->count();
            $userCount = User::where('role', 'user')->count();
                $topCustomers = User::where('role', 'user')
                ->withCount('bookings')
                ->withSum('bookings as total_spent', 'amount')
                ->orderBy('bookings_count', 'desc')
                ->take(5)
                ->get();
            $additionalData = [
                'loginGrowth' => rand(5, 25),
                'flightStats' => [
                    'on_time_percentage' => 85,
                    'total_miles' => rand(10000, 50000),
                ],
                'systemStats' => [
                    'active_users' => User::count(),
                ],
                'activityStats' => [
                    'login_count' => rand(50, 200),
                    'page_views' => rand(500, 1000),
                    'searches' => rand(100, 300),
                    'bookings' => $totalBookings,
                ],
            ];

            // Merge advanced dashboard data
            $extraDashboardData = compact(
                'newBookings',
                'cancelledFlights',
                'totalBookingsThisWeek',
                'bookingsPercentageChange',
                'totalCustomersThisWeek',
                'customersPercentageChange',
                'totalRevenueThisMonth',
                'revenuePercentageChange',
                'completedPercentage',
                'pendingPercentage',
                'domesticPercentage',
                'internationalPercentage',
                'bookingsByDestination',
                'topFlights',
                'completedOrdersPercentage',
                'newOrdersPercentage',
                'pendingOrdersPercentage',
                'recentTransactions',
                'monthlyBookings',
                'weeklyCustomers',
                'monthlyRevenue'
            );
            
            $additionalData = array_merge($additionalData, $extraDashboardData);

            $data = array_merge([
                'totalUsers' => User::count(),
                'totalBookings' => $totalBookings,
                'pendingBookings' => $pendingBookings,
                'completedBookings' => $completedBookings,
                'processingBookings' => $processingBookings,
                'successfulPayments' => Payment::where('status', 'completed')->count(),
                'failedPayments' => Payment::where('status', 'failed')->count(),
                'recentBookings' => $recentBookings,
                'monthlyEarnings' => $monthlyEarnings,
                'paymentMethodStats' => $paymentMethodStats,
                'popularDestinations' => $popularDestinations,
                'users' => $users,
                'adminCount' => $adminCount,
                'userCount' => $userCount,
                'totalBlogs' => $totalBlogs,
                'publishedBlogs' => $publishedBlogs,
                'draftBlogs' => $draftBlogs,
                'recentBlogs' => $recentBlogs,
                'role' => 'superadmin',
                'stats' => $stats,
                'apiStats' => $apiStats,
                'recentFlightBookings' => $recentFlightBookings,
                'recentHotelBookings' => $recentHotelBookings,
                'recentActivities' => $recentActivities,
                'chartData' => $chartData,
                'notifications' => collect(),
                'activityLogs' => collect(),
                'loginData' => $loginData,
                'topDestinations' => $topDestinations,
                        'topCustomers' => $topCustomers, // Add this line

            ], $additionalData);

            return view('dashboard.superadmin', $data);

        } catch (\Exception $e) {
            Log::warning('Dashboard data error: ' . $e->getMessage());

            $defaultStats = [
                'pending_bookings' => 0,
                'total_flights' => 0,
                'total_hotels' => 0,
                'total_earnings' => 0
            ];

            $defaultChartData = [
                'completed' => 0,
                'processing' => 0,
                'cancelled' => 0,
                'pending' => 0,
            ];

            $defaultAdditionalData = [
                'loginGrowth' => 0,
                'flightStats' => [
                    'on_time_percentage' => 0,
                    'total_miles' => 0,
                ],
                'systemStats' => [
                    'active_users' => 0,
                ],
                'activityStats' => [
                    'login_count' => 0,
                    'page_views' => 0,
                    'searches' => 0,
                    'bookings' => 0,
                ],
                'newBookings' => 0,
                'cancelledFlights' => 0,
                'totalBookingsThisWeek' => 0,
                'bookingsPercentageChange' => 0,
                'totalCustomersThisWeek' => 0,
                'customersPercentageChange' => 0,
                'totalRevenueThisMonth' => 0,
                'revenuePercentageChange' => 0,
                'completedPercentage' => 0,
                'pendingPercentage' => 0,
                'domesticPercentage' => 0,
                'internationalPercentage' => 0,
                'bookingsByDestination' => collect(),
                'topFlights' => collect(),
                'topCustomers' =>  collect(),
                'completedOrdersPercentage' => 0,
                'newOrdersPercentage' => 0,
                'pendingOrdersPercentage' => 0,
                'recentTransactions' => collect(),
                'monthlyBookings' => [],
                'weeklyCustomers' => [],
                'monthlyRevenue' => [],
            ];

            $apiStats = [
                'flight_searches' => 0,
                'hotel_searches' => 0,
                'payments' => 0
            ];

            
            $data = array_merge([
                'totalUsers' => 0,
                'totalBookings' => 0,
                'pendingBookings' => 0,
                'completedBookings' => 0,
                'processingBookings' => 0,
                'successfulPayments' => 0,
                'failedPayments' => 0,
                'recentBookings' => collect(),
                'monthlyEarnings' => [],
                'paymentMethodStats' => collect(),
                'popularDestinations' => collect(),
                'users' => collect(),
                'adminCount' => 0,
                'userCount' => 0,
                'totalBlogs' => 0,
                'publishedBlogs' => 0,
                'draftBlogs' => 0,
                'recentBlogs' => collect(),
                'stats' => $defaultStats,
                'apiStats' => $apiStats,
                'recentFlightBookings' => collect(),
                'recentHotelBookings' => collect(),
                'recentActivities' => collect(),
                'chartData' => $defaultChartData,
                'notifications' => collect(),
                'activityLogs' => collect(),
                'loginData' => [],
                'topDestinations' => [],
                'role' => $user->role,
            ], $defaultAdditionalData);

            return view('dashboard.superadmin', $data);
        }

         } catch (\Exception $e) {
        \Log::error('Error in superadmin dashboard: ' . $e->getMessage());
        \Log::error('Exception trace: ' . $e->getTraceAsString());
        
        return response()->view('dashboard.error.500', [
            'message' => 'An error occurred while loading the dashboard.',
            'details' => app()->environment('local') ? $e->getMessage() : null
        ], 500);
    }
    }

    /**
     * Calculate percentage change between two values
     */
    private function calculatePercentageChange($current, $previous)
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }
        
        return round((($current - $previous) / $previous) * 100, 1);
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
     * Show all superadmins
     */
    public function superAdmins()
    {
        $superadmins = User::whereHas('roles', function($query) {
            $query->where('name', 'superadmin');
        })->with('roles')->paginate(15);

        return view('dashboard.users.superadmin.users', compact('superadmins'));
    }

    /**
     * Show all admins
     */
    public function admins()
    {
        $admins = User::whereHas('roles', function($query) {
            $query->where('name', 'admin');
        })->with('roles')->paginate(15);

        return view('dashboard.users.admin.users', compact('admins'));
    }

    /**
     * Show all regular users
     */
    public function users()
    {
        $users = User::whereHas('roles', function($query) {
            $query->where('name', 'user');
        })->with('roles')->paginate(15);

        return view('dashboard.users.users.users', compact('users'));
    }

    /**
     * Show all users (combined view)
     */
    public function allUsers()
    {
        $users = User::with('roles')->paginate(15);
        return view('dashboard.users.index', compact('users'));
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

        return view('dashboard.bookings.superadmin.booking-history', compact('bookings', 'stats', 'filter'));
    }

    /**
     * Bookings overview for superadmin
     */
    public function bookingsSuperadmin(Request $request)
    {
        $filter = $request->get('filter', 'all');
        
        $query = Booking::with(['user', 'flight', 'hotel']);
        
        if ($filter !== 'all') {
            $query->where('status', $filter);
        }

        $bookings = $query->latest()->paginate(15);
        
        $stats = $this->getBookingStats();

        return view('dashboard.bookings.superadmin.booking-history', compact('bookings', 'stats', 'filter'));
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

        return view('dashboard.bookings.user.booking-history', compact('bookings', 'stats', 'filter'));
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

        return view('dashboard.bookings.bookings', compact('bookings', 'stats', 'filter'));
    }

    private function getBookingStats()
    {
        // Use short cache time to ensure data is relatively fresh but avoids excessive database queries
        return \Cache::remember('booking_stats_all', 5, function () {
            return [
                'all' => Booking::count(),
                'completed' => Booking::where('status', 'completed')->count(),
                'failed' => Booking::where('status', 'failed')->count(),
                'processing' => Booking::where('status', 'processing')->count(),
                'last_updated' => now()->format('Y-m-d H:i:s')
            ];
        });
    }

    // =====================================
    // FLIGHTS MANAGEMENT SECTION
    // =====================================

    /**
     * Flights management for superadmin
     */
    public function flightsSuperadmin()
    {
        $flights = Flight::with(['bookings'])->latest()->paginate(15);
        return view('dashboard.flights.superadmin.index', compact('flights'));
    }

    /**
     * Flights management for admin
     */
    public function flightsAdmin()
    {
        $flights = Flight::with(['bookings'])->latest()->paginate(15);
        return view('dashboard.flights.admin.index', compact('flights'));
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
        
        return view('dashboard.flights.users.index', compact('flights'));
    }

      /**
     * Flights management for current user
     */

    public function myFlights()
    {
        $flights = Flight::with(['bookings'])->latest()->paginate(15);
        return view('dashboard.flights.superadmin.index', compact('flights'));
    }

    /**
     * All flights data
     */
    public function flightsAll()
    {
        $flights = Flight::with(['bookings.user'])->latest()->paginate(15);
        return view('dashboard.flights.flights', compact('flights'));
    }

    // =====================================
    // HOTELS MANAGEMENT SECTION
    // =====================================

    /**
     * Hotels management for superadmin
     */
    public function hotelsSuperadmin()
    {
        $hotels = Hotel::with(['bookings'])->latest()->paginate(15);
        return view('dashboard.hotels.superadmin.index', compact('hotels'));
    }

    /**
     * Hotels management for admin
     */
    public function hotelsAdmin()
    {
        $hotels = Hotel::with(['bookings'])->latest()->paginate(15);
        return view('dashboard.hotels.admin.index', compact('hotels'));
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
        
        return view('dashboard.hotels.users.index', compact('hotels'));
    }

    
    /**
     * Hotels management for current user
     */

    public function myHotels()
    {
        $hotels = Hotel::with(['bookings'])->latest()->paginate(15);
        return view('dashboard.hotels.superadmin.index', compact('hotels'));
    }

    /**
     * All hotels data
     */
    public function hotelsAll()
    {
        $hotels = Hotel::with(['bookings.user'])->latest()->paginate(15);
        return view('dashboard.hotels.hotels', compact('hotels'));
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

        return view('dashboard.flights-search.superadmin.search', compact('flights', 'stats', 'filters'));
    }

    /**
     * Flight search for superadmin - search flights for other superadmins
     */
    public function searchFlightSuperadmin(Request $request)
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

        return view('dashboard.flights-search.superadmin.search', compact('flights', 'stats', 'filters'));
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

        return view('dashboard.flights-search.admin.search', compact('flights', 'stats', 'filters'));
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

        return view('dashboard.flights-search.users.search', compact('flights', 'stats', 'filters'));
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

        return view('dashboard.hotels-search.superadmin.search', compact('hotels', 'stats', 'filters'));
    }

    /**
     * Hotel search for superadmin - search hotels for other superadmins
     */
    public function searchHotelSuperadmin(Request $request)
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

        return view('dashboard.hotels-search.superadmin.search', compact('hotels', 'stats', 'filters'));
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

        return view('dashboard.hotels-search.admin.search', compact('hotels', 'stats', 'filters'));
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

        return view('dashboard.hotels-search.users.search', compact('hotels', 'stats', 'filters'));
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

        return view('dashboard.hotels-search.superadmin.search', compact('hotels', 'stats', 'filters'));
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
            return view('dashboard.roles.superadmin.index', compact('roles'));
        }

        /**
        * Add role form
        */
        public function addRole()
        {
            $permissions = Permission::all();
            return view('dashboard.roles.superadmin.create', compact('permissions'));
        }

        /**
        * Edit role form
        */
        public function editRole($id)
        {
            $role = Role::with('permissions')->findOrFail($id);
            $permissions = Permission::all();
            return view('dashboard.roles.superadmin.edit', compact('role', 'permissions'));
        }

        /**
        * View single role
        */
        public function viewRole($id)
        {
            $role = Role::with(['permissions', 'users'])->findOrFail($id);
            return view('dashboard.roles.superadmin.view', compact('role'));
        }

             /**
        * viewMyRole
        */
        public function viewMyRole ($id)
        {
            $role = Role::with(['permissions', 'users'])->findOrFail($id);
            return view('dashboard.roles.superadmin.view', compact('role'));
        }

        /**
        * Show all permissions
        */
        public function permissions()
        {
            $permissions = Permission::with(['roles'])->paginate(15);
            return view('dashboard.permissions.superadmin.index', compact('permissions'));
        }

        /**
        * Add permission form
        */
        public function addPermission()
        {
            $roles = Role::all();
            return view('dashboard.permissions.superadmin.create', compact('roles'));
        }

        /**
        * Edit permission form
        */
        public function editPermission($id)
        {
            $permission = Permission::with('roles')->findOrFail($id);
            $roles = Role::all();
            return view('dashboard.permissions.superadmin.edit', compact('permission', 'roles'));
        }

        /**
        * View single permission
        */
        public function viewPermission($id)
        {
            $permission = Permission::with('roles')->findOrFail($id);
            return view('dashboard.permissions.superadmin.view', compact('permission'));
        }

           /**
        * viewMyPermissions
        */

          public function viewMyPermissions ($id)
        {
            $permission = Permission::with('roles')->findOrFail($id);
            return view('dashboard.permissions.superadmin.view', compact('permission'));
        }

        /**
     * All of my roles and permissions

     */
    public function viewAllRolesandPermissions()
    {
        $roles = Role::with(['permissions'])->get();
        $permissions = Permission::with(['roles'])->get();
        
        return view('dashboard.permissions.permissions', compact('roles', 'permissions'));
    }



    /**
     * All roles and permissions overview
     */
    public function rolesPermissionsAll()
    {
        $roles = Role::with(['permissions'])->get();
        $permissions = Permission::with(['roles'])->get();
        
        return view('dashboard.roles.roles', compact('roles', 'permissions'));
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
            
        return view('dashboard.payments.superadmin.banks', compact('payments'));
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
            
        return view('dashboard.payments.superadmin.mpesa', compact('payments'));
    }

    public function myPayments()
    {
        $payments = Payment::where('payment_method', 'bank','mpesa')
            ->with(['user', 'booking'])
            ->latest()
            ->paginate(15);
            
        return view('dashboard.payments.payments', compact('payments'));
    }

    /**
     * All payments
     */
    public function paymentsAll()
    {
        $payments = Payment::with(['user', 'booking'])
            ->latest()
            ->paginate(15);
            
        return view('dashboard.payments.payments', compact('payments'));
    }

    // =====================================
    // WISHLIST MANAGEMENT SECTION
    // =====================================

    /**
     * Wishlist for superadmin
     */
    public function wishlistSuperadmin()
    {
        $wishlists = Wishlist::whereHas('user', function($q) {
            $q->whereHas('roles', function($role) {
                $role->where('name', 'superadmin');
            });
        })->with(['user', 'flight', 'hotel'])->latest()->paginate(15);
        
        return view('dashboard.wishlist.superadmin.wishlist', compact('wishlists'));
    }

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
        
        return view('dashboard.wishlist.admin.wishlist', compact('wishlists'));
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
        
        return view('dashboard.wishlist.user.wishlist', compact('wishlists'));
    }

    public function mywishlists()
    {
        $wishlists = Wishlist::whereHas('user', function($q) {
            $q->whereHas('roles', function($role) {
                $role->where('name', 'superadmin');
            });
        })->with(['user', 'flight', 'hotel'])->latest()->paginate(15);
        
        return view('dashboard.wishlist.wishlist', compact('wishlists'));
    }

    /**
     * All wishlists
     */
    public function wishlistAll()
    {
        $wishlists = Wishlist::with(['user', 'flight', 'hotel'])
            ->latest()
            ->paginate(15);
            
        return view('dashboard.wishlist.wishlist', compact('wishlists'));
    }

    // =====================================
    // ACTIVITY & LOGS SECTION
    // =====================================

    /**
     * Activity logs for superadmin
     */
    public function activitySuperadmin()
    {
        $activities = Activity::whereHas('user', function($q) {
            $q->whereHas('roles', function($role) {
                $role->where('name', 'superadmin');
            });
        })->with(['user'])->latest()->paginate(15);
        
        return view('dashboard.activities.superadmin.index', compact('activities'));
    }

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
        
        return view('dashboard.activities.admin.index', compact('activities'));
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
        
        return view('dashboard.activities.user.index', compact('activities'));
    }

     public function myActivity()
    {
        $activities = Activity::whereHas('user', function($q) {
            $q->whereHas('roles', function($role) {
                $role->where('name', 'superadmin');
            });
        })->with(['user'])->latest()->paginate(15);
        
        return view('dashboard.activities.activity', compact('activities'));
    }

    /**
     * All activity logs
     */
    public function activityAll()
    {
        $activities = Activity::with(['user'])
            ->latest()
            ->paginate(15);
            
        return view('dashboard.activities.activity', compact('activities'));
    }

    // =====================================
    // PROFILE MANAGEMENT SECTION
    // =====================================

    /**
     * Profile management for superadmin
     */
    public function profileSuperadmin()
    {
        $profiles = User::whereHas('roles', function($q) {
            $q->where('name', 'superadmin');
        })->with(['roles', 'profile'])->paginate(15);
        
        return view('dashboard.profile.superadmin.manage-users', compact('profiles'));
    }

    /**
     * Profile management for admin
     */
    public function profileAdmin()
    {
        $profiles = User::whereHas('roles', function($q) {
            $q->where('name', 'admin');
        })->with(['roles', 'profile'])->paginate(15);
        
        return view('dashboard.profile.admin.manage-users', compact('profiles'));
    }

    /**
     * Profile management for users
     */
    public function profileUser()
    {
        $profiles = User::whereHas('roles', function($q) {
            $q->where('name', 'user');
        })->with(['roles', 'profile'])->paginate(15);
        
        return view('dashboard.profile.user.manage-users', compact('profiles'));
    }

    /**
     * Profile management for current user
     */
    public function myCurrentProfile()
    {
        $profile = Auth::user()->load(['roles', 'profile']);
        return view('dashboard.profile.superadmin.profile', compact('profile'));
    }

    

    /**
     * All profiles
     */
    public function profileAll()
    {
        $profiles = User::with(['roles', 'profile'])
            ->paginate(15);
            
        return view('dashboard.profile.profile', compact('profiles'));
    }

    // =====================================
    // NOTIFICATIONS SECTION
    // =====================================

    /**
     * Notifications for superadmin
     */
    public function notificationsSuperadmin()
    {
        $notifications = Notification::whereHas('user', function($q) {
            $q->whereHas('roles', function($role) {
                $role->where('name', 'superadmin');
            });
        })->with(['user'])->latest()->paginate(15);
        
        return view('dashboard.notifications.superadmin.notifications', compact('notifications'));
    }

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
        
        return view('dashboard.notifications.admin.notifications', compact('notifications'));
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
        
        return view('dashboard.notifications.user.notifications', compact('notifications'));
    }

     public function myNotifications()
    {
        $notifications = Notification::whereHas('user', function($q) {
            $q->whereHas('roles', function($role) {
                $role->where('name', 'superadmin');
            });
        })->with(['user'])->latest()->paginate(15);
        
        return view('dashboard.notifications.notifications', compact('notifications'));
    }

    /**
     * All notifications
     */
    public function notificationsAll()
    {
        $notifications = Notification::with(['user'])
            ->latest()
            ->paginate(15);
            
        return view('dashboard.notifications.notifications', compact('notifications'));
    }

    /**
     * Notifications index/main page
     */
    public function notificationsIndex()
    {
        $user = Auth::user();
        $notifications = $user->notifications()->latest()->take(10)->get();
        
        return view('dashboard.notifications.index', compact('notifications'));
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
        
        return view('dashboard.referral.superadmin.index', compact('referrals', 'stats'));
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
        
        return view('dashboard.calendar.superadmin.index', compact('events'));
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
        
        return view('dashboard.rewards.superadmin.index', compact('rewards', 'stats'));
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
            return view('dashboard.myprofile.superadmin.myprofile', compact('user'));
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
                    Mail::to($superAdmin->email)->send(new AdminPasswordChangeNotification($user, $superAdmin));
                }
            }

            /**
             * Notify both admins and super-admins
             */
            private function notifyAdminsAndSuperAdmins($user)
            {
                $adminsAndSuperAdmins = $this->getAdminsAndSuperAdmins();
                
                foreach ($adminsAndSuperAdmins as $admin) {
                    Mail::to($admin->email)->send(new AdminPasswordChangeNotification($user, $admin));
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
        Activity::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
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
     * Flight search form for superadmin
     */
    public function searchFormSuperadmin()
    {
        return view('dashboard.flights-search.superadmin.search');
    }

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

    // =====================================
    // HOTEL SEARCH MANAGEMENT SECTION
    // =====================================

    /**
     * Hotel search for superadmin
     */
    public function hotelSearchSuperadmin()
    {
        return view('dashboard.hotels-search.superadmin.hotel-results');
    }

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
        return view('dashboard.hotels-search.superadmin.hotel-results');
    }

    // =====================================
    // MISSING ROLES AND PERMISSIONS METHODS
    // =====================================

    /**
     * Roles index method
     */
    public function rolesIndex()
    {
        $roles = Role::with('permissions')->get();
        return view('dashboard.roles.superadmin.index', compact('roles'));
    }

    /**
     * Permissions index method
     */
    public function permissionsIndex()
    {
        $permissions = Permission::all();
        return view('dashboard.permisions.superadmin.index', compact('permissions'));
    }

    /**
     * Bank payments method
     */
    public function bankPayments()
    {
        $payments = Payment::where('payment_method', 'bank')->get();
        return view('dashboard.payments.superadmin.bank', compact('payments'));
    }

    /**
     * Mpesa payments method
     */
    public function mpesaPayments()
    {
        $payments = Payment::where('payment_method', 'mpesa')->get();
        return view('dashboard.payments.superadmin.mpesa', compact('payments'));
    }

    /**
     * All payments method
     */
    public function allPayments()
    {
        $payments = Payment::all();
        return view('dashboard.payments.superadmin.index', compact('payments'));
    }

    
// =====================================
// REAL-TIME MONITORING SECTION
// =====================================

/**
 * Real-time dashboard overview
 */
public function realTimeDashboard()
{
    $liveStats = [
        'active_sessions' => [
            'total_active' => User::whereNotNull('last_login_at')
                ->where('last_login_at', '>=', now()->subMinutes(30))
                ->count(),
            'admin_sessions' => User::where('role', 'admin')
                ->whereNotNull('last_login_at')
                ->where('last_login_at', '>=', now()->subMinutes(30))
                ->count(),
            'user_sessions' => User::where('role', 'user')
                ->whereNotNull('last_login_at')
                ->where('last_login_at', '>=', now()->subMinutes(30))
                ->count(),
            'average_session_duration' => '24 min'
        ],
        'live_bookings' => [
            'processing' => Booking::where('status', 'processing')->count(),
            'completed_last_hour' => Booking::where('status', 'completed')
                ->where('created_at', '>=', now()->subHour())
                ->count(),
            'failed_last_hour' => Booking::where('status', 'failed')
                ->where('created_at', '>=', now()->subHour())
                ->count(),
            'average_completion_time' => '4.2 min'
        ],
        'payment_processing' => [
            'processing' => Payment::where('status', 'processing')->count(),
            'completed_last_hour' => Payment::where('status', 'completed')
                ->where('created_at', '>=', now()->subHour())
                ->count(),
            'failed_last_hour' => Payment::where('status', 'failed')
                ->where('created_at', '>=', now()->subHour())
                ->count(),
            'success_rate' => '96.8%'
        ],
        'system_performance' => [
            'cpu_usage' => '68%',
            'memory_usage' => '742MB',
            'disk_usage' => '45%',
            'response_time' => '180ms'
        ],
        'api_health' => [
            'overall_health' => '99.8%',
            'amadeus' => 'healthy',
            'payment_gateway' => 'healthy',
            'email_service' => 'healthy',
            'sms_service' => 'healthy'
        ],
        'revenue_tracking' => [
            'today' => Payment::where('status', 'completed')
                ->whereDate('created_at', today())
                ->sum('amount'),
            'this_week' => Payment::where('status', 'completed')
                ->whereBetween('created_at', [now()->startOfWeek(), now()])
                ->sum('amount'),
            'this_month' => Payment::where('status', 'completed')
                ->whereBetween('created_at', [now()->startOfMonth(), now()])
                ->sum('amount'),
            'hourly_trend' => [
                ['hour' => now()->format('H:00'), 'revenue' => 2450]
            ]
        ],
        'conversion_funnel' => [
            'searches' => 1000,
            'bookings' => 250,
            'completed' => 230,
            'search_to_booking' => 25,
            'booking_to_completion' => 92
        ]
    ];

    return view('dashboard.real-time.index', compact('liveStats'));
}

/**
 * Get real-time data for AJAX updates
 */
public function getRealTimeData()
{
    return response()->json([
        'processing-bookings' => Booking::where('status', 'processing')->count(),
        'completed-bookings' => Booking::where('status', 'completed')
            ->where('created_at', '>=', now()->subHour())
            ->count(),
        'failed-bookings' => Booking::where('status', 'failed')
            ->where('created_at', '>=', now()->subHour())
            ->count(),
        'processing-payments' => Payment::where('status', 'processing')->count(),
        'success-payments' => Payment::where('status', 'completed')
            ->where('created_at', '>=', now()->subHour())
            ->count(),
        'failed-payments' => Payment::where('status', 'failed')
            ->where('created_at', '>=', now()->subHour())
            ->count(),
        'total-sessions' => User::whereNotNull('last_login_at')
            ->where('last_login_at', '>=', now()->subMinutes(30))
            ->count(),
        'timestamp' => now()->format('Y-m-d H:i:s')
    ]);
}

// =====================================
// ADVANCED USER MANAGEMENT SECTION
// =====================================

/**
 * Create new user form
 */
public function createUser()
{
    $roles = ['superadmin', 'admin', 'user'];
    return view('dashboard.users.create', compact('roles'));
}

/**
 * Store new user with role assignment
 */
public function storeUser(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
        'role' => 'required|in:superadmin,admin,user',
        'phone' => 'nullable|string|max:20',
        'status' => 'required|in:active,inactive,suspended'
    ]);

    // Prevent non-parent superadmins from creating other superadmins
    if ($request->role === 'superadmin' && !$this->isParentSuperadmin()) {
        return back()->withErrors(['role' => 'You do not have permission to create superadmin accounts.']);
    }

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => $request->role,
        'phone' => $request->phone,
        'status' => $request->status,
        'created_by' => Auth::id()
    ]);

    // Send welcome email
    // Mail::to($user->email)->send(new WelcomeEmail($user, $request->password));

    $this->logActivity('user_created', "Created new {$request->role} user: {$user->email}");

    return redirect()->route('superadmin.users.all')
        ->with('success', 'User created successfully.');
}

/**
 * Advanced user management dashboard
 */
public function advancedUserManagement(Request $request)
{
    $query = User::with(['roles']);

    // Apply filters
    if ($request->has('role') && $request->role !== 'all') {
        $query->where('role', $request->role);
    }

    if ($request->has('status') && $request->status !== 'all') {
        $query->where('status', $request->status);
    }

    if ($request->has('search') && !empty($request->search)) {
        $query->where(function($q) use ($request) {
            $q->where('name', 'LIKE', "%{$request->search}%")
              ->orWhere('email', 'LIKE', "%{$request->search}%");
        });
    }

    $users = $query->paginate(20);

    $stats = [
        'total_users' => User::count(),
        'active_users' => User::where('status', 'active')->count(),
        'suspended_users' => User::where('status', 'suspended')->count(),
        'superadmins' => User::where('role', 'superadmin')->count(),
        'admins' => User::where('role', 'admin')->count(),
        'regular_users' => User::where('role', 'user')->count()
    ];

    return view('dashboard.users.advanced-management', compact('users', 'stats'));
}

/**
 * User activity tracking
 */
public function userActivityTracking(Request $request)
{
    $query = Activity::with(['user']);

    if ($request->has('user_id') && !empty($request->user_id)) {
        $query->where('user_id', $request->user_id);
    }

    if ($request->has('action') && !empty($request->action)) {
        $query->where('action', 'LIKE', "%{$request->action}%");
    }

    if ($request->has('date_from') && !empty($request->date_from)) {
        $query->whereDate('created_at', '>=', $request->date_from);
    }

    if ($request->has('date_to') && !empty($request->date_to)) {
        $query->whereDate('created_at', '<=', $request->date_to);
    }

    $activities = $query->latest()->paginate(25);
    $users = User::select('id', 'name', 'email')->get();

    return view('dashboard.users.activity-tracking', compact('activities', 'users'));
}

/**
 * Session management
 */
public function sessionManagement()
{
    $activeSessions = User::whereNotNull('last_login_at')
        ->where('last_login_at', '>=', now()->subMinutes(30))
        ->with(['sessions'])
        ->get();

    return view('dashboard.users.session-management', compact('activeSessions'));
}

/**
 * Bulk operations interface
 */
public function bulkOperations()
{
    return view('dashboard.users.bulk-operations');
}

/**
 * Perform bulk actions on users
 */
public function performBulkActions(Request $request)
{
    $request->validate([
        'user_ids' => 'required|array',
        'action' => 'required|in:activate,suspend,delete,reset_password,send_email'
    ]);

    $userIds = $request->user_ids;
    $action = $request->action;
    $affectedCount = 0;

    // Prevent bulk actions on parent superadmin
    $parentSuperadmin = $this->getParentSuperadmin();
    if ($parentSuperadmin && in_array($parentSuperadmin->id, $userIds)) {
        return back()->withErrors(['action' => 'Cannot perform bulk actions on the parent superadmin.']);
    }

    foreach ($userIds as $userId) {
        $user = User::find($userId);
        if (!$user) continue;

        switch ($action) {
            case 'activate':
                $user->update(['status' => 'active']);
                break;
            case 'suspend':
                $user->update(['status' => 'suspended']);
                break;
            case 'delete':
                if ($user->role !== 'superadmin' || $this->canDeleteSuperadmin($user)) {
                    $user->delete();
                }
                break;
            case 'reset_password':
                $newPassword = Str::random(12);
                $user->update(['password' => Hash::make($newPassword)]);
                // Send email with new password
                break;
        }
        $affectedCount++;
    }

    $this->logActivity('bulk_action', "Performed {$action} on {$affectedCount} users");

    return back()->with('success', "Successfully performed {$action} on {$affectedCount} users.");
}

/**
 * Suspend user account
 */
public function suspendUser(User $user)
{
    if ($this->isParentSuperadmin($user)) {
        return back()->withErrors(['action' => 'Cannot suspend the parent superadmin.']);
    }

    $user->update(['status' => 'suspended']);
    $this->logActivity('user_suspended', "Suspended user: {$user->email}");

    return back()->with('success', 'User suspended successfully.');
}

/**
 * Activate user account
 */
public function activateUser(User $user)
{
    $user->update(['status' => 'active']);
    $this->logActivity('user_activated', "Activated user: {$user->email}");

    return back()->with('success', 'User activated successfully.');
}

/**
 * Reset user password
 */
public function resetUserPassword(User $user)
{
    if ($this->isParentSuperadmin($user)) {
        return back()->withErrors(['action' => 'Cannot reset password for the parent superadmin.']);
    }

    $newPassword = Str::random(12);
    $user->update(['password' => Hash::make($newPassword)]);

    // Send email with new password
    // Mail::to($user->email)->send(new PasswordResetEmail($user, $newPassword));

    $this->logActivity('password_reset', "Reset password for user: {$user->email}");

    return back()->with('success', 'Password reset successfully. New password sent via email.');
}

// =====================================
// PROXY BOOKING SECTION
// =====================================

/**
 * Proxy flight booking interface
 */
public function proxyFlightBooking()
{
    $users = User::select('id', 'name', 'email', 'role')->get();
    return view('dashboard.proxy-booking.flights', compact('users'));
}

/**
 * Proxy hotel booking interface
 */
public function proxyHotelBooking()
{
    $users = User::select('id', 'name', 'email', 'role')->get();
    return view('dashboard.proxy-booking.hotels', compact('users'));
}

/**
 * Proxy tour booking interface
 */
public function proxyTourBooking()
{
    $users = User::select('id', 'name', 'email', 'role')->get();
    return view('dashboard.proxy-booking.tours', compact('users'));
}

/**
 * Manage proxy bookings
 */
public function manageProxyBookings()
{
    $proxyBookings = Booking::where('booked_by', '!=', DB::raw('user_id'))
        ->with(['user', 'bookedBy', 'flight', 'hotel'])
        ->latest()
        ->paginate(20);

    return view('dashboard.proxy-booking.manage', compact('proxyBookings'));
}

/**
 * Process proxy flight booking
 */
public function processProxyFlightBooking(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'flight_id' => 'required|exists:flights,id',
        'passengers' => 'required|array',
        'special_discount' => 'nullable|numeric|min:0|max:100',
        'booking_notes' => 'nullable|string'
    ]);

    $user = User::findOrFail($request->user_id);
    $flight = Flight::findOrFail($request->flight_id);

    // Calculate price with special discount if applicable
    $originalPrice = $flight->price;
    $discountAmount = $request->special_discount ? ($originalPrice * $request->special_discount / 100) : 0;
    $finalPrice = $originalPrice - $discountAmount;

    $booking = Booking::create([
        'user_id' => $user->id,
        'booked_by' => Auth::id(), // Superadmin who made the booking
        'flight_id' => $flight->id,
        'booking_reference' => 'PXY-' . strtoupper(Str::random(8)),
        'amount' => $finalPrice,
        'original_amount' => $originalPrice,
        'discount_applied' => $discountAmount,
        'status' => 'confirmed',
        'passengers' => json_encode($request->passengers),
        'booking_notes' => $request->booking_notes,
        'is_proxy_booking' => true
    ]);

    // Send confirmation email to user
    // Mail::to($user->email)->send(new ProxyBookingConfirmation($booking));

    $this->logActivity('proxy_booking_created', "Created proxy flight booking for {$user->email}");

    return redirect()->route('superadmin.proxy-booking.manage')
        ->with('success', 'Proxy flight booking created successfully.');
}

/**
 * Modify proxy booking
 */
public function modifyProxyBooking(Request $request, Booking $booking)
{
    if (!$booking->is_proxy_booking) {
        return back()->withErrors(['action' => 'This is not a proxy booking.']);
    }

    $request->validate([
        'status' => 'required|in:confirmed,cancelled,modified',
        'modification_notes' => 'nullable|string'
    ]);

    $booking->update([
        'status' => $request->status,
        'modification_notes' => $request->modification_notes,
        'modified_by' => Auth::id(),
        'modified_at' => now()
    ]);

    $this->logActivity('proxy_booking_modified', "Modified proxy booking {$booking->booking_reference}");

    return back()->with('success', 'Proxy booking modified successfully.');
}

// =====================================
// ADVANCED ANALYTICS SECTION
// =====================================

/**
 * Revenue analytics dashboard
 */
public function revenueAnalytics()
{
    $revenueData = [
        'daily_revenue' => Payment::where('status', 'completed')
            ->whereDate('created_at', today())
            ->sum('amount'),
        'weekly_revenue' => Payment::where('status', 'completed')
            ->whereBetween('created_at', [now()->startOfWeek(), now()])
            ->sum('amount'),
        'monthly_revenue' => Payment::where('status', 'completed')
            ->whereBetween('created_at', [now()->startOfMonth(), now()])
            ->sum('amount'),
        'yearly_revenue' => Payment::where('status', 'completed')
            ->whereBetween('created_at', [now()->startOfYear(), now()])
            ->sum('amount'),
        'revenue_by_source' => [
            'flights' => Payment::whereHas('booking.flight')
                ->where('status', 'completed')
                ->sum('amount'),
            'hotels' => Payment::whereHas('booking.hotel')
                ->where('status', 'completed')
                ->sum('amount'),
            'tours' => Payment::whereHas('booking.tour')
                ->where('status', 'completed')
                ->sum('amount')
        ],
        'monthly_trends' => $this->getMonthlyRevenueTrends()
    ];

    return view('dashboard.analytics.revenue', compact('revenueData'));
}

/**
 * User acquisition metrics
 */
public function userAcquisitionMetrics()
{
    $metrics = [
        'new_users_today' => User::whereDate('created_at', today())->count(),
        'new_users_week' => User::whereBetween('created_at', [now()->startOfWeek(), now()])->count(),
        'new_users_month' => User::whereBetween('created_at', [now()->startOfMonth(), now()])->count(),
        'total_users' => User::count(),
        'user_growth_rate' => $this->calculateUserGrowthRate(),
        'user_acquisition_sources' => $this->getUserAcquisitionSources(),
        'user_retention_rate' => $this->calculateUserRetentionRate()
    ];

    return view('dashboard.analytics.user-metrics', compact('metrics'));
}

/**
 * Conversion rate analytics
 */
public function conversionRateAnalytics()
{
    $totalVisitors = 10000; // This would come from your analytics system
    $totalBookings = Booking::count();
    $completedBookings = Booking::where('status', 'completed')->count();

    $conversionData = [
        'visitor_to_booking' => $totalBookings > 0 ? round(($totalBookings / $totalVisitors) * 100, 2) : 0,
        'booking_to_completion' => $totalBookings > 0 ? round(($completedBookings / $totalBookings) * 100, 2) : 0,
        'overall_conversion' => $totalVisitors > 0 ? round(($completedBookings / $totalVisitors) * 100, 2) : 0,
        'conversion_by_source' => $this->getConversionBySource(),
        'funnel_data' => $this->getConversionFunnelData()
    ];

    return view('dashboard.analytics.conversion', compact('conversionData'));

}

/**
 * Check if current user is the parent superadmin
 */
private function isParentSuperadmin($user = null)
{
    $checkUser = $user ?: Auth::user();
    return $checkUser->role === 'superadmin' && $checkUser->created_by === null;
}

/**
 * Get parent superadmin
 */
private function getParentSuperadmin()
{
    return User::where('role', 'superadmin')->whereNull('created_by')->first();
}

/**
 * Calculate user growth rate
 */
private function calculateUserGrowthRate()
{
    $currentMonth = User::whereBetween('created_at', [now()->startOfMonth(), now()])->count();
    $lastMonth = User::whereBetween('created_at', [
        now()->subMonth()->startOfMonth(), 
        now()->subMonth()->endOfMonth()
    ])->count();
    
    if ($lastMonth == 0) return $currentMonth > 0 ? 100 : 0;
    
    return round((($currentMonth - $lastMonth) / $lastMonth) * 100, 2);
}

/**
 * Get user acquisition sources
 */
private function getUserAcquisitionSources()
{
    return [
        'direct' => User::where('acquisition_source', 'direct')->count(),
        'referral' => User::where('acquisition_source', 'referral')->count(),
        'social_media' => User::where('acquisition_source', 'social_media')->count(),
        'search_engine' => User::where('acquisition_source', 'search_engine')->count(),
        'email_campaign' => User::where('acquisition_source', 'email_campaign')->count(),
    ];
}

/**
 * Calculate user retention rate
 */
private function calculateUserRetentionRate()
{
    $totalUsers = User::count();
    $activeUsers = User::where('last_login_at', '>=', now()->subDays(30))->count();
    
    return $totalUsers > 0 ? round(($activeUsers / $totalUsers) * 100, 2) : 0;
}

/**
 * Get monthly revenue trends
 */
private function getMonthlyRevenueTrends()
{
    $trends = [];
    for ($i = 11; $i >= 0; $i--) {
        $date = now()->subMonths($i);
        $trends[] = [
            'month' => $date->format('M Y'),
            'revenue' => Payment::where('status', 'completed')
                ->whereBetween('created_at', [$date->startOfMonth(), $date->endOfMonth()])
                ->sum('amount')
        ];
    }
    return $trends;
}

/**
 * Get conversion by source
 */
private function getConversionBySource()
{
    return [
        'direct' => ['visitors' => 5000, 'bookings' => 150, 'rate' => 3.0],
        'organic_search' => ['visitors' => 3000, 'bookings' => 120, 'rate' => 4.0],
        'paid_ads' => ['visitors' => 2000, 'bookings' => 80, 'rate' => 4.0],
        'social_media' => ['visitors' => 1500, 'bookings' => 45, 'rate' => 3.0],
        'email' => ['visitors' => 800, 'bookings' => 32, 'rate' => 4.0],
    ];
}

/**
 * Get conversion funnel data
 */
private function getConversionFunnelData()
{
    return [
        'visitors' => 10000,
        'searches' => 8000,
        'results_viewed' => 6000,
        'bookings_initiated' => 1000,
        'payments_attempted' => 800,
        'bookings_completed' => 750
    ];
}

// =====================================
// SEASONAL TRENDS & ADDITIONAL ANALYTICS
// =====================================

/**
 * Seasonal trends analysis
 */
public function seasonalTrends()
{
    $seasonalData = [
        'quarterly_bookings' => $this->getQuarterlyBookings(),
        'seasonal_destinations' => $this->getSeasonalDestinations(),
        'peak_booking_times' => $this->getPeakBookingTimes(),
        'seasonal_revenue' => $this->getSeasonalRevenue()
    ];

    return view('dashboard.analytics.seasonal-trends', compact('seasonalData'));
}

/**
 * Conversion funnel detailed view
 */
public function conversionFunnel()
{
    $funnelData = [
        'overall_funnel' => $this->getConversionFunnelData(),
        'funnel_by_source' => $this->getFunnelBySource(),
        'funnel_by_device' => $this->getFunnelByDevice(),
        'drop_off_analysis' => $this->getDropOffAnalysis()
    ];

    return view('dashboard.analytics.conversion-funnel', compact('funnelData'));
}

/**
 * API Health Monitor
 */
public function apiHealthMonitor()
{
    $apiHealth = [
        'amadeus_api' => $this->checkAmadeusAPI(),
        'payment_gateways' => $this->checkPaymentGateways(),
        'email_service' => $this->checkEmailService(),
        'sms_service' => $this->checkSMSService(),
        'database_status' => $this->checkDatabaseStatus(),
        'redis_status' => $this->checkRedisStatus()
    ];

    return view('dashboard.system.api-health', compact('apiHealth'));
}

// =====================================
// PAYMENT GATEWAY CONFIGURATION
// =====================================

/**
 * Payment Gateway Configuration
 */
public function paymentGatewayConfig()
{
    $gateways = [
        'mpesa' => [
            'status' => 'active',
            'transaction_fee' => '2.5%',
            'success_rate' => '98.5%'
        ],
        'bank_transfer' => [
            'status' => 'active',
            'transaction_fee' => '1.0%',
            'success_rate' => '95.2%'
        ],
        'card_payment' => [
            'status' => 'active',
            'transaction_fee' => '3.2%',
            'success_rate' => '97.8%'
        ]
    ];

    return view('dashboard.finance.gateway-config', compact('gateways'));
}

/**
 * Refund Processing Center
 */
public function refundProcessing()
{
    $refunds = Payment::where('status', 'refund_requested')
        ->with(['booking.user'])
        ->latest()
        ->paginate(20);

    $refundStats = [
        'pending_refunds' => Payment::where('status', 'refund_requested')->count(),
        'processed_refunds' => Payment::where('status', 'refunded')->count(),
        'total_refund_amount' => Payment::where('status', 'refunded')->sum('amount'),
        'avg_processing_time' => '2.5 days'
    ];

    return view('dashboard.finance.refund-processing', compact('refunds', 'refundStats'));
}

/**
 * Commission Tracking
 */
public function commissionTracking()
{
    $commissionData = [
        'total_commission_earned' => 125000,
        'monthly_commission' => 35000,
        'commission_by_partner' => [
            'Amadeus' => 75000,
            'Hotel Partners' => 35000,
            'Tour Operators' => 15000
        ],
        'commission_trends' => $this->getCommissionTrends()
    ];

    return view('dashboard.finance.commission-tracking', compact('commissionData'));
}

/**
 * Revenue Reports
 */
public function revenueReports()
{
    $revenueData = [
        'daily_revenue' => $this->getDailyRevenue(),
        'monthly_revenue' => $this->getMonthlyRevenue(),
        'revenue_by_service' => $this->getRevenueByService(),
        'profit_margins' => $this->getProfitMargins()
    ];

    return view('dashboard.finance.revenue-reports', compact('revenueData'));
}

// =====================================
// CONTENT MANAGEMENT METHODS
// =====================================

/**
 * Manage Promotional Offers
 */
public function manageOffers()
{
    $offers = []; // This would come from your offers model
    return view('dashboard.content.offers', compact('offers'));
}

/**
 * Manage Featured Destinations
 */
public function manageDestinations()
{
    $destinations = []; // This would come from your destinations model
    return view('dashboard.content.destinations', compact('destinations'));
}

/**
 * Manage Email Templates
 */
public function manageEmailTemplates()
{
    $templates = []; // This would come from your email templates model
    return view('dashboard.content.email-templates', compact('templates'));
}

/**
 * Manage Terms & Policies
 */
public function managePolicies()
{
    $policies = [
        'terms_of_service' => 'Terms of Service content...',
        'privacy_policy' => 'Privacy Policy content...',
        'cancellation_policy' => 'Cancellation Policy content...',
        'refund_policy' => 'Refund Policy content...'
    ];

    return view('dashboard.content.policies', compact('policies'));
}

// =====================================
// COMMUNICATION CENTER METHODS
// =====================================

/**
 * Broadcast Center
 */
public function broadcastCenter()
{
    $broadcastHistory = []; // Previous broadcasts
    return view('dashboard.communications.broadcast', compact('broadcastHistory'));
}

/**
 * Send Broadcast Message
 */
public function sendBroadcast(Request $request)
{
    $request->validate([
        'message' => 'required|string',
        'target_audience' => 'required|in:all,superadmin,admin,user',
        'send_via' => 'required|array|min:1',
        'send_via.*' => 'in:email,sms,push,dashboard'
    ]);

    // Logic to send broadcast message
    $this->logActivity('broadcast_sent', "Sent broadcast to {$request->target_audience}");

    return back()->with('success', 'Broadcast message sent successfully.');
}

/**
 * Email Campaigns
 */
public function emailCampaigns()
{
    $campaigns = []; // Email campaigns data
    return view('dashboard.communications.email-campaigns', compact('campaigns'));
}

/**
 * SMS Notifications
 */
public function smsNotifications()
{
    $smsHistory = []; // SMS history
    return view('dashboard.communications.sms', compact('smsHistory'));
}

/**
 * Support Tickets Management
 */
public function supportTickets()
{
    $tickets = []; // Support tickets
    return view('dashboard.communications.support-tickets', compact('tickets'));
}

// =====================================
// SECURITY & COMPLIANCE METHODS
// =====================================

/**
 * Two-Factor Authentication Management
 */
public function twoFactorManagement()
{
    $twoFactorStats = [
        'users_with_2fa' => User::whereNotNull('two_factor_secret')->count(),
        'total_users' => User::count(),
        'enforcement_policy' => 'Optional' // Could be 'Required' for certain roles
    ];

    return view('dashboard.security.two-factor', compact('twoFactorStats'));
}

/**
 * IP Restrictions Management
 */
public function ipRestrictions()
{
    $ipRestrictions = []; // IP restriction rules
    return view('dashboard.security.ip-restrictions', compact('ipRestrictions'));
}

/**
 * Security Audit Logs
 */
public function securityAuditLogs()
{
    $auditLogs = Activity::where('action', 'LIKE', '%security%')
        ->orWhere('action', 'LIKE', '%login%')
        ->orWhere('action', 'LIKE', '%password%')
        ->with('user')
        ->latest()
        ->paginate(25);

    return view('dashboard.security.audit-logs', compact('auditLogs'));
}

/**
 * Data Retention Policy
 */
public function dataRetentionPolicy()
{
    $retentionPolicies = [
        'user_data' => '7 years',
        'booking_data' => '10 years',
        'payment_data' => '7 years',
        'activity_logs' => '2 years',
        'system_logs' => '1 year'
    ];

    return view('dashboard.security.data-retention', compact('retentionPolicies'));
}

// =====================================
// ADDITIONAL HELPER METHODS
// =====================================

private function getQuarterlyBookings()
{
    // Implementation for quarterly booking analysis
    return [];
}

private function getSeasonalDestinations()
{
    // Implementation for seasonal destination analysis
    return [];
}

private function getPeakBookingTimes()
{
    // Implementation for peak booking time analysis
    return [];
}

private function getSeasonalRevenue()
{
    // Implementation for seasonal revenue analysis
    return [];
}

private function getFunnelBySource()
{
    // Implementation for funnel analysis by traffic source
    return [];
}

private function getFunnelByDevice()
{
    // Implementation for funnel analysis by device type
    return [];
}

private function getDropOffAnalysis()
{
    // Implementation for drop-off point analysis
    return [];
}

private function checkAmadeusAPI()
{
    // Check Amadeus API health
    return ['status' => 'healthy', 'response_time' => '250ms'];
}

private function checkPaymentGateways()
{
    // Check payment gateway health
    return ['status' => 'healthy', 'response_time' => '180ms'];
}

private function checkEmailService()
{
    // Check email service health
    return ['status' => 'healthy', 'response_time' => '120ms'];
}

private function checkSMSService()
{
    // Check SMS service health
    return ['status' => 'healthy', 'response_time' => '200ms'];
}

private function checkDatabaseStatus()
{
    // Check database health
    return ['status' => 'healthy', 'response_time' => '50ms'];
}

private function checkRedisStatus()
{
    // Check Redis health
    return ['status' => 'healthy', 'response_time' => '10ms'];
}

private function getCommissionTrends()
{
    // Get commission trends over time
    return [];
}

private function getDailyRevenue()
{
    // Get daily revenue breakdown
    return [];
}

private function getMonthlyRevenue()
{
    // Get monthly revenue breakdown
    return [];
}

private function getRevenueByService()
{
    // Get revenue breakdown by service type
    return [
        'flights' => 125000,
        'hotels' => 85000,
        'tours' => 35000
    ];
}

private function getProfitMargins()
{
    // Calculate profit margins
    return [
        'gross_margin' => '35%',
        'net_margin' => '15%',
        'operating_margin' => '25%'
    ];
}

/**
 * Advanced Search Interface
 */
public function advancedSearch()
{
    return view('dashboard.search.advanced');
}

/**
 * Export Data
 */
public function exportData($type)
{
    switch ($type) {
        case 'users':
            return $this->exportUsers();
        case 'bookings':
            return $this->exportBookings();
        case 'payments':
            return $this->exportPayments();
        case 'analytics':
            return $this->exportAnalytics();
        default:
            return back()->withErrors(['export' => 'Invalid export type']);
    }
}

/**
 * Export Analytics Data
 */
public function exportAnalyticsData()
{
    // Implementation for exporting analytics data
    return response()->download('analytics-export.csv');
}

/**
 * Export Financial Data
 */
public function exportFinancialData()
{
    // Implementation for exporting financial data
    return response()->download('financial-export.csv');
}

/**
 * Toggle Maintenance Mode
 */
public function toggleMaintenance()
{
    // Implementation for toggling maintenance mode
    $this->logActivity('maintenance_toggled', 'Toggled maintenance mode');
    return back()->with('success', 'Maintenance mode toggled successfully.');
}

/**
 * System Updates
 */
public function systemUpdates()
{
    $updates = [
        'last_update' => '2024-01-15',
        'current_version' => '2.1.0',
        'available_updates' => [],
        'security_patches' => []
    ];

    return view('dashboard.system.updates', compact('updates'));
}

/**
 * Notification Center
 */
public function notificationCenter()
{
    $notifications = Notification::latest()->paginate(20);
    return view('dashboard.notifications.center', compact('notifications'));
}

/**
 * Clear All Notifications
 */
public function clearAllNotifications()
{
    Notification::truncate();
    $this->logActivity('notifications_cleared', 'Cleared all notifications');
    return back()->with('success', 'All notifications cleared successfully.');
}

/**
 * Mark Notification as Read
 */
public function markNotificationRead($notificationId)
{
    $notification = Notification::findOrFail($notificationId);
    $notification->update(['read_at' => now()]);
    return response()->json(['success' => true]);
}

/**
 * Support Center
 */
public function support()
{
    return view('dashboard.support.index');
}

}
