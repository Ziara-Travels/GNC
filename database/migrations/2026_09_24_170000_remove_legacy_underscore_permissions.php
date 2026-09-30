<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Retires the legacy snake_case permission vocabulary.
 *
 * These 21 permissions were never enforced anywhere in the application - the
 * codebase checks a separate set of dot-notation names (flights.*, payments.*,
 * users.*, bookings.view_own). Granting them had no effect, so they only added
 * noise to the role-assignment form.
 *
 * Rows are soft deleted rather than destroyed so the change is reversible and
 * any historical pivot rows stay referentially intact.
 */
return new class extends Migration
{
    /**
     * The legacy, unenforced permission names.
     *
     * @var list<string>
     */
    private array $legacyNames = [
        'view_users',
        'create_users',
        'edit_users',
        'delete_users',
        'manage_user_roles',
        'view_roles',
        'create_roles',
        'edit_roles',
        'delete_roles',
        'view_permissions',
        'create_permissions',
        'edit_permissions',
        'delete_permissions',
        'view_dashboard',
        'manage_settings',
        'view_logs',
        'view_content',
        'create_content',
        'edit_content',
        'delete_content',
        'publish_content',
    ];

    public function up(): void
    {
        if (! Schema::hasTable('permissions') || ! Schema::hasColumn('permissions', 'deleted_at')) {
            return;
        }

        DB::table('permissions')
            ->whereIn('name', $this->legacyNames)
            ->whereNull('deleted_at')
            ->update(['deleted_at' => now()]);
    }

    public function down(): void
    {
        if (! Schema::hasTable('permissions') || ! Schema::hasColumn('permissions', 'deleted_at')) {
            return;
        }

        DB::table('permissions')
            ->whereIn('name', $this->legacyNames)
            ->whereNotNull('deleted_at')
            ->update(['deleted_at' => null]);
    }
};
