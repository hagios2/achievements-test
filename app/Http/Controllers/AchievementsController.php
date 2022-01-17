<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddCommentRequest;
use App\Models\Lesson;
use App\Models\User;
use App\Services\AchievementService;
use Illuminate\Http\Request;

class AchievementsController extends Controller
{
    private AchievementService $achievementService;

    public function __construct(AchievementService $achievementService)
    {
        $this->achievementService = $achievementService;
    }

    public function index(User $user)
    {
       return $this->achievementService->index($user);
    }

    public function addComment(AddCommentRequest $request)
    {
        return $this->achievementService->addComment($request);
    }

    public function watchLesson(Lesson $lesson)
    {
        return $this->achievementService->watchLesson($lesson);;
    }
}
