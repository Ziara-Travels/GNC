<?php


namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Payment;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Mail\PaymentConfirmation;
use App\Jobs\SendPaymentConfirmationJob;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PaymentsExport;

class PaymentController extends Controller
{
    protected $jengaApiUrl;
    protected $jengaApiKey;

    public function __construct()
    {
        $this->jengaApiUrl = env('JENGA_BASE_URL', 'https://api.jenga.co.ke');
        $this->jengaApiKey = env('JENGA_API_KEY');
    }

    public function index()
    {
        $user = auth()->user();
        
        if (!$user->hasPermission('payments.view_all')) {
            abort(403, 'Unauthorized access');
        }

        // Get payment statistics
        $stats = $this->getPaymentStats();
        
        // Get recent payments
        $recentPayments = Payment::with(['user', 'booking'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get payment methods breakdown
        $paymentMethodsData = Payment::select('payment_method', DB::raw('count(*) as count'), DB::raw('sum(amount) as total'))
            ->groupBy('payment_method')
            ->get();

        // Get monthly payment trends
        $monthlyData = Payment::select(
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

        // Get flight vs hotel payments
        $bookingTypeData = Payment::join('bookings', 'payments.booking_id', '=', 'bookings.id')
            ->select('bookings.type', DB::raw('count(*) as count'), DB::raw('sum(payments.amount) as total'))
            ->groupBy('bookings.type')
            ->get();

        return view('dashboard.payments.superadmin.index', compact(
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
        
        if (!$user->hasPermission('payments.view_all')) {
            abort(403, 'Unauthorized access');
        }

        $flightPayments = Payment::join('bookings', 'payments.booking_id', '=', 'bookings.id')
            ->where('bookings.type', 'flight')
            ->with(['user', 'booking'])
            ->select('payments.*')
            ->paginate(20);

        $flightStats = [
            'total_payments' => Payment::join('bookings', 'payments.booking_id', '=', 'bookings.id')
                ->where('bookings.type', 'flight')
                ->count(),
            'total_revenue' => Payment::join('bookings', 'payments.booking_id', '=', 'bookings.id')
                ->where('bookings.type', 'flight')
                ->sum('payments.amount'),
            'pending_payments' => Payment::join('bookings', 'payments.booking_id', '=', 'bookings.id')
                ->where('bookings.type', 'flight')
                ->where('payments.status', 'pending')
                ->count(),
            'completed_payments' => Payment::join('bookings', 'payments.booking_id', '=', 'bookings.id')
                ->where('bookings.type', 'flight')
                ->where('payments.status', 'completed')
                ->count(),
        ];

        return view('dashboard.payments.superadmin.flights', compact('flightPayments', 'flightStats'));
    }

    public function hotelPayments()
    {
        $user = auth()->user();
        
        if (!$user->hasPermission('payments.view_all')) {
            abort(403, 'Unauthorized access');
        }

        $hotelPayments = Payment::join('bookings', 'payments.booking_id', '=', 'bookings.id')
            ->where('bookings.type', 'hotel')
            ->with(['user', 'booking'])
            ->select('payments.*')
            ->paginate(20);

        $hotelStats = [
            'total_payments' => Payment::join('bookings', 'payments.booking_id', '=', 'bookings.id')
                ->where('bookings.type', 'hotel')
                ->count(),
            'total_revenue' => Payment::join('bookings', 'payments.booking_id', '=', 'bookings.id')
                ->where('bookings.type', 'hotel')
                ->sum('payments.amount'),
            'pending_payments' => Payment::join('bookings', 'payments.booking_id', '=', 'bookings.id')
                ->where('bookings.type', 'hotel')
                ->where('payments.status', 'pending')
                ->count(),
            'completed_payments' => Payment::join('bookings', 'payments.booking_id', '=', 'bookings.id')
                ->where('bookings.type', 'hotel')
                ->where('payments.status', 'completed')
                ->count(),
        ];

        return view('dashboard.payments.superadmin.hotels', compact('hotelPayments', 'hotelStats'));
    }

    public function checkout(Request $request)
    {
        $user = auth()->user();
        
        if (!$user->hasPermission('payments.process')) {
            abort(403, 'Unauthorized access');
        }

        // Get or restore booking details from session
        $bookingDetails = $request->session()->get('booking_details', []);
        
        if (empty($bookingDetails)) {
            return redirect()->route('superadmin.bookings.all')
                ->with('error', 'No booking details found. Please start a new booking.');
        }

        // Store session for recovery
        $request->session()->put('payment_session', [
            'booking_details' => $bookingDetails,
            'step' => 'checkout',
            'timestamp' => now(),
            'user_id' => $user->id
        ]);

        return view('dashboard.payments.superadmin.checkout', compact('bookingDetails'));
    }

    public function processPayment(Request $request)
    {
        $user = auth()->user();
        
        if (!$user->hasPermission('payments.process')) {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'booking_reference' => 'required|string',
            'payment_method' => 'required|in:mpesa,bank',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'terms_accepted' => 'required|accepted',
        ]);

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

            // Create booking first
            $booking = Booking::create([
                'user_id' => $user->id,
                'booking_reference' => $request->booking_reference,
                'type' => $request->input('booking_type', 'flight'),
                'departure_airport' => $request->input('flight_details.departure_airport'),
                'arrival_airport' => $request->input('flight_details.arrival_airport'),
                'departure_time' => $request->input('flight_details.departure_time'),
                'arrival_time' => $request->input('flight_details.arrival_time'),
                'flight_number' => $request->input('flight_details.flight_number'),
                'airline_name' => $request->input('flight_details.airline_name'),
                'status' => 'pending',
                'booking_details' => json_encode($request->input('traveler_details')),
                'created_by' => $user->id,
            ]);

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
                ]);

                $booking->update(['status' => 'processing']);

                // Clear session
                $request->session()->forget(['booking_details', 'payment_session']);

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Payment initiated successfully',
                    'redirect_url' => route('superadmin.payments.status', $payment->id)
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
            Log::error('Payment processing error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing payment'
            ]);
        }
    }

