<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportExecutionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_executions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scheduled_report_id')->constrained()->onDelete('cascade');
            $table->string('execution_id')->unique();
            $table->enum('status', ['running', 'completed', 'failed'])->default('running');
            $table->json('filters_applied')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->integer('records_count')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamp('started_at');
            $table->timestamp('completed_at')->nullable();
            $table->json('recipients_notified')->nullable();
            $table->timestamps();

            // Add indexes
            $table->index(['scheduled_report_id', 'status']);
            $table->index(['execution_id']);
            $table->index(['status', 'started_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('report_executions');
    }
}
