<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Flights extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flights', function (Blueprint $table) {
            $table->id();
            $table->string('flight_number');
            $table->foreignId('departure_airport_id')->constrained('airports');
            $table->foreignId('arrival_airport_id')->constrained('airports');
            $table->dateTime('departure_time');
            $table->dateTime('arrival_time');
            $table->string('aircraft_type');
            $table->decimal('price', 10, 2);
            $table->string('status')->default('scheduled');
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
        Schema::dropIfExists('flights');
    }
}
