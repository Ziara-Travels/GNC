<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Align the seeded role name with the users.role enum value.
     */
    public function up(): void
    {
        $table = 'roles';

        if (! Schema::hasTable($table)) {
            return;
        }

        $exists = DB::table($table)->where('name', 'superadmin')->exists();
        $legacy = DB::table($table)->where('name', 'super_admin')->exists();

        if ($exists || ! $legacy) {
            return;
        }

        DB::table($table)->where('name', 'super_admin')->update(['name' => 'superadmin']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $table = 'roles';

        if (! Schema::hasTable($table)) {
            return;
        }

        $exists = DB::table($table)->where('name', 'super_admin')->exists();
        $renamed = DB::table($table)->where('name', 'superadmin')->exists();

        if ($exists || ! $renamed) {
            return;
        }

        DB::table($table)->where('name', 'superadmin')->update(['name' => 'super_admin']);
    }
};
