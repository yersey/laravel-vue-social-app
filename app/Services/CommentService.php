<?php

namespace App\Services;

use App\Models\Like;
use App\Models\Comment;
use App\DataTransferObjects\CommentDto;
use App\Exceptions\LikeNotFoundException;
use App\Exceptions\InvalidReplyDepthException;
use App\Exceptions\CommentAlreadyLikedException;

class CommentService
{
    function store(Comment $comment, CommentDto $dto): Comment
    {
        if ($comment->isCommentReply()) {
            throw new InvalidReplyDepthException();
        }

        return $comment->comments()->create([
            'content' => $dto->content,
            'user_id' => $dto->userId
        ]);
    }

    function delete(Comment $comment): void
    {
        $comment->delete();
    }

    function like(Comment $comment): Like
    {
        if ($comment->likes()->where('user_id', auth()->id())->exists()) {
            throw new CommentAlreadyLikedException();
        }

        $like = $comment->likes()->create([
            'user_id' => auth()->id()
        ]);
        
        return $like;
    }

    function unlike(Comment $comment): void
    {
        $like = $comment->likes()->where('user_id', auth()->id())->first();

        if (!$like) {
            throw new LikeNotFoundException();
        }

        $like->delete();
    }
}
