<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class UserPaymentController extends Controller
{
    protected $jengaApiUrl;
    protected $jengaApiKey;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:user');
        $this->jengaApiUrl = env('JENGA_BASE_URL', 'https://api.jenga.co.ke');
        $this->jengaApiKey = env('JENGA_API_KEY');
    }

    public function index()
    {
        $user = auth()->user();
        
        if (!$user->hasPermission('payments.view_own')) {
            abort(403, 'Unauthorized access');
        }

        // Get user's payment statistics
        $stats = $this->getUserPaymentStats($user);
        
        // Get user's recent payments
        $recentPayments = Payment::with(['booking'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get user's unpaid bookings
        $unpaidBookings = Booking::where('user_id', $user->id)
            ->whereNotIn('id', function($query) {
                $query->select('booking_id')
                    ->from('payments')
                    ->where('status', 'completed');
            })
            ->with(['payments' => function($query) {
                $query->where('status', '!=', 'completed');
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        // Get user's payment methods breakdown
        $paymentMethodsData = Payment::where('user_id', $user->id)
            ->select('payment_method', DB::raw('count(*) as count'), DB::raw('sum(amount) as total'))
            ->groupBy('payment_method')
            ->get();

        // Get monthly payment trends for user
        $monthlyData = Payment::where('user_id', $user->id)
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

        return view('dashboard.payments.user.index', compact(
            'stats',
            'recentPayments',
            'unpaidBookings',
            'paymentMethodsData',
            'monthlyData'
        ));
    }

    public function myFlightPayments()
    {
        $user = auth()->user();
        
        if (!$user->hasPermission('payments.view_own')) {
            abort(403, 'Unauthorized access');
        }

        $flightPayments = Payment::join('bookings', 'payments.booking_id', '=', 'bookings.id')
            ->where('bookings.type', 'flight')
            ->where('payments.user_id', $user->id)
            ->with(['booking'])
            ->select('payments.*')
            ->paginate(10);

        $flightStats = [
            'total_payments' => Payment::join('bookings', 'payments.booking_id', '=', 'bookings.id')
                ->where('bookings.type', 'flight')
                ->where('payments.user_id', $user->id)
                ->count(),
            'total_amount' => Payment::join('bookings', 'payments.booking_id', '=', 'bookings.id')
                ->where('bookings.type', 'flight')
                ->where('payments.user_id', $user->id)
                ->sum('payments.amount'),
            'pending_payments' => Payment::join('bookings', 'payments.booking_id', '=', 'bookings.id')
                ->where('bookings.type', 'flight')
                ->where('payments.user_id', $user->id)
                ->where('payments.status', 'pending')
                ->count(),
            'completed_payments' => Payment::join('bookings', 'payments.booking_id', '=', 'bookings.id')
                ->where('bookings.type', 'flight')
                ->where('payments.user_id', $user->id)
                ->where('payments.status', 'completed')
                ->count(),
        ];

        return view('dashboard.payments.user.flights', compact('flightPayments', 'flightStats'));
    }

    public function myHotelPayments()
    {
        $user = auth()->user();
        
        if (!$user->hasPermission('payments.view_own')) {
            abort(403, 'Unauthorized access');
        }

        $hotelPayments = Payment::join('bookings', 'payments.booking_id', '=', 'bookings.id')
            ->where('bookings.type', 'hotel')
            ->where('payments.user_id', $user->id)
            ->with(['booking'])
            ->select('payments.*')
            ->paginate(10);

        $hotelStats = [
            'total_payments' => Payment::join('bookings', 'payments.booking_id', '=', 'bookings.id')
                ->where('bookings.type', 'hotel')
                ->where('payments.user_id', $user->id)
                ->count(),
            'total_amount' => Payment::join('bookings', 'payments.booking_id', '=', 'bookings.id')
                ->where('bookings.type', 'hotel')
                ->where('payments.user_id', $user->id)
                ->sum('payments.amount'),
            'pending_payments' => Payment::join('bookings', 'payments.booking_id', '=', 'bookings.id')
                ->where('bookings.type', 'hotel')
                ->where('payments.user_id', $user->id)
                ->where('payments.status', 'pending')
                ->count(),
            'completed_payments' => Payment::join('bookings', 'payments.booking_id', '=', 'bookings.id')
                ->where('bookings.type', 'hotel')
                ->where('payments.user_id', $user->id)
                ->where('payments.status', 'completed')
                ->count(),
        ];

        return view('dashboard.payments.user.hotels', compact('hotelPayments', 'hotelStats'));
    }

    public function checkout(Request $request)
    {
        $user = auth()->user();
        
        if (!$user->hasPermission('payments.create')) {
            abort(403, 'Unauthorized access');
        }

        $bookingId = $request->input('booking_id');
        $booking = null;

        if ($bookingId) {
            $booking = Booking::where('id', $bookingId)
                ->where('user_id', $user->id)
                ->with(['payments' => function($query) {
                    $query->where('status', '!=', 'completed');
                }])
                ->first();

            if (!$booking) {
                return redirect()->route('user.payments.index')
                    ->with('error', 'Booking not found.');
            }
        }

        // Store session for recovery
        $request->session()->put('user_payment_session', [
            'booking_id' => $bookingId,
            'step' => 'checkout',
            'timestamp' => now(),
            'user_id' => $user->id
        ]);

        return view('dashboard.payments.user.checkout', compact('booking'));
    }

    public function processPayment(Request $request)
    {
        $user = auth()->user();
        
        if (!$user->hasPermission('payments.create')) {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'payment_method' => 'required|in:mpesa,bank',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'terms_accepted' => 'required|accepted',
        ]);

        // Verify booking belongs to user
        $booking = Booking::where('id', $request->booking_id)
            ->where('user_id', $user->id)
            ->first();

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found or access denied'
            ]);
        }

        // Additional validation based on payment method
        if ($request->payment_method === 'mpesa') {
            $request->validate([
                'phone_number' => 'required|string|regex:/^[0-9]{9}$/',
            ]);
        } else if ($request->payment_method === 'bank') {
            $request->validate([
                'account_holder_name' => 'required|string|max:100',
                'account_number' => 'required|string|max:20',
                'bank_name' => 'required|string|max:50',
                'branch_code' => 'required|string|max:10',
            ]);
        }

        try {
            DB::beginTransaction();

            // Create payment record
            $payment = Payment::create([
                'user_id' => $user->id,
                'booking_id' => $booking->id,
                'payment_reference' => $this->generatePaymentReference(),
                'amount' => $request->amount,
                'currency' => $request->currency,
                'payment_method' => $request->payment_method,
                'status' => 'pending',
                'payment_details' => json_encode($request->only([
                    'phone_number',
                    'account_holder_name',
                    'account_number',
                    'bank_name',
                    'branch_code'
                ])),
                'processed_by' => $user->id,
            ]);

            // Process payment based on method
            if ($request->payment_method === 'mpesa') {
                $result = $this->processMpesaPayment($payment, $request->phone_number);
            } else {
                $result = $this->processBankPayment($payment, $request->all());
            }

            if ($result['success']) {
                $payment->update([
                    'status' => 'processing',
                    'provider_transaction_id' => $result['transaction_id'] ?? null,
                    'provider_response' => json_encode($result['response'] ?? []),
                    'processed_at' => now()
                ]);

                $booking->update(['status' => 'processing']);

                // Clear session
                $request->session()->forget('user_payment_session');

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Payment initiated successfully',
                    'redirect_url' => route('user.payments.status', $payment->id)
                ]);
            } else {
                DB::rollback();
                return response()->json([
                    'success' => false,
                    'message' => $result['message'] ?? 'Payment processing failed'
                ]);
            }

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('User payment processing error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing payment'
            ]);
        }
    }

    public function paymentStatus($paymentId)
    {
        $user = auth()->user();
        
        if (!$user->hasPermission('payments.view_own')) {
            abort(403, 'Unauthorized access');
        }

        $payment = Payment::with(['booking'])
            ->where('user_id', $user->id)
            ->findOrFail($paymentId);

        // Check real-time status from Jenga API
        $this->updatePaymentStatus($payment);

        return view('dashboard.payments.user.status', compact('payment'));
    }

    public function show($paymentId)
    {
        $user = auth()->user();
        
        if (!$user->hasPermission('payments.view_own')) {
            abort(403, 'Unauthorized access');
        }

        $payment = Payment::with(['booking'])
            ->where('user_id', $user->id)
            ->findOrFail($paymentId);

        return response()->json($payment);
    }

    public function restoreSession(Request $request)
    {
        $sessionData = $request->session()->get('user_payment_session');
        
        if ($sessionData && Carbon::parse($sessionData['timestamp'])->addHours(2)->isFuture()) {
            return response()->json([
                'success' => true,
                'data' => $sessionData
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Session expired or not found'
        ]);
    }

    private function getUserPaymentStats($user)
    {
        $userPaymentsQuery = Payment::where('user_id', $user->id);

        return [
            'total_payments' => $userPaymentsQuery->count(),
            'total_paid' => $userPaymentsQuery->where('status', 'completed')->sum('amount'),
            'pending_payments' => $userPaymentsQuery->where('status', 'pending')->count(),
            'failed_payments' => $userPaymentsQuery->where('status', 'failed')->count(),
            'unpaid_bookings' => Booking::where('user_id', $user->id)
                ->whereNotIn('id', function($query) {
                    $query->select('booking_id')
                        ->from('payments')
                        ->where('status', 'completed');
                })
                ->count(),
            'total_bookings' => Booking::where('user_id', $user->id)->count(),
            'success_rate' => $this->getUserSuccessRate($user),
        ];
    }

    private function getUserSuccessRate($user)
    {
        $total = Payment::where('user_id', $user->id)->count();
        $completed = Payment::where('user_id', $user->id)->where('status', 'completed')->count();
        
        return $total > 0 ? round(($completed / $total) * 100, 2) : 0;
    }

    private function processMpesaPayment($payment, $phoneNumber)
    {
        try {
            $formattedPhone = '254' . $phoneNumber;
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->getJengaAccessToken(),
                'Content-Type' => 'application/json',
            ])->post($this->jengaApiUrl . '/mpesa/stkpush', [
                'merchant_code' => env('JENGA_MERCHANT_CODE'),
                'phone_number' => $formattedPhone,
                'amount' => $payment->amount,
                'reference' => $payment->payment_reference,
                'description' => 'Booking payment - ' . $payment->booking->booking_reference,
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'transaction_id' => $response->json('checkout_request_id'),
                    'response' => $response->json()
                ];
            } else {
                return [
                    'success' => false,
                    'message' => $response->json('message') ?? 'M-Pesa payment failed'
                ];
            }
        } catch (\Exception $e) {
            Log::error('User M-Pesa payment error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'M-Pesa service temporarily unavailable'
            ];
        }
    }

    private function processBankPayment($payment, $details)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->getJengaAccessToken(),
                'Content-Type' => 'application/json',
            ])->post($this->jengaApiUrl . '/bank/transfer', [
                'account_number' => $details['account_number'],
                'account_name' => $details['account_holder_name'],
                'bank_code' => $this->getBankCode($details['bank_name']),
                'branch_code' => $details['branch_code'],
                'amount' => $payment->amount,
                'reference' => $payment->payment_reference,
                'description' => 'Booking payment - ' . $payment->booking->booking_reference,
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'transaction_id' => $response->json('transaction_id'),
                    'response' => $response->json()
                ];
            } else {
                return [
                    'success' => false,
                    'message' => $response->json('message') ?? 'Bank transfer failed'
                ];
            }
        } catch (\Exception $e) {
            Log::error('User bank payment error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Bank service temporarily unavailable'
            ];
        }
    }

    private function updatePaymentStatus($payment)
    {
        if ($payment->status === 'completed' || $payment->status === 'failed') {
            return;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->getJengaAccessToken(),
                'Content-Type' => 'application/json',
            ])->get($this->jengaApiUrl . '/payment/status/' . $payment->provider_transaction_id);

            if ($response->successful()) {
                $status = $response->json('status');
                $payment->update([
                    'status' => $this->mapJengaStatus($status),
                    'provider_response' => json_encode($response->json()),
                    'updated_at' => now()
                ]);

                if ($payment->status === 'completed') {
                    $payment->booking->update(['status' => 'confirmed']);
                } elseif ($payment->status === 'failed') {
                    $payment->booking->update(['status' => 'pending']);
                }
            }
        } catch (\Exception $e) {
            Log::error('User payment status update error: ' . $e->getMessage());
        }
    }

    private function mapJengaStatus($jengaStatus)
    {
        $statusMap = [
            'SUCCESS' => 'completed',
            'FAILED' => 'failed',
            'PENDING' => 'processing',
            'CANCELLED' => 'failed',
        ];

        return $statusMap[$jengaStatus] ?? 'pending';
    }

    private function getJengaAccessToken()
    {
        $response = Http::post($this->jengaApiUrl . '/auth/token', [
            'api_key' => $this->jengaApiKey,
            'grant_type' => 'client_credentials'
        ]);

        return $response->json('access_token');
    }

    private function getBankCode($bankName)
    {
        $bankCodes = [
            'KCB' => '01',
            'Equity' => '68',
            'Standard Chartered' => '02',
            'Barclays' => '03',
            'Co-operative' => '11',
            'NCBA' => '07',
        ];

        return $bankCodes[$bankName] ?? '01';
    }

    private function generatePaymentReference()
    {
        return 'PAY-USER-' . strtoupper(uniqid()) . '-' . now()->format('YmdHis');
    }
}