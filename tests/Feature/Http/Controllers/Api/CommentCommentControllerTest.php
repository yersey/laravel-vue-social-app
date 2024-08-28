<?php

namespace Tests\Feature\Http\Controllers\Api;

use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentCommentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_comment_is_stored_successfully(): void
    {
        $comment = Comment::factory()->for(Post::factory(), 'commentable')->create();
        $this->actingAs(User::factory()->create());
        $data = [
            'content' => fake()->paragraph()
        ];

        $response = $this->postJson('/api/v1/comments/' . $comment->id . '/comments', $data);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'content',
                    'user',
                    'created_at',
                    'comments',
                    'likes_count',
                    'is_liked',
                ]
            ]);
    }

    public function test_reply_to_reply_throws_error(): void
    {
        $comment = Comment::factory()->for(Post::factory(), 'commentable')->create();
        $reply = Comment::factory()->for($comment, 'commentable')->create();
        $this->actingAs(User::factory()->create());
        $data = [
            'content' => fake()->paragraph()
        ];

        $response = $this->postJson('/api/v1/comments/' . $reply->id . '/comments', $data);

        $response->assertStatus(400)
            ->assertJson(['message' => 'Replying to this comment is not allowed.']);
    }

    public function test_comment_validation_throws_error(): void
    {
        $comment = Comment::factory()->for(Post::factory(), 'commentable')->create();
        $this->actingAs(User::factory()->create());
        $data = [
            'content' => '1'
        ];

        $response = $this->postJson('/api/v1/comments/' . $comment->id . '/comments', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrorFor('content');
    }

    public function test_comment_store_throws_authentication_error(): void
    {
        $comment = Comment::factory()->for(Post::factory(), 'commentable')->create();
        $data = [
            'content' => fake()->paragraph()
        ];

        $response = $this->postJson('/api/v1/comments/' . $comment->id . '/comments', $data);

        $response->assertStatus(401)
            ->assertJson(['message' => 'Unauthenticated.']);
    }
}
