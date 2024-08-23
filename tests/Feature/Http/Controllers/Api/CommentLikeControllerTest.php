<?php

namespace Tests\Feature\Http\Controllers\Api;

use Tests\TestCase;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentLikeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_like_is_stored_successfully(): void
    {
        $comment = Comment::factory()->for(Post::factory(), 'commentable')->create();
        $this->actingAs(User::factory()->create());

        $response = $this->postJson('/api/comments/' . $comment->id . '/likes');

        $response->assertStatus(201);
    }

    public function test_duplicated_like_throws_error(): void
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->for(Post::factory(), 'commentable')->create();
        Like::factory()->for($user)->for($comment, 'likeable')->create();
        $this->actingAs($user);

        $response = $this->postJson('/api/comments/' . $comment->id . '/likes');

        $response->assertStatus(400)
            ->assertJson(['error' => 'You have already liked this comment']);
    }

    public function test_like_store_throws_authentication_error(): void
    {
        $comment = Comment::factory()->for(Post::factory(), 'commentable')->create();

        $response = $this->postJson('/api/comments/' . $comment->id . '/likes');

        $response->assertStatus(401)
            ->assertJson(['message' => 'Unauthenticated.']);
    }

    public function test_like_is_deleted_successfully(): void
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->for(Post::factory(), 'commentable')->create();
        Like::factory()->for($user)->for($comment, 'likeable')->create();
        $this->actingAs($user);

        $response = $this->deleteJson('/api/comments/' . $comment->id . '/likes');

        $response->assertStatus(204);
    }

    public function test_like_deletion_throws_unauthenticated_error(): void
    {
        $comment = Comment::factory()->for(Post::factory(), 'commentable')->create();
        Like::factory()->for(User::factory())->for($comment, 'likeable')->create();

        $response = $this->deleteJson('/api/comments/' . $comment->id . '/likes');

        $response->assertStatus(401)
            ->assertJson(['message' => 'Unauthenticated.']);
    }

    public function test_like_deletion_throws_not_found_error(): void
    {
        $comment = Comment::factory()->for(Post::factory(), 'commentable')->create();
        Like::factory()->for(User::factory())->for($comment, 'likeable')->create();
        $this->actingAs(User::factory()->create());

        $response = $this->deleteJson('/api/comments/' . $comment->id . '/likes');

        $response->assertStatus(404)
            ->assertJson(['error' => 'Like not found']);
    }

}
