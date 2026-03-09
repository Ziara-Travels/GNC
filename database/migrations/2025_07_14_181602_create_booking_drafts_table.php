<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingDraftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_drafts', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('passenger_name')->nullable();
            $table->string('email');
            $table->string('phone')->nullable();
            $table->enum('booking_type', ['flight', 'hotel', 'both'])->default('flight');
            $table->json('flight_details')->nullable();
            $table->json('hotel_details')->nullable();
            $table->json('passenger_details')->nullable();
            $table->enum('status', ['active', 'abandoned', 'completed', 'expired'])->default('active');
            $table->integer('step_completed')->default(1);
            $table->decimal('total_price', 10, 2)->nullable();
            $table->string('currency', 3)->default('USD');
            $table->boolean('reminder_sent')->default(false);
            $table->string('discount_code')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('booking_drafts');
    }
}
