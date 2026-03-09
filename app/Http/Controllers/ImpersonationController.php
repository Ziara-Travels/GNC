<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ImpersonationLog;
use Illuminate\Support\Facades\Auth;

class ImpersonationController extends Controller
{
    public function impersonate(Request $request, User $user)
    {
        // Only superadmins can impersonate
        if (!auth()->user()->hasRole('superadmin')) {
            abort(403, 'Unauthorized action.');
        }

        // Cannot impersonate yourself
        if (auth()->id() === $user->id) {
            return redirect()->back()->with('error', 'You cannot impersonate yourself.');
        }

        // Store the original user ID in session
        session(['impersonated_by' => auth()->id()]);

        // Log the impersonation
        ImpersonationLog::create([
            'impersonator_id' => auth()->id(),
            'impersonated_id' => $user->id,
            'started_at' => now(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Login as the target user
        Auth::login($user);

        return redirect()->route('dashboard')->with('success', "You are now impersonating {$user->name}");
    }

    public function stopImpersonation()
    {
        if (!session('impersonated_by')) {
            return redirect()->route('dashboard');
        }

        $originalUserId = session('impersonated_by');
        $impersonatedUserId = auth()->id();

        // Update the log entry
        ImpersonationLog::where('impersonator_id', $originalUserId)
            ->where('impersonated_id', $impersonatedUserId)
            ->whereNull('ended_at')
            ->update(['ended_at' => now()]);

        // Remove impersonation session
        session()->forget('impersonated_by');

        // Login back as original user
        Auth::login(User::findOrFail($originalUserId));

        return redirect()->route('superadmin.dashboard')->with('success', 'Stopped impersonation');
    }
}