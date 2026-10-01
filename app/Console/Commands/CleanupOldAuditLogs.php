<?php

namespace App\Console\Commands;

use App\Models\BookingAuditLog;
use Illuminate\Console\Command;

class CleanupOldAuditLogs extends Command
{
    protected $signature = 'cleanup:old-audit-logs
                            {--days=365 : Delete audit log rows older than this many days}
                            {--dry-run : Report how many rows would be deleted, delete none}';

    protected $description = 'Delete booking audit log rows older than the retention window';

    public function handle(): int
    {
        $days = (int) $this->option('days');

        if ($days < 1) {
            $this->error('--days must be a positive number of days.');

            return self::INVALID;
        }

        $cutoff = now()->subDays($days);

        $query = BookingAuditLog::where('created_at', '<', $cutoff);

        $count = (clone $query)->count();

        if ($count === 0) {
            $this->info("No audit logs older than {$days} days.");

            return self::SUCCESS;
        }

        if ($this->option('dry-run')) {
            $this->info("{$count} audit log row(s) older than {$cutoff->toDateString()} would be deleted.");

            return self::SUCCESS;
        }

        $query->delete();

        $this->info("Deleted {$count} audit log row(s) older than {$cutoff->toDateString()}.");

        return self::SUCCESS;
    }
}