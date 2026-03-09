<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlightBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flight_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Amadeus API related fields
            $table->string('amadeus_offer_id')->nullable();
            $table->json('amadeus_data')->nullable(); // Store raw Amadeus offer data
            
            // Flight details
            $table->string('origin_code', 5);
            $table->string('origin_city', 100);
            $table->string('destination_code', 5);
            $table->string('destination_city', 100);
            $table->date('departure_date');
            $table->time('departure_time')->nullable();
            $table->date('return_date')->nullable();
            $table->time('return_time')->nullable();
            
            // Booking details
            $table->integer('passenger_count')->default(1);
            $table->string('cabin_class', 20)->default('economy');
            $table->string('airline_code', 10)->nullable();
            $table->string('flight_duration', 20)->nullable();
            $table->integer('stops')->default(0);
            
            // Pricing
            $table->decimal('total_price', 12, 2);
            $table->string('currency', 3)->default('KES');
            
            // Booking management
            $table->string('booking_reference', 50)->unique()->nullable();
            $table->json('passenger_details')->nullable(); // Store passenger information
            $table->json('contact_details')->nullable(); // Store contact information
            
            // Status tracking
            $table->enum('booking_status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->string('payment_method', 50)->nullable();
            $table->string('payment_reference', 100)->nullable();
            
            // Timestamps
            $table->timestamp('booked_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->text('notes')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['user_id', 'booking_status']);
            $table->index(['departure_date']);
            $table->index(['booking_reference']);
            $table->index(['amadeus_offer_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('flight_bookings');
    }
}
