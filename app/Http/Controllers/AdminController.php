<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class AdminController extends Controller
{

    
    public function index()
    {
        $user = auth()->user();
        
        if (!$user->hasPermission('users.manage')) {
            abort(403, 'Unauthorized access');
        }

        // Get only users (not admins or superadmins)
        $users = User::where('role', 'user')
            ->with('permissions')
            ->paginate(10);

        // Stats for admin dashboard
        $stats = [
            'total_users' => User::where('role', 'user')->count(),
            'active_users' => User::where('role', 'user')->where('is_active', true)->count(),
            'new_users_this_month' => User::where('role', 'user')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'online_users' => User::where('role', 'user')
                ->where('last_login_at', '>=', now()->subMinutes(30))
                ->count()
        ];

        // Recent activity
        $recentActivity = collect([
            (object)[
                'title' => 'User Created',
                'description' => 'A new user was created',
                'icon' => 'user-plus',
                'created_at' => now()->subMinutes(10)
            ],
            (object)[
                'title' => 'User Updated',
                'description' => 'User permissions were updated',
                'icon' => 'edit',
                'created_at' => now()->subMinutes(30)
            ],
            (object)[
                'title' => 'User Status Changed',
                'description' => 'User account was activated',
                'icon' => 'user-check',
                'created_at' => now()->subHours(1)
            ]
        ]);

        return view('dashboard.roles.admin.index', compact('users', 'stats', 'recentActivity'));
    }

    public function createUser()
    {
        $user = auth()->user();
        
        if (!$user->hasPermission('users.create')) {
            abort(403, 'Unauthorized access');
        }

        // Get only user-level permissions
        $permissions = Permission::where('name', 'LIKE', 'users.%')
            ->orWhere('name', 'LIKE', 'flights.%')
            ->orWhere('name', 'LIKE', 'bookings.%')
            ->orWhere('name', 'LIKE', 'dashboard.view')
            ->whereNotIn('name', [
                'users.manage',
                'users.create_admin',
                'users.create_superadmin',
                'users.delete',
                'flights.search_for_others',
                'system.settings'
            ])
            ->get()
            ->groupBy(function($permission) {
                return explode('.', $permission->name)[0];
            });

        return view('dashboard.roles.admin.create-user', compact('permissions'));
    }

    public function storeUser(Request $request)
    {
        $user = auth()->user();
        
        if (!$user->hasPermission('users.create')) {
            abort(403, 'Unauthorized access');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'date_of_birth' => 'nullable|date',
            'password' => 'required|string|min:8|confirmed',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $newUser = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'country' => $request->country,
                'date_of_birth' => $request->date_of_birth,
                'password' => Hash::make($request->password),
                'role' => 'user',
                'is_active' => true,
                'created_by' => $user->id
            ]);

            // Assign permissions
            if ($request->permissions) {
                $newUser->permissions()->sync($request->permissions);
            }

            return redirect()->route('dashboard.roles.admin.index')
                ->with('success', 'User created successfully');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create user: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function editUser($id)
    {
        $user = auth()->user();
        
        if (!$user->hasPermission('users.update')) {
            abort(403, 'Unauthorized access');
        }

        $editUser = User::where('role', 'user')->findOrFail($id);

        // Get only user-level permissions
        $permissions = Permission::where('name', 'LIKE', 'users.%')
            ->orWhere('name', 'LIKE', 'flights.%')
            ->orWhere('name', 'LIKE', 'bookings.%')
            ->orWhere('name', 'LIKE', 'dashboard.view')
            ->whereNotIn('name', [
                'users.manage',
                'users.create_admin',
                'users.create_superadmin',
                'users.delete',
                'flights.search_for_others',
                'system.settings'
            ])
            ->get()
            ->groupBy(function($permission) {
                return explode('.', $permission->name)[0];
            });

        $userPermissions = $editUser->permissions->pluck('id')->toArray();

        return view('dashboard.roles.admin.edit-user', [
            'user' => $editUser,
            'permissions' => $permissions,
            'userPermissions' => $userPermissions
        ]);
    }

    public function updateUser(Request $request, $id)
    {
        $user = auth()->user();
        
        if (!$user->hasPermission('users.update')) {
            abort(403, 'Unauthorized access');
        }

        $editUser = User::where('role', 'user')->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'date_of_birth' => 'nullable|date',
            'password' => 'nullable|string|min:8|confirmed',
            'is_active' => 'required|boolean',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $editUser->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'country' => $request->country,
                'date_of_birth' => $request->date_of_birth,
                'is_active' => $request->is_active,
                'updated_by' => $user->id
            ]);

            // Update password if provided
            if ($request->password) {
                $editUser->update(['password' => Hash::make($request->password)]);
            }

            // Update permissions
            if ($request->permissions) {
                $editUser->permissions()->sync($request->permissions);
            } else {
                $editUser->permissions()->detach();
            }

            return redirect()->route('dashboard.roles.admin.index')
                ->with('success', 'User updated successfully');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update user: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function showUser($id)
    {
        $user = auth()->user();
        
        if (!$user->hasPermission('users.view')) {
            abort(403, 'Unauthorized access');
        }

        $viewUser = User::where('role', 'user')->with('permissions')->findOrFail($id);

        return response()->json([
            'id' => $viewUser->id,
            'name' => $viewUser->name,
            'email' => $viewUser->email,
            'phone' => $viewUser->phone,
            'country' => $viewUser->country,
            'role' => $viewUser->role,
            'is_active' => $viewUser->is_active,
            'created_at' => $viewUser->created_at->format('M d, Y'),
            'permissions' => $viewUser->permissions->map(function($permission) {
                return [
                    'id' => $permission->id,
                    'name' => $permission->name,
                    'display_name' => $permission->display_name,
                    'description' => $permission->description
                ];
            })
        ]);
    }

    public function toggleUserStatus($id)
    {
        $user = auth()->user();
        
        if (!$user->hasPermission('users.update')) {
            abort(403, 'Unauthorized access');
        }

        $targetUser = User::where('role', 'user')->findOrFail($id);

        $targetUser->update([
            'is_active' => !$targetUser->is_active,
            'updated_by' => $user->id
        ]);

        return response()->json(['success' => true]);
    }
}
