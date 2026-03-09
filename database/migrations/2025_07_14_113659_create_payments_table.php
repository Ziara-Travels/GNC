<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->string('payment_reference')->unique();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->enum('payment_method', ['mpesa', 'bank', 'card']);
            $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'cancelled'])->default('pending');
            $table->json('payment_details')->nullable();
            $table->string('provider_transaction_id')->nullable();
            $table->json('provider_response')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users');
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'created_at']);
            $table->index(['user_id', 'status']);
            $table->index(['payment_method', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
