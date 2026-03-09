<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserPreferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('preferred_currency', 3)->default('USD');
            $table->string('preferred_language', 2)->default('en');
            $table->string('timezone')->default('UTC');
            $table->json('notification_preferences'); // email, sms, push, etc.
            $table->json('travel_preferences')->nullable(); // class, airline, etc.
            $table->boolean('marketing_emails')->default(true);
            $table->boolean('booking_reminders')->default(true);
            $table->boolean('price_alerts')->default(false);
            $table->boolean('newsletter_subscription')->default(true);
            $table->json('accessibility_options')->nullable();
            $table->timestamps();

            // Add indexes
            $table->unique(['user_id']);
            $table->index(['preferred_currency']);
            $table->index(['preferred_language']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_preferences');
    }
}
