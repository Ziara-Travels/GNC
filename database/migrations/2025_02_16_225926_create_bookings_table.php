<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Check if table doesn't exist before creating it
        if (!Schema::hasTable('bookings')) {
            Schema::create('bookings', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                
                // Role field
                $table->string('role')->nullable()->comment('Role of the user at time of booking');
                
                // Booking identification and type
                $table->string('booking_reference')->unique();
                $table->enum('booking_type', ['flight', 'hotel', 'tour', 'activity'])->default('flight');
                $table->enum('type', ['flight', 'hotel', 'tour', 'activity'])->default('flight');
                $table->string('title')->nullable();
                
                // Amadeus API related fields
                $table->string('amadeus_offer_id')->nullable();
                $table->string('amadeus_activity_id')->nullable();
                $table->json('amadeus_data')->nullable(); // Store raw Amadeus offer data
                
                // Flight specific fields
                $table->string('flight_number')->nullable();
                $table->string('departure_airport', 3)->nullable();
                $table->string('arrival_airport', 3)->nullable();
                $table->string('origin_code', 5)->nullable();
                $table->string('origin_city', 100)->nullable();
                $table->string('destination_code', 5)->nullable();
                $table->string('destination_city', 100)->nullable();
                $table->dateTime('departure_time')->nullable();
                $table->dateTime('arrival_time')->nullable();
                $table->date('departure_date')->nullable();
                $table->time('departure_time_only')->nullable();
                $table->date('return_date')->nullable();
                $table->time('return_time')->nullable();
                $table->string('cabin_class', 20)->nullable();
                $table->string('airline_code', 10)->nullable();
                $table->string('flight_duration', 20)->nullable();
                $table->integer('stops')->default(0);
                
                // Hotel specific fields
                $table->string('hotel_id')->nullable();
                $table->string('hotel_code', 10)->nullable();
                $table->string('hotel_name', 255)->nullable();
                $table->dateTime('check_in_date')->nullable();
                $table->dateTime('check_out_date')->nullable();
                $table->string('room_type', 100)->nullable();
                $table->integer('room_count')->default(1);
                $table->integer('nights')->default(1);
                
                // Tour/Activity specific fields
                $table->string('activity_name', 255)->nullable();
                $table->string('destination', 100)->nullable();
                $table->string('country', 100)->nullable();
                $table->string('city', 100)->nullable();
                $table->date('activity_date')->nullable();
                $table->time('activity_time')->nullable();
                $table->string('duration', 50)->nullable();
                $table->string('activity_type', 100)->nullable();
                $table->string('difficulty_level', 50)->nullable();
                $table->integer('traveler_count')->default(1);
                $table->integer('guest_count')->default(1);
                
                // Pricing fields
                $table->decimal('amount', 10, 2);
                $table->decimal('total_amount', 10, 2)->default(0);
                $table->decimal('total_price', 12, 2)->nullable(); // For new booking system
                $table->decimal('paid_amount', 10, 2)->default(0);
                $table->string('currency', 3);
                
                // Status fields
                $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled', 'processing'])->default('pending');
                $table->enum('booking_status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
                $table->enum('payment_method', ['mpesa', 'bank', 'card', 'mobile_money', 'bank_transfer'])->nullable();
                $table->enum('payment_status', ['pending', 'completed', 'failed', 'paid', 'refunded'])->default('pending');
                $table->string('transaction_id')->nullable();
                $table->string('payment_reference', 100)->nullable();
                
                // Detailed information (JSON fields)
                $table->json('flight_details')->nullable();
                $table->json('hotel_details')->nullable();
                $table->json('tour_details')->nullable();
                $table->json('traveler_details')->nullable();
                $table->json('passenger_details')->nullable(); // Store passenger information
                $table->json('contact_details')->nullable(); // Store contact information
                $table->json('guest_details')->nullable(); // For hotel bookings
                $table->json('passengers')->nullable();
                
                // Passenger/guest counts
                $table->integer('passenger_count')->default(1);
                $table->integer('adults')->default(1);
                $table->integer('children')->default(0);
                $table->integer('infants')->default(0);
                $table->string('travel_class')->nullable();
                
                // Contact information fields
                $table->string('contact_email')->nullable();
                $table->string('contact_phone')->nullable();
                
                // Communication tracking fields
                $table->boolean('confirmation_sent')->default(false);
                $table->boolean('reminder_sent')->default(false);
                
                // Booking management timestamps
                $table->timestamp('booked_at')->nullable();
                $table->timestamp('cancelled_at')->nullable();
                $table->text('notes')->nullable();
                
                // Timestamps and soft deletes
                $table->timestamps();
                $table->softDeletes();

                // Add indexes for faster searches
                $table->index(['booking_reference', 'flight_number']);
                $table->index(['departure_airport', 'arrival_airport']);
                $table->index(['payment_status', 'payment_method']);
                $table->index(['booking_type', 'status']);
                $table->index(['type', 'status']);
                $table->index(['user_id', 'status']);
                $table->index(['contact_email']);
                $table->index(['confirmation_sent', 'reminder_sent']);
            });
        }
    }


    /**
     * Add foreign key constraints separately
     */
    private function addForeignKeyConstraints()
    {
        // Only add foreign key constraints if the referenced tables exist
        if (Schema::hasTable('airports')) {
            try {
                Schema::table('bookings', function (Blueprint $table) {
                    $table->foreign('departure_airport')
                          ->references('iata_code')
                          ->on('airports')
                          ->onDelete('restrict');
                    
                    $table->foreign('arrival_airport')
                          ->references('iata_code')
                          ->on('airports')
                          ->onDelete('restrict');
                });
            } catch (\Exception $e) {
                // Log the error but don't fail the migration
                \Log::warning('Could not add airport foreign key constraints: ' . $e->getMessage());
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Disable foreign key checks temporarily
        Schema::disableForeignKeyConstraints();
        
        // Drop the table
        Schema::dropIfExists('bookings');
        
        // Re-enable foreign key checks
        Schema::enableForeignKeyConstraints();
    }
}

