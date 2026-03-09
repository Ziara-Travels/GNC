<?php

namespace App\Http\Controllers\Roles;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PermissionController extends Controller
{
    /**
     * Display a listing of permissions
     */
    public function index()
    {
        $permissions = Permission::with(['roles'])->paginate(15);
        return view('superadmin.permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new permission
     */
    public function create()
    {
        $roles = Role::all();
        $permissionGroups = $this->getPermissionGroups();
        return view('superadmin.permissions.create', compact('roles', 'permissionGroups'));
    }

    /**
     * Store a newly created permission
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'group' => 'required|string|max:100',
            'roles' => 'array',
            'roles.*' => 'exists:roles,id'
        ]);

        DB::beginTransaction();
        try {
            $permission = Permission::create([
                'name' => $request->name,
                'display_name' => $request->display_name,
                'description' => $request->description,
                'group' => $request->group,
                'guard_name' => 'web'
            ]);

            if ($request->has('roles')) {
                $permission->roles()->attach($request->roles);
            }

            DB::commit();
            return redirect()->route('superadmin.permissions.index')
                           ->with('success', 'Permission created successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                           ->with('error', 'Error creating permission: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Display the specified permission
     */
    public function show(Permission $permission)
    {
        $permission->load(['roles']);
        return view('superadmin.permissions.show', compact('permission'));
    }

    /**
     * Show the form for editing the specified permission
     */
    public function edit(Permission $permission)
    {
        $roles = Role::all();
        $permissionRoles = $permission->roles->pluck('id')->toArray();
        $permissionGroups = $this->getPermissionGroups();
        return view('superadmin.permissions.edit', compact('permission', 'roles', 'permissionRoles', 'permissionGroups'));
    }

    /**
     * Update the specified permission
     */
    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('permissions')->ignore($permission->id)],
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'group' => 'required|string|max:100',
            'roles' => 'array',
            'roles.*' => 'exists:roles,id'
        ]);

        DB::beginTransaction();
        try {
            $permission->update([
                'name' => $request->name,
                'display_name' => $request->display_name,
                'description' => $request->description,
                'group' => $request->group,
            ]);

            // Sync roles
            $permission->roles()->sync($request->roles ?? []);

            DB::commit();
            return redirect()->route('superadmin.permissions.index')
                           ->with('success', 'Permission updated successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                           ->with('error', 'Error updating permission: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Remove the specified permission
     */
    public function destroy(Permission $permission)
    {
        // Prevent deletion of critical permissions
        $protectedPermissions = [
            'manage_users', 'manage_roles', 'manage_permissions', 
            'view_dashboard', 'manage_system_settings'
        ];
        
        if (in_array($permission->name, $protectedPermissions)) {
            return redirect()->route('superadmin.permissions.index')
                           ->with('error', 'Cannot delete system permission: ' . $permission->name);
        }

        DB::beginTransaction();
        try {
            $permission->roles()->detach();
            $permission->delete();

            DB::commit();
            return redirect()->route('superadmin.permissions.index')
                           ->with('success', 'Permission deleted successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('superadmin.permissions.index')
                           ->with('error', 'Error deleting permission: ' . $e->getMessage());
        }
    }

    /**
     * Get permission groups for categorization
     */
    private function getPermissionGroups()
    {
        return [
            'User Management' => [
                'view_users' => 'View Users',
                'create_users' => 'Create Users',
                'edit_users' => 'Edit Users',
                'delete_users' => 'Delete Users',
                'manage_user_roles' => 'Manage User Roles'
            ],
            'Role Management' => [
                'view_roles' => 'View Roles',
                'create_roles' => 'Create Roles',
                'edit_roles' => 'Edit Roles',
                'delete_roles' => 'Delete Roles'
            ],
            'Permission Management' => [
                'view_permissions' => 'View Permissions',
                'create_permissions' => 'Create Permissions',
                'edit_permissions' => 'Edit Permissions',
                'delete_permissions' => 'Delete Permissions'
            ],
            'Content Management' => [
                'view_content' => 'View Content',
                'create_content' => 'Create Content',
                'edit_content' => 'Edit Content',
                'delete_content' => 'Delete Content',
                'publish_content' => 'Publish Content'
            ],
            'System Management' => [
                'view_dashboard' => 'View Dashboard',
                'manage_settings' => 'Manage Settings',
                'view_logs' => 'View Logs',
                'manage_backups' => 'Manage Backups',
                'manage_system_maintenance' => 'System Maintenance'
            ],
            'Reports & Analytics' => [
                'view_reports' => 'View Reports',
                'export_data' => 'Export Data',
                'view_analytics' => 'View Analytics'
            ],
            'Financial Management' => [
                'view_transactions' => 'View Transactions',
                'manage_payments' => 'Manage Payments',
                'view_financial_reports' => 'View Financial Reports'
            ]
        ];
    }

    /**
     * Bulk assign permissions to role
     */
    public function bulkAssignToRole(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'permission_ids' => 'required|array',
            'permission_ids.*' => 'exists:permissions,id'
        ]);

        $role = Role::findOrFail($request->role_id);
        $role->permissions()->sync($request->permission_ids);

        return response()->json([
            'success' => true,
            'message' => 'Permissions assigned successfully.'
        ]);
    }

    /**
 * Superadmin: Assign all permissions to all users
 */
public function assignAllPermissionsToAllUsers()
{
    $permissions = Permission::all();
    $users = \App\Models\User::all();

    foreach ($users as $user) {
        $user->givePermissionTo($permissions);
    }

    return redirect()->back()->with('success', 'All permissions assigned to all users.');
}

/**
 * Admin: Assign selected permissions to a user
 */
public function assignPermissionsToUser(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'permission_ids' => 'required|array',
        'permission_ids.*' => 'exists:permissions,id'
    ]);

    $user = \App\Models\User::findOrFail($request->user_id);
    $user->givePermissionTo($request->permission_ids);

    return redirect()->back()->with('success', 'Permissions assigned to user successfully.');
}
}
