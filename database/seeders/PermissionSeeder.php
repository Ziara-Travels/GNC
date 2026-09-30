<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Seed the system roles and permissions.
     *
     * Both model helpers are idempotent, so this is safe to re-run.
     *
     * @return void
     */
    public function run()
    {
        Role::createDefaultRoles();
        Permission::createDefaultPermissions();
    }
}
