<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserManagementController extends Controller
{
    /**
     * Only superadmins can access this controller
     */
    public function __construct()
    {
        $this->middleware(['auth', 'role:superadmin']);
    }
    
    /**
     * Display a listing of users
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $users = User::withTrashed()->paginate(15);
        return view('dashboard.users.superadmin.index', compact('users'));
    }
    
    /**
     * Show form to create a new user
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('dashboard.users.superadmin.create');
    }
    
    /**
     * Store a newly created user
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', 'in:user,admin,superadmin'],
            'is_active' => ['boolean'],
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        
        // Check if trying to create superadmin when not superadmin
        if ($request->role === 'superadmin' && Auth::user()->role !== 'superadmin') {
            return back()->with('error', 'You do not have permission to create superadmin users.');
        }
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_active' => $request->has('is_active') ? true : false,
            'email_verified_at' => now(), // Admin-created users are pre-verified
            'created_by' => Auth::id(),
        ]);
        
        return redirect()->route('superadmin.users.index')->with('success', 'User created successfully.');
    }
    
    /**
     * Show form to edit a user
     * 
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        
        // Prevent editing of superadmin by non-superadmins
        if ($user->role === 'superadmin' && Auth::user()->role !== 'superadmin') {
            return back()->with('error', 'You do not have permission to edit superadmin users.');
        }
        
        // Prevent users from editing users who created them
        if ($user->id === Auth::user()->created_by) {
            return back()->with('error', 'You cannot edit the user who created your account.');
        }
        
        return view('dashboard.users.superadmin.edit', compact('user'));
    }
    
    /**
     * Update the specified user
     * 
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $user = User::withTrashed()->findOrFail($id);
        
        // Prevent editing of superadmin by non-superadmins
        if ($user->role === 'superadmin' && Auth::user()->role !== 'superadmin') {
            return back()->with('error', 'You do not have permission to edit superadmin users.');
        }
        
        // Prevent users from editing users who created them
        if ($user->id === Auth::user()->created_by) {
            return back()->with('error', 'You cannot edit the user who created your account.');
        }
        
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $id],
            'role' => ['required', 'string', 'in:user,admin,superadmin'],
            'is_active' => ['boolean'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        
        // Check if trying to change role to superadmin when not superadmin
        if ($request->role === 'superadmin' && Auth::user()->role !== 'superadmin') {
            return back()->with('error', 'You do not have permission to create superadmin users.');
        }
        
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'is_active' => $request->has('is_active') ? true : false,
            'updated_by' => Auth::id(),
        ];
        
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        
        $user->update($data);
        
        return redirect()->route('superadmin.users.index')->with('success', 'User updated successfully.');
    }
    
    /**
     * Soft delete a user
     * 
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent deletion of superadmin by non-superadmins
        if ($user->role === 'superadmin' && Auth::user()->role !== 'superadmin') {
            return back()->with('error', 'You do not have permission to delete superadmin users.');
        }
        
        // Prevent users from deleting users who created them
        if ($user->id === Auth::user()->created_by) {
            return back()->with('error', 'You cannot delete the user who created your account.');
        }
        
        // Prevent superadmins from deleting other superadmins
        if ($user->role === 'superadmin' && Auth::user()->role === 'superadmin' && $user->id !== Auth::id()) {
            return back()->with('error', 'Superadmins cannot delete other superadmins.');
        }
        
        $user->delete();
        
        return redirect()->route('superadmin.users.index')->with('success', 'User deleted successfully.');
    }
    
    /**
     * Restore a soft deleted user
     * 
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();
        
        return redirect()->route('superadmin.users.index')->with('success', 'User restored successfully.');
    }
}
