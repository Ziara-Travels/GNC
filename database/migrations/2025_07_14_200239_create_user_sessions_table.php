<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('session_id');
            $table->string('ip_address');
            $table->text('user_agent');
            $table->string('device_type')->nullable();
            $table->string('browser')->nullable();
            $table->string('platform')->nullable();
            $table->string('location_country')->nullable();
            $table->string('location_city')->nullable();
            $table->string('location_region')->nullable();
            $table->boolean('is_suspicious')->default(false);
            $table->timestamp('login_at');
            $table->timestamp('logout_at')->nullable();
            $table->timestamp('last_activity')->useCurrent(); // Fixed: Add default current timestamp
            $table->timestamps();

            $table->index(['user_id', 'login_at']);
            $table->index(['ip_address', 'login_at']);
            $table->index(['is_suspicious']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_sessions');
    }
}