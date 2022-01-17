<?php

namespace App\Observers;

use App\Events\BadgeUnlockEvent;
use App\Models\Achievement;
use App\Models\Badge;

class AchievementObserver
{
    /**
     * Handle the Achievement "created" event.
     *
     * @param  \App\Models\Achievement  $achievement
     * @return void
     */
    public function created(Achievement $achievement)
    {
        $user = $achievement->user;

        $user_achievement_count = $user->achievement->count();

        if (in_array($user_achievement_count, array_keys(Badge::USER_BADGE_LEVEL))) {
            //fire achievement unlock event
            BadgeUnlockEvent::dispatch($user, Badge::USER_BADGE_LEVEL[$user_achievement_count]);
        } elseif ($user->badge->count() === 0 && $user_achievement_count < 4) {
            BadgeUnlockEvent::dispatch($user, Badge::USER_BADGE_LEVEL[0]);
        }
    }
}
