<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoyaltyPointsHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loyalty_points_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('booking_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('type', ['earned', 'redeemed', 'expired', 'bonus', 'refunded'])->default('earned');
            $table->integer('points');
            $table->integer('balance_after');
            $table->string('description');
            $table->string('reference_id')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            // Add indexes
            $table->index(['user_id', 'type']);
            $table->index(['booking_id']);
            $table->index(['type', 'created_at']);
            $table->index(['expires_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('loyalty_points_history');
    }
}
