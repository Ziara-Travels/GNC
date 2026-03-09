<?php

namespace App\Http\Controllers\Roles;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    /**
     * Display a listing of roles
     */
    public function index()
    {
        $roles = Role::with(['permissions', 'users'])->paginate(15);
        return view('superadmin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new role
     */
    public function create()
    {
        $permissions = Permission::all();
        return view('superadmin.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created role
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'description' => 'nullable|string|max:500',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        DB::beginTransaction();
        try {
            $role = Role::create([
                'name' => $request->name,
                'description' => $request->description,
                'guard_name' => 'web'
            ]);

            if ($request->has('permissions')) {
                $role->permissions()->attach($request->permissions);
            }

            DB::commit();
            return redirect()->route('superadmin.roles.index')
                           ->with('success', 'Role created successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                           ->with('error', 'Error creating role: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Display the specified role
     */
    public function show(Role $role)
    {
        $role->load(['permissions', 'users']);
        return view('superadmin.roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified role
     */
    public function edit(Role $role)
    {
        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        return view('superadmin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Update the specified role
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('roles')->ignore($role->id)],
            'description' => 'nullable|string|max:500',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        DB::beginTransaction();
        try {
            $role->update([
                'name' => $request->name,
                'description' => $request->description,
            ]);

            // Sync permissions
            $role->permissions()->sync($request->permissions ?? []);

            DB::commit();
            return redirect()->route('superadmin.roles.index')
                           ->with('success', 'Role updated successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                           ->with('error', 'Error updating role: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Remove the specified role
     */
    public function destroy(Role $role)
    {
        // Prevent deletion of default roles
        $protectedRoles = ['super_admin', 'admin', 'user'];
        if (in_array($role->name, $protectedRoles)) {
            return redirect()->route('superadmin.roles.index')
                           ->with('error', 'Cannot delete system role: ' . $role->name);
        }

        // Check if role is assigned to users
        if ($role->users()->count() > 0) {
            return redirect()->route('superadmin.roles.index')
                           ->with('error', 'Cannot delete role that is assigned to users.');
        }

        DB::beginTransaction();
        try {
            $role->permissions()->detach();
            $role->delete();

            DB::commit();
            return redirect()->route('superadmin.roles.index')
                           ->with('success', 'Role deleted successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('superadmin.roles.index')
                           ->with('error', 'Error deleting role: ' . $e->getMessage());
        }
    }

    /**
     * Assign role to user
     */
    public function assignToUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id'
        ]);

        $user = User::findOrFail($request->user_id);
        $role = Role::findOrFail($request->role_id);

        $user->assignRole($role);

        return response()->json([
            'success' => true,
            'message' => 'Role assigned successfully.'
        ]);
    }

    /**
 * Superadmin: Assign all roles to all users
 */
public function assignAllRolesToAllUsers()
{
    $roles = Role::all();
    $users = User::all();

    foreach ($users as $user) {
        $user->syncRoles($roles);
    }

    return redirect()->back()->with('success', 'All roles assigned to all users.');
}

/**
 * Admin: Assign selected roles to a user
 */
public function assignRolesToUser(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'role_ids' => 'required|array',
        'role_ids.*' => 'exists:roles,id'
    ]);

    $user = User::findOrFail($request->user_id);
    $user->syncRoles($request->role_ids);

    return redirect()->back()->with('success', 'Roles assigned to user successfully.');
}

    /**
     * Remove role from user
     */
    public function removeFromUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id'
        ]);

        $user = User::findOrFail($request->user_id);
        $role = Role::findOrFail($request->role_id);

        $user->removeRole($role);

        return response()->json([
            'success' => true,
            'message' => 'Role removed successfully.'
        ]);
    }
}
