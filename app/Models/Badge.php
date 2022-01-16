<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    use HasFactory;

    public const USER_BADGE_LEVEL = [
        0 => 'Beginner',
        4 => 'Intermediate',
        8 => 'Advanced',
        10 => 'Master'
    ];

    protected $fillable = ['user_id', 'badge_name'];
}
