<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('message');
            $table->enum('type', ['info', 'success', 'warning', 'error'])->default('info');
            $table->enum('category', ['booking', 'payment', 'system', 'promotion', 'reminder'])->default('system');
            $table->json('data')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->boolean('is_global')->default(false); // For admin announcements
            $table->string('action_url')->nullable();
            $table->string('action_text')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            // Add indexes
            $table->index(['user_id', 'is_read']);
            $table->index(['type', 'category']);
            $table->index(['is_global', 'expires_at']);
            $table->index(['created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('system_notifications');
    }
}
