<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * bookings.departure_airport / arrival_airport were constrained to
 * airports.iata_code with ON DELETE RESTRICT. In production the airport codes
 * come straight from live Amadeus/SkyScanner offers, but the airports table is
 * only seeded with a small set of East African airports, so any booking for a
 * route outside that set failed the foreign key check and the booking could
 * never be written.
 *
 * The IATA code is kept in the column (and in origin_code / destination_code,
 * which never had a constraint), so nothing is lost: only the hard constraint
 * that made an incomplete reference table able to block all bookings is
 * removed. The plain indexes on both columns are left in place.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('bookings')) {
            return;
        }

        $this->dropForeignKeys(['departure_airport', 'arrival_airport']);
    }

    public function down(): void
    {
        if (! Schema::hasTable('bookings') || ! Schema::hasTable('airports')) {
            return;
        }

        Schema::table('bookings', function (Blueprint $table) {
            $table->foreign('departure_airport')
                ->references('iata_code')
                ->on('airports')
                ->onDelete('restrict');

            $table->foreign('arrival_airport')
                ->references('iata_code')
                ->on('airports')
                ->onDelete('restrict');
        });
    }

    /**
     * Drop the named foreign keys where they exist.
     *
     * Blueprint has no portable "list foreign keys" API, so each column is
     * attempted individually and a missing constraint is treated as success.
     * That keeps the migration safe on SQLite (where a missing constraint is a
     * no-op) and idempotent if the booking schema is edited later.
     */
    protected function dropForeignKeys(array $columns): void
    {
        foreach ($columns as $column) {
            if (! Schema::hasColumn('bookings', $column)) {
                continue;
            }

            try {
                Schema::table('bookings', function (Blueprint $table) use ($column) {
                    $table->dropForeign($column);
                });
            } catch (\Throwable $e) {
                // Constraint already absent on this connection.
            }
        }
    }
};