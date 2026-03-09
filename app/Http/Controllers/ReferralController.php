<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserReferral;
use App\Models\User;
use App\Models\Notification;
use App\Notifications\ReferralSubmitted;
use App\Notifications\ReferralCodeGenerated;
use App\Mail\ReferralCodeMail;
use App\Mail\ReferralSuccessMail;
use App\Mail\ReferralRewardMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ReferralController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->role === 'user') {
            $referrals = UserReferral::where('user_id', $user->id)->with('user')->paginate(10);
            $stats = [
                'total' => $referrals->total(),
                'pending' => UserReferral::where('user_id', $user->id)->where('status', 'pending')->count(),
                'approved' => UserReferral::where('user_id', $user->id)->where('status', 'approved')->count(),
                'completed' => UserReferral::where('user_id', $user->id)->where('status', 'completed')->count(),
            ];
            return view('dashboard.referral.user.index', compact('referrals', 'stats'));
        } elseif ($user->role === 'admin') {
            $referrals = UserReferral::with('user')->paginate(15);
            $stats = [
                'total' => UserReferral::count(),
                'pending' => UserReferral::where('status', 'pending')->count(),
                'approved' => UserReferral::where('status', 'approved')->count(),
                'completed' => UserReferral::where('status', 'completed')->count(),
            ];
            return view('dashboard.referral.admin.index', compact('referrals', 'stats'));
        } else {
            $referrals = UserReferral::with('user')->paginate(20);
            $stats = [
                'total' => UserReferral::count(),
                'pending' => UserReferral::where('status', 'pending')->count(),
                'approved' => UserReferral::where('status', 'approved')->count(),
                'completed' => UserReferral::where('status', 'completed')->count(),
            ];
            return view('dashboard.referral.superadmin.index', compact('referrals', 'stats'));
        }
    }

    public function submit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:user_referrals,email',
            'phone' => 'nullable|string|max:20',
            'relationship' => 'nullable|string|max:100',
            'message' => 'nullable|string|max:500'
        ]);

        $referral = UserReferral::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'relationship' => $request->relationship,
            'message' => $request->message,
            'status' => 'pending',
            'submitted_at' => now()
        ]);

        // Notify admins and superadmins
        $admins = User::whereIn('role', ['admin', 'superadmin'])->get();
        foreach ($admins as $admin) {
            $admin->notify(new ReferralSubmitted($referral));
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Referral submitted successfully! We will review it and get back to you.',
                'referral' => $referral
            ]);
        }

        return back()->with('success', 'Referral submitted successfully! We will review it and get back to you.');
    }

    public function generateCode(UserReferral $referral)
    {
        if (!auth()->user()->hasRole(['admin', 'superadmin'])) {
            abort(403, 'Unauthorized');
        }

        $referral->code = strtoupper(Str::random(8));
        $referral->status = 'approved';
        $referral->approved_at = now();
        $referral->approved_by = auth()->id();
        $referral->save();

        // Notify user
        $referral->user->notify(new ReferralCodeGenerated($referral));

        // Send email
        Mail::to($referral->user->email)->send(new ReferralCodeMail($referral));

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Referral code generated and sent successfully!',
                'code' => $referral->code
            ]);
        }

        return back()->with('success', 'Referral code generated and sent successfully!');
    }

    public function approve(UserReferral $referral)
    {
        if (!auth()->user()->hasRole(['admin', 'superadmin'])) {
            abort(403, 'Unauthorized');
        }

        $referral->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id()
        ]);

        return back()->with('success', 'Referral approved successfully!');
    }

    public function reject(UserReferral $referral, Request $request)
    {
        if (!auth()->user()->hasRole(['admin', 'superadmin'])) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $referral->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'rejected_by' => auth()->id(),
            'rejection_reason' => $request->rejection_reason
        ]);

        return back()->with('success', 'Referral rejected.');
    }

    public function show(UserReferral $referral)
    {
        if (!auth()->user()->hasRole(['admin', 'superadmin']) && $referral->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        return view('dashboard.referral.show', compact('referral'));
    }

    public function stats()
    {
        $user = auth()->user();
        
        $stats = [
            'total_referrals' => UserReferral::when($user->role === 'user', function($q) use ($user) {
                return $q->where('user_id', $user->id);
            })->count(),
            'pending_referrals' => UserReferral::when($user->role === 'user', function($q) use ($user) {
                return $q->where('user_id', $user->id);
            })->where('status', 'pending')->count(),
            'approved_referrals' => UserReferral::when($user->role === 'user', function($q) use ($user) {
                return $q->where('user_id', $user->id);
            })->where('status', 'approved')->count(),
            'completed_referrals' => UserReferral::when($user->role === 'user', function($q) use ($user) {
                return $q->where('user_id', $user->id);
            })->where('status', 'completed')->count(),
        ];

        if ($user->role !== 'user') {
            $stats['monthly_referrals'] = UserReferral::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();
            $stats['top_referrers'] = User::withCount(['referrals' => function($q) {
                $q->where('status', 'completed');
            }])->orderBy('referrals_count', 'desc')->take(5)->get();
        }

        return response()->json($stats);
    }

    public function markCompleted(UserReferral $referral)
    {
        if (!auth()->user()->hasRole(['admin', 'superadmin'])) {
            abort(403, 'Unauthorized');
        }

        $referral->update([
            'status' => 'completed',
            'completed_at' => now()
        ]);

        // Award referral bonus to user if applicable
        $this->awardReferralBonus($referral);

        // Send reward email notification
        Mail::to($referral->user->email)->send(new ReferralRewardMail($referral));

        return back()->with('success', 'Referral marked as completed and reward email sent!');
    }

    private function awardReferralBonus(UserReferral $referral)
    {
        // Implement referral bonus logic here
        // This could involve loyalty points, discounts, cash rewards, etc.
        
        $user = $referral->user;
        $bonusAmount = 1000; // Example: 1000 loyalty points

        // Create loyalty point entry (if you have loyalty system)
        // LoyaltyPoint::create([
        //     'user_id' => $user->id,
        //     'points' => $bonusAmount,
        //     'type' => 'referral_bonus',
        //     'description' => "Referral bonus for {$referral->name}"
        // ]);
    }
}
