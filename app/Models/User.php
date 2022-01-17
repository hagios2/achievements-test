<?php

namespace App\Models;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @method static find(int $int)
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The comments that belong to the user.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function addComment($comment): Model
    {
        return $this->comments()->create($comment);
    }

    /**
     * The lessons that a user has access to.
     */
    public function lessons(): BelongsToMany
    {
        return $this->belongsToMany(Lesson::class);
    }

    public function addLesson($lesson)
    {
        $this->watched()->attach($lesson, ['watched' => true]);
    }

    /**
     * The lessons that a user has watched.
     */
    public function watched(): BelongsToMany
    {
        return $this->belongsToMany(Lesson::class)->wherePivot('watched', true);
    }

    public function achievement(): HasMany
    {
        return $this->hasMany(Achievement::class);
    }

    public function addAchievement($achievement): Model
    {
        return $this->achievement()->create($achievement);
    }

    public function badge(): HasMany
    {
        return $this->hasMany(Badge::class);
    }

    public function addBadge($badge): Model
    {
        return $this->badge()->create($badge);
    }

    public function currentBadge()
    {
        $current_badge = $this->badge()->latest()->first();

        return $current_badge?->badge_name ?? Badge::USER_BADGE_LEVEL[0];
    }

    public function nextBadge()
    {
        $current_badge = $this->currentBadge();

        $current_badge_index = array_search($current_badge, array_values(Badge::USER_BADGE_LEVEL));

        $next_badge_index = $current_badge_index + 1;

        if (array_key_exists($next_badge_index , array_values(Badge::USER_BADGE_LEVEL))) {
            return array_values(Badge::USER_BADGE_LEVEL)[$next_badge_index];
        }
    }

    public function remainingToUnlockNextBadge(): bool|int|string
    {
        $next_badge = $this->nextBadge();

        if ($next_badge) {
            $number_of_achievement_to_next_level = array_search($next_badge, Badge::USER_BADGE_LEVEL);

            return $number_of_achievement_to_next_level - $this->achievement()->count();
        } else {
            return 0;
        }
    }
}
