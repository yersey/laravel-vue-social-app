<?php

namespace App\Hateoas;

use Illuminate\Support\Facades\Gate;

class CommentHateoas extends Hateoas
{
    public function selfLink(): array
    {
        return $this->link('self', 'GET', route('comments.show', ['comment' => $this->resource]));
    }

    public function deleteLink(): ?array
    {
        if ($this->user && Gate::forUser($this->user)->allows('delete-comment', $this->resource)) {
            return $this->link('delete', 'GET', route('comments.destroy', ['comment' => $this->resource->id]));
        }
        
        return null;
    }

    public function likeLink(): ?array
    {
        if ($this->user && !$this->resource->isLikedByUser($this->user)) {
            return $this->link('like', 'POST', route('comment-likes.store', ['comment' => $this->resource->id]));
        }
        
        return null;
    }

    public function unlikeLink(): ?array
    {
        if ($this->user && $this->resource->isLikedByUser($this->user)) {
            return $this->link('unlike', 'DELETE', route('comment-likes.destroy', ['comment' => $this->resource->id]));
        }
        
        return null;
    }

    public function addCommentLink(): ?array
    {
        if ($this->user && !$this->resource->isCommentReply()) {
            return $this->link('add-comment', 'POST', route('comment-comments.store', ['comment' => $this->resource->id]));
        }
        
        return null;
    }

    public function commentsLink(): ?array
    {
        if (!$this->resource->isCommentReply()) {
            return $this->link('comments', 'GET', route('comment-comments.index', ['comment' => $this->resource->id]));
        }

        return null;
    }

    public function userLink(): array
    {
        return $this->link('user', 'GET', route('users.show', ['user' => $this->resource->user_id]));
    }
}
