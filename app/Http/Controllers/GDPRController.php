<?php


namespace App\Http\Controllers;

use App\Models\UserConsent;
use App\Models\User;
use App\Mail\GDPRDataExportMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use ZipArchive;

class GDPRController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function consentForm()
    {
        $user = Auth::user();
        $consents = UserConsent::where('user_id', $user->id)
            ->whereNull('withdrawn_at')
            ->get()
            ->keyBy('consent_type');

        return view('gdpr.consent-form', compact('user', 'consents'));
    }

    public function updateConsent(Request $request)
    {
        $user = Auth::user();
        $consentTypes = UserConsent::getConsentTypes();

        foreach ($consentTypes as $type => $label) {
            $consentGiven = $request->has($type);
            
            // Check if consent already exists
            $existingConsent = UserConsent::where('user_id', $user->id)
                ->where('consent_type', $type)
                ->whereNull('withdrawn_at')
                ->first();

            if ($existingConsent) {
                if (!$consentGiven) {
                    // Withdraw consent
                    $existingConsent->update([
                        'consent_given' => false,
                        'withdrawn_at' => now()
                    ]);
                }
            } else {
                if ($consentGiven) {
                    // Give new consent
                    UserConsent::create([
                        'user_id' => $user->id,
                        'consent_type' => $type,
                        'consent_given' => true,
                        'consent_text' => $request->input($type . '_text', $label),
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                        'version' => '1.0',
                        'consented_at' => now()
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Consent preferences updated successfully.');
    }

    public function exportData()
    {
        $user = Auth::user();
        
        // Collect all user data
        $userData = [
            'user_profile' => $user->toArray(),
            'bookings' => $user->bookings->toArray(),
            'payments' => $user->payments->toArray(),
            'consents' => $user->consents->toArray(),
            'sessions' => $user->sessions->toArray(),
            'notifications' => $user->notifications->toArray(),
            'preferences' => $user->preferences->toArray() ?? [],
            'loyalty_points' => $user->loyaltyHistory->toArray() ?? []
        ];

        // Create temporary directory
        $tempDir = storage_path('app/temp/gdpr-export-' . $user->id);
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        // Create JSON file
        file_put_contents($tempDir . '/user_data.json', json_encode($userData, JSON_PRETTY_PRINT));

        // Create readable HTML report
        $htmlContent = view('gdpr.data-export', compact('userData'))->render();
        file_put_contents($tempDir . '/user_data.html', $htmlContent);

        // Create ZIP file
        $zipPath = storage_path('app/temp/gdpr-export-' . $user->id . '.zip');
        $zip = new ZipArchive;
        
        if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
            $zip->addFile($tempDir . '/user_data.json', 'user_data.json');
            $zip->addFile($tempDir . '/user_data.html', 'user_data.html');
            $zip->close();
        }

        // Clean up temporary directory
        unlink($tempDir . '/user_data.json');
        unlink($tempDir . '/user_data.html');
        rmdir($tempDir);

        // Send email notification about the export
        Mail::to($user->email)->send(new GDPRDataExportMail($user, $zipPath));

        return Response::download($zipPath, 'my_data_export.zip')->deleteFileAfterSend();
    }

    public function deleteAccount(Request $request)
    {
        $request->validate([
            'password' => 'required|current_password',
            'confirmation' => 'required|in:DELETE_MY_ACCOUNT'
        ]);

        $user = Auth::user();

        // Anonymize bookings instead of deleting (for business records)
        $user->bookings()->update([
            'contact_email' => 'deleted@example.com',
            'contact_phone' => null,
            'traveler_details' => json_encode(['anonymized' => true])
        ]);

        // Delete personal data
        $user->consents()->delete();
        $user->sessions()->delete();
        $user->notifications()->delete();
        $user->preferences()->delete();
        $user->loyaltyHistory()->delete();

        // Anonymize user account
        $user->update([
            'name' => 'Deleted User',
            'email' => 'deleted_' . $user->id . '@example.com',
            'phone' => null,
            'email_verified_at' => null,
            'password' => bcrypt('deleted'),
            'remember_token' => null,
            'deleted_at' => now()
        ]);

        Auth::logout();

        return redirect('/')->with('success', 'Your account has been deleted successfully.');
    }
}