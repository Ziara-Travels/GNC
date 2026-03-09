<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApiLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_logs', function (Blueprint $table) {
            $table->id();
            $table->string('service');
            $table->string('endpoint');
            $table->string('method');
            $table->json('request_data')->nullable();
            $table->json('response_data')->nullable();
            $table->integer('status_code');
            $table->decimal('response_time', 8, 3);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->index(['service', 'created_at']);
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('api_logs');
    }
}
