<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBookedByToBookingsTable extends Migration
{
    /**
     * BookingService::createBooking() and Booking::bookedBy() have always
     * referenced 'booked_by' (who made the booking, e.g. an admin booking on
     * behalf of a customer, vs 'user_id' who it's for) — but the column was
     * never actually added to the bookings table, so every booking made
     * through BookingService (hotels, tours) failed with "Unknown column".
     */
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('booked_by')->nullable()->after('user_id')->constrained('users')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['booked_by']);
            $table->dropColumn('booked_by');
        });
    }
}
