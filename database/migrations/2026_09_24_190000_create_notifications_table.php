<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The App\Models\Notification model and every dashboard controller/layout query
 * the "notifications" table (user_id, type, priority, read_at, opened_at, ...),
 * but the table was never created, so any page rendering the notification badge
 * failed with "Table 'flightapp.notifications' doesn't exist".
 *
 * The existing system_notifications table is not a substitute: its `type` is a
 * strict enum of info/success/warning/error while the app stores values such as
 * 'admin' and 'broadcast', and it has no `priority` column, which
 * getNotificationStats() filters on.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('notifications')) {
            return;
        }

        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('message');
            $table->string('type')->default('info');
            $table->string('priority')->nullable();
            $table->json('data')->nullable();
            $table->string('action_url')->nullable();
            $table->timestamp('opened_at')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'read_at']);
            $table->index(['type', 'priority']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
