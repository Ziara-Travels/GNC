<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateInsuranceClaimsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Check if table doesn't exist before creating it
        if (!Schema::hasTable('insurance_claims')) {
            Schema::create('insurance_claims', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('travel_insurance_id');
                $table->unsignedBigInteger('user_id');
                $table->string('claim_number')->unique();
                $table->string('claim_type'); // medical, trip_cancellation, baggage_loss, etc.
                $table->text('description');
                $table->decimal('claimed_amount', 10, 2);
                $table->decimal('approved_amount', 10, 2)->nullable();
                $table->enum('status', ['pending', 'under_review', 'approved', 'rejected', 'paid'])->default('pending');
                $table->json('supporting_documents')->nullable();
                $table->date('incident_date');
                $table->string('incident_location')->nullable();
                $table->text('admin_notes')->nullable();
                $table->timestamp('submitted_at');
                $table->timestamp('reviewed_at')->nullable();
                $table->timestamp('processed_at')->nullable();
                $table->timestamps();
                $table->softDeletes();

                // Add indexes
                $table->index(['travel_insurance_id', 'status']);
                $table->index(['user_id', 'status']);
                $table->index(['claim_number']);
                $table->index(['status', 'submitted_at']);
            });

            // Add foreign key constraints separately
            $this->addForeignKeyConstraints();
        }
    }

    /**
     * Add foreign key constraints separately
     */
    private function addForeignKeyConstraints()
    {
        // Add user foreign key if users table exists
        if (Schema::hasTable('users')) {
            try {
                Schema::table('insurance_claims', function (Blueprint $table) {
                    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                });
            } catch (\Exception $e) {
                \Log::warning('Could not add user_id foreign key to insurance_claims: ' . $e->getMessage());
            }
        }

        // Add travel_insurance foreign key if travel_insurances table exists
        if (Schema::hasTable('travel_insurances')) {
            try {
                Schema::table('insurance_claims', function (Blueprint $table) {
                    $table->foreign('travel_insurance_id')->references('id')->on('travel_insurances')->onDelete('cascade');
                });
            } catch (\Exception $e) {
                \Log::warning('Could not add travel_insurance_id foreign key to insurance_claims: ' . $e->getMessage());
            }
        } else {
            // If travel_insurances doesn't exist, log a warning
            \Log::warning('travel_insurances table does not exist. Foreign key constraint not added.');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('insurance_claims');
    }
}