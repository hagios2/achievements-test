<?php

namespace App\Listeners;

use App\Events\AchievementUnlockEvent;
use App\Models\Achievement;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Str;

class AchievementUnlockListener
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
     * @param  \App\Events\AchievementUnlockEvent  $event
     * @return void
     */
    public function handle(AchievementUnlockEvent $event)
    {
        $event->user->addAchievement([
            'achievement_name' => $event->achievement_name,
            'achievement_type' => Str::contains('Watched', $event->achievement_name) ? Achievement::LESSON_TYPE : Achievement::COMMENT_TYPE
        ]);
    }
}
