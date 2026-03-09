<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrencyExchangeRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currency_exchange_rates', function (Blueprint $table) {
            $table->id();
            $table->string('base_currency', 3);
            $table->string('target_currency', 3);
            $table->decimal('rate', 10, 6);
            $table->decimal('inverse_rate', 10, 6);
            $table->string('provider')->default('exchangerate-api');
            $table->timestamp('last_updated');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Add indexes
            $table->unique(['base_currency', 'target_currency']);
            $table->index(['base_currency', 'is_active']);
            $table->index(['last_updated']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('currency_exchange_rates');
    }
}
