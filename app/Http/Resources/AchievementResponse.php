<?php

namespace App\Http\Resources;

use App\Models\Achievement;
use App\Models\User;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;

class AchievementResponse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|Arrayable|\JsonSerializable
     */
    public function toArray($request): array|\JsonSerializable|Arrayable
    {
        $user = User::find($this->id);

        return [
            'unlocked_achievements' => Achievement::unlockedAchievements($user),
            'next_available_achievements' => Achievement::nextAchievementToUnlock($user),
            'current_badge' => $user->currentBadge(),
            'next_badge' => $user->nextBadge() ?? 'N/A',
            'remaining_to_unlock_next_badge' => $user->remainingToUnlockNextBadge()
        ];
    }
}
