<?php

namespace App\Http\Controllers;

use App\Models\TwoFactorAuthentication;
use App\Mail\TwoFactorMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use PragmaRX\Google2FA\Google2FA;
use PragmaRX\Google2FAQRCode\Google2FA as Google2FAQRCode;

class TwoFactorController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the 2FA setup page.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        $user = Auth::user();
        
        // Check if 2FA is already set up
        $twoFactorAuth = $user->twoFactorAuth;
        if ($twoFactorAuth && $twoFactorAuth->enabled) {
            return redirect()->route('user.profile.my')->with('info', 'Two-factor authentication is already enabled.');
        }
        
        // Generate new secret
        $google2fa = new Google2FA();
        $secretKey = $google2fa->generateSecretKey();
        
        // Store the secret key temporarily in the session
        Session::put('2fa_secret', $secretKey);
        
        // Generate QR Code
        $google2fa = new Google2FAQRCode();
        $qrCodeUrl = $google2fa->getQRCodeInline(
            config('app.name'),
            $user->email,
            $secretKey
        );
        
        return view('auth.2FA-setup', [
            'secretKey' => $secretKey,
            'qrCodeUrl' => $qrCodeUrl,
        ]);
    }

    /**
     * Enable 2FA for the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function enable(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);
        
        $user = Auth::user();
        $google2fa = new Google2FA();
        $secretKey = Session::get('2fa_secret');
        
        if (!$secretKey) {
            return back()->withErrors(['code' => 'Something went wrong. Please try again.']);
        }
        
        // Verify the code
        $valid = $google2fa->verifyKey($secretKey, $request->code);
        
        if (!$valid) {
            return back()->withErrors(['code' => 'Invalid authentication code. Please try again.']);
        }
        
        // Store or update the 2FA record
        $twoFactorAuth = $user->twoFactorAuth;
        
        if (!$twoFactorAuth) {
            $twoFactorAuth = new TwoFactorAuthentication([
                'user_id' => $user->id,
                'secret_key' => encrypt($secretKey),
                'enabled' => true,
            ]);
            $twoFactorAuth->save();
        } else {
            $twoFactorAuth->secret_key = encrypt($secretKey);
            $twoFactorAuth->enabled = true;
            $twoFactorAuth->save();
        }
        
        // Clear the session
        Session::forget('2fa_secret');
        
        // Mark the session as 2FA verified
        Session::put('two_factor_verified', true);
        
        return redirect()->route('user.profile.my')->with('success', 'Two-factor authentication has been enabled.');
    }

    /**
     * Show the 2FA verification page.
     *
     * @return \Illuminate\View\View
     */
    public function verifyForm()
    {
        return view('auth.2FA-verification');
    }

    /**
     * Verify 2FA code.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verify(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string'],
        ]);
        
        $user = Auth::user();
        $twoFactorAuth = $user->twoFactorAuth;
        
        if (!$twoFactorAuth || !$twoFactorAuth->enabled) {
            // If 2FA is not enabled, skip verification
            Session::put('two_factor_verified', true);
            return redirect()->intended();
        }
        
        $google2fa = new Google2FA();
        
        // Check if it's a backup code (8 characters)
        if (strlen($request->code) === 8) {
            // Check backup codes
            $backupCodes = $twoFactorAuth->backup_codes ?: [];
            
            foreach ($backupCodes as $index => $hashedCode) {
                if (Hash::check($request->code, $hashedCode)) {
                    // Remove the used backup code
                    unset($backupCodes[$index]);
                    $twoFactorAuth->backup_codes = array_values($backupCodes);
                    $twoFactorAuth->save();
                    
                    // Mark the session as 2FA verified
                    Session::put('two_factor_verified', true);
                    
                    return redirect()->intended();
                }
            }
            
            return back()->withErrors(['code' => 'Invalid backup code.']);
        } else {
            // Regular 6-digit code
            $secretKey = decrypt($twoFactorAuth->secret_key);
            $valid = $google2fa->verifyKey($secretKey, $request->code);
            
            if ($valid) {
                // Mark the session as 2FA verified
                Session::put('two_factor_verified', true);
                
                return redirect()->intended();
            }
            
            return back()->withErrors(['code' => 'Invalid authentication code.']);
        }
    }

    /**
     * Disable 2FA for the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function disable(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string'],
        ]);
        
        $user = Auth::user();
        
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Incorrect password.']);
        }
        
        $twoFactorAuth = $user->twoFactorAuth;
        
        if ($twoFactorAuth) {
            $twoFactorAuth->enabled = false;
            $twoFactorAuth->save();
        }
        
        return redirect()->route('user.profile.my')->with('success', 'Two-factor authentication has been disabled.');
    }

    /**
     * Generate backup codes for 2FA.
     *
     * @return \Illuminate\View\View
     */
    public function generateBackupCodes()
    {
        $user = Auth::user();
        $twoFactorAuth = $user->twoFactorAuth;
        
        if (!$twoFactorAuth || !$twoFactorAuth->enabled) {
            return redirect()->route('user.profile.my')->with('error', 'Two-factor authentication is not enabled.');
        }
        
        // Generate 8 backup codes
        $plainBackupCodes = [];
        $hashedBackupCodes = [];
        
        for ($i = 0; $i < 8; $i++) {
            $code = $this->generateRandomCode();
            $plainBackupCodes[] = $code;
            $hashedBackupCodes[] = Hash::make($code);
        }
        
        // Store the backup codes
        $twoFactorAuth->backup_codes = $hashedBackupCodes;
        $twoFactorAuth->save();
        
        return view('auth.2FA-backup-codes', ['backupCodes' => $plainBackupCodes]);
    }

    /**
     * Generate a random 8-character code.
     *
     * @return string
     */
    private function generateRandomCode()
    {
        return substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8);
    }

    /**
     * Send 2FA code via email for verification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendEmailCode(Request $request)
    {
        $user = Auth::user();
        
        // Generate 6-digit code
        $code = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
        
        // Store code in session with expiration
        Session::put('2fa_email_code', $code);
        Session::put('2fa_email_code_expires_at', now()->addMinutes(10));
        
        // Send email
        Mail::to($user->email)->send(new TwoFactorMail($user, $code, 'verification'));
        
        return response()->json([
            'success' => true,
            'message' => 'Verification code sent to your email address.'
        ]);
    }

    /**
     * Verify email-based 2FA code.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyEmailCode(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);
        
        $storedCode = Session::get('2fa_email_code');
        $expiresAt = Session::get('2fa_email_code_expires_at');
        
        if (!$storedCode || !$expiresAt || now()->isAfter($expiresAt)) {
            return response()->json([
                'success' => false,
                'message' => 'Verification code has expired. Please request a new one.'
            ], 400);
        }
        
        if ($request->code !== $storedCode) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid verification code.'
            ], 400);
        }
        
        // Clear the session
        Session::forget(['2fa_email_code', '2fa_email_code_expires_at']);
        
        // Mark as verified
        Session::put('two_factor_verified', true);
        
        return response()->json([
            'success' => true,
            'message' => 'Two-factor authentication verified successfully.',
            'redirect' => url('/dashboard')
        ]);
    }

    /**
     * Send setup code via email during 2FA setup.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendSetupCode(Request $request)
    {
        $user = Auth::user();
        
        // Generate 6-digit code
        $code = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
        
        // Store code in session with expiration
        Session::put('2fa_setup_code', $code);
        Session::put('2fa_setup_code_expires_at', now()->addMinutes(10));
        
        // Send email
        Mail::to($user->email)->send(new TwoFactorMail($user, $code, 'setup'));
        
        return response()->json([
            'success' => true,
            'message' => 'Setup code sent to your email address.'
        ]);
    }
}
