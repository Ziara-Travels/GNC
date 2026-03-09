<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;
use App\Models\ReferralCode;
use App\Models\UserReferral;
use App\Notifications\AdminReferralAlert;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validator($request->all())->validate();

        $user = $this->createUser($request->all());

        event(new Registered($user));

        Auth::login($user);

        // Check if there's a redirect_to parameter for returning to booking flow
        if ($request->has('redirect_to')) {
            return redirect($request->input('redirect_to'));
        }
        
        // Check if user came from checkout process
        if (session()->has('checkout_redirect') && session()->has('flight_booking_data')) {
            session()->forget('checkout_redirect');
            return redirect()->route('checkout.index');
        }

        // Redirect to verification notice instead of dashboard
        return redirect()->route('verification.notice');
    }

    protected function validator(array $data)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed']
        ];
        
        // Only require these fields if they are present in the request
        // This allows for a simplified registration during the booking process
        if (isset($data['country'])) {
            $rules['country'] = ['required', 'string', 'max:100'];
        }
        
        if (isset($data['phone_country_code'])) {
            $rules['phone_country_code'] = ['required', 'string', 'max:5'];
        }
        
        if (isset($data['phone_number'])) {
            $rules['phone_number'] = ['required', 'string', 'max:20'];
        }
        
        if (isset($data['referral_code'])) {
            $rules['referral_code'] = ['nullable', 'string', 'max:50'];
        }
        
        return Validator::make($data, $rules);
    }

    
    protected function createUser(array $data)
    {
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'is_active' => false, // Not active until email verified
            'role' => 'user', // Default role is user
        ];
        
        // Add optional fields if they exist
        if (isset($data['country'])) {
            $userData['country'] = $data['country'];
        }
        
        if (isset($data['phone_country_code'])) {
            $userData['phone_country_code'] = $data['phone_country_code'];
        }
        
        if (isset($data['phone_number'])) {
            $userData['phone_number'] = $data['phone_number'];
        }
        
        if (isset($data['referral_code'])) {
            $userData['referral_code_used'] = $data['referral_code'];
        }
        
        $user = User::create($userData);

        // Process referral code if provided
        if (!empty($data['referral_code'])) {
            $this->processReferralCode($user, $data['referral_code']);
        }

        return $user;
    }

    private function processReferralCode(User $user, string $referralCode)
    {
        $referral = ReferralCode::where('code', $referralCode)
            ->where('is_active', true)
            ->where('admin_approved', true)
            ->first();

        if ($referral && $referral->canBeUsed()) {
            // Update user with referrer info
            $user->update(['referred_by' => $referral->user_id]);

            UserReferral::create([
                'referrer_id' => $referral->user_id,
                'referred_user_id' => $user->id,
                'referral_code_id' => $referral->id,
                'reward_percentage' => $referral->reward_percentage ?? 0,
                'status' => 'pending',
            ]);
            
            // Increment usage count
            $referral->increment('used_count');

            // Send admin alert
            $this->sendAdminReferralAlert($referral->user, $user, $referral);
        }
    }

    private function sendAdminReferralAlert(User $referrer, User $newUser, ReferralCode $referralCode)
    {
        // Get admin users
        $admins = User::where('role', 'admin')->orWhere('role', 'superadmin')->get();

        foreach ($admins as $admin) {
            $admin->notify(new AdminReferralAlert($referrer, $newUser, $referralCode));
        }
    }
}