<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoginLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        if (!Schema::hasTable('login_logs')) {
            Schema::create('login_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('ip_address', 45); // IPv6 max length
                $table->text('user_agent');
                $table->timestamp('login_at');
                $table->timestamps();
                
                // Create separate indexes instead of composite
                $table->index('user_id');
                $table->index('ip_address');
                $table->index('login_at');
                $table->index(['user_id', 'ip_address']); // This combination is still useful
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('login_logs');
    }
}