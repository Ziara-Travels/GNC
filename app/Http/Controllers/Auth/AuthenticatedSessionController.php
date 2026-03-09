<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LoginLog;
use App\Mail\LoginAlert;
use Illuminate\Support\Facades\Mail;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
    */
   public function store(LoginRequest $request)
    {
        try {
            $request->authenticate();
            $request->session()->regenerate();
            $user = auth()->user();
            
            // Log the login attempt
            $this->logLoginAttempt($user, $request);
            
            // Check for new device/IP and send alert
            $this->checkAndSendLoginAlert($user, $request);
            
            // Check if there's a redirect parameter for returning to booking flow
            if ($request->has('redirect')) {
                return redirect($request->input('redirect'));
            }
            
            // Use try/catch inside the redirection logic
            try {
                if ($user->role === 'superadmin') {
                    \Log::info('Superadmin login: Redirecting to superadmin.dashboard');
                    return redirect()->intended(route('superadmin.dashboard'));
                } elseif ($user->role === 'admin') {
                    return redirect()->intended(route('admin.dashboard'));
                } else {
                    return redirect()->intended(route('user.dashboard'))->with([
                        'flight_data' => session('flight_data'),
                        'price_kes' => session('price_kes')
                    ]);
                }
            } catch (\Exception $e) {
                // Log detailed information about the redirect failure
                \Log::error('Redirect after login failed: ' . $e->getMessage());
                \Log::error('Exception trace: ' . $e->getTraceAsString());
                
                // Fallback to home if any routes fail
                return redirect()->route('home')->with('error', 'Error accessing dashboard. Please contact support.');
            }
        } catch (\Exception $e) {
            // Log authentication errors
            \Log::error('Authentication error: ' . $e->getMessage());
            \Log::error('Exception trace: ' . $e->getTraceAsString());
            
            return back()->withErrors([
                'email' => 'Authentication failed. Please try again.',
            ])->onlyInput('email');
        }
    }

   

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Log login attempt
     */
    private function logLoginAttempt($user, $request)
    {
        LoginLog::create([
            'user_id' => $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'login_at' => now(),
        ]);
    }

    /**
     * Check for new device/IP and send login alert
     */
    private function checkAndSendLoginAlert($user, $request)
    {
        $currentIp = $request->ip();
        $currentUserAgent = $request->userAgent();

        // Check if this is a new device/IP combination
        $existingLog = LoginLog::where('user_id', $user->id)
            ->where('ip_address', $currentIp)
            ->where('user_agent', $currentUserAgent)
            ->exists();

        if (!$existingLog) {
            // This is a new device/IP, send alert
            Mail::to($user->email)->send(new LoginAlert($user, $currentIp, $currentUserAgent));
        }
    
        }
    
}