<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTravelInsuranceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('travel_insurance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('policy_number')->unique();
            $table->string('provider_name');
            $table->string('coverage_type');
            $table->decimal('coverage_amount', 10, 2);
            $table->decimal('premium_amount', 8, 2);
            $table->date('start_date');
            $table->date('end_date');
            $table->json('coverage_details');
            $table->json('beneficiary_details')->nullable();
            $table->enum('status', ['active', 'expired', 'cancelled', 'claimed'])->default('active');
            $table->string('payment_status')->default('pending');
            $table->string('payment_method')->nullable();
            $table->string('transaction_id')->nullable();
            $table->text('policy_document_url')->nullable();
            $table->timestamp('purchased_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Add indexes
            $table->index(['booking_id', 'status']);
            $table->index(['user_id', 'status']);
            $table->index(['policy_number']);
            $table->index(['start_date', 'end_date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('travel_insurance');
    }
}
