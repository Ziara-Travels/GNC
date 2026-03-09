<?php

namespace App\Listeners;

use App\Events\Verified;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class MarkUserActive
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\Verified  $event
     * @return void
     */
     public function handle(Verified $event): void
    {
        $user = $event->user;
        $user->update([
            'is_active' => true,
            'status'    => 'active',
        ]);
    }
}
