<?php

namespace Tests\Feature;

use App\Models\Lesson;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class AchievementTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public $user;

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

    public function test_unauthenticated_user_cant_watch_a_lesson()
    {
        $data = ['body' => $this->faker->text];

        $response = $this->json('POST','/api/user/add/comment', $data, [
            'accept' => 'application/json',
            'Content-Type' => 'application/json'
        ]);

        $response->assertStatus(401)
            ->assertExactJson(['message' => 'Unauthenticated.']);
    }

}
