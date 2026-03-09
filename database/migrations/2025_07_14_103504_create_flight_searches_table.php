<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlightSearchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flight_searches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('searched_by');
            $table->string('from_iata', 3);
            $table->string('to_iata', 3);
            $table->date('departure_date');
            $table->date('return_date')->nullable();
            $table->integer('adults');
            $table->integer('children')->default(0);
            $table->integer('infants')->default(0);
            $table->string('travel_class');
            $table->string('trip_type');
            $table->string('search_type')->default('personal');
            $table->json('search_parameters');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('searched_by')->references('id')->on('users');
            $table->index(['user_id', 'created_at']);
            $table->index(['searched_by', 'created_at']);
            $table->index(['from_iata', 'to_iata']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('flight_searches');
    }
}
