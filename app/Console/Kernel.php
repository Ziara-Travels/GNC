<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

     /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\CustomServeCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('campaigns:process')->weekly();

        // Send pre-trip reminders
        $schedule->command('bookings:send-reminders')->weekly();
        
        // Process abandoned bookings
        $schedule->command('bookings:process-abandoned')->weekly();
        
        // Clean up old files
        $schedule->command('files:cleanup')->weekly();

        $schedule->job(new \App\Jobs\CleanupExpiredTokensJob)->hourly();
        $schedule->job(new \App\Jobs\CancelUnpaidBookingsJob)->everyTenMinutes();
        $schedule->job(new \App\Jobs\SendFailedPaymentRemindersJob)->hourly();
        $schedule->job(new \App\Jobs\UpdateSeatAvailabilityJob)->everyFifteenMinutes();
        
        // Data cleanup jobs
        $schedule->command('cleanup:expired-sessions')->daily();
        $schedule->command('cleanup:old-audit-logs')->weekly();
        
        // GDPR compliance jobs
        $schedule->command('gdpr:process-deletion-requests')->daily();
        $schedule->command('gdpr:anonymize-old-data')->monthly();

        // Send weekly newsletter every Monday at 9:00 AM
        $schedule->command('newsletter:send weekly')
             ->weekly()
             ->mondays()
             ->at('09:00');
    
        // Send monthly newsletter on the first day of every month at 9:00 AM
        $schedule->command('newsletter:send monthly')
             ->monthlyOn(1, '09:00');
    }



    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
