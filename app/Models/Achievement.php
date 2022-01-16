<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
