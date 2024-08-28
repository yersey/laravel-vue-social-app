<?php

namespace App\Services;

use App\Models\User;
use App\Models\Like;
use App\Models\Post;
use App\Models\Comment;
use App\DataTransferObjects\PostDto;
use App\DataTransferObjects\CommentDto;
use App\Exceptions\LikeNotFoundException;
use App\Exceptions\PostAlreadyLikedException;

class PostService
{
    function store(PostDto $dto): Post
    {
        return Post::create([
            'content' => $dto->content,
            'user_id' => $dto->userId,
        ]);
    }

    function update(Post $post, PostDto $dto): Post
    {
        return tap($post)->update([
            'content' => $dto->content,
        ]);
    }

    function delete(Post $post): void
    {
        $post->delete();
    }

    function like(Post $post, User $user): Like
    {
        if ($post->likes()->where('user_id', $user->id)->exists()) {
            throw new PostAlreadyLikedException();
        }

        $like = $post->likes()->create([
            'user_id' => $user->id
        ]);
        
        return $like;
    }

    function unlike(Post $post, User $user): void
    {
        $like = $post->likes()->where('user_id', $user->id)->first();

        if (!$like) {
            throw new LikeNotFoundException();
        }

        $like->delete();
    }

    function comment(Post $post, CommentDto $dto): Comment
    {
        return $post->comments()->create([
            'content' => $dto->content,
            'user_id' => $dto->userId
        ]);
    }
}