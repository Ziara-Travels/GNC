<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScheduledReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
    {
        Schema::create('scheduled_reports', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // users, bookings, revenue, etc.
            $table->string('frequency'); // daily, weekly, monthly
            $table->json('filters')->nullable();
            $table->json('recipients');
            $table->string('format')->default('pdf'); // pdf, excel, csv
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_run_at')->nullable();
            $table->timestamp('next_run_at')->nullable();
            $table->timestamps();

            // Add indexes
            $table->index(['type', 'frequency']);
            $table->index(['is_active', 'next_run_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('scheduled_reports');
    }
}
