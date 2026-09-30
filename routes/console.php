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
*/

Schedule::command('campaigns:process')->weekly();

// Send pre-trip reminders
Schedule::command('bookings:send-reminders')->weekly();

// Process abandoned bookings
Schedule::command('bookings:process-abandoned')->weekly();

// Clean up old files
Schedule::command('files:cleanup')->weekly();

Schedule::job(new \App\Jobs\CleanupExpiredTokensJob)->hourly();
Schedule::job(new \App\Jobs\CancelUnpaidBookingsJob)->everyTenMinutes();
Schedule::job(new \App\Jobs\SendFailedPaymentRemindersJob)->hourly();
Schedule::job(new \App\Jobs\UpdateSeatAvailabilityJob)->everyFifteenMinutes();

// Data cleanup jobs
Schedule::command('cleanup:expired-sessions')->daily();
Schedule::command('cleanup:old-audit-logs')->weekly();

// GDPR compliance jobs
Schedule::command('gdpr:process-deletion-requests')->daily();
Schedule::command('gdpr:anonymize-old-data')->monthly();

// Send weekly newsletter every Monday at 9:00 AM
Schedule::command('newsletter:send weekly')
    ->weekly()
    ->mondays()
    ->at('09:00');

// Send monthly newsletter on the first day of every month at 9:00 AM
Schedule::command('newsletter:send monthly')
    ->monthlyOn(1, '09:00');