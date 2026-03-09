<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddContactFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Check if columns don't exist before adding them
            if (!Schema::hasColumn('users', 'country')) {
                $table->string('country')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'phone_country_code')) {
                $table->string('phone_country_code')->nullable()->after('country');
            }
            if (!Schema::hasColumn('users', 'phone_number')) {
                $table->string('phone_number')->nullable()->after('phone_country_code');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Check if columns exist before dropping them
            if (Schema::hasColumn('users', 'phone_number')) {
                $table->dropColumn('phone_number');
            }
            if (Schema::hasColumn('users', 'phone_country_code')) {
                $table->dropColumn('phone_country_code');
            }
            if (Schema::hasColumn('users', 'country')) {
                $table->dropColumn('country');
            }
        });
    }
}