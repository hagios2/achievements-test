<?php

namespace App\Services;


use App\Events\AchievementUnlockEvent;
use App\Http\Requests\AddCommentRequest;
use App\Models\Comment;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class AchievementService
{
    public function addComment(AddCommentRequest $request): JsonResponse
    {
        $user = User::find(1);

        $comment = $user->addComment($request->validated());

        $this->commentsCount($user);

        return response()->json(['message' => 'comment saved', 'new_comment' => $comment], 201);
    }

    public function watchLesson(Lesson $lesson): JsonResponse
    {
        $user = User::find(1);

        $user->addlesson($lesson);

        return response()->json(['message' => 'user watched lesson added']);
    }

    public function commentsCount(User $user)
    {
        $commentCounts = $user->comments()->count();

        //check if count of the user's comment is in the comment_achievement_level array
        if (in_array($commentCounts, array_keys(Comment::COMMENT_ACHIEVEMENT_LEVEL))) {
            AchievementUnlockEvent::dispatch($user, Comment::COMMENT_ACHIEVEMENT_LEVEL[$commentCounts]);
        }
    }

}
