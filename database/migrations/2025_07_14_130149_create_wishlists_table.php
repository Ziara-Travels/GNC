<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWishlistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
    {
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('type', ['flight', 'hotel']);
            $table->json('item_data'); // Contains serialized data like flight number, destination, price, etc.
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('original_price', 10, 2)->nullable();
            $table->string('location')->nullable();
            $table->string('item_id')->nullable(); // External ID for the flight/hotel
            $table->timestamps();
            
            $table->index(['user_id', 'type']);
            $table->index(['type', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('wishlists');
    }
}
