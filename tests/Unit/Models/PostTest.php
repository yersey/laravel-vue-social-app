<?php

namespace Tests\Unit\Models;

use App\Models\Comment;
use Tests\TestCase;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function test_is_liked_by_logged_in_user_returns_true(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->for($user)->create();
        Like::factory()->for($user)->for($post, 'likeable')->create();
        
        $result = $post->isLikedByUser($user);

        $this->assertTrue($result);
    }

    public function test_is_liked_by_logged_in_user_when_user_not_liked_returns_false(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->for($user)->create();
        
        $result = $post->isLikedByUser($user);

        $this->assertFalse($result);
    }

    public function test_is_liked_by_logged_in_user_when_user_not_logged_in_returns_false(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->for($user)->create();
        Like::factory()->for($user)->for($post, 'likeable')->create();
        
        $result = $post->isLikedByUser(null);

        $this->assertFalse($result);
    }

    public function test_post_deletion_deletes_related_models(): void
    {
        $post = Post::factory()->create();
        $like = Like::factory()->for($post, 'likeable')->create();
        $comment = Comment::factory()->for($post, 'commentable')->create();
        $reply = Comment::factory()->for($comment, 'commentable')->create();

        $post->delete();

        $this->assertNull($like->fresh());
        $this->assertNull($comment->fresh());
        $this->assertNull($reply->fresh());
    }
}
