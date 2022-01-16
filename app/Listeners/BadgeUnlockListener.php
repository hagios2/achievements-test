<?php

namespace App\Listeners;

use App\Events\BadgeUnlockEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class BadgeUnlockListener
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
     * @param  \App\Events\BadgeUnlockEvent  $event
     * @return void
     */
    public function handle(BadgeUnlockEvent $event)
    {
        $event->user->addBadge(['badge_name' => $event->badge_name]);
    }
}
