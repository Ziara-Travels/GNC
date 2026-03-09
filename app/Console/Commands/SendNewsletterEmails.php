<?php


namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Newsletter;
use Illuminate\Support\Facades\Mail;
use App\Mail\WeeklyNewsletterMail;
use App\Mail\MonthlyNewsletterMail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SendNewsletterEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'newsletter:send {type=weekly}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send newsletter emails (weekly or monthly)';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $type = $this->argument('type');
        
        if ($type === 'weekly') {
            $this->sendWeeklyNewsletter();
        } elseif ($type === 'monthly') {
            $this->sendMonthlyNewsletter();
        } else {
            $this->error("Invalid newsletter type. Use 'weekly' or 'monthly'.");
            return Command::FAILURE;
        }
        
        return Command::SUCCESS;
    }
    
    /**
     * Send weekly newsletter emails.
     *
     * @return void
     */
    private function sendWeeklyNewsletter()
    {
        $this->info('Sending weekly newsletter...');
        
        // Get subscribers who want weekly emails
        $subscribers = Newsletter::where('is_subscribed', true)
                                ->where('weekly_emails', true)
                                ->get();
        
        $count = 0;
        
        foreach ($subscribers as $subscriber) {
            try {
                // In a production app, you'd use a queue job here
                // Mail::to($subscriber->email)->queue(new WeeklyNewsletterMail($subscriber));
                
                // For now, we're just logging
                Log::info("Weekly newsletter would be sent to {$subscriber->email}");
                $count++;
            } catch (\Exception $e) {
                Log::error("Failed to send weekly newsletter to {$subscriber->email}: " . $e->getMessage());
            }
        }
        
        $this->info("Weekly newsletter processing complete. Would send to {$count} subscribers.");
    }
    
    /**
     * Send monthly newsletter emails.
     *
     * @return void
     */
    private function sendMonthlyNewsletter()
    {
        $this->info('Sending monthly newsletter...');
        
        // Check if it's the first day of the month
        if (Carbon::now()->day !== 1) {
            $this->info('Not the first day of the month. Skipping monthly newsletter.');
            return;
        }
        
        // Get subscribers who want monthly emails
        $subscribers = Newsletter::where('is_subscribed', true)
                                ->where('monthly_emails', true)
                                ->get();
        
        $count = 0;
        
        foreach ($subscribers as $subscriber) {
            try {
                // In a production app, you'd use a queue job here
                // Mail::to($subscriber->email)->queue(new MonthlyNewsletterMail($subscriber));
                
                // For now, we're just logging
                Log::info("Monthly newsletter would be sent to {$subscriber->email}");
                $count++;
            } catch (\Exception $e) {
                Log::error("Failed to send monthly newsletter to {$subscriber->email}: " . $e->getMessage());
            }
        }
        
        $this->info("Monthly newsletter processing complete. Would send to {$count} subscribers.");
    }
}