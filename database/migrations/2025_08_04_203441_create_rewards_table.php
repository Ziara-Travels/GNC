<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRewardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rewards', function (Blueprint $table) {
            $table->id();
             $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('points');
            $table->string('type'); // earned, redeemed, expired, bonus
            $table->text('description');
            $table->enum('status', ['active', 'redeemed', 'expired', 'pending'])->default('active');
            $table->string('source_type')->nullable(); // Booking, Referral, etc.
            $table->unsignedBigInteger('source_id')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('redeemed_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['source_type', 'source_id']);
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rewards');
    }
}
