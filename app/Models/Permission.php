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
            // Flight Management
            [
                'name' => 'flights.search',
                'display_name' => 'Search Flights',
                'description' => 'Can search for flights',
                'group' => 'Flight Management',
                'guard_name' => 'web',
                'is_system_permission' => true
            ],
            [
                'name' => 'flights.view',
                'display_name' => 'View Flights',
                'description' => 'Can view flight search results and details',
                'group' => 'Flight Management',
                'guard_name' => 'web',
                'is_system_permission' => true
            ],
            [
                'name' => 'flights.book',
                'display_name' => 'Book Flights',
                'description' => 'Can book flights',
                'group' => 'Flight Management',
                'guard_name' => 'web',
                'is_system_permission' => true
            ],
            [
                'name' => 'flights.search_for_others',
                'display_name' => 'Search Flights For Others',
                'description' => 'Can search flights on behalf of another user',
                'group' => 'Flight Management',
                'guard_name' => 'web',
                'is_system_permission' => true
            ],

            // Booking Management
            [
                'name' => 'bookings.view_own',
                'display_name' => 'View Own Bookings',
                'description' => 'Can view only their own bookings',
                'group' => 'Booking Management',
                'guard_name' => 'web',
                'is_system_permission' => true
            ],

            // Payment Management
            [
                'name' => 'payments.view',
                'display_name' => 'View Payments',
                'description' => 'Can view payment records',
                'group' => 'Payment Management',
                'guard_name' => 'web',
                'is_system_permission' => true
            ],
            [
                'name' => 'payments.view_all',
                'display_name' => 'View All Payments',
                'description' => 'Can view payment records for every user',
                'group' => 'Payment Management',
                'guard_name' => 'web',
                'is_system_permission' => true
            ],
            [
                'name' => 'payments.view_own',
                'display_name' => 'View Own Payments',
                'description' => 'Can view only their own payment records',
                'group' => 'Payment Management',
                'guard_name' => 'web',
                'is_system_permission' => true
            ],
            [
                'name' => 'payments.create',
                'display_name' => 'Create Payments',
                'description' => 'Can initiate payment records',
                'group' => 'Payment Management',
                'guard_name' => 'web',
                'is_system_permission' => true
            ],
            [
                'name' => 'payments.process',
                'display_name' => 'Process Payments',
                'description' => 'Can process and approve pending payments',
                'group' => 'Payment Management',
                'guard_name' => 'web',
                'is_system_permission' => true
            ],
            [
                'name' => 'payments.export',
                'display_name' => 'Export Payments',
                'description' => 'Can export payment data',
                'group' => 'Payment Management',
                'guard_name' => 'web',
                'is_system_permission' => true
            ],

            // User Management
            [
                'name' => 'users.view',
                'display_name' => 'View User Records',
                'description' => 'Can view user listings and profiles',
                'group' => 'User Management',
                'guard_name' => 'web',
                'is_system_permission' => true
            ],
            [
                'name' => 'users.create',
                'display_name' => 'Create User Records',
                'description' => 'Can create new users',
                'group' => 'User Management',
                'guard_name' => 'web',
                'is_system_permission' => true
            ],
            [
                'name' => 'users.update',
                'display_name' => 'Update User Records',
                'description' => 'Can update user information',
                'group' => 'User Management',
                'guard_name' => 'web',
                'is_system_permission' => true
            ],
            [
                'name' => 'users.manage',
                'display_name' => 'Manage Users',
                'description' => 'Can manage users, including their roles and status',
                'group' => 'User Management',
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