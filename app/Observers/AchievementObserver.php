<?php

namespace App\Observers;

use App\Models\Achievement;

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


    }

    /**
     * Handle the Achievement "updated" event.
     *
     * @param  \App\Models\Achievement  $achievement
     * @return void
     */
    public function updated(Achievement $achievement)
    {
        //
    }

    /**
     * Handle the Achievement "deleted" event.
     *
     * @param  \App\Models\Achievement  $achievement
     * @return void
     */
    public function deleted(Achievement $achievement)
    {
        //
    }

    /**
     * Handle the Achievement "restored" event.
     *
     * @param  \App\Models\Achievement  $achievement
     * @return void
     */
    public function restored(Achievement $achievement)
    {
        //
    }

    /**
     * Handle the Achievement "force deleted" event.
     *
     * @param  \App\Models\Achievement  $achievement
     * @return void
     */
    public function forceDeleted(Achievement $achievement)
    {
        //
    }
}
