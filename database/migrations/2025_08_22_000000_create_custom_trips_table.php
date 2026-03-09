<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('custom_trips')) {
            Schema::create('custom_trips', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('name');
                $table->string('email');
                $table->string('phone');
                $table->text('description');
                $table->string('travel_timing')->nullable();
                $table->boolean('subscribe_newsletter')->default(false);
                $table->string('status')->default('new');
                $table->text('admin_notes')->nullable();
                $table->timestamp('last_contacted_at')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_trips');
    }
};