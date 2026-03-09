<?php


namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\AmadeusToken;
use Illuminate\Support\Facades\Log;

class CleanupExpiredTokensJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $expiredTokens = AmadeusToken::where('expires_at', '<', now())
            ->orWhere('is_active', false)
            ->get();

        foreach ($expiredTokens as $token) {
            $token->delete();
        }

        Log::info('Cleaned up expired tokens', ['count' => $expiredTokens->count()]);
    }
}