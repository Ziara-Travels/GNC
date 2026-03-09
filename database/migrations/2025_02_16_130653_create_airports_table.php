<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAirportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('airports', function (Blueprint $table) {
            $table->id();
            $table->string('iata_code', 3)->unique();
            $table->string('name');
            $table->string('city');
            $table->string('country');
            $table->string('location');
            $table->timestamps();
             $table->string('region')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->integer('elevation')->nullable();
            $table->string('timezone')->nullable();
            $table->boolean('is_active')->default(true);

            // Add index for faster searches
            $table->index(['iata_code', 'city', 'country']);
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('airports');
    }
}
