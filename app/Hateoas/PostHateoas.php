<?php

namespace App\Hateoas;

use Illuminate\Support\Facades\Gate;

class PostHateoas extends Hateoas
{
    public function selfLink(): array
    {
        return $this->link('self', 'GET', route('posts.show', ['post' => $this->resource]));
    }

    public function deleteLink(): ?array
    {
        if ($this->user && Gate::forUser($this->user)->allows('delete-post', $this->resource)) {
            return $this->link('delete', 'GET', route('posts.destroy', ['post' => $this->resource->id]));
        }
        
        return null;
    }

    public function likeLink(): ?array
    {
        if ($this->user && !$this->resource->isLikedByUser($this->user)) {
            return $this->link('like', 'POST', route('post-likes.store', ['post' => $this->resource->id]));
        }
        
        return null;
    }

    public function unlikeLink(): ?array
    {
        if ($this->user && $this->resource->isLikedByUser($this->user)) {
            return $this->link('unlike', 'DELETE', route('post-likes.destroy', ['post' => $this->resource->id]));
        }
        
        return null;
    }

    public function addCommentLink(): ?array
    {
        if ($this->user) {
            return $this->link('add-comment', 'POST', route('post-comments.store', ['post' => $this->resource->id]));
        }
        
        return null;
    }

    public function commentsLink(): array
    {
        return $this->link('comments', 'GET', route('post-comments.index', ['post' => $this->resource->id]));
    }

    public function userLink(): array
    {
        return $this->link('user', 'GET', route('users.show', ['user' => $this->resource->user_id]));
    }
}
