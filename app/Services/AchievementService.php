<?php

namespace App\Services;

use App\Events\AchievementUnlockEvent;
use App\Http\Requests\AddCommentRequest;
use App\Http\Resources\AchievementResponse;
use App\Models\Comment;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class AchievementService
{
    public function index(User $user): AchievementResponse
    {
        return new AchievementResponse($user);
    }

    public function addComment(AddCommentRequest $request): JsonResponse
    {
        $user = auth()->user();

        $comment = $user->addComment($request->validated());

        $this->commentsCount($user);

        return response()->json(['message' => 'comment saved', 'new_comment' => $comment], 201);
    }

    public function watchLesson(Lesson $lesson): JsonResponse
    {
        $user = auth()->user();

        $already_watched = $user->watched()->where(['lesson_id' => $lesson->id])->count();

        if ($already_watched === 0) {
            $user->addLesson($lesson);

            $this->lessonCount($user);

            return response()->json(['message' => 'user watched lesson added']);
        } else {
            return response()->json(['message' => 'lesson already watched']);
        }
    }

    public function commentsCount(User $user)
    {
        $commentCounts = $user->comments()->count();

        //check if count of the user's comment is in the comment_achievement_level array
        if (in_array($commentCounts, array_keys(Comment::COMMENT_ACHIEVEMENT_LEVEL))) {
            //fire achievement unlock event
            AchievementUnlockEvent::dispatch($user, Comment::COMMENT_ACHIEVEMENT_LEVEL[$commentCounts]);
        }
    }

    public function lessonCount(User $user)
    {
        $lessonCounts = $user->watched()->count();

        //check if count of the user's watched lesson is in the comment_achievement_level array
        if (in_array($lessonCounts, array_keys(Lesson::LESSON_ACHIEVEMENT_LEVEL))) {
            //fire achievement unlock event
            AchievementUnlockEvent::dispatch($user, Lesson::LESSON_ACHIEVEMENT_LEVEL[$lessonCounts]);
        }
    }
}
