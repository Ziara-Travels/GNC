<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserConsentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
    {
        Schema::create('user_consents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('consent_type');
            $table->boolean('consent_given')->default(false);
            $table->text('consent_text');
            $table->string('ip_address');
            $table->text('user_agent');
            $table->string('version')->default('1.0');
            $table->timestamp('consented_at');
            $table->timestamp('withdrawn_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'consent_type']);
            $table->index(['consent_type', 'consent_given']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_consents');
    }
}
