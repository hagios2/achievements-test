<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static where(array $array)
 */
class Achievement extends Model
{
    use HasFactory;

    //lesson Watched Achievements
    public const FIRST_LESSON_WATCHED = 'First Lesson Watched';
    public const FIVE_LESSONS_WATCHED = '5 Lessons Watched';
    public const TEN_LESSONS_WATCHED = '10 lessons Watched';
    public const TWENTY_FIVE_LESSONS_WATCHED = '25 Lessons Watched';
    public const FIFTY_LESSONS_WATCHED = '50 Lessons Watched';

    //Comments Written Achievements
    public const FIRST_COMMENT_WRITTEN = 'First Comment Written';
    public const THREE_COMMENTS_WRITTEN = '3 Comments Written';
    public const FIVE_COMMENTS_WRITTEN = '5 Comments Written';
    public const TEN_COMMENTS_WRITTEN = '10 Comments Written';
    public const TWENTY_COMMENTS_WRITTEN = '20 Comments Written';

    //achievement types
    public const COMMENT_TYPE = 'comment';
    public const LESSON_TYPE = 'lesson';

    protected $fillable = ['achievement_name', 'achievement_type', 'user_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function unlockedAchievements(User $user): Collection|\Illuminate\Support\Collection|array|null
    {
        $achievements = self::query()
            ->where('user_id', '=', $user->id)
            ->get(['achievement_name']);

        return $achievements?->map(function ($achievement) {
            return $achievement->achievement_name;
        });
    }

    public static function nextAchievementToUnlock(User $user): \Illuminate\Support\Collection
    {
        $achievement_types = [Achievement::LESSON_TYPE, Achievement::COMMENT_TYPE];

        $next_achievement_collection = collect();

        foreach ($achievement_types as $achievement_type) {
            $achievement = self::where([['user_id', '=', $user->id], ['achievement_type', '=', $achievement_type]])
                ->latest()->first(['achievement_name', 'achievement_type']);

            $next_achievement = match ($achievement_type) {
                'comment' => self::getNextCommentAchievement($achievement),
                'lesson' => self::getNextLessonAchievement($achievement),
            };

            if ($next_achievement) {
                $next_achievement_collection->push($next_achievement);
            }
        }

        return $next_achievement_collection;
    }

    public static function getNextCommentAchievement(?Achievement $achievement)
    {
        if ($achievement) {
            return self::getNextAchievement($achievement, Comment::COMMENT_ACHIEVEMENT_LEVEL);
        } else {
            return Achievement::FIRST_COMMENT_WRITTEN;
        }
    }

    public static function getNextLessonAchievement(?Achievement $achievement)
    {
        if ($achievement) {
            return self::getNextAchievement($achievement, Lesson::LESSON_ACHIEVEMENT_LEVEL);
        } else {
            return Achievement::FIRST_LESSON_WATCHED;
        }
    }

    public static function getNextAchievement(Achievement $achievement, array $achievement_level_array)
    {
        $current_achievement_index = array_search($achievement->achievement_name,
            array_values($achievement_level_array));

        $next_achievement_index = $current_achievement_index+1;

        if (array_key_exists($next_achievement_index, array_values($achievement_level_array))) {
            return array_values($achievement_level_array)[$next_achievement_index];
        }
    }
}
