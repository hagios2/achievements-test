<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    public const LESSON_ACHIEVEMENT_LEVEL = [
        1 => Achievement::FIRST_LESSON_WATCHED,
        5 => Achievement::FIVE_LESSONS_WATCHED,
        10 => Achievement::TEN_LESSONS_WATCHED,
        25 => Achievement::TWENTY_FIVE_LESSONS_WATCHED,
        50 => Achievement::FIFTY_LESSONS_WATCHED
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title'
    ];
}
