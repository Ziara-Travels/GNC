<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTwoFactorAuthenticationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('two_factor_authentications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('secret_key');
            $table->json('backup_codes')->nullable();
            $table->boolean('enabled')->default(false);
            $table->timestamps();
            
            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('two_factor_authentications');
    }
}
