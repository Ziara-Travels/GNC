<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('booking_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('booking_reference')->unique();
            $table->json('flight_data');
            $table->json('passenger_details');
            $table->json('booking_details');
            $table->decimal('total_amount', 10, 2);
            $table->string('currency', 3);
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed']);
            $table->date('departure_date');
            $table->date('return_date')->nullable();
            $table->string('route_from', 3);
            $table->string('route_to', 3);
            $table->timestamps();
            
            $table->index(['user_id', 'departure_date']);
            $table->index(['route_from', 'route_to']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_histories');
    }
}
