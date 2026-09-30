<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Creates the hotel reference domain.
 *
 * The dashboard and hotel search controllers reference an App\Models\Hotel
 * (count, status filter, bookings/amenities/location eager loads and a search
 * across name/city/country/address/price_per_night/star_rating) but no table or
 * model ever existed, so those code paths could not run.
 *
 * Hotel *bookings* are intentionally not created here: they live in the
 * unified `bookings` table, which already carries hotel_id, hotel_name,
 * check_in_date, check_out_date, room_type, room_count, nights and guest_count.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('locations')) {
            Schema::create('locations', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('city')->nullable();
                $table->string('country')->nullable();
                $table->string('address')->nullable();
                $table->decimal('latitude', 10, 7)->nullable();
                $table->decimal('longitude', 10, 7)->nullable();
                $table->timestamps();

                $table->index(['country', 'city']);
            });
        }

        if (! Schema::hasTable('amenities')) {
            Schema::create('amenities', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('icon')->nullable();
                $table->timestamps();

                $table->unique('name');
            });
        }

        if (! Schema::hasTable('hotels')) {
            Schema::create('hotels', function (Blueprint $table) {
                $table->id();
                $table->string('name')->index();
                $table->text('description')->nullable();
                $table->string('address')->nullable();
                $table->string('city')->nullable()->index();
                $table->string('country')->nullable()->index();
                $table->foreignId('location_id')->nullable()->constrained('locations')->nullOnDelete();
                $table->decimal('price_per_night', 10, 2)->default(0);
                $table->unsignedTinyInteger('star_rating')->nullable();
                $table->string('image')->nullable();
                $table->string('status')->default('active')->index();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('hotel_amenity')) {
            Schema::create('hotel_amenity', function (Blueprint $table) {
                $table->foreignId('hotel_id')->constrained('hotels')->cascadeOnDelete();
                $table->foreignId('amenity_id')->constrained('amenities')->cascadeOnDelete();

                $table->primary(['hotel_id', 'amenity_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('hotel_amenity');
        Schema::dropIfExists('hotels');
        Schema::dropIfExists('amenities');
        Schema::dropIfExists('locations');
    }
};
