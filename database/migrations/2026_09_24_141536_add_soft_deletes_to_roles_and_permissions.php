<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $roles = 'roles';
        $permissions = 'permissions';

        if (!Schema::hasColumn($roles, 'deleted_at')) {
            Schema::table($roles, function (Blueprint $table) {
                $table->timestamp('deleted_at')->nullable();
            });
        }

        if (!Schema::hasColumn($permissions, 'deleted_at')) {
            Schema::table($permissions, function (Blueprint $table) {
                $table->timestamp('deleted_at')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $roles = 'roles';
        $permissions = 'permissions';

        Schema::table($roles, function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });

        Schema::table($permissions, function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });
    }
};