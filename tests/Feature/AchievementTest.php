<?php

namespace Tests\Feature;

use App\Models\Achievement;
use App\Models\Comment;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class AchievementTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public $user;

    public $lesson;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->lesson = Lesson::factory()->create();

        $this->token = JWTAuth::fromUser($this->user);
    }

    public function test_unauthenticated_user_cant_add_a_comment()
    {
        $data = ['body' => $this->faker->text];

        $response = $this->json('POST','/api/user/add/comment', $data, [
            'accept' => 'application/json',
            'Content-Type' => 'application/json'
        ]);

        $response->assertStatus(401)
            ->assertExactJson(['message' => 'Unauthenticated.']);
    }

    public function test_comment_is_required_for_user_to_add_comment()
    {
        $data = ['body' => ''];
        $response = $this->json('POST','/api/user/add/comment', $data, [
            'Authorization' => "Bearer {$this->token}",
            'accept' => 'application/json',
            'Content-Type' => 'application/json'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrorFor('body');
    }

    public function test_user_can_add_a_comment()
    {
        $data = ['body' => $this->faker->text];

        $response = $this->json('POST','/api/user/add/comment', $data, [
            'Authorization' => "Bearer {$this->token}",
            'accept' => 'application/json',
            'Content-Type' => 'application/json'
        ]);

        $response->assertStatus(201)
            ->assertExactJson([
                'message' => 'comment saved'
            ]);

        $this->assertDatabaseHas('comments', [
            'body' => $data['body']
        ]);
    }

    public function test_achievements_are_added_for_enough_user_comments()
    {
        for ($comment_count = 1; $comment_count < 21; $comment_count++) {
            $data = ['body' => $this->faker->text];

            $response = $this->json('POST','/api/user/add/comment', $data, [
                'Authorization' => "Bearer {$this->token}",
                'accept' => 'application/json',
                'Content-Type' => 'application/json'
            ]);

            $response->assertStatus(201)
                ->assertExactJson([
                    'message' => 'comment saved'
                ]);

            if (in_array($comment_count, array_keys(Comment::COMMENT_ACHIEVEMENT_LEVEL))) {
                $this->assertDatabaseHas('achievements', [
                    'achievement_name' => Comment::COMMENT_ACHIEVEMENT_LEVEL[$comment_count],
                    'achievement_type' => Achievement::COMMENT_TYPE
                ]);
            }
        }
    }

    public function test_unauthenticated_user_cant_watch_a_lesson()
    {
        $response = $this->json('POST',"/api/user/watch/{$this->lesson->id}/lesson", [], [
            'accept' => 'application/json',
            'Content-Type' => 'application/json'
        ]);

        $response->assertStatus(401)
            ->assertExactJson(['message' => 'Unauthenticated.']);
    }

    public function test_user_cant_watch_non_existing_lesson()
    {
        $response = $this->json('POST',"/api/user/watch/100/lesson", [], [
            'Authorization' => "Bearer {$this->token}",
            'accept' => 'application/json',
            'Content-Type' => 'application/json'
        ]);

        $response->assertStatus(404)
            ->assertExactJson(['message' => 'Record Not Found']);
    }

    public function test_a_user_can_watch_existing_lesson()
    {
        $response = $this->json('POST',"/api/user/watch/{$this->lesson->id}/lesson", [], [
            'Authorization' => "Bearer {$this->token}",
            'accept' => 'application/json',
            'Content-Type' => 'application/json'
        ]);

        $response->assertStatus(200)
            ->assertExactJson([
                'message' => 'user watched lesson added'
            ]);

        $this->assertDatabaseHas('lesson_user', [
            'lesson_id' => $this->lesson->id,
            'user_id' => $this->user->id
        ]);
    }

    public function test_a_watched_lesson_can_not_be_saved_more_than_once_for_a_user()
    {
        $response = $this->json('POST',"/api/user/watch/{$this->lesson->id}/lesson", [], [
            'Authorization' => "Bearer {$this->token}",
            'accept' => 'application/json',
            'Content-Type' => 'application/json'
        ]);

        $response->assertStatus(200)
            ->assertExactJson([
                'message' => 'user watched lesson added'
            ]);

        $this->assertDatabaseHas('lesson_user', [
            'lesson_id' => $this->lesson->id,
            'user_id' => $this->user->id
        ]);

        $response = $this->json('POST',"/api/user/watch/{$this->lesson->id}/lesson", [], [
            'Authorization' => "Bearer {$this->token}",
            'accept' => 'application/json',
            'Content-Type' => 'application/json'
        ]);

        $response->assertStatus(200)
            ->assertExactJson([
                'message' => 'lesson already watched'
            ]);
    }

    public function test_achievements_are_added_for_enough_lessons_watch_by_user()
    {
        $lessons = Lesson::factory(50)->create();

        $lesson_counter = 1;

        foreach ($lessons as $lesson) {

            $response = $this->json('POST',"/api/user/watch/{$lesson->id}/lesson", [], [
                'Authorization' => "Bearer {$this->token}",
                'accept' => 'application/json',
                'Content-Type' => 'application/json'
            ]);

            $response->assertStatus(200)
                ->assertExactJson([
                    'message' => 'user watched lesson added'
                ]);

            if (in_array($lesson_counter, array_keys(Lesson::LESSON_ACHIEVEMENT_LEVEL))) {
                $this->assertDatabaseHas('achievements', [
                    'achievement_name' => Lesson::LESSON_ACHIEVEMENT_LEVEL[$lesson_counter],
                    'achievement_type' => Achievement::LESSON_TYPE
                ]);
            }
            $lesson_counter += 1;
        }
    }
}
