<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTourIdToBookingsTable extends Migration
{
    /**
     * The Tour model already declares bookings() as hasMany(Booking::class, 'tour_id'),
     * but the bookings table was never given the matching column.
     */
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('tour_id')->nullable()->after('hotel_id')->constrained('tours')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['tour_id']);
            $table->dropColumn('tour_id');
        });
    }
}