    public function paymentStatus($paymentId)
    {
        $user = auth()->user();
        
        if (!$user->hasPermission('payments.view')) {
            abort(403, 'Unauthorized access');
        }

        $payment = Payment::with(['user', 'booking'])->findOrFail($paymentId);

        // Check real-time status from Jenga API
        $this->updatePaymentStatus($payment);

        return view('dashboard.payments.superadmin.status', compact('payment'));
    }

    public function realTimeUpdates()
    {
        $user = auth()->user();
        
        if (!$user->hasPermission('payments.view_all')) {
            abort(403, 'Unauthorized access');
        }

        // Get latest payment updates
        $recentPayments = Payment::with(['user', 'booking'])
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

    public function exportPayments(Request $request)
    {
        $user = auth()->user();
        
        if (!$user->hasPermission('payments.export')) {
            abort(403, 'Unauthorized access');
        }

        $type = $request->input('type', 'all');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        return Excel::download(new PaymentsExport($type, $startDate, $endDate), 'payments-' . now()->format('Y-m-d') . '.xlsx');
    }

    public function restoreSession(Request $request)
    {
        $sessionData = $request->session()->get('payment_session');
        
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

    private function getPaymentStats()
    {
        return [
            'total_payments' => Payment::count(),
            'total_revenue' => Payment::where('status', 'completed')->sum('amount'),
            'pending_payments' => Payment::where('status', 'pending')->count(),
            'failed_payments' => Payment::where('status', 'failed')->count(),
            'today_payments' => Payment::whereDate('created_at', today())->count(),
            'today_revenue' => Payment::whereDate('created_at', today())
                ->where('status', 'completed')
                ->sum('amount'),
            'monthly_revenue' => Payment::whereMonth('created_at', now()->month)
                ->where('status', 'completed')
                ->sum('amount'),
            'success_rate' => $this->getSuccessRate(),
        ];
    }

    private function getSuccessRate()
    {
        $total = Payment::count();
        $completed = Payment::where('status', 'completed')->count();
        
        return $total > 0 ? round(($completed / $total) * 100, 2) : 0;
    }

    private function processMpesaPayment($payment, $phoneNumber)
    {
        try {
            // Format phone number
            $formattedPhone = '254' . $phoneNumber;
            
            // Jenga API M-Pesa STK Push
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->getJengaAccessToken(),
                'Content-Type' => 'application/json',
            ])->post($this->jengaApiUrl . '/mpesa/stkpush', [
                'merchant_code' => env('JENGA_MERCHANT_CODE'),
                'phone_number' => $formattedPhone,
                'amount' => $payment->amount,
                'reference' => $payment->payment_reference,
                'description' => 'Flight booking payment - ' . $payment->booking->booking_reference,
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
            Log::error('M-Pesa payment error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'M-Pesa service temporarily unavailable'
            ];
        }
    }

    private function processBankPayment($payment, $details)
    {
        try {
            // Jenga API Bank Transfer
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
                'description' => 'Flight booking payment - ' . $payment->booking->booking_reference,
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
            Log::error('Bank payment error: ' . $e->getMessage());
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
                    $payment->booking->update(['status' => 'cancelled']);
                }
            }
        } catch (\Exception $e) {
            Log::error('Payment status update error: ' . $e->getMessage());
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
        // Implementation would cache the token
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
        return 'PAY-' . strtoupper(uniqid()) . '-' . now()->format('YmdHis');
    }

    /**
     * Handle Jenga payment callback (IPN)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleJengaCallback(Request $request)
    {
        // Log the raw callback for debugging
        Log::channel('payments')->info('Jenga Callback received', [
            'payload' => $request->all(),
            'headers' => $request->header()
        ]);
        
        try {
            // Validate basic structure of the callback
            $request->validate([
                'callbackType' => 'required|string',
                'customer' => 'required|array',
                'transaction' => 'required|array',
                'transaction.reference' => 'required|string',
                'transaction.status' => 'required|string'
            ]);
            
            // Extract relevant data
            $callbackType = $request->input('callbackType');
            $transactionRef = $request->input('transaction.reference');
            $status = $request->input('transaction.status');
            $paymentMode = $request->input('transaction.paymentMode');
            $amount = $request->input('transaction.amount');
            $currency = $request->input('transaction.currency');
            $remarks = $request->input('transaction.remarks');
            $customerRef = $request->input('customer.reference');
            
            // Find the booking by reference
            $booking = Booking::where('booking_reference', $customerRef)
                ->orWhere('transaction_id', $transactionRef)
                ->first();
                
            if (!$booking) {
                Log::channel('payments')->warning('Booking not found for Jenga callback', [
                    'transaction_reference' => $transactionRef,
                    'customer_reference' => $customerRef
                ]);
                
                return response()->json([
                    'status' => 'error',
                    'message' => 'Booking not found'
                ], 404);
            }
            
            // Record the payment transaction
            $transaction = new PaymentTransaction();
            $transaction->booking_id = $booking->id;
            $transaction->transaction_reference = $transactionRef;
            $transaction->payment_mode = $paymentMode;
            $transaction->amount = $amount;
            $transaction->currency = $currency;
            $transaction->status = $status;
            $transaction->remarks = $remarks;
            $transaction->raw_response = json_encode($request->all());
            $transaction->save();
            
            // Update booking status based on payment result
            if ($status === 'SUCCESS') {
                $booking->payment_status = 'completed';
                $booking->status = 'confirmed';
                $booking->transaction_id = $transactionRef;
                $booking->save();
                
                // Dispatch payment confirmation email job
                SendPaymentConfirmationJob::dispatch($booking);
                
                // Additional business logic for successful payment
                $this->handleSuccessfulPayment($booking);
                
                Log::channel('payments')->info('Payment successful', [
                    'booking_id' => $booking->id,
                    'booking_reference' => $booking->booking_reference,
                    'transaction_reference' => $transactionRef
                ]);
            } else {
                $booking->payment_status = 'failed';
                $booking->status = 'pending';
                $booking->save();
                
                Log::channel('payments')->warning('Payment failed', [
                    'booking_id' => $booking->id,
                    'booking_reference' => $booking->booking_reference,
                    'transaction_reference' => $transactionRef,
                    'remarks' => $remarks
                ]);
            }
            
            return response()->json([
                'status' => 'success',
                'message' => 'Callback processed successfully'
            ]);
            
        } catch (\Exception $e) {
            Log::channel('payments')->error('Error processing Jenga callback', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Error processing callback: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Handle additional business logic for successful payments
     *
     * @param Booking $booking
     * @return void
     */
    private function handleSuccessfulPayment(Booking $booking)
    {
        try {
            // 1. Update inventory/availability if applicable
            if ($booking->booking_type === 'flight') {
                // Flight booking specific logic
            } elseif ($booking->booking_type === 'hotel') {
                // Hotel booking specific logic
            }
            
            // 2. Assign loyalty points to user if applicable
            if ($booking->user_id) {
                $this->assignLoyaltyPoints($booking);
            }
            
            // 3. Send notifications to relevant stakeholders
            // This could be handled by the job we dispatched earlier
            
            // 4. Create commission records if this is from a partner/affiliate
            if ($booking->affiliate_id) {
                $this->recordCommission($booking);
            }
        } catch (\Exception $e) {
            Log::error('Error in post-payment processing', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Assign loyalty points to the user
     *
     * @param Booking $booking
     * @return void
     */
    private function assignLoyaltyPoints(Booking $booking)
    {
        // Implementation depends on your loyalty system
    }
    
    /**
     * Record commission for affiliate/partner
     *
     * @param Booking $booking
     * @return void
     */
    private function recordCommission(Booking $booking)
    {
        // Implementation depends on your commission structure
    }
}