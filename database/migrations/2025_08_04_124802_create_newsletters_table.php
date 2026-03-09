<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewslettersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('newsletters', function (Blueprint $table) {
            $table->id();
             $table->string('name');
            $table->string('email')->unique();
            $table->boolean('weekly_emails')->default(true);
            $table->boolean('monthly_emails')->default(true);
            $table->boolean('promotional_emails')->default(true);
            $table->boolean('is_subscribed')->default(true);
            $table->string('source')->default('website');
            $table->string('hubspot_contact_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('newsletters');
    }
}
