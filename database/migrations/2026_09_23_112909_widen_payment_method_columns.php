<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
     * Nullability is preserved from the original create migrations rather
     * than relaxed:
     *   bookings.payment_method  nullable
     *   payments.payment_method  NOT NULL
     *
     * Native ->change() is used (not raw ALTER ... MODIFY) so that SQLite is
     * handled by Laravel's own table-rebuild, which carries indexes and
     * foreign keys across. A hand-rolled rebuild silently dropped every
     * index and foreign key on bookings and payments.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('payment_method', 30)->nullable()->change();
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->string('payment_method', 30)->change();
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->enum('payment_method', ['mpesa', 'bank', 'card', 'mobile_money', 'bank_transfer'])
                ->nullable()
                ->change();
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->enum('payment_method', ['mpesa', 'bank', 'card'])->change();
        });
    }
}