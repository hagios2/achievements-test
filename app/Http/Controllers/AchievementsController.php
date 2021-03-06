<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddCommentRequest;
use App\Http\Resources\AchievementResponse;
use App\Models\Lesson;
use App\Models\User;
use App\Services\AchievementService;
use Illuminate\Http\JsonResponse;

class AchievementsController extends Controller
{
    public function __construct(protected AchievementService $achievementService)
    {
        $this->middleware('auth:api');
    }

    public function index(User $user): AchievementResponse
    {
        return $this->achievementService->index($user);
    }

    public function addComment(AddCommentRequest $request): JsonResponse
    {
        return $this->achievementService->addComment($request);
    }

    public function watchLesson(Lesson $lesson): JsonResponse
    {
        return $this->achievementService->watchLesson($lesson);
    }
}
