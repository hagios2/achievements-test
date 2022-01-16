<?php

namespace App\Services;


use App\Http\Requests\AddCommentRequest;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class AchievementService
{
    public function addComment(AddCommentRequest $request): JsonResponse
    {
        $user = User::find(1);

        $comment = $user->addComment($request->validated());

        return response()->json(['message' => 'comment saved', 'new_comment' => $comment], 201);
    }

    public function watchLesson(Lesson $lesson): JsonResponse
    {
        $user = User::find(1);

        $user->addlesson($lesson);

        return response()->json(['message' => 'user watched lesson added']);
    }
}
