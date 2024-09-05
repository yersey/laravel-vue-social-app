<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\User;
use App\Models\Like;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_is_liked_by_logged_in_user_returns_true(): void
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->for($user)->for(Post::factory(), 'commentable')->create();
        Like::factory()->for($user)->for($comment, 'likeable')->create();
        
        $result = $comment->isLikedByUser($user);

        $this->assertTrue($result);
    }

    public function test_is_liked_by_logged_in_user_when_user_not_liked_returns_false(): void
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->for($user)->for(Post::factory(), 'commentable')->create();
        
        $result = $comment->isLikedByUser($user);

        $this->assertFalse($result);
    }

    public function test_is_liked_by_logged_in_user_when_user_not_logged_in_returns_false(): void
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->for($user)->for(Post::factory(), 'commentable')->create();
        Like::factory()->for($user)->for($comment, 'likeable')->create();
        
        $result = $comment->isLikedByUser(null);

        $this->assertFalse($result);
    }

    public function test_is_comment_reply_returns_true(): void
    {
        $comment = Comment::factory()->for(Post::factory(), 'commentable')->create();
        $reply = Comment::factory()->for($comment, 'commentable')->create();

        $result = $reply->isCommentReply();

        $this->assertTrue($result);
    }

    public function test_is_comment_reply_when_different_commentable_type_returns_false(): void
    {
        $comment = Comment::factory()->for(Post::factory(), 'commentable')->create();

        $result = $comment->isCommentReply();

        $this->assertFalse($result);
    }

    public function test_comment_deletion_deletes_related_models(): void
    {
        $comment = Comment::factory()->for(Post::factory(), 'commentable')->create();
        $like = Like::factory()->for($comment, 'likeable')->create();
        $reply = Comment::factory()->for($comment, 'commentable')->create();

        $comment->delete();

        $this->assertNull($like->fresh());
        $this->assertNull($reply->fresh());
    }
}
