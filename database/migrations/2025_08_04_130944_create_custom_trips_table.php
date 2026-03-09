<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomTripsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('custom_trips', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->text('description');
            $table->string('travel_timing')->nullable();
            $table->boolean('subscribe_newsletter')->default(false);
            $table->string('status')->default('new'); // new, contacted, in-progress, completed, cancelled
            $table->foreignId('user_id')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamp('last_contacted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('custom_trips');
    }
}
