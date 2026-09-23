<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAmadeusOrderFieldsToBookingsTable extends Migration
{
    /**
     * OrderFlightController::processCheckout already tries to store the Amadeus
     * flight-order id/response and extracted e-ticket data on the booking, but
     * none of these columns ever existed — the writes were silently dropped by
     * the $fillable guard, so e-tickets never showed real Amadeus ticket numbers.
     */
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('amadeus_booking_id')->nullable()->after('amadeus_data');
            $table->json('amadeus_booking_data')->nullable()->after('amadeus_booking_id');
            $table->json('e_ticket_data')->nullable()->after('amadeus_booking_data');
        });
    }

    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['amadeus_booking_id', 'amadeus_booking_data', 'e_ticket_data']);
        });
    }
}
