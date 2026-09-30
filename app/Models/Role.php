<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'display_name',
        'description',
     'guard_name',

        'created_by',
        
        'is_system_role'
        
    ];

    protected $casts = [
       
        'is_system_role' => 'boolean',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'role', 'name');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function hasPermission($permission, $action = null)
    {
        if (!$this->permissions) {
            return false;
        }

        if ($action) {
            return in_array($action, $this->permissions[$permission] ?? []);
        }

        return array_key_exists($permission, $this->permissions);
    }

    /**
     * The permissions that belong to the role
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_has_permissions');
    }

    /**
     * The users that belong to the role
     */
    // public function users()
    // {
    //     return $this->belongsToMany(User::class, 'model_has_roles', 'role_id', 'model_id')
    //                 ->where('model_type', User::class);
    // }

    /**
     * Check if role has permission
     */
    // public function hasPermission($permission)
    // {
    //     if (is_string($permission)) {
    //         return $this->permissions->contains('name', $permission);
    //     }

    //     return $this->permissions->contains($permission);
    // }

    /**
     * Give permission to role
     */
    public function givePermissionTo($permission)
    {
        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)->first();
        }

        if ($permission && !$this->hasPermission($permission)) {
            $this->permissions()->attach($permission);
        }

        return $this;
    }

    /**
     * Revoke permission from role
     */
    public function revokePermissionTo($permission)
    {
        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)->first();
        }

        if ($permission) {
            $this->permissions()->detach($permission);
        }

        return $this;
    }

    /**
     * Sync permissions
     */
    public function syncPermissions($permissions)
    {
        $permissionIds = collect($permissions)->map(function ($permission) {
            if (is_string($permission)) {
                return Permission::where('name', $permission)->first()?->id;
            }
            return $permission instanceof Permission ? $permission->id : $permission;
        })->filter()->toArray();

        $this->permissions()->sync($permissionIds);
        return $this;
    }

    /**
     * Get role by name
     */
    public static function findByName($name, $guardName = 'web')
    {
        return static::where('name', $name)
                    ->where('guard_name', $guardName)
                    ->first();
    }

    /**
     * Create default roles
     */
    public static function createDefaultRoles()
    {
        $roles = [
            [
                'name' => 'superadmin',
                'display_name' => 'Super Administrator',
                'description' => 'Full system access with all permissions',
                'guard_name' => 'web',
                'is_system_role' => true
            ],
            [
                'name' => 'admin',
                'display_name' => 'Administrator',
                'description' => 'Administrative access with most permissions',
                'guard_name' => 'web',
                'is_system_role' => true
            ],
            [
                'name' => 'user',
                'display_name' => 'User',
                'description' => 'Standard user with basic permissions',
                'guard_name' => 'web',
                'is_system_role' => true
            ]
        ];

        foreach ($roles as $roleData) {
            static::firstOrCreate(
                ['name' => $roleData['name'], 'guard_name' => $roleData['guard_name']],
                $roleData
            );
        }
    }

    /**
     * Scope for non-system roles
     */
    public function scopeCustom($query)
    {
        return $query->where('is_system_role', false);
    }

    /**
     * Scope for system roles
     */
    public function scopeSystem($query)
    {
        return $query->where('is_system_role', true);
    }

    /**
     * Get permissions count
     */
    public function getPermissionsCountAttribute()
    {
        return $this->permissions()->count();
    }

    /**
     * Get users count
     */
    public function getUsersCountAttribute()
    {
        return $this->users()->count();
    }
}