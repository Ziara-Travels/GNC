<?php


namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Mail\PasswordChangedNotification;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;

class ProfileController extends Controller
{
    // User Profile Methods
    public function userProfile()
    {
        $user = auth()->user();
        
        if (!$user->hasRole('user')) {
            abort(403, 'Unauthorized access');
        }

        $completionPercentage = $this->calculateProfileCompletion($user);
        
        return view('dashboard.profile.user.profile', compact('user', 'completionPercentage'));
    }

    public function updateUserProfile(Request $request)
    {
        $user = auth()->user();
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string|max:500',
            'bio' => 'nullable|string|max:1000',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $request->only(['name', 'email', 'phone', 'country', 'date_of_birth', 'gender', 'address', 'bio']);

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($user->profile_image) {
                Storage::disk('public')->delete('profile_images/' . $user->profile_image);
            }

            $image = $request->file('profile_image');
            $imageName = time() . '_' . $user->id . '.' . $image->getClientOriginalExtension();
            $image->storeAs('profile_images', $imageName, 'public');
            $data['profile_image'] = $imageName;
        }

        $user->update($data);

        // Send email notification
        // Mail::to($user->email)->send(new ProfileUpdatedNotification($user));

        return back()->with('success', 'Profile updated successfully!');
    }

    // Admin Profile Methods
    public function adminProfile()
    {
        $user = auth()->user();
        
        if (!$user->hasRole('admin')) {
            abort(403, 'Unauthorized access');
        }

        $completionPercentage = $this->calculateProfileCompletion($user);
        
        return view('dashboard.profile.admin.profile', compact('user', 'completionPercentage'));
    }

    public function adminUsers(Request $request)
    {
        $user = auth()->user();
        
        if (!$user->hasRole('admin')) {
            abort(403, 'Unauthorized access');
        }

        $query = User::whereHas('roles', function($q) {
            $q->where('name', 'user');
        });

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        if ($request->filled('country')) {
            $query->where('country', $request->country);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);
        $countries = User::distinct()->pluck('country')->filter();

        return view('dashboard.profile.admin.users', compact('users', 'countries'));
    }

    // Superadmin Profile Methods
    public function superadminProfile()
    {
        $user = auth()->user();
        
        if (!$user->hasRole('superadmin')) {
            abort(403, 'Unauthorized access');
        }

        $completionPercentage = $this->calculateProfileCompletion($user);
        
        return view('dashboard.profile.superadmin.profile', compact('user', 'completionPercentage'));
    }

    public function superadminUsers(Request $request)
    {
        $user = auth()->user();
        
        if (!$user->hasRole('superadmin')) {
            abort(403, 'Unauthorized access');
        }

        $query = User::with('roles');

        // Apply filters
        if ($request->filled('role')) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        if ($request->filled('country')) {
            $query->where('country', $request->country);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);
        $countries = User::distinct()->pluck('country')->filter();

        return view('dashboard.profile.superadmin.users', compact('users', 'countries'));
    }

    // Common Methods
    public function changePassword(Request $request)
    {
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
            'new_password_confirmation' => 'required'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        // Send email notification
        Mail::to($user->email)->send(new PasswordChangedNotification($user));

        return back()->with('success', 'Password changed successfully! A confirmation email has been sent.');
    }

    public function getUserDetails($id)
    {
        $user = User::with('roles')->findOrFail($id);
        
        // Check authorization
        $currentUser = auth()->user();
        if ($currentUser->hasRole('admin') && $user->hasRole(['admin', 'superadmin'])) {
            abort(403, 'Unauthorized access');
        }

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'country' => $user->country,
                'date_of_birth' => $user->date_of_birth,
                'gender' => $user->gender,
                'address' => $user->address,
                'bio' => $user->bio,
                'profile_image' => $user->profile_image ? asset('storage/profile_images/' . $user->profile_image) : null,
                'role' => $user->roles->first()->name ?? 'user',
                'status' => $user->status,
                'created_at' => $user->created_at->format('M d, Y'),
                'completion_percentage' => $this->calculateProfileCompletion($user)
            ]
        ]);
    }

    public function updateUserStatus(Request $request, $id)
    {
        $currentUser = auth()->user();
        
        if (!$currentUser->hasRole(['admin', 'superadmin'])) {
            abort(403, 'Unauthorized access');
        }

        $user = User::findOrFail($id);
        
        // Admin can't modify other admins or superadmins
        if ($currentUser->hasRole('admin') && $user->hasRole(['admin', 'superadmin'])) {
            abort(403, 'Unauthorized access');
        }

        $user->update([
            'status' => $request->status
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User status updated successfully'
        ]);
    }

    public function deleteUser($id)
    {
        $currentUser = auth()->user();
        
        if (!$currentUser->hasRole('superadmin')) {
            abort(403, 'Unauthorized access');
        }

        $user = User::findOrFail($id);
        
        // Can't delete self
        if ($user->id === $currentUser->id) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete your own account'
            ]);
        }

        // Delete profile image if exists
        if ($user->profile_image) {
            Storage::disk('public')->delete('profile_images/' . $user->profile_image);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully'
        ]);
    }

    public function exportUsers(Request $request)
    {
        $currentUser = auth()->user();
        
        if (!$currentUser->hasRole(['admin', 'superadmin'])) {
            abort(403, 'Unauthorized access');
        }

        $filters = $request->all();
        return Excel::download(new UsersExport($filters, $currentUser->getRoleNames()->first()), 'users.xlsx');
    }

    private function calculateProfileCompletion($user)
    {
        $fields = ['name', 'email', 'phone', 'country', 'date_of_birth', 'gender', 'address', 'bio', 'profile_image'];
        $completed = 0;
        
        foreach ($fields as $field) {
            if (!empty($user->$field)) {
                $completed++;
            }
        }
        
        return round(($completed / count($fields)) * 100);
    }
}