<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    public const COMMENT_ACHIEVEMENT_LEVEL = [
        1 => Achievement::FIRST_COMMENT_WRITTEN,
        3 => Achievement::THREE_COMMENTS_WRITTEN,
        5 => Achievement::FIVE_COMMENTS_WRITTEN,
        10 => Achievement::TEN_COMMENTS_WRITTEN,
        20 => Achievement::TWENTY_COMMENTS_WRITTEN
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'body',
        'user_id'
    ];

    /**
     * Get the user that wrote the comment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
