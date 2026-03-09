<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    use HasFactory, SoftDeletes;

     protected $fillable = [
        'name',
        'guard_name',
        'display_name',
        'description',
        'group',
        'is_system_permission',
    ];
     protected $casts = [
        'is_system_permission' => 'boolean',
    ];

    /**
     * The roles that belong to the permission
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_has_permissions');
    }

    /**
     * The users that have this permission directly
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'model_has_permissions', 'permission_id', 'model_id')
                    ->where('model_type', User::class);
    }

    /**
     * Get permission by name
     */
    public static function findByName($name, $guardName = 'web')
    {
        return static::where('name', $name)
                    ->where('guard_name', $guardName)
                    ->first();
    }

    /**
     * Create default permissions
     */
    public static function createDefaultPermissions()
    {
        $permissions = [
            // User Management
            [
                'name' => 'view_users',
                'display_name' => 'View Users',
                'description' => 'Can view user list and profiles',
                'group' => 'User Management',
                'guard_name' => 'web',
                'is_system_permission' => true
            ],
            [
                'name' => 'create_users',
                'display_name' => 'Create Users',
                'description' => 'Can create new users',
                'group' => 'User Management',
                'guard_name' => 'web',
                'is_system_permission' => true
            ],
            [
                'name' => 'edit_users',
                'display_name' => 'Edit Users',
                'description' => 'Can edit user information',
                'group' => 'User Management',
                'guard_name' => 'web',
                'is_system_permission' => true
            ],
            [
                'name' => 'delete_users',
                'display_name' => 'Delete Users',
                'description' => 'Can delete users',
                'group' => 'User Management',
                'guard_name' => 'web',
                'is_system_permission' => true
            ],
            [
                'name' => 'manage_user_roles',
                'display_name' => 'Manage User Roles',
                'description' => 'Can assign/remove roles from users',
                'group' => 'User Management',
                'guard_name' => 'web',
                'is_system_permission' => true
            ],

            // Role Management
            [
                'name' => 'view_roles',
                'display_name' => 'View Roles',
                'description' => 'Can view roles list',
                'group' => 'Role Management',
                'guard_name' => 'web',
                'is_system_permission' => true
            ],
            [
                'name' => 'create_roles',
                'display_name' => 'Create Roles',
                'description' => 'Can create new roles',
                'group' => 'Role Management',
                'guard_name' => 'web',
                'is_system_permission' => true
            ],
            [
                'name' => 'edit_roles',
                'display_name' => 'Edit Roles',
                'description' => 'Can edit roles',
                'group' => 'Role Management',
                'guard_name' => 'web',
                'is_system_permission' => true
            ],
            [
                'name' => 'delete_roles',
                'display_name' => 'Delete Roles',
                'description' => 'Can delete roles',
                'group' => 'Role Management',
                'guard_name' => 'web',
                'is_system_permission' => true
            ],

            // Permission Management
            [
                'name' => 'view_permissions',
                'display_name' => 'View Permissions',
                'description' => 'Can view permissions list',
                'group' => 'Permission Management',
                'guard_name' => 'web',
                'is_system_permission' => true
            ],
            [
                'name' => 'create_permissions',
                'display_name' => 'Create Permissions',
                'description' => 'Can create new permissions',
                'group' => 'Permission Management',
                'guard_name' => 'web',
                'is_system_permission' => true
            ],
            [
                'name' => 'edit_permissions',
                'display_name' => 'Edit Permissions',
                'description' => 'Can edit permissions',
                'group' => 'Permission Management',
                'guard_name' => 'web',
                'is_system_permission' => true
            ],
            [
                'name' => 'delete_permissions',
                'display_name' => 'Delete Permissions',
                'description' => 'Can delete permissions',
                'group' => 'Permission Management',
                'guard_name' => 'web',
                'is_system_permission' => true
            ],

            // System Management
            [
                'name' => 'view_dashboard',
                'display_name' => 'View Dashboard',
                'description' => 'Can access admin dashboard',
                'group' => 'System Management',
                'guard_name' => 'web',
                'is_system_permission' => true
            ],
            [
                'name' => 'manage_settings',
                'display_name' => 'Manage Settings',
                'description' => 'Can manage system settings',
                'group' => 'System Management',
                'guard_name' => 'web',
                'is_system_permission' => true
            ],
            [
                'name' => 'view_logs',
                'display_name' => 'View Logs',
                'description' => 'Can view system logs',
                'group' => 'System Management',
                'guard_name' => 'web',
                'is_system_permission' => true
            ],

            // Content Management
            [
                'name' => 'view_content',
                'display_name' => 'View Content',
                'description' => 'Can view content',
                'group' => 'Content Management',
                'guard_name' => 'web',
                'is_system_permission' => true
            ],
            [
                'name' => 'create_content',
                'display_name' => 'Create Content',
                'description' => 'Can create new content',
                'group' => 'Content Management',
                'guard_name' => 'web',
                'is_system_permission' => true
            ],
            [
                'name' => 'edit_content',
                'display_name' => 'Edit Content',
                'description' => 'Can edit content',
                'group' => 'Content Management',
                'guard_name' => 'web',
                'is_system_permission' => true
            ],
            [
                'name' => 'delete_content',
                'display_name' => 'Delete Content',
                'description' => 'Can delete content',
                'group' => 'Content Management',
                'guard_name' => 'web',
                'is_system_permission' => true
            ],
            [
                'name' => 'publish_content',
                'display_name' => 'Publish Content',
                'description' => 'Can publish/unpublish content',
                'group' => 'Content Management',
                'guard_name' => 'web',
                'is_system_permission' => true
            ]
        ];

        foreach ($permissions as $permissionData) {
            static::firstOrCreate(
                ['name' => $permissionData['name'], 'guard_name' => $permissionData['guard_name']],
                $permissionData
            );
        }
    }

    /**
     * Scope for permissions by group
     */
    public function scopeByGroup($query, $group)
    {
        return $query->where('group', $group);
    }

    /**
     * Scope for system permissions
     */
    public function scopeSystem($query)
    {
        return $query->where('is_system_permission', true);
    }

    /**
     * Scope for custom permissions
     */
    public function scopeCustom($query)
    {
        return $query->where('is_system_permission', false);
    }

    /**
     * Get all permission groups
     */
    public static function getGroups()
    {
        return static::select('group')
                    ->distinct()
                    ->orderBy('group')
                    ->pluck('group')
                    ->toArray();
    }

    /**
     * Get permissions grouped by group
     */
    public static function getGroupedPermissions()
    {
        return static::orderBy('group')
                    ->orderBy('display_name')
                    ->get()
                    ->groupBy('group');
    }

    /**
     * Get roles count
     */
    public function getRolesCountAttribute()
    {
        return $this->roles()->count();
    }
    }