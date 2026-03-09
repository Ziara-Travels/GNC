<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            
            // Contact fields
            $table->string('phone')->nullable();
            $table->string('country')->nullable();
            $table->string('phone_country_code')->nullable();
            $table->string('phone_number')->nullable();
            
            // Personal information fields
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->text('address')->nullable();
            $table->text('bio')->nullable();
            $table->string('profile_image')->nullable();
            
            // Role and status fields
            $table->enum('role', ['user', 'admin', 'superadmin'])->default('user');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->boolean('is_active')->default(false);
            $table->enum('status', ['active', 'inactive'])->default('inactive');
            $table->boolean('is_deleted')->default(false);
            
            // Authentication fields
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            
            // Loyalty program fields
            $table->integer('loyalty_points')->default(0);
            $table->timestamp('last_login_at')->nullable();
            $table->boolean('reengagement_sent')->default(false);
            
            // Referral system fields
            $table->string('referral_code')->unique()->nullable();
            $table->unsignedBigInteger('referred_by')->nullable();
            $table->integer('referral_count')->default(0);
            $table->decimal('referral_earnings', 10, 2)->default(0.00);
            $table->boolean('referral_active')->default(true);
            
            // Laravel default fields
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
            
            // Foreign key constraints
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('referred_by')->references('id')->on('users')->onDelete('set null');
            
            // Indexes for performance
            $table->index('email');
            $table->index('role');
            $table->index('is_active');
            $table->index('status');
            $table->index('referral_code');
            $table->index('referred_by');
            $table->index('created_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        Schema::dropIfExists('users');
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}