<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
     public function run()
    {
        $permissions = [
            // Dashboard Access
            [
                'name' => 'dashboard.access',
                'display_name' => 'Access Dashboard',
                'description' => 'Can access the dashboard',
                'group' => 'dashboard'
            ],
            [
                'name' => 'dashboard.analytics',
                'display_name' => 'View Analytics',
                'description' => 'Can view dashboard analytics',
                'group' => 'dashboard'
            ],

            // User Management
            [
                'name' => 'users.view',
                'display_name' => 'View Users',
                'description' => 'Can view user listings',
                'group' => 'users'
            ],
            [
                'name' => 'users.create',
                'display_name' => 'Create Users',
                'description' => 'Can create new users',
                'group' => 'users'
            ],
            [
                'name' => 'users.update',
                'display_name' => 'Update Users',
                'description' => 'Can update user information',
                'group' => 'users'
            ],
            [
                'name' => 'users.delete',
                'display_name' => 'Delete Users',
                'description' => 'Can delete users',
                'group' => 'users'
            ],
            [
                'name' => 'users.create_admin',
                'display_name' => 'Create Admin Users',
                'description' => 'Can create admin users',
                'group' => 'users'
            ],
            [
                'name' => 'users.create_superadmin',
                'display_name' => 'Create Super Admin Users',
                'description' => 'Can create super admin users',
                'group' => 'users'
            ],

            // Flight Management
            [
                'name' => 'flights.view',
                'display_name' => 'View Flights',
                'description' => 'Can view flight listings',
                'group' => 'flights'
            ],
            [
                'name' => 'flights.search',
                'display_name' => 'Search Flights',
                'description' => 'Can search for flights',
                'group' => 'flights'
            ],
            [
                'name' => 'flights.search_for_others',
                'display_name' => 'Search Flights for Others',
                'description' => 'Can search flights for other users',
                'group' => 'flights'
            ],
            [
                'name' => 'flights.book',
                'display_name' => 'Book Flights',
                'description' => 'Can book flights',
                'group' => 'flights'
            ],
            [
                'name' => 'flights.cancel',
                'display_name' => 'Cancel Flights',
                'description' => 'Can cancel flight bookings',
                'group' => 'flights'
            ],
            [
                'name' => 'flights.modify',
                'display_name' => 'Modify Flights',
                'description' => 'Can modify flight bookings',
                'group' => 'flights'
            ],

            // Booking Management
            [
                'name' => 'bookings.view',
                'display_name' => 'View Bookings',
                'description' => 'Can view booking listings',
                'group' => 'bookings'
            ],
            [
                'name' => 'bookings.view_all',
                'display_name' => 'View All Bookings',
                'description' => 'Can view all user bookings',
                'group' => 'bookings'
            ],
            [
                'name' => 'bookings.create',
                'display_name' => 'Create Bookings',
                'description' => 'Can create new bookings',
                'group' => 'bookings'
            ],
            [
                'name' => 'bookings.update',
                'display_name' => 'Update Bookings',
                'description' => 'Can update booking information',
                'group' => 'bookings'
            ],
            [
                'name' => 'bookings.delete',
                'display_name' => 'Delete Bookings',
                'description' => 'Can delete bookings',
                'group' => 'bookings'
            ],
            [
                'name' => 'bookings.manage_status',
                'display_name' => 'Manage Booking Status',
                'description' => 'Can change booking status',
                'group' => 'bookings'
            ],

            // Payment Management
            [
                'name' => 'payments.view',
                'display_name' => 'View Payments',
                'description' => 'Can view payment information',
                'group' => 'payments'
            ],
            [
                'name' => 'payments.process',
                'display_name' => 'Process Payments',
                'description' => 'Can process payments',
                'group' => 'payments'
            ],
            [
                'name' => 'payments.refund',
                'display_name' => 'Process Refunds',
                'description' => 'Can process refunds',
                'group' => 'payments'
            ],

            // Reports
            [
                'name' => 'reports.view',
                'display_name' => 'View Reports',
                'description' => 'Can view system reports',
                'group' => 'reports'
            ],
            [
                'name' => 'reports.export',
                'display_name' => 'Export Reports',
                'description' => 'Can export reports',
                'group' => 'reports'
            ],

            // System Settings
            [
                'name' => 'settings.view',
                'display_name' => 'View Settings',
                'description' => 'Can view system settings',
                'group' => 'settings'
            ],
            [
                'name' => 'settings.update',
                'display_name' => 'Update Settings',
                'description' => 'Can update system settings',
                'group' => 'settings'
            ],

            // Roles & Permissions
            [
                'name' => 'roles.view',
                'display_name' => 'View Roles',
                'description' => 'Can view roles and permissions',
                'group' => 'roles'
            ],
            [
                'name' => 'roles.manage',
                'display_name' => 'Manage Roles',
                'description' => 'Can manage roles and permissions',
                'group' => 'roles'
            ],

            // Profile Management
            [
                'name' => 'profile.view',
                'display_name' => 'View Profile',
                'description' => 'Can view own profile',
                'group' => 'profile'
            ],
            [
                'name' => 'profile.update',
                'display_name' => 'Update Profile',
                'description' => 'Can update own profile',
                'group' => 'profile'
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        // Assign permissions to roles
        $this->assignRolePermissions();
    }

    private function assignRolePermissions()
    {
        $permissions = Permission::all();
        
        // SuperAdmin gets all permissions
        foreach ($permissions as $permission) {
            DB::table('role_permissions')->insert([
                'role' => 'superadmin',
                'permission_id' => $permission->id,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Admin permissions (excluding superadmin creation and some system settings)
        $adminPermissions = [
            'dashboard.access',
            'dashboard.analytics',
            'users.view',
            'users.create',
            'users.update',
            'flights.view',
            'flights.search',
            'flights.search_for_others',
            'flights.book',
            'flights.cancel',
            'flights.modify',
            'bookings.view',
            'bookings.view_all',
            'bookings.create',
            'bookings.update',
            'bookings.manage_status',
            'payments.view',
            'payments.process',
            'reports.view',
            'reports.export',
            'settings.view',
            'profile.view',
            'profile.update'
        ];

        foreach ($adminPermissions as $permissionName) {
            $permission = Permission::where('name', $permissionName)->first();
            if ($permission) {
                DB::table('role_permissions')->insert([
                    'role' => 'admin',
                    'permission_id' => $permission->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }

        // User permissions (read-only and basic functionality)
        $userPermissions = [
            'dashboard.access',
            'flights.view',
            'flights.search',
            'flights.book',
            'bookings.view',
            'bookings.create',
            'payments.view',
            'profile.view',
            'profile.update'
        ];

        foreach ($userPermissions as $permissionName) {
            $permission = Permission::where('name', $permissionName)->first();
            if ($permission) {
                DB::table('role_permissions')->insert([
                    'role' => 'user',
                    'permission_id' => $permission->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }
}
