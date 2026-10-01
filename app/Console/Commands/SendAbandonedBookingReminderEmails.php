<?php

namespace App\Console\Commands;

use App\Jobs\SendAbandonedBookingReminderJob;
use App\Models\BookingDraft;
use Illuminate\Console\Command;

class SendAbandonedBookingReminderEmails extends Command
{
    protected $signature = 'bookings:send-abandoned-reminders
                            {--dry-run : List the drafts that would be reminded without sending}';

    protected $description = 'Email abandoned booking drafts that have been idle for 24+ hours';

    public function handle(): int
    {
        $drafts = BookingDraft::query()
            ->where('status', 'abandoned')
            ->where('reminder_sent', false)
            ->where('created_at', '<=', now()->subHours(24))
            ->whereNotNull('email')
            ->get();

        if ($this->option('dry-run')) {
            $this->table(
                ['id', 'email', 'type', 'created_at'],
                $drafts->map(fn ($d) => [
                    $d->id,
                    $d->email,
                    $d->booking_type,
                    optional($d->created_at)->toDateTimeString(),
                ])->all()
            );

            $this->info($drafts->count().' draft(s) would be reminded.');

            return self::SUCCESS;
        }

        $sent = 0;

        foreach ($drafts as $draft) {
            SendAbandonedBookingReminderJob::dispatch($draft);
            $sent++;
        }

        $this->info("Dispatched {$sent} abandoned booking reminder(s).");

        return self::SUCCESS;
    }
}