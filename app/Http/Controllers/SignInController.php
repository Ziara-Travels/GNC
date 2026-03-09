<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class SignInController extends Controller
{
   
    /**
     * Redirect the user to the provider authentication page.
     *
     * @param  string  $provider
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectToProvider($provider)
    {
        try {
            // Add scopes for specific providers if needed
            if ($provider == 'google') {
                return Socialite::driver($provider)->scopes(['profile', 'email'])->redirect();
            } elseif ($provider == 'facebook') {
                return Socialite::driver($provider)->scopes(['email', 'public_profile'])->redirect();
            } elseif ($provider == 'twitter' || $provider == 'x') {
                // Use Twitter OAuth 2.0
                return Socialite::driver('twitter')
                    ->with(['response_type' => 'code'])
                    ->redirect();
            }
            
            // Default fallback
            return Socialite::driver($provider)->redirect();
            
        } catch (\Exception $e) {
            Log::error('Social login redirect error: ' . $e->getMessage());
            return redirect()->route('login')
                ->withErrors(['email' => 'Could not connect to ' . ucfirst($provider) . '. Please try again later.']);
        }
    }

    /**
     * Handle provider callback.
     *
     * @param  string  $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleProviderCallback($provider)
    {
        try {
            // Get the social user data
            $socialUser = Socialite::driver($provider)->user();
            
            // For Twitter/X which might not always provide email
            $email = $socialUser->getEmail();
            if (!$email && ($provider == 'twitter' || $provider == 'x')) {
                // Generate a temporary email based on Twitter ID
                $email = 'twitter_' . $socialUser->getId() . '@example.com';
                
                // Store flag to request email later
                session(['needs_email_update' => true]);
            }
            
            // Check if user exists by social ID first
            $user = User::where('social_id', $socialUser->getId())
                ->where('social_type', $provider)
                ->first();
            
            // If not found by social ID, try email
            if (!$user && $email) {
                $user = User::where('email', $email)->first();
            }
            
            if (!$user) {
                // Create new user
                $userData = [
                    'name' => $socialUser->getName() ?? ($socialUser->getNickname() ?? 'User'),
                    'email' => $email,
                    'password' => Hash::make(Str::random(16)),  // Random password
                    'role' => 'user',
                    'is_active' => true,
                    'is_deleted' => false,
                    'email_verified_at' => now(), // Consider social logins as verified
                    'social_id' => $socialUser->getId(),
                    'social_type' => $provider,
                ];
                
                // Get profile image if available
                if ($socialUser->getAvatar()) {
                    $userData['profile_image'] = $socialUser->getAvatar();
                }
                
                $user = User::create($userData);
                
                // Mark email as verified
                $user->markEmailAsVerified();
                
                // Log registration via social login
                activity()
                    ->causedBy($user)
                    ->log('User registered via ' . ucfirst($provider) . ' login');
                    
                // Event for new user registration
                event(new Registered($user));
                
            } else if ($user->is_deleted) {
                return redirect()->route('login')
                    ->withErrors(['email' => 'This account has been deactivated. Please contact support.']);
            } else {
                // Update social ID if user was found by email but didn't have social ID yet
                if (!$user->social_id && $socialUser->getId()) {
                    $user->social_id = $socialUser->getId();
                    $user->social_type = $provider;
                    $user->save();
                }
            }
            
            // Login user
            Auth::login($user);
            
            // Update active status and last login
            $user->is_active = true;
            $user->last_login_at = now();
            $user->save();
            
            // Check if we need to request email update (Twitter/X)
            if (session('needs_email_update')) {
                session()->forget('needs_email_update');
                // If it's a temporary email, redirect to email update page
                if (Str::startsWith($user->email, 'twitter_')) {
                    return redirect()->route('profile.edit')
                        ->with('message', 'Please update your email address to complete your profile.');
                }
            }
            
            // Check if there's a redirect_to parameter for returning to booking flow
            if (session()->has('redirect_after_login')) {
                $redirect = session()->pull('redirect_after_login');
                return redirect($redirect);
            }
            
            // Redirect based on user role
            if ($user->role === 'user') {
                return redirect()->route('user.dashboard');
            } else {
                // Admins and superadmins shouldn't use social login, but handle just in case
                return redirect()->route($user->role . '.dashboard');
            }
            
        } catch (\Exception $e) {
            Log::error('Social login callback error: ' . $e->getMessage());
            Log::error('Exception trace: ' . $e->getTraceAsString());
            
            return redirect()->route('login')
                ->withErrors(['email' => 'Social login failed. Please try again.']);
        }
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}