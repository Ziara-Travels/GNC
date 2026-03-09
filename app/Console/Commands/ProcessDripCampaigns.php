<?php


namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BookingDraft;
use App\Models\Wishlist;
use App\Models\User;
use App\Jobs\SendAbandonedBookingReminderJob;
use App\Jobs\SendWishlistReminderJob;
use App\Jobs\SendReEngagementJob;
use Carbon\Carbon;

class ProcessDripCampaigns extends Command
{
    protected $signature = 'campaigns:process';
    protected $description = 'Process drip email campaigns';

    public function handle()
    {
        $this->info('Processing drip campaigns...');
        
        // Process abandoned bookings
        $this->processAbandonedBookings();
        
        // Process wishlist reminders
        $this->processWishlistReminders();
        
        // Process re-engagement campaigns
        $this->processReEngagementCampaigns();
        
        $this->info('Drip campaigns processed successfully!');
    }

    private function processAbandonedBookings()
    {
        $abandonedBookings = BookingDraft::where('status', 'abandoned')
            ->where('created_at', '<=', Carbon::now()->subHours(24))
            ->where('reminder_sent', false)
            ->get();

        foreach ($abandonedBookings as $booking) {
            SendAbandonedBookingReminderJob::dispatch($booking);
            $booking->update(['reminder_sent' => true]);
        }

        $this->info("Processed {$abandonedBookings->count()} abandoned bookings");
    }

    private function processWishlistReminders()
    {
        $wishlistItems = Wishlist::where('created_at', '<=', Carbon::now()->subDays(7))
            ->where('reminder_sent', false)
            ->get();

        foreach ($wishlistItems as $item) {
            SendWishlistReminderJob::dispatch($item);
            $item->update(['reminder_sent' => true]);
        }

        $this->info("Processed {$wishlistItems->count()} wishlist reminders");
    }

    private function processReEngagementCampaigns()
    {
        $inactiveUsers = User::where('last_login_at', '<=', Carbon::now()->subDays(30))
            ->where('reengagement_sent', false)
            ->get();

        foreach ($inactiveUsers as $user) {
            SendReEngagementJob::dispatch($user);
            $user->update(['reengagement_sent' => true]);
        }

        $this->info("Processed {$inactiveUsers->count()} re-engagement campaigns");
    }
}