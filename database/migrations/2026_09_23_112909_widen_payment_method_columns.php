<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class WidenPaymentMethodColumns extends Migration
{
    /**
     * bookings.payment_method and payments.payment_method were both narrow
     * enums that didn't include values the live checkout flow already
     * validates and accepts (mpesa_stk, equitel, equity) — picking any of
     * those payment options would fail the insert outright. Converting both
     * to plain strings avoids this whole class of bug reappearing every time
     * a payment method is added.
     *
     * Raw SQL instead of Schema::table(...)->change(): the installed
     * doctrine/dbal (^4.2) is incompatible with Laravel 8's DBAL integration
     * (its Driver::connect() signature changed in DBAL 4.x), so ->change()
     * fatals here. ALTER ... MODIFY needs no DBAL involvement.
     */
    public function up()
    {
        DB::statement("ALTER TABLE bookings MODIFY payment_method VARCHAR(30) NULL");

        // Nullable: BookingService::createPaymentRecord() intentionally creates
        // the payment row before the user has chosen a method, filling it in
        // later via processPayment()'s update().
        DB::statement("ALTER TABLE payments MODIFY payment_method VARCHAR(30) NULL");
    }

    public function down()
    {
        DB::statement("ALTER TABLE bookings MODIFY payment_method ENUM('mpesa','bank','card','mobile_money','bank_transfer') NULL");
        DB::statement("ALTER TABLE payments MODIFY payment_method ENUM('mpesa','bank','card') NOT NULL");
    }
}
