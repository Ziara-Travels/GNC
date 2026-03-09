<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->string('document_type'); // invoice, e-ticket, itinerary, insurance
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_size');
            $table->string('mime_type');
            $table->json('metadata')->nullable();
            $table->timestamp('generated_at');
            $table->timestamps();

            // Add indexes
            $table->index(['booking_id', 'document_type']);
            $table->index(['document_type', 'generated_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('booking_documents');
    }
}
