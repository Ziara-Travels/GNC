<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingAuditTrailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Check if table doesn't exist before creating it
        if (!Schema::hasTable('booking_audit_trail')) {
            Schema::create('booking_audit_trail', function (Blueprint $table) {
                $table->id();
                $table->foreignId('booking_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
                $table->string('action'); // create, update, cancel, refund, etc.
                $table->string('field_changed')->nullable();
                $table->text('old_value')->nullable();
                $table->text('new_value')->nullable();
                $table->json('metadata')->nullable();
                $table->string('ip_address')->nullable();
                $table->string('user_agent')->nullable();
                $table->enum('source', ['web', 'api', 'admin', 'system'])->default('web');
                $table->timestamp('created_at');

                // Add indexes
                $table->index(['booking_id', 'created_at']);
                $table->index(['user_id', 'action']);
                $table->index(['action', 'created_at']);
            });
        } else {
            // Table exists, ensure all columns are present
            $this->updateExistingTable();
        }
    }

    /**
     * Update existing table if needed
     */
    private function updateExistingTable()
    {
        Schema::table('booking_audit_trail', function (Blueprint $table) {
            // Add missing columns if they don't exist
            if (!Schema::hasColumn('booking_audit_trail', 'booking_id')) {
                $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            }
            if (!Schema::hasColumn('booking_audit_trail', 'user_id')) {
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            }
            if (!Schema::hasColumn('booking_audit_trail', 'action')) {
                $table->string('action');
            }
            if (!Schema::hasColumn('booking_audit_trail', 'field_changed')) {
                $table->string('field_changed')->nullable();
            }
            if (!Schema::hasColumn('booking_audit_trail', 'old_value')) {
                $table->text('old_value')->nullable();
            }
            if (!Schema::hasColumn('booking_audit_trail', 'new_value')) {
                $table->text('new_value')->nullable();
            }
            if (!Schema::hasColumn('booking_audit_trail', 'metadata')) {
                $table->json('metadata')->nullable();
            }
            if (!Schema::hasColumn('booking_audit_trail', 'ip_address')) {
                $table->string('ip_address')->nullable();
            }
            if (!Schema::hasColumn('booking_audit_trail', 'user_agent')) {
                $table->string('user_agent')->nullable();
            }
            if (!Schema::hasColumn('booking_audit_trail', 'source')) {
                $table->enum('source', ['web', 'api', 'admin', 'system'])->default('web');
            }
            if (!Schema::hasColumn('booking_audit_trail', 'created_at')) {
                $table->timestamp('created_at');
            }
        });

        // Add indexes if they don't exist
        $this->addIndexesIfNotExist();
    }

    /**
     * Add indexes if they don't exist
     */
    private function addIndexesIfNotExist()
    {
        try {
            Schema::table('booking_audit_trail', function (Blueprint $table) {
                $table->index(['booking_id', 'created_at']);
            });
        } catch (\Exception $e) {
            // Index might already exist, ignore
        }

        try {
            Schema::table('booking_audit_trail', function (Blueprint $table) {
                $table->index(['user_id', 'action']);
            });
        } catch (\Exception $e) {
            // Index might already exist, ignore
        }

        try {
            Schema::table('booking_audit_trail', function (Blueprint $table) {
                $table->index(['action', 'created_at']);
            });
        } catch (\Exception $e) {
            // Index might already exist, ignore
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booking_audit_trail');
    }
}