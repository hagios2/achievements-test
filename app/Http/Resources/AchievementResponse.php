<?php

namespace App\Http\Resources;

use App\Models\Achievement;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class AchievementResponse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $user = User::find($this->id);

        return [
            'unlocked_achievements' => Achievement::unlockedAchievements($user),
            'next_available_achievements' => Achievement::nextAchievementToUnlock($user),
//            'current_badge' => ',
//            'next_badge' => '',
//            'remaining_to_unlock_next_badge' => 0
        ];
    }
}
