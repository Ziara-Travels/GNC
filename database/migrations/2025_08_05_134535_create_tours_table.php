<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateToursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tours', function (Blueprint $table) {
           $table->id();
            $table->string('tour_reference')->unique();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('short_description');
            $table->longText('full_description');
            $table->string('city');
            $table->string('country');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->json('images'); // Array of image URLs
            $table->decimal('price', 10, 2);
            $table->decimal('original_price', 10, 2)->nullable();
            $table->string('currency', 3)->default('USD');
            $table->integer('discount_percent')->default(0);
            $table->string('duration'); // e.g., "Half Day", "Full Day", "3 Days"
            $table->integer('duration_hours')->nullable();
            $table->string('difficulty_level')->default('Easy'); // Easy, Moderate, Challenging
            $table->integer('max_participants')->default(20);
            $table->integer('min_participants')->default(1);
            $table->decimal('rating', 3, 2)->default(4.5);
            $table->integer('review_count')->default(0);
            $table->json('included_services'); // What's included
            $table->json('excluded_services')->nullable(); // What's not included
            $table->json('highlights'); // Tour highlights
            $table->json('itinerary')->nullable(); // Detailed itinerary
            $table->string('category'); // Adventure, Cultural, Wildlife, Historical, etc.
            $table->json('languages')->default('["English"]'); // Available languages
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('meeting_point')->nullable();
            $table->json('pickup_locations')->nullable(); // Available pickup points
            $table->boolean('hotel_pickup')->default(false);
            $table->boolean('instant_confirmation')->default(true);
            $table->boolean('free_cancellation')->default(true);
            $table->integer('cancellation_hours')->default(24); // Hours before tour
            $table->json('age_restrictions')->nullable(); // Min/max age
            $table->json('requirements')->nullable(); // Fitness level, equipment, etc.
            $table->string('operator_name')->nullable();
            $table->string('operator_contact')->nullable();
            $table->boolean('featured')->default(false);
            $table->boolean('seasonal')->default(false);
            $table->json('available_months')->nullable(); // For seasonal tours
            $table->json('available_days')->default('["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"]');
            $table->integer('advance_booking_days')->default(0); // Minimum days in advance
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index(['city', 'country']);
            $table->index(['category', 'status']);
            $table->index(['featured', 'status']);
            $table->index('rating');
            $table->index('price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tours');
    }
}
