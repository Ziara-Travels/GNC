<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Schedule
|--------------------------------------------------------------------------
|
| NOTE: every entry below is either a command that exists in
| app/Console/Commands or a job class that exists in app/Jobs. Seven of the
| previous entries pointed at commands that were never implemented
| (bookings:send-reminders, bookings:process-abandoned, files:cleanup,
| cleanup:expired-sessions, cleanup:old-audit-logs,
| gdpr:process-deletion-requests, gdpr:anonymize-old-data), so `schedule:work`
| logged a command-not-found failure for each of them on every cycle.
|
| The abandoned-booking and expired-token concerns they were meant to cover are
| already handled by CancelUnpaidBookingsJob and CleanupExpiredTokensJob below.
| Cleanup of old audit logs is a real command: cleanup:old-audit-logs.
|
*/

Schedule::command('campaigns:process')->weekly();

// Send pre-trip reminders for imminent departures.
Schedule::job(new \App\Jobs\SendPreTripReminderJob)->daily();

// Abandoned / unpaid bookings.
Schedule::command('bookings:send-abandoned-reminders')->hourly();
Schedule::job(new \App\Jobs\CancelUnpaidBookingsJob)->everyTenMinutes();

// Maintenance.
Schedule::job(new \App\Jobs\CleanupExpiredTokensJob)->hourly();
Schedule::job(new \App\Jobs\SendFailedPaymentRemindersJob)->hourly();
Schedule::job(new \App\Jobs\UpdateSeatAvailabilityJob)->everyFifteenMinutes();

// Retention: drop booking audit rows older than the configured window.
Schedule::command('cleanup:old-audit-logs')->weekly();

// Send weekly newsletter every Monday at 9:00 AM
Schedule::command('newsletter:send weekly')
    ->weekly()
    ->mondays()
    ->at('09:00');

// Send monthly newsletter on the first day of every month at 9:00 AM
Schedule::command('newsletter:send monthly')
    ->monthlyOn(1, '09:00');