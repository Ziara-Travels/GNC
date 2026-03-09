<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePassengerProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('passenger_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->date('date_of_birth');
            $table->string('nationality');
            $table->string('passport_number')->nullable();
            $table->date('passport_expiry')->nullable();
            $table->enum('gender', ['male', 'female', 'other']);
            $table->string('frequent_flyer_number')->nullable();
            $table->text('special_requirements')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
            
            $table->index(['user_id', 'email']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('passenger_profiles');
    }
}
