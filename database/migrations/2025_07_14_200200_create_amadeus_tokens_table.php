<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAmadeusTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
    {
        Schema::create('amadeus_tokens', function (Blueprint $table) {
            $table->id();
            $table->text('access_token');
            $table->string('token_type')->default('Bearer');
            $table->timestamp('expires_at');
            $table->text('scope')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'expires_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('amadeus_tokens');
    }
}
