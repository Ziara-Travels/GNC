<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Payment;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AdminPaymentsExport;

class AdminPaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index()
    {
        $user = auth()->user();
        
        if (!$user->hasPermission('payments.view')) {
            abort(403, 'Unauthorized access');
        }

        // Get payment statistics for users only
        $stats = $this->getPaymentStats();
        
        // Get recent payments from users only
        $recentPayments = Payment::with(['user', 'booking'])
            ->whereHas('user', function($query) {
                $query->where('role', 'user');
            })
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get payment methods breakdown for users only
        $paymentMethodsData = Payment::whereHas('user', function($query) {
                $query->where('role', 'user');
            })
            ->select('payment_method', DB::raw('count(*) as count'), DB::raw('sum(amount) as total'))
            ->groupBy('payment_method')
            ->get();

        // Get monthly payment trends for users only
        $monthlyData = Payment::whereHas('user', function($query) {
                $query->where('role', 'user');
            })
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(amount) as total')
            )
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        // Get flight vs hotel payments for users only
        $bookingTypeData = Payment::join('bookings', 'payments.booking_id', '=', 'bookings.id')
            ->whereHas('user', function($query) {
                $query->where('role', 'user');
            })
            ->select('bookings.type', DB::raw('count(*) as count'), DB::raw('sum(payments.amount) as total'))
            ->groupBy('bookings.type')
            ->get();

        return view('dashboard.payments.admin.index', compact(
            'stats',
            'recentPayments',
            'paymentMethodsData',
            'monthlyData',
            'bookingTypeData'
        ));
    }

    public function flightPayments()
    {
        $user = auth()->user();
        
        if (!$user->hasPermission('payments.view')) {
            abort(403, 'Unauthorized access');
        }

        $flightPayments = Payment::join('bookings', 'payments.booking_id', '=', 'bookings.id')
            ->where('bookings.type', 'flight')
            ->whereHas('user', function($query) {
                $query->where('role', 'user');
            })
            ->with(['user', 'booking'])
            ->select('payments.*')
            ->paginate(20);

        $flightStats = [
            'total_payments' => Payment::join('bookings', 'payments.booking_id', '=', 'bookings.id')
                ->where('bookings.type', 'flight')
                ->whereHas('user', function($query) {
                    $query->where('role', 'user');
                })
                ->count(),
            'total_revenue' => Payment::join('bookings', 'payments.booking_id', '=', 'bookings.id')
                ->where('bookings.type', 'flight')
                ->whereHas('user', function($query) {
                    $query->where('role', 'user');
                })
                ->sum('payments.amount'),
            'pending_payments' => Payment::join('bookings', 'payments.booking_id', '=', 'bookings.id')
                ->where('bookings.type', 'flight')
                ->whereHas('user', function($query) {
                    $query->where('role', 'user');
                })
                ->where('payments.status', 'pending')
                ->count(),
            'completed_payments' => Payment::join('bookings', 'payments.booking_id', '=', 'bookings.id')
                ->where('bookings.type', 'flight')
                ->whereHas('user', function($query) {
                    $query->where('role', 'user');
                })
                ->where('payments.status', 'completed')
                ->count(),
        ];

        return view('dashboard.payments.admin.flights', compact('flightPayments', 'flightStats'));
    }

    public function hotelPayments()
    {
        $user = auth()->user();
        
        if (!$user->hasPermission('payments.view')) {
            abort(403, 'Unauthorized access');
        }

        $hotelPayments = Payment::join('bookings', 'payments.booking_id', '=', 'bookings.id')
            ->where('bookings.type', 'hotel')
            ->whereHas('user', function($query) {
                $query->where('role', 'user');
            })
            ->with(['user', 'booking'])
            ->select('payments.*')
            ->paginate(20);

        $hotelStats = [
            'total_payments' => Payment::join('bookings', 'payments.booking_id', '=', 'bookings.id')
                ->where('bookings.type', 'hotel')
                ->whereHas('user', function($query) {
                    $query->where('role', 'user');
                })
                ->count(),
            'total_revenue' => Payment::join('bookings', 'payments.booking_id', '=', 'bookings.id')
                ->where('bookings.type', 'hotel')
                ->whereHas('user', function($query) {
                    $query->where('role', 'user');
                })
                ->sum('payments.amount'),
            'pending_payments' => Payment::join('bookings', 'payments.booking_id', '=', 'bookings.id')
                ->where('bookings.type', 'hotel')
                ->whereHas('user', function($query) {
                    $query->where('role', 'user');
                })
                ->where('payments.status', 'pending')
                ->count(),
            'completed_payments' => Payment::join('bookings', 'payments.booking_id', '=', 'bookings.id')
                ->where('bookings.type', 'hotel')
                ->whereHas('user', function($query) {
                    $query->where('role', 'user');
                })
                ->where('payments.status', 'completed')
                ->count(),
        ];

        return view('dashboard.payments.admin.hotels', compact('hotelPayments', 'hotelStats'));
    }

    public function show($paymentId)
    {
        $user = auth()->user();
        
        if (!$user->hasPermission('payments.view')) {
            abort(403, 'Unauthorized access');
        }

        $payment = Payment::with(['user', 'booking'])
            ->whereHas('user', function($query) {
                $query->where('role', 'user');
            })
            ->findOrFail($paymentId);

        return response()->json($payment);
    }

    public function exportPayments(Request $request)
    {
        $user = auth()->user();
        
        if (!$user->hasPermission('payments.export')) {
            abort(403, 'Unauthorized access');
        }

        $type = $request->input('type', 'all');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        return Excel::download(new AdminPaymentsExport($type, $startDate, $endDate), 'user-payments-' . now()->format('Y-m-d') . '.csv');
    }

    public function realTimeUpdates()
    {
        $user = auth()->user();
        
        if (!$user->hasPermission('payments.view')) {
            abort(403, 'Unauthorized access');
        }

        // Get latest payment updates from users only
        $recentPayments = Payment::with(['user', 'booking'])
            ->whereHas('user', function($query) {
                $query->where('role', 'user');
            })
            ->where('updated_at', '>=', Carbon::now()->subMinutes(5))
            ->orderBy('updated_at', 'desc')
            ->get();

        $stats = $this->getPaymentStats();

        return response()->json([
            'recent_payments' => $recentPayments,
            'stats' => $stats,
            'timestamp' => now()
        ]);
    }

    private function getPaymentStats()
    {
        $userPaymentsQuery = Payment::whereHas('user', function($query) {
            $query->where('role', 'user');
        });

        return [
            'total_payments' => $userPaymentsQuery->count(),
            'total_revenue' => $userPaymentsQuery->where('status', 'completed')->sum('amount'),
            'pending_payments' => $userPaymentsQuery->where('status', 'pending')->count(),
            'failed_payments' => $userPaymentsQuery->where('status', 'failed')->count(),
            'today_payments' => $userPaymentsQuery->whereDate('created_at', today())->count(),
            'today_revenue' => $userPaymentsQuery->whereDate('created_at', today())
                ->where('status', 'completed')
                ->sum('amount'),
            'monthly_revenue' => $userPaymentsQuery->whereMonth('created_at', now()->month)
                ->where('status', 'completed')
                ->sum('amount'),
            'success_rate' => $this->getSuccessRate(),
        ];
    }

    private function getSuccessRate()
    {
        $total = Payment::whereHas('user', function($query) {
            $query->where('role', 'user');
        })->count();
        
        $completed = Payment::whereHas('user', function($query) {
            $query->where('role', 'user');
        })->where('status', 'completed')->count();
        
        return $total > 0 ? round(($completed / $total) * 100, 2) : 0;
    }
}