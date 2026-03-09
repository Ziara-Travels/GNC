<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeatAvailabilityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seat_availability', function (Blueprint $table) {
            $table->id();
            $table->string('flight_number');
            $table->date('departure_date');
            $table->string('departure_airport', 3);
            $table->string('arrival_airport', 3);
            $table->string('travel_class');
            $table->integer('available_seats');
            $table->integer('total_seats');
            $table->timestamp('last_updated');
            $table->boolean('is_critical')->default(false);
            $table->timestamps();

            // Custom unique constraint name to avoid length limit
            $table->unique(
                ['flight_number', 'departure_date', 'departure_airport', 'arrival_airport', 'travel_class'],
                'seat_avail_flight_route_class_unique'
            );
            
            // Regular indexes with custom names
            $table->index(['flight_number', 'departure_date'], 'seat_avail_flight_date_idx');
            $table->index(['is_critical', 'last_updated'], 'seat_avail_critical_updated_idx');
            $table->index(['departure_airport', 'arrival_airport'], 'seat_avail_airports_idx');
            $table->index(['available_seats', 'is_critical'], 'seat_avail_seats_critical_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seat_availability');
    }
}