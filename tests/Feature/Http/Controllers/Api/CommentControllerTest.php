<?php

namespace Tests\Feature\Http\Controllers\Api;

use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_comment_is_deleted_successfully(): void
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->for(Post::factory(), 'commentable')->for($user)->create();
        $this->actingAs($user);

        $response = $this->deleteJson('/api/comments/' . $comment->id);

        $response->assertStatus(204);
    }

    public function test_unauthorized_comment_deletion_throws_error(): void
    {
        $comment = Comment::factory()->for(Post::factory(), 'commentable')->create();
        $this->actingAs(User::factory()->create());

        $response = $this->deleteJson('/api/comments/' . $comment->id);

        $response->assertStatus(403);
    }

    public function test_unauthenticated_comment_deletion_throws_error(): void
    {
        $comment = Comment::factory()->for(Post::factory(), 'commentable')->create();

        $response = $this->deleteJson('/api/comments/' . $comment->id);

        $response->assertStatus(401)
            ->assertJson(['message' => 'Unauthenticated.']);
    }
}
