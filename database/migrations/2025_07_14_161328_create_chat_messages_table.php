<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        // Check if table doesn't exist before creating it
        if (!Schema::hasTable('chat_messages')) {
            Schema::create('chat_messages', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('session_id');
                $table->enum('role', ['user', 'assistant']);
                $table->text('message');
                $table->timestamp('timestamp');
                $table->json('metadata')->nullable();
                $table->timestamps();
                
                // Add foreign key constraint only if chat_sessions table exists
                if (Schema::hasTable('chat_sessions')) {
                    $table->foreign('session_id')->references('id')->on('chat_sessions')->onDelete('cascade');
                }
                
                $table->index(['session_id', 'timestamp']);
            });
        } else {
            // Table exists, ensure all columns and indexes are present
            Schema::table('chat_messages', function (Blueprint $table) {
                // Add missing columns if they don't exist
                if (!Schema::hasColumn('chat_messages', 'session_id')) {
                    $table->unsignedBigInteger('session_id');
                }
                if (!Schema::hasColumn('chat_messages', 'role')) {
                    $table->enum('role', ['user', 'assistant']);
                }
                if (!Schema::hasColumn('chat_messages', 'message')) {
                    $table->text('message');
                }
                if (!Schema::hasColumn('chat_messages', 'timestamp')) {
                    $table->timestamp('timestamp');
                }
                if (!Schema::hasColumn('chat_messages', 'metadata')) {
                    $table->json('metadata')->nullable();
                }
                
                // Add foreign key constraint if it doesn't exist and chat_sessions table exists
                if (Schema::hasTable('chat_sessions')) {
                    try {
                        $table->foreign('session_id')->references('id')->on('chat_sessions')->onDelete('cascade');
                    } catch (\Exception $e) {
                        // Foreign key might already exist, ignore error
                    }
                }
                
                // Add indexes if they don't exist
                try {
                    $table->index(['session_id', 'timestamp']);
                } catch (\Exception $e) {
                    // Index might already exist, ignore error
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
    }
}